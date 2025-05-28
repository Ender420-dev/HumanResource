function applyTheme(theme) {
    // Set Bootstrap theme
    document.documentElement.setAttribute("data-bs-theme", theme);
    localStorage.setItem("theme", theme);

    // Update icon
    const icon = document.getElementById("theme-icon");
    icon.className = theme === "dark" ? "fas fa-sun" : "fas fa-moon";
  }

  document.addEventListener("DOMContentLoaded", function () {
    // On page load, apply saved theme
    const savedTheme = localStorage.getItem("theme") || "light";
    applyTheme(savedTheme);

    // Add toggle functionality
    document.getElementById("theme-toggle").addEventListener("click", function () {
      const currentTheme = localStorage.getItem("theme") || "light";
      const newTheme = currentTheme === "light" ? "dark" : "light";
      applyTheme(newTheme);
    });
  });