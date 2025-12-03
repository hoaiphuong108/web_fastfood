// Hiệu ứng mở/đóng dropdown khi hover sidebar
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".nav-dropdown").forEach((dropdown) => {
    const collapse = dropdown.querySelector(".collapse");
    const bsCollapse = new bootstrap.Collapse(collapse, { toggle: false });

    // Khi rê chuột vào → mở menu
    dropdown.addEventListener("mouseenter", () => bsCollapse.show());

    // Khi rời chuột ra → đóng menu
    dropdown.addEventListener("mouseleave", () => bsCollapse.hide());
  });
});

// Ẩn sidebar ở màn hình nhỏ
document.addEventListener("DOMContentLoaded", () => {
  const sidebar = document.querySelector(".sidebar-main");
  const toggleBtn = document.getElementById("toggleSidebar");

  if (toggleBtn && sidebar) {
    toggleBtn.addEventListener("click", () => {
      sidebar.classList.toggle("active");
    });
  }
});
