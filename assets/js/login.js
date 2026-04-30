// login.js — handles messages passed via URL params (from PHP redirect)

document.addEventListener("DOMContentLoaded", () => {
  const params  = new URLSearchParams(window.location.search);
  const error   = params.get("error");
  const message = params.get("message");
  const box     = document.getElementById("messageBox");

  if (error || message) {
    box.textContent  = error ? "❌ " + error : "✅ " + message;
    box.className    = "message-box " + (error ? "error-message" : "success-message");
    box.style.display = "block";

    // ── Auto-hide after 3 seconds ──────────────────────────
    setTimeout(() => {
      box.style.opacity = "0";
      box.style.height  = "0";
      box.style.padding = "0";
      box.style.margin  = "0";
      box.style.border  = "none";
      setTimeout(() => { box.style.display = "none"; }, 500);
    }, 3000);

    // ── Clean URL so message doesn't re-show on refresh ───
    const cleanUrl = window.location.pathname;
    window.history.replaceState({}, document.title, cleanUrl);
  }

  // ── Client-side form validation ───────────────────────────
  const form = document.getElementById("loginForm");
  form.addEventListener("submit", (e) => {
    const id  = form.student_id.value.trim();
    const pwd = form.password.value.trim();

    if (!id || !pwd) {
      e.preventDefault();
      showInlineError("Please fill in both fields.");
      return;
    }

    if (isNaN(id) || Number(id) <= 0) {
      e.preventDefault();
      showInlineError("Student ID must be a valid number.");
    }
  });

  function showInlineError(msg) {
    box.textContent   = "❌ " + msg;
    box.className     = "message-box error-message";
    box.style.display = "block";
    box.style.opacity = "1";
    box.style.height  = "auto";
    box.style.padding = "";
    box.style.margin  = "";
  }
});