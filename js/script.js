document.addEventListener("DOMContentLoaded", function () {

  // ============================================================
  // Navbar: Highlight the active link based on current page
  // ============================================================
  const navLinks = document.querySelectorAll(".nav-link");
  const currentPage = window.location.pathname.split("/").pop() || "index.php";

  navLinks.forEach(function (link) {
    const linkPage = link.getAttribute("href");
    if (linkPage === currentPage) {
      link.classList.add("active");
    }
  });


  // ============================================================
  // Login Form: Client-side validation only.
  // We do NOT call event.preventDefault() so the form can
  // submit normally to PHP for actual authentication.
  // ============================================================
  const loginForm = document.getElementById("loginForm");

  if (loginForm) {
    loginForm.addEventListener("submit", function (event) {
      let isValid = true;
      const loginEmail = document.getElementById("loginEmail");
      const loginPassword = document.getElementById("loginPassword");
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (!emailPattern.test(loginEmail.value.trim())) {
        loginEmail.classList.add("is-invalid");
        isValid = false;
      } else {
        loginEmail.classList.remove("is-invalid");
      }

      if (loginPassword.value.trim().length < 6) {
        loginPassword.classList.add("is-invalid");
        isValid = false;
      } else {
        loginPassword.classList.remove("is-invalid");
      }

      // If validation fails, stop the form from submitting
      if (!isValid) {
        event.preventDefault();
      }
      // If valid, the form submits normally to PHP (no preventDefault)
    });
  }


  // ============================================================
  // Contact Form: Client-side validation.
  // We allow the form to submit to PHP by NOT calling
  // preventDefault when the form is valid.
  // ============================================================
  const contactForm = document.getElementById("contactForm");

  if (contactForm) {
    contactForm.addEventListener("submit", function (event) {
      let isValid = true;

      const nameField = document.getElementById("fullName");
      const emailField = document.getElementById("email");
      const messageField = document.getElementById("message");
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (nameField.value.trim() === "") {
        nameField.classList.add("is-invalid");
        isValid = false;
      } else {
        nameField.classList.remove("is-invalid");
        nameField.classList.add("is-valid");
      }

      if (!emailPattern.test(emailField.value.trim())) {
        emailField.classList.add("is-invalid");
        isValid = false;
      } else {
        emailField.classList.remove("is-invalid");
        emailField.classList.add("is-valid");
      }

      if (messageField.value.trim() === "") {
        messageField.classList.add("is-invalid");
        isValid = false;
      } else {
        messageField.classList.remove("is-invalid");
        messageField.classList.add("is-valid");
      }

      // If validation fails, stop the form from submitting
      if (!isValid) {
        event.preventDefault();
      }
      // If valid, form submits normally to PHP
    });
  }


  // ============================================================
  // Password Toggle: Show/Hide password on login page
  // ============================================================
  const togglePasswordBtn = document.getElementById("togglePassword");
  if (togglePasswordBtn) {
    togglePasswordBtn.addEventListener("click", function () {
      const passwordField = document.getElementById("loginPassword");
      const isPassword = passwordField.getAttribute("type") === "password";
      passwordField.setAttribute("type", isPassword ? "text" : "password");
      togglePasswordBtn.textContent = isPassword ? "🙈" : "👁";
    });
  }


  // ============================================================
  // Upload Form: We do NOT block submit with preventDefault.
  // The PHP backend handles the actual upload.
  // We only show an alert if no file is selected.
  // ============================================================
  const uploadForm = document.getElementById("uploadForm");
  const fileInput = document.getElementById("fileInput");

  if (uploadForm && fileInput) {
    uploadForm.addEventListener("submit", function (event) {
      if (!fileInput.files.length) {
        event.preventDefault(); // Only block if no file selected
        alert("Please choose a file before uploading.");
      }
      // If a file is selected, let form submit normally to PHP
    });
  }


  // ============================================================
  // Smooth scroll for anchor links
  // ============================================================
  const scrollLinks = document.querySelectorAll(".smooth-scroll");
  scrollLinks.forEach(function (link) {
    link.addEventListener("click", function (event) {
      const targetId = link.getAttribute("href");
      if (targetId && targetId.startsWith("#")) {
        const targetEl = document.querySelector(targetId);
        if (targetEl) {
          event.preventDefault();
          targetEl.scrollIntoView({ behavior: "smooth" });
        }
      }
    });
  });

});