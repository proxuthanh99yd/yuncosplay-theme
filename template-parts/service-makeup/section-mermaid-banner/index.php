<?php 
    $are_you_ready = get_field('areyouready', get_the_ID());
    $ready_title = $are_you_ready['title'] ?? '';
    $ready_desc = $are_you_ready['desc'] ?? '';
    $image_desktop = $are_you_ready['image_desktop'] ?? null;
    $image_mobile = $are_you_ready['image_mobile'] ?? null;
    $image_desktop_id = is_array($image_desktop) ? ($image_desktop['ID'] ?? null) : $image_desktop;
    $image_mobile_id = is_array($image_mobile) ? ($image_mobile['ID'] ?? null) : $image_mobile;
    $image_desktop_alt = is_array($image_desktop) ? ($image_desktop['alt'] ?: ($image_desktop['title'] ?? 'Yun Cosplay CTA Desktop')) : 'Yun Cosplay CTA Desktop';
    $image_mobile_alt = is_array($image_mobile) ? ($image_mobile['alt'] ?: ($image_mobile['title'] ?? 'Yun Cosplay CTA Mobile')) : 'Yun Cosplay CTA Mobile';
    $link_socials = function_exists('get_field') ? get_field('cta', 'option') : [];
 

 ?>
<section class="faq-cosplay-cta">
    <?= wp_get_attachment_image(10266, 'full', false, [ 'class' => 'faq-cosplay-cta__page-bg', 'alt' => '', 'aria-hidden' => 'true', 'loading' => 'lazy', ]) ?>
    <div class="faq-cosplay-cta__container">
        <div class="faq-cosplay-cta__banner"> <?php if (!empty($image_desktop_id)) : ?>
            <?= wp_get_attachment_image($image_desktop_id, 'full', false, [ 'class' => 'faq-cosplay-cta__image faq-cosplay-cta__image--desktop', 'alt' => esc_attr($image_desktop_alt), 'loading' => 'lazy', ]) ?>
            <?php endif;?> <?php if (!empty($image_mobile_id)) : ?>
            <?= wp_get_attachment_image($image_mobile_id, 'full', false, [ 'class' => 'faq-cosplay-cta__image faq-cosplay-cta__image--mobile', 'alt' => esc_attr($image_mobile_alt), 'loading' => 'lazy', ]) ?>
            <?php endif; ?> <div class="faq-cosplay-cta__overlay"></div>
            <div class="faq-cosplay-cta__content"> <?php if (!empty($ready_title)) : ?> <div
                    class="faq-cosplay-cta__title"> <?= wp_kses_post($ready_title); ?> </div> <?php endif; ?>
                <?php if (!empty($ready_desc)) : ?> <p class="faq-cosplay-cta__desc"> <?= esc_html($ready_desc); ?> </p>
                <?php endif; ?> <div class="cta_socials"> <?php if (isset($link_socials['link_zalo'])) : ?> <a
                        href="<?= $link_socials['link_zalo']['url'] ?>"
                        target="<?= $link_socials['link_zalo']['target'] ?>">
                        <div class="icon"> <svg xmlns="http://www.w3.org/2000/svg" width="33" height="13"
                                viewBox="0 0 33 13" fill="none">
                                <path
                                    d="M7.21575 2.33978L2.35083 10.3163V10.4936H7.21575V12.6206H0V10.4936L4.89757 2.51703V2.33978H0V0.212707H7.21575V2.33978Z"
                                    fill="#F6F3EA" />
                                <path
                                    d="M7.67707 12.6206V12.2307L10.1912 0.212707L13.9786 0.230432L16.509 12.2307V12.6206H14.4194L13.7827 9.21731H10.4034L9.7667 12.6206H7.67707ZM10.6972 7.30295H13.4725L12.542 2.26888H11.6441L10.6972 7.30295Z"
                                    fill="#F6F3EA" />
                                <path d="M17.5301 0.212707H19.5381V10.6354H22.9011V12.6206H17.5301V0.212707Z"
                                    fill="#F6F3EA" />
                                <path
                                    d="M23.1001 6.41667C23.1001 4.9159 23.3232 3.69283 23.7694 2.74747C24.2156 1.8021 24.7979 1.1108 25.5162 0.673573C26.2454 0.224524 27.0345 0 27.8834 0C28.7323 0 29.5159 0.224524 30.2342 0.673573C30.9634 1.1108 31.5511 1.8021 31.9973 2.74747C32.4436 3.69283 32.6667 4.9159 32.6667 6.41667C32.6667 7.91743 32.4436 9.1405 31.9973 10.0859C31.5511 11.0312 30.9634 11.7284 30.2342 12.1775C29.5159 12.6147 28.7323 12.8333 27.8834 12.8333C27.0345 12.8333 26.2454 12.6147 25.5162 12.1775C24.7979 11.7284 24.2156 11.0312 23.7694 10.0859C23.3232 9.1405 23.1001 7.91743 23.1001 6.41667ZM25.1734 6.41667C25.1734 7.38567 25.2931 8.18923 25.5325 8.82735C25.772 9.46547 26.093 9.94997 26.4957 10.2808C26.9093 10.5999 27.3664 10.7594 27.867 10.7594C28.3677 10.7594 28.8248 10.5999 29.2384 10.2808C29.6628 9.94997 29.9948 9.46547 30.2342 8.82735C30.4845 8.18923 30.6097 7.38567 30.6097 6.41667C30.6097 5.43585 30.4845 4.62638 30.2342 3.98826C29.9839 3.35014 29.6519 2.87155 29.2384 2.55249C28.8248 2.23343 28.3731 2.07389 27.8834 2.07389C27.3718 2.07389 26.9093 2.23933 26.4957 2.57021C26.093 2.88927 25.772 3.36786 25.5325 4.00599C25.2931 4.64411 25.1734 5.44767 25.1734 6.41667Z"
                                    fill="#F6F3EA" />
                            </svg> </div>
                    </a> <?php endif; ?> <?php if (isset($link_socials['link_messenger'])) : ?> <a
                        href="<?= $link_socials['link_messenger']['url'] ?>"
                        target="<?= $link_socials['link_messenger']['target'] ?>">
                        <div class="icon"> <svg xmlns="http://www.w3.org/2000/svg" width="33" height="33"
                                viewBox="0 0 33 33" fill="none">
                                <path
                                    d="M16.3333 1.30859C8.03648 1.30859 1.30664 7.58161 1.30664 15.3553C1.30664 19.4718 3.20029 23.3254 6.53331 26.0051V31.7549L12.153 28.8149C13.5235 29.2054 14.8965 29.3356 16.3333 29.3356C24.6301 29.3356 31.36 23.0651 31.36 15.2889C31.36 7.58161 24.6301 1.30859 16.3333 1.30859ZM17.8365 19.9949L14.0466 15.9422L6.99013 19.9286L14.8301 11.6318L18.6863 15.4854L25.5463 11.6318L17.8365 19.9949Z"
                                    fill="#F6F3EA" />
                            </svg> </div>
                    </a> <?php endif; ?> <?php if (isset($link_socials['link_hotline'])) : ?> <a
                        href="<?= $link_socials['link_hotline']['url'] ?>"
                        target="<?= $link_socials['link_hotline']['target'] ?>">
                        <div class="icon"> <svg style="width: 1.625rem;" xmlns="http://www.w3.org/2000/svg" width="26"
                                height="26" viewBox="0 0 26 26" fill="none">
                                <path
                                    d="M23.8818 18.98C23.1717 18.3844 19.0068 15.7471 18.3146 15.8681C17.9896 15.9258 17.7409 16.2029 17.0755 16.9967C16.7678 17.3874 16.4298 17.7533 16.0647 18.0911C15.3959 17.9296 14.7484 17.6896 14.1359 17.3761C11.7335 16.2065 9.79276 14.2652 8.62388 11.8625C8.31044 11.2499 8.07045 10.6025 7.90887 9.93363C8.24665 9.56857 8.61259 9.23061 9.00331 8.92288C9.79631 8.25744 10.0742 8.01044 10.1319 7.68381C10.2529 6.98994 7.61313 2.82669 7.02 2.11656C6.77138 1.82244 6.5455 1.625 6.25625 1.625C5.41775 1.625 1.625 6.31475 1.625 6.9225C1.625 6.97206 1.70625 11.8544 7.87231 18.1277C14.1456 24.2938 19.0279 24.375 19.0775 24.375C19.6852 24.375 24.375 20.5822 24.375 19.7437C24.375 19.4545 24.1776 19.2286 23.8818 18.98ZM18.6875 12.1875H20.3125C20.3106 10.4642 19.6251 8.81201 18.4066 7.59344C17.188 6.37488 15.5358 5.68944 13.8125 5.6875V7.3125C15.105 7.31379 16.3443 7.82782 17.2582 8.74178C18.1722 9.65574 18.6862 10.895 18.6875 12.1875Z"
                                    fill="#F6F3EA" />
                                <path
                                    d="M22.75 12.1875H24.375C24.3718 9.38714 23.2579 6.7024 21.2778 4.72225C19.2976 2.74209 16.6129 1.62823 13.8125 1.625V3.25C16.182 3.2528 18.4537 4.19532 20.1292 5.87082C21.8047 7.54632 22.7472 9.81799 22.75 12.1875Z"
                                    fill="#F6F3EA" />
                            </svg> </div>
                    </a> <?php endif; ?> </div>
            </div>
        </div>
    </div>
</section>