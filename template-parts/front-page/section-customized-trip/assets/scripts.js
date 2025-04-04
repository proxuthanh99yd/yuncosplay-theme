const data = [
    {
        tag: "Aventure",
        image: "https://via.placeholder.com/300x200",
        title: "Aventure en famille 1",
        day: 5,
        night: 4,
        location: [
            "Hanoi",
            "Tuan Chau",
            "Lan Ha Bay",
            "Ha Giang",
            "Cao Bang",
            "Hanoi",
        ],
        link: "#",
    },
    {
        tag: "Aventure",
        image: "https://via.placeholder.com/300x200",
        title: "Aventure en famille 2",
        day: 5,
        night: 4,
        location: [
            "Hanoi",
            "Tuan Chau",
            "Lan Ha Bay",
            "Ha Giang",
            "Cao Bang",
            "Hanoi",
        ],
        link: "#",
    },
    {
        tag: "Aventure",
        image: "https://via.placeholder.com/300x200",
        title: "Aventure en famille 3",
        day: 5,
        night: 4,
        location: [
            "Hanoi",
            "Tuan Chau",
            "Lan Ha Bay",
            "Ha Giang",
            "Cao Bang",
            "Hanoi",
        ],
        link: "#",
    },
    {
        tag: "Aventure",
        image: "https://via.placeholder.com/300x200",
        title: "Aventure en famille 4",
        day: 5,
        night: 4,
        location: [
            "Hanoi",
            "Tuan Chau",
            "Lan Ha Bay",
            "Ha Giang",
            "Cao Bang",
            "Hanoi",
        ],
        link: "#",
    },
    {
        tag: "Aventure",
        image: "https://via.placeholder.com/300x200",
        title: "Aventure en famille 5",
        day: 5,
        night: 4,
        location: [
            "Hanoi",
            "Tuan Chau",
            "Lan Ha Bay",
            "Ha Giang",
            "Cao Bang",
            "Hanoi",
        ],
        link: "#",
    },
];

const fakeData = new Promise((resolve) => {
    setTimeout(() => {
        resolve(data);
    }, 3000);
});

class HandleFetchTour {
    constructor(swiper) {
        this.swiper = swiper;
        this.template = document.getElementById(
            "customized-trip__card-template"
        );
        this.navList = document.querySelector(".customized-trip__nav-list");
        this.navs = Array.from(
            document.getElementsByClassName("customized-trip__nav-item")
        );
        this.selectBtn = document.querySelector(".customized-trip__nav-select");
        this.init();
    }
    init() {
        this.navs.forEach((nav) => {
            nav.addEventListener("click", () => {
                this.navs.forEach((el) => el.classList.remove("active"));
                nav.classList.add("active");
                this.selectBtn.querySelector(".text").textContent =
                    nav.textContent;
                this.selectBtn.querySelector(".customized-trip__nav-flag").src =
                    nav.querySelector(".customized-trip__nav-flag").src;

                this.selectBtn.classList.remove("active");
                this.fetchData(nav);
            });
        });
        this.selectBtn.addEventListener("click", () => {
            this.selectBtn.classList.toggle("active");
        });
        document.addEventListener("click", (e) => {
            if (
                !this.navList.contains(e.target) &&
                !this.selectBtn.contains(e.target)
            ) {
                this.selectBtn.classList.remove("active");
            }
        });
    }
    async fetchData(nav) {
        try {
            this.swiper.wrapperEl.classList.add("loading");
            const data = await fakeData;
            this.swiper.removeAllSlides();
            this.updateContent.bind(this)(data);
            this.swiper.update();
        } catch (error) {
            console.log(error);
        } finally {
            this.swiper.wrapperEl.classList.remove("loading");
        }
    }
    updateContent(data) {
        if (!data) {
            this.swiper.wrapperEl.innerHTML = "No data";
            return;
        }
        data.forEach(({ tag, image, title, day, night, location, link }) => {
            const clone = this.template.content.cloneNode(true);
            const card = clone.querySelector(".customized-trip__card");
            const div = document.createElement("div");
            div.classList.add("swiper-slide");
            const cardImage = clone.querySelector(
                ".customized-trip__card-image"
            );
            const cardTitle = clone.querySelector(
                ".customized-trip__card-title"
            );
            const cardTag = clone.querySelector(
                ".customized-trip__card-image-icon span"
            );
            const cardDuration = clone.querySelector(
                ".customized-trip__card-duration"
            );
            const cardLocation = clone.querySelector(
                ".customized-trip__card-location span"
            );
            const cardLink = clone.querySelector(".customized-trip__card-link");
            cardImage.src = image;
            cardTitle.textContent = title;
            cardTag.textContent = tag;
            cardDuration.textContent = `${day} jours - ${night} nuits`;
            cardLocation.textContent = location.join(" - ");
            cardLink.href = link;
            div.appendChild(card);
            this.swiper.appendSlide(div);
        });
    }
}

function sectionCustomizedTrip() {
    let extraConfig = {};
    if (window.innerWidth < 640) {
        extraConfig = {
            slidesOffsetBefore: remToPixels(1),
            slidesOffsetAfter: remToPixels(1),
        };
    }
    const customizedSwiper = new Swiper(".customized-trip__swiper", {
        slidesPerView: 1.2,
        spaceBetween: remToPixels(1.11),
        breakpoints: {
            640: {
                slidesPerView: 4,
            },
        },
        ...extraConfig,
        navigation: {
            nextEl: ".customized-trip__swiper-button-next",
            prevEl: ".customized-trip__swiper-button-prev",
        },
        pagination: {
            el: ".customized-trip__swiper-pagination",
            clickable: true,
        },
    });
    const handleFetchTour = new HandleFetchTour(customizedSwiper);
}
export default sectionCustomizedTrip;
