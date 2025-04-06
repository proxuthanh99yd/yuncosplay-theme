function sectionPresentationHorizon() {
  const playerVideoPresentationHorizon = document.getElementById(
    "plyr-video-presentation-horizon"
  );
  if (playerVideoPresentationHorizon) {
    new Plyr(playerVideoPresentationHorizon);
  }
}
export default sectionPresentationHorizon;
