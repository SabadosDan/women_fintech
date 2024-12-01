document.addEventListener("DOMContentLoaded", function () {
  // Validare formular
  const form = document.querySelector("form");
  if (form) {
    form.addEventListener("submit", function (e) {
      const email = document.querySelector('input[type="email"]');
      const linkedin = document.querySelector('input[name="linkedin_profile"]');

      if (email && !validateEmail(email.value)) {
        e.preventDefault();
        alert("Please enter a valid email address");
      }

      if (linkedin && !validateLinkedIn(linkedin.value)) {
        e.preventDefault();
        alert("Please enter a valid LinkedIn URL");
      }
    });
  }

  // Dark mode
  const darkModeToggle = document.getElementById("dark-mode-toggle");
  darkModeToggle.addEventListener("click", function () {
    const body = document.getElementById("body");
    body.classList.toggle("bg-secondary");
    body.classList.toggle("text-light");
    document.querySelector("nav").classList.toggle("bg-dark");
    document.querySelector("nav").classList.toggle("text-light");
    document.querySelector("footer").classList.toggle("bg-dark");
    document.querySelector("footer").classList.toggle("text-light");
    document.querySelectorAll(".member-card").forEach((card) => {
      card.classList.toggle("bg-dark");
      card.classList.toggle("text-light");
    });
  });
});
function validateEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}
function validateLinkedIn(url) {
  return url.includes("linkedin.com/");
}
