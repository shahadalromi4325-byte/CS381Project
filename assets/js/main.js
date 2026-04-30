// ========== SCROLL REVEAL ==========
// ✅ Uses IntersectionObserver instead of scroll event — far more efficient
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
    }
  });
}, { threshold: 0.1 });

document.querySelectorAll('section:not(.hero-section)').forEach(section => {
  observer.observe(section);
});

// ========== SCROLL TO TOP ==========
const scrollTopBtn = document.getElementById('scrollTop');

window.addEventListener('scroll', () => {
  // toggle scroll-to-top button
  scrollTopBtn.classList.toggle('show', window.scrollY > 300);

  // navbar style change on scroll
  document.querySelector('.nav-links').classList.toggle('scrolled', window.scrollY > 50);
});

scrollTopBtn.addEventListener('click', () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
});

// ========== SMOOTH NAV SCROLL ==========
// ✅ html { scroll-behavior: smooth } in CSS handles this natively.
//    This JS override is only needed to offset for the fixed navbar height.
document.querySelectorAll('.nav-links a[href^="#"]').forEach(link => {
  link.addEventListener('click', function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      const offset = 80; // fixed nav height
      const top = target.getBoundingClientRect().top + window.scrollY - offset;
      window.scrollTo({ top, behavior: 'smooth' });
    }
  });
});

// ========== FORM VALIDATION ==========
const form = document.getElementById('feedbackForm');
if (form) {
  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const name    = document.getElementById('userName');
    const email   = document.getElementById('userEmail');
    const message = document.getElementById('userMessage');

    let valid = true;

    // Helper: show or clear error
    function validate(field, errorId, condition, msg) {
      const err = document.getElementById(errorId);
      if (condition) {
        field.classList.add('invalid');
        err.textContent = msg;
        valid = false;
      } else {
        field.classList.remove('invalid');
        err.textContent = '';
      }
    }

    validate(name, 'nameError',
      name.value.trim().length < 3,
      'Please enter your full name (at least 3 characters).'
    );

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    validate(email, 'emailError',
      !emailPattern.test(email.value.trim()),
      'Please enter a valid email address.'
    );

    validate(message, 'messageError',
      message.value.trim().length < 10,
      'Message must be at least 10 characters.'
    );

    if (valid) {
      // ✅ Show success message and reset form
      const successMsg = document.getElementById('formSuccess');
      successMsg.style.display = 'block';
      form.reset();

      // Hide success after 5 seconds
      setTimeout(() => { successMsg.style.display = 'none'; }, 5000);
    }
  });

  // ✅ Live validation: clear error as user types
  ['userName', 'userEmail', 'userMessage'].forEach(id => {
    document.getElementById(id).addEventListener('input', function () {
      this.classList.remove('invalid');
      const errId = { userName: 'nameError', userEmail: 'emailError', userMessage: 'messageError' }[id];
      document.getElementById(errId).textContent = '';
    });
  });
}