function sectionProgram() {
    const section = document.getElementById("program");
    const toggleButton = section.querySelector(".program__header-btn");
    const buttonTxt = toggleButton.querySelector("span");
    toggleButton.addEventListener("click", function () {
        section.classList.toggle("hidden");
        toggleButton.classList.toggle("active");
        if (toggleButton.classList.contains("active")) {
            buttonTxt.textContent = "Masquer tout";
        } else {
            buttonTxt.textContent = "Afficher tout";
        }
    });
}

export default sectionProgram;
