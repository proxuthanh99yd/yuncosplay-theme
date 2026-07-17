function initAllSwipers() {
    const desktopSwiperElement = document.querySelector('.swiper-desktop');
    if (desktopSwiperElement) {
        new Swiper(desktopSwiperElement, {
            slidesPerView: 2,
            spaceBetween: 24,
            loop: true,
            autoplay: {
                delay: 3000,
            },
            navigation: {
                nextEl: '.featured-desktop-wrapper .next',
                prevEl: '.featured-desktop-wrapper .prev',
            },
            pagination: {
                el: '.pagination-desktop',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '"></span>';
                },
            },
        });
    }

    // 2. Khởi tạo Swiper Mobile
    const mobileSwiperElement = document.querySelector('.swiper-mobile');
    if (mobileSwiperElement) {
        new Swiper(mobileSwiperElement, {
            slidesPerView: 1,
            spaceBetween: 15,
            loop: true,
            autoplay: {
                delay: 3000,
            },
            pagination: {
                el: '.pagination-mobile',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '"></span>';
                },
            },
        });
    }
}
