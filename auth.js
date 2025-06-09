// Initialize Netlify Identity
if (window.netlifyIdentity) {
  window.netlifyIdentity.on("init", user => {
    if (!user) {
      window.netlifyIdentity.on("login", () => {
        document.location.href = "/dashboard/";
      });
    }
  });
}

// Auth status check
export function checkAuth() {
  const user = netlifyIdentity.currentUser();
  if (!user) {
    netlifyIdentity.open('login');
    return false;
  }
  return true;
}
