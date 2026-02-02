<?php
$section_overview = get_field('overview');
$title = $section_overview['title'];
$desc = $section_overview['desc'];
$contact = $section_overview['contact'];

$title_contact = $contact['title'];
$desc_contact = $contact['desc'];
$contact_link = $contact['contact_link'];
$tripadvisor_link = $contact['tripadvisor_link'];

$icon_phone_id = 1836;
?>

<section id="overview">
  <div class="container">
  <div class="breadcrumb">
    <a href="<?= home_url() ?>" class="breadcrumb__link">Home</a>
    <span class="breadcrumb__separator">/</span>
    <a href="<?= home_url() ?>/hotels-and-resorts" class="breadcrumb__link last-link">Hotels and Resorts</a>
  </div>

  <div class="overview__content">
    <div class="overview__content-left">
      <h2 class="overview__content-left-title"><?= $title ?></h2>
      <div class="overview__content-left-desc"><?= $desc ?></div>
    </div>
    <div class="overview__content-right">
      <div class="overview__content-right-contact">
        <?= wp_get_attachment_image($icon_phone_id, 'full', false, array( 'class' => 'overview__content-right-contact-icon')) ?>
      </div>
      <h2 class="overview__content-right-title"><?= $title_contact ?></h2>
      <p class="overview__content-right-desc"><?= $desc_contact ?></p>
      <a href="<?= $contact_link['url'] ?>" class="overview__content-right-link compound-avian-button">
        <p><?= $contact_link['title'] ?></p>
      </a>
      <a href="<?= $tripadvisor_link['url'] ?>" target="_blank" class="overview__content-right-tripadvisor">
        <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
          <g clip-path="url(#clip0_1382_6508)">
          <path d="M11.7597 20.6706C8.95373 23.2683 4.62596 23.0869 2.04056 20.4619C0.650084 19.05 -0.0360598 17.34 0.00145925 15.3594C0.0389783 13.3736 0.85587 11.7205 2.25715 10.3267C1.49767 9.49991 0.751271 8.68729 0.00543854 7.87468L0.0190818 7.8434C0.0685387 7.8434 0.117996 7.8434 0.167453 7.8434C1.7495 7.8434 3.33099 7.8434 4.91304 7.84568C5.02958 7.84568 5.12622 7.82009 5.22513 7.75412C7.05845 6.52753 9.0623 5.72231 11.2339 5.33675C12.4913 5.11384 13.7584 5.04333 15.0324 5.12919C17.8707 5.32083 20.4755 6.20453 22.8432 7.7854C22.9017 7.82464 22.987 7.84056 23.0597 7.84056C24.6503 7.8434 26.2409 7.84283 27.8309 7.84283C27.8775 7.84283 27.9247 7.84283 27.9713 7.84283L27.9924 7.87184C27.2499 8.68047 26.5075 9.4891 25.7549 10.3091C25.8089 10.37 25.8583 10.428 25.9089 10.4843C26.5581 11.2064 27.118 11.9849 27.4893 12.8925C28.8661 16.259 27.3608 20.2095 24.0921 21.8028C22.9455 22.3618 21.7375 22.6018 20.4692 22.5051C18.8826 22.3846 17.4933 21.7841 16.304 20.7247C16.2853 20.7082 16.2654 20.6928 16.2364 20.6695C15.4928 21.4781 14.7533 22.2833 13.9978 23.1056C13.2497 22.2919 12.5089 21.485 11.7597 20.6706ZM20.9916 20.2419C23.6129 20.2333 25.7219 18.1202 25.7162 15.5084C25.7105 12.8948 23.5896 10.7873 20.9723 10.7947C18.3562 10.8021 16.2614 12.9244 16.2677 15.5601C16.2739 18.135 18.408 20.2498 20.9916 20.2419ZM6.98966 20.2419C9.58018 20.2487 11.7176 18.1418 11.7273 15.572C11.737 12.9289 9.64385 10.8078 7.0215 10.7936C4.40142 10.7788 2.28387 12.9227 2.27761 15.5095C2.27136 18.1134 4.38152 20.235 6.98966 20.2419ZM19.2067 8.43026C15.7265 7.06377 12.2651 7.0632 8.78887 8.43026C11.6579 9.70064 13.5976 11.6864 13.9978 14.7748C14.4002 11.6875 16.3364 9.70292 19.2067 8.43026Z" fill="#1D1D1D"/>
          <path d="M21.0035 13.0215C22.3843 13.0277 23.4889 14.1486 23.4826 15.5384C23.4763 16.902 22.3474 18.0172 20.9813 18.0086C19.5937 17.9995 18.4971 16.8793 18.5039 15.4775C18.5102 14.1224 19.6369 13.0158 21.0035 13.0221V13.0215Z" fill="#1A1A1A"/>
          <path d="M6.99989 13.0215C8.3574 13.0136 9.48695 14.1281 9.4949 15.4844C9.50343 16.8736 8.39889 18.0013 7.02206 18.0092C5.65091 18.0172 4.5242 16.91 4.51567 15.5458C4.50715 14.1486 5.60771 13.0295 6.99932 13.0215H6.99989Z" fill="#1A1A1A"/>
          </g>
          <defs>
          <clipPath id="clip0_1382_6508">
          <rect width="28" height="18.0117" fill="white" transform="translate(0 5.0918)"/>
          </clipPath>
          </defs>
        </svg>

        <p><?= $tripadvisor_link['title'] ?> reviews on</p>
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="17" viewBox="0 0 18 17" fill="none">
          <path d="M8.55859 0L11.2036 5.35942L17.1181 6.21885L12.8383 10.3906L13.8487 16.2812L8.55859 13.5L3.26853 16.2812L4.27884 10.3906L-0.000914574 6.21885L5.91356 5.35942L8.55859 0Z" fill="#FFEE00"/>
        </svg>
        <p>Trustpilot</p>
      </a>
    </div>
  </div>
  </div>
</section>