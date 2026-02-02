<?php
$share_link_image_id = 2253;
$facebook_image_id = 2254;
$linkedin_image_id = 2255;
$twitter_image_id = 2252;
?>

<div class="socials" aria-label="Share">
  <div class="socials__item">
    <button
      type="button"
      id="share-btn"
      class="socials__link share-btn"
      aria-label="Share on Link">
      <?= wp_get_attachment_image($share_link_image_id, 'full', false, array('class' => '')) ?>
    </button>
  </div>
  <div class="socials__item">
    <a class="socials__link socials__link--facebook" href="<?= esc_url('https://www.facebook.com/sharer/sharer.php?u=' . urlencode(get_permalink())); ?>" target="_blank" rel="noopener noreferrer" aria-label="Share on Facebook">
      <?= wp_get_attachment_image($facebook_image_id, 'full', false, array('class' => '')) ?>
    </a>
  </div>
  <div class="socials__item">
    <a class="socials__link socials__link--linkedin" href="<?= esc_url('https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode(get_permalink())); ?>" target="_blank" rel="noopener noreferrer" aria-label="Share on LinkedIn">
      <?= wp_get_attachment_image($linkedin_image_id, 'full', false, array('class' => '')) ?>
    </a>
  </div>
  <div class="socials__item">
    <a class="socials__link socials__link--twitter" href="<?= esc_url('https://twitter.com/intent/tweet?url=' . urlencode(get_permalink()) . '&text=' . urlencode(get_the_title())); ?>" target="_blank" rel="noopener noreferrer" aria-label="Share on Twitter">
      <?= wp_get_attachment_image($twitter_image_id, 'full', false, array('class' => '')) ?>
    </a>
  </div>
</div>