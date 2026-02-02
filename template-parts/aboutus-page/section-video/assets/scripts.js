export function sectionVideoScripts() {
  const video = document.querySelector('.about__media__video');
  const poster = document.querySelector('.about__media__picture');
  const container = document.querySelector('.about__media__container');

  if (!container || !video || !poster) return

  container.addEventListener('click', () => {
    const videoSource = video.dataset.src;
    container.classList.add('active');
    poster.classList.add('active');

    video.src = videoSource;
    video.load();
    video.play();
  });


}