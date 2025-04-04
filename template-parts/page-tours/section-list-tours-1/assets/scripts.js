function listTours() {
    document
        .querySelector("custom-dropdown")
        .addEventListener("change", (event) => {
            console.log("Giá trị được chọn:", event.detail.value);
        });

    const filterOpens = Array.from(
        document.getElementsByClassName("list-tours__nav--mb__filter")
    );
    const filterPopups = Array.from(
        document.getElementsByClassName("list-tours__filter-popup")
    );
    const filterCloseButtons = Array.from(
        document.getElementsByClassName("list-tours__filter-header")
    );
    const filterOverlays = Array.from(
        document.getElementsByClassName("list-tours__filter-overlay")
    );

    const filterCancelButtons = Array.from(
        document.getElementsByClassName("list-tours__filter-footer-cancel")
    );

    const filterForms = Array.from(
        document.getElementsByClassName("list-tours__filter-form")
    );

    const checkboxes = Array.from(
        document.getElementsByClassName("list-tours__filter-item-checkbox")
    );

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener("click", (el) => {
            checkboxes.forEach((checkbox) => {
                if (checkbox !== el.target) {
                    checkbox.checked = false;
                }
            });
        });
    });

    filterOpens.forEach((filterOpen, index) => {
        filterOpen.addEventListener("click", (e) => {
            e.preventDefault();
            document.body.classList.add("no-scroll");
            filterPopups[index].classList.toggle("active");
        });
    });

    const closePopup = (index) => {
        document.body.classList.remove("no-scroll");
        filterPopups[index].classList.remove("active");
    };
    filterCloseButtons.forEach((filterCloseButton, index) => {
        filterCloseButton.addEventListener("click", () => closePopup(index));
    });
    filterOverlays.forEach((filterOverlay, index) => {
        filterOverlay.addEventListener("click", () => closePopup(index));
    });
    filterCancelButtons.forEach((filterCancelButton, index) => {
        filterCancelButton.addEventListener("click", () => closePopup(index));
    });

    filterForms.forEach((filterForm, index) => {
        filterForm.addEventListener("submit", (e) => {
            e.preventDefault();
            const formData = new FormData(filterForm);
            const data = Object.fromEntries(formData.entries());
            console.log(data);
            closePopup(index);
        });
    });
}

export default listTours;
