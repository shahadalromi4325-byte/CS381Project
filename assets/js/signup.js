// signup.js — mirrors login.js pattern exactly

document.addEventListener("DOMContentLoaded", () => {
  const params  = new URLSearchParams(window.location.search);
  const error   = params.get("error");
  const message = params.get("message");
  const box     = document.getElementById("messageBox");

  // ── Show URL message (from PHP redirect) ─────────────────
  if (error || message) {
    box.textContent  = error ? "❌ " + error : "✅ " + message;
    box.className    = "message-box " + (error ? "error-message" : "success-message");
    box.style.display = "block";

    setTimeout(() => {
      box.style.opacity = "0";
      box.style.height  = "0";
      box.style.padding = "0";
      box.style.margin  = "0";
      box.style.border  = "none";
      setTimeout(() => { box.style.display = "none"; }, 500);
    }, 3000);

    // Clean URL so message doesn't re-show on refresh
    window.history.replaceState({}, document.title, window.location.pathname);
  }

  // ── Client-side validation ────────────────────────────────
  const form = document.getElementById("signupForm");

  form.addEventListener("submit", (e) => {
    const name  = form.username.value.trim();
    const email = form.email.value.trim();
    const id    = form.ID.value.trim();
    const pass  = form.Password.value.trim();

    if (!name || !email || !id || !pass) {
      e.preventDefault();
      showInlineError("Please fill in all fields.");
      return;
    }

    if (name.length < 3) {
      e.preventDefault();
      showInlineError("Name must be at least 3 characters.");
      return;
    }

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
      e.preventDefault();
      showInlineError("Please enter a valid email address.");
      return;
    }

    if (isNaN(id) || Number(id) <= 0) {
      e.preventDefault();
      showInlineError("Student ID must be a valid number.");
      return;
    }

    if (pass.length < 6) {
      e.preventDefault();
      showInlineError("Password must be at least 6 characters.");
    }
  });

  function showInlineError(msg) {
    box.textContent    = "❌ " + msg;
    box.className      = "message-box error-message";
    box.style.display  = "block";
    box.style.opacity  = "1";
    box.style.height   = "auto";
    box.style.padding  = "";
    box.style.margin   = "";
    box.style.border   = "";
  }
});