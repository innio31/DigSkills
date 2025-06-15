const { Pool } = require('pg');

exports.handler = async (event, context) => {
  // Only allow POST requests
  if (event.httpMethod !== 'POST') {
    return { statusCode: 405, body: 'Method Not Allowed' };
  }

  // Parse the incoming data
  const { userEmail, userName, courseId } = JSON.parse(event.body);

  // Connect to Neon
  const pool = new Pool({
    connectionString: process.env.NEON_DB_URL,
  });

  try {
    const client = await pool.connect();
    
    // 1. Create user if they don't exist
    await client.query(`
      INSERT INTO users (email, name)
      VALUES ($1, $2)
      ON CONFLICT (email) DO NOTHING
    `, [userEmail, userName]);

    // 2. Create enrollment
    const result = await client.query(`
      INSERT INTO enrollments (user_id, course_id)
      SELECT id, $1 FROM users WHERE email = $2
      RETURNING id
    `, [courseId, userEmail]);

    client.release();
    
    return {
      statusCode: 200,
      body: JSON.stringify({ enrollmentId: result.rows[0].id }),
    };
  } catch (error) {
    return { statusCode: 500, body: error.message };
  }
};
