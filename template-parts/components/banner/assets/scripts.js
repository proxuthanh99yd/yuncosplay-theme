function bannerComponent() {
    document.addEventListener('DOMContentLoaded', function () {
        const btn_scroll = document.querySelector('.component-banner .component-banner__content .component-banner__down-icon');
        const bannerComponent = document.querySelector('.component-banner');

        if (btn_scroll && bannerComponent) {
            btn_scroll.addEventListener('click', function () {
                const bannerHeight = bannerComponent.offsetHeight;
                window.scrollTo({
                    top: bannerHeight,
                    behavior: 'smooth'
                });
            });
        }
    });
}
export default bannerComponent;
