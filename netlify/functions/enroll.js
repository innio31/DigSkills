const { Client } = require('@notionhq/client');

exports.handler = async (event, context) => {
  if (event.httpMethod !== 'POST') {
    return { statusCode: 405, body: 'Method Not Allowed' };
  }

  try {
    const { userId, course } = JSON.parse(event.body);
    
    // Save to Notion or your database
    const notion = new Client({ auth: process.env.NOTION_API_KEY });
    
    await notion.pages.create({
      parent: { database_id: process.env.NOTION_DB_ID },
      properties: {
        'User ID': { rich_text: [{ text: { content: userId } }] },
        'Course': { title: [{ text: { content: course } }] },
        'Status': { select: { name: 'Enrolled' } },
        'Date': { date: { start: new Date().toISOString() } }
      }
    });

    return {
      statusCode: 200,
      body: JSON.stringify({ success: true })
    };
  } catch (error) {
    return {
      statusCode: 500,
      body: JSON.stringify({ error: error.message })
    };
  }
};
