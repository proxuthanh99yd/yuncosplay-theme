export function initRelatedTourSection() {
    const section = document.querySelector(".related-tour");
    if (!section) return;

    const track = section.querySelector(".related-tour__track");
    if (!track) return;

    const mq = window.matchMedia("(max-width: 639.98px)");

    if (mq.matches) return;

    let isDown = false;
    let startX = 0;
    let scrollLeft = 0;

    const down = (e) => {
        isDown = true;
        startX = e.pageX || 0;
        scrollLeft = track.scrollLeft;
        track.classList.add("is-dragging");
    };

    const move = (e) => {
        if (!isDown) return;
        const x = e.pageX || 0;
        const walk = startX - x;
        track.scrollLeft = scrollLeft + walk;
    };

    const up = () => {
        if (!isDown) return;
        isDown = false;
        track.classList.remove("is-dragging");
    };

    track.addEventListener("mousedown", down);
    window.addEventListener("mousemove", move);
    window.addEventListener("mouseup", up);
}
