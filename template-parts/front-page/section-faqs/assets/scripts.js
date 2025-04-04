class FaqsAccordion {
    constructor(section) {
        this.section = document.getElementById(section);
        this.accordionBtns = Array.from(
            this.section.getElementsByClassName("faqs__item-title")
        );
        // console.log(this.accordionBtns);
        this.handleAccordion();
    }

    handleAccordion() {
        const btnDomMapping = {};

        this.accordionBtns.forEach((btn, index) => {
            const content = btn.nextElementSibling;
            const contentText = content.querySelector(
                ".faqs__item-content-text"
            );

            // Chỉ xử lý nếu có submenu
            if (content && contentText) {
                btnDomMapping[index] = {
                    btn,
                    content,
                    contentText,
                };

                btn.addEventListener("click", function (e) {
                    e.preventDefault();

                    const isActive = btn.classList.contains("active");

                    // Đóng tất cả các menu trước khi mở menu mới
                    Object.keys(btnDomMapping).forEach((key) => {
                        const { btn, content, contentText } =
                            btnDomMapping[key];

                        btn.classList.remove("active");

                        if (content && contentText) {
                            content.style.maxHeight = null;
                        }
                    });

                    // Nếu menu chưa mở, thì mở nó
                    if (!isActive) {
                        btn.classList.add("active");
                        content.style.maxHeight =
                            contentText.scrollHeight + 16 + "px";
                    }
                });
            }
            // console.log(btnDomMapping);
        });

        btnDomMapping[0].btn.click(); // Mở menu đầu tiên
    }
}

export default FaqsAccordion;
