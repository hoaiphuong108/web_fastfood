document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("form");
  form.addEventListener("submit", function (e) {
    const email = form.email.value.trim();
    const password = form.password.value.trim();

    if (!email || !password) {
      e.preventDefault(); // chặn submit
      alert("Vui lòng nhập đầy đủ email và mật khẩu!");
    }
  });
});
