document.addEventListener("DOMContentLoaded", function () {
  document.addEventListener("wpcf7mailsent", function (event) {
    let messagesContainer = document.querySelector(".messages-form");
    if (messagesContainer) {
      messagesContainer.innerHTML =
        '<p class="success">Merci! Votre inscription a été envoyée.</p>';
      messagesContainer.style.color = "green";
    }
  });

  document.addEventListener("wpcf7invalid", function (event) {
    let messagesContainer = document.querySelector(".messages-form");
    if (messagesContainer) {
      messagesContainer.innerHTML =
        '<p class="error">Erreur! Veuillez vérifier vos informations.</p>';
      messagesContainer.style.color = "red";
    }
  });

  // Validation custom
  let form = document.querySelector(".footer-form form");
  if (form) {
    let emailField = form.querySelector('input[type="email"]');
    let messagesContainer = document.querySelector(".messages-form");

    form.addEventListener("submit", function (event) {
      if (emailField && !emailField.value.match(/^\S+@\S+\.\S+$/)) {
        event.preventDefault();
        if (messagesContainer) {
          messagesContainer.innerHTML =
            '<p class="error">Veuillez entrer une adresse e-mail valide.</p>';
          messagesContainer.style.color = "red";
        }
      }
    });

    // Validation onchange
    if (emailField) {
      emailField.addEventListener("input", function () {
        if (!emailField.value.match(/^\S+@\S+\.\S+$/)) {
          if (messagesContainer) {
            messagesContainer.innerHTML =
              '<p class="error">Format e-mail invalide.</p>';
            messagesContainer.style.color = "red";
          }
        } else {
          if (messagesContainer) {
            messagesContainer.innerHTML = "";
          }
        }
      });
    }
  }
});

// handle acordion on mobile
const titleToggle = document.querySelectorAll(
  ".footer-body__links--group.acordion .footer-body__links--item__title"
);

const footer_body = document.querySelector(".footer-body");
function isMobile() {
  return window.innerWidth <= 768; // Xác định mobile (mày có thể chỉnh lại breakpoint)
}

if (isMobile()) {
  titleToggle.forEach((title) => {
    title.addEventListener("click", function () {
      const body = this.nextElementSibling; // Lấy phần nội dung accordion
      const isOpen = body.style.height;

      // Đóng tất cả accordion khác trước khi mở cái mới
      document
        .querySelectorAll(".footer-body__links--item__list")
        .forEach((item) => {
          item.style.height = null;
        });

      // Nếu đang đóng thì mở, nếu mở rồi thì giữ nguyên trạng thái đóng
      if (!isOpen) {
        body.style.height = body.scrollHeight + "px";
      }
    });
  });
}
