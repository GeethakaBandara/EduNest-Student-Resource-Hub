document.addEventListener("DOMContentLoaded", function () {
 
 
  const navLinks = document.querySelectorAll(".nav-link");
  const currentPage = window.location.pathname.split("/").pop() || "index.html";
 
  navLinks.forEach(function (link) {
    const linkPage = link.getAttribute("href");
    if (linkPage === currentPage) {
      link.classList.add("active");
    }
  });
 
 
  const applyFiltersBtn = document.getElementById("applyFiltersBtn");
  const searchInput = document.getElementById("browseSearchInput");
 
  if (applyFiltersBtn) {
    applyFiltersBtn.addEventListener("click", filterResources);
  }
 
 
  if (searchInput) {
    searchInput.addEventListener("input", filterResources);
  }
 
  function filterResources() {
    const cards = document.querySelectorAll(".resource-card");
    const checkedCategories = document.querySelectorAll(".category-checkbox:checked");
 
    
    const activeCategories = Array.from(checkedCategories).map(function (cb) {
      return cb.value.toLowerCase();
    });
 
    const searchTerm = searchInput ? searchInput.value.trim().toLowerCase() : "";
 
    cards.forEach(function (card) {
      const cardCategory = (card.dataset.category || "").toLowerCase();
      const cardTitle = (card.dataset.title || "").toLowerCase();
 
      const matchesCategory = activeCategories.length === 0 || activeCategories.includes(cardCategory);
      const matchesSearch = searchTerm === "" || cardTitle.includes(searchTerm);
 
      if (matchesCategory && matchesSearch) {
        card.closest(".col-resource").classList.remove("d-none-filtered");
        card.classList.add("fade-in");
      } else {
        card.closest(".col-resource").classList.add("d-none-filtered");
      }
    });
  }
 
 

  const contactForm = document.getElementById("contactForm");
 
  if (contactForm) {
    contactForm.addEventListener("submit", function (event) {
      event.preventDefault(); 
 
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
 
      const successAlert = document.getElementById("contactSuccessAlert");
 
      if (isValid) {
        successAlert.classList.remove("d-none");
        contactForm.reset();
        
        [nameField, emailField, messageField].forEach(function (f) {
          f.classList.remove("is-valid");
        });
      } else {
        successAlert.classList.add("d-none");
      }
    });
  }
 
 
  
  const loginForm = document.getElementById("loginForm");
 
  if (loginForm) {
    loginForm.addEventListener("submit", function (event) {
      event.preventDefault();
 
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
 
      if (isValid) {
        alert("Login successful! (This is a static demo, PHP backend not connected yet.)");
        loginForm.reset();
      }
    });
  }
 

  const togglePasswordBtn = document.getElementById("togglePassword");
  if (togglePasswordBtn) {
    togglePasswordBtn.addEventListener("click", function () {
      const passwordField = document.getElementById("loginPassword");
      const isPassword = passwordField.getAttribute("type") === "password";
      passwordField.setAttribute("type", isPassword ? "text" : "password");
      togglePasswordBtn.textContent = isPassword ? "🙈" : "👁";
    });
  }
 
 
  
  const dropzone = document.getElementById("uploadDropzone");
  const fileInput = document.getElementById("fileInput");
  const chooseFileBtn = document.getElementById("chooseFileBtn");
  const fileNameDisplay = document.getElementById("fileNameDisplay");
 
  if (dropzone && fileInput) {
 
    
    chooseFileBtn.addEventListener("click", function () {
      fileInput.click();
    });
 
   
    fileInput.addEventListener("change", function () {
      if (fileInput.files.length > 0) {
        fileNameDisplay.textContent = "Selected file: " + fileInput.files[0].name;
      }
    });
 
    dropzone.addEventListener("dragover", function (event) {
      event.preventDefault();
      dropzone.classList.add("dragover");
    });
 
   
    dropzone.addEventListener("dragleave", function () {
      dropzone.classList.remove("dragover");
    });
 
   
    dropzone.addEventListener("drop", function (event) {
      event.preventDefault();
      dropzone.classList.remove("dragover");
 
      if (event.dataTransfer.files.length > 0) {
        fileInput.files = event.dataTransfer.files;
        fileNameDisplay.textContent = "Selected file: " + event.dataTransfer.files[0].name;
      }
    });
  }
 
 
  const uploadForm = document.getElementById("uploadForm");
  if (uploadForm) {
    uploadForm.addEventListener("submit", function (event) {
      event.preventDefault();
      if (!fileInput.files.length) {
        alert("Please choose a file before uploading.");
        return;
      }
      alert("Upload started! (This is a static demo, PHP backend not connected yet.)");
    });
  }
 
 
 
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
 