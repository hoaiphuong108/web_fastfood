document.addEventListener("DOMContentLoaded", function () {
  // Dropdown Header
  const toggleBtnHeader = document.getElementById("toggleDropdownHeader");
  const dropdownMenuHeader = document.querySelector(".dropdown-menu-header");
  const dropdownWrapHeader = document.querySelector(".dropdown-header");

  if (toggleBtnHeader && dropdownMenuHeader && dropdownWrapHeader) {
    // Click để toggle menu
    toggleBtnHeader.addEventListener("click", function (e) {
      e.stopPropagation();
      dropdownMenuHeader.classList.toggle("show");
    });

    // Hover: mở khi rê vào, đóng khi rời
    let hoverTimeout;
    dropdownWrapHeader.addEventListener("mouseenter", function () {
      clearTimeout(hoverTimeout);
      dropdownMenuHeader.classList.add("show");
    });

    dropdownWrapHeader.addEventListener("mouseleave", function () {
      hoverTimeout = setTimeout(() => {
        dropdownMenuHeader.classList.remove("show");
      }, 150);
    });

    // Click ra ngoài đóng menu
    document.addEventListener("click", function (e) {
      if (!dropdownWrapHeader.contains(e.target)) {
        dropdownMenuHeader.classList.remove("show");
      }
    });
  }
  // Dropdown CON
  const dropdownWrap = document.querySelector(".dropdown-wrap");
  const dropdownMenu = document.querySelector(".dropdown-menu");
  const dropdownBtn = document.querySelector(".dropdown-btn");

  if (dropdownWrap && dropdownMenu && dropdownBtn) {
    dropdownBtn.addEventListener("click", function (e) {
      e.stopPropagation();
      dropdownMenu.classList.toggle("show");
    });

    document.addEventListener("click", function (e) {
      if (!dropdownWrap.contains(e.target)) {
        dropdownMenu.classList.remove("show");
      }
    });
  }
});
