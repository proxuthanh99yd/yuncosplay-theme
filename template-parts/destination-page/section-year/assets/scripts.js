export function sectionYearScripts() {
    const tabs = document.querySelectorAll(".destination-year_tab");
    const content = document.querySelector(".destination-year_content-html");
    const img = document.querySelector(".destination-year_img");
    
    if (!tabs.length || !content || !img) return;
    
    tabs.forEach((tab) => {
        tab.addEventListener("click", () => {
            const dataMonth = tab.getAttribute("data-month"); 
            tabs.forEach(tab => tab.classList.remove("active"));
            tab.classList.add("active");
            
            const item = years.find(data => data.month.toString() === dataMonth);
            
            if (!item) return;
            
            content.innerHTML = item.content;
            img.src = item.image;
            img.srcset = '';
        })
    })

}