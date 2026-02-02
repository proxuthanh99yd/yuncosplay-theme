document.addEventListener('wpcf7beforesubmit', function (e) {
  const btn = e.target.querySelector('button[type="submit"]');
  if (btn) btn.disabled = true;
});

document.addEventListener('wpcf7submit', function (e) {
  const btn = e.target.querySelector('button[type="submit"]');
  if (btn) btn.disabled = false;
});
