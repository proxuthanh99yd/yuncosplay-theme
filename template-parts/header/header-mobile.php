<?php
$_MENU_OPEN_ICON_ID = 28;
$_ARROW_DOWN_ICON_ID = 16;
$_MENU_CLOSE_ICON_ID = 30;
$MENU_CONST = [
    [
        'title' => 'Circuit',
        'link' => [
            'title' => 'Circuit',
            'url' => '#',
            'target' => '_blank'
        ],
        'has_sub_menu' => true,
        'sub_menu' => [
            [
                'title' => 'Circuit Nord',
                'link' => [
                    'title' => 'Circuit',
                    'url' => '#',
                    'target' => '_blank'
                ],
            ],
            [
                'title' => 'Circuit Centre',
                'link' => [
                    'title' => 'Circuit',
                    'url' => '#',
                    'target' => '_blank'
                ],
            ],
            [
                'title' => 'Circuit Sud',
                'link' => [
                    'title' => 'Circuit',
                    'url' => '#',
                    'target' => '_blank'
                ],
            ],
            [
                'title' => 'Circuit Sur Mesure',
                'link' => [
                    'title' => 'Circuit',
                    'url' => '#',
                    'target' => '_blank'
                ],
            ],
            [
                'title' => 'Circuit Nord',
                'link' => [
                    'title' => 'Circuit',
                    'url' => '#',
                    'target' => '_blank'
                ],
            ],
            [
                'title' => 'Circuit Centre',
                'link' => [
                    'title' => 'Circuit',
                    'url' => '#',
                    'target' => '_blank'
                ],
            ],
            [
                'title' => 'Circuit Sud',
                'link' => [
                    'title' => 'Circuit',
                    'url' => '#',
                    'target' => '_blank'
                ],
            ],
            [
                'title' => 'Circuit Sur Mesure',
                'link' => [
                    'title' => 'Circuit',
                    'url' => '#',
                    'target' => '_blank'
                ],
            ],
        ],
    ],
    [
        'link' => [
            'title' => 'Destination',
            'url' => '#',
            'target' => '_blank'
        ],
        'has_sub_menu' => true,
        'sub_menu' => [
            [
                'title' => 'Nord Vietnam',
                'link' => [
                    'title' => 'Circuit',
                    'url' => '#',
                    'target' => '_blank'
                ],
            ],
            [
                'title' => 'Centre Vietnam',
                'link' => [
                    'title' => 'Circuit',
                    'url' => '#',
                    'target' => '_blank'
                ],
            ],
            [
                'title' => 'Sud Vietnam',
                'link' => [
                    'title' => 'Circuit',
                    'url' => '#',
                    'target' => '_blank'
                ],
            ],
            [
                'title' => 'Sur Mesure',
                'link' => [
                    'title' => 'Circuit',
                    'url' => '#',
                    'target' => '_blank'
                ],
            ],
        ],
    ],
    [
        'link' => [
            'title' => 'iNFO PRATIQUE',
            'url' => '#',
            'target' => '_blank'
        ],
        'has_sub_menu' => false,
    ],
    [
        'link' => [
            'title' => 'CULTURE',
            'url' => '#',
            'target' => '_blank'
        ],
        'has_sub_menu' => false,
    ],
    [
        'link' => [
            'title' => 'QUI SOMMES-NOUS',
            'url' => '#',
            'target' => '_blank'
        ],
        'has_sub_menu' => false,
    ]
];

?>
<header id="header" class="header header--mobile">
    <div class="header__nav">
        <a href="<?= home_url() ?>" class="header__logo">
            <img class="header__logo--white" src="/wp-content/uploads/2025/03/header-logo-white.png"
                alt="Horizon Vietnam Travel">
            <img class="header__logo--color" src="/wp-content/uploads/2025/03/header-logo-color.png"
                alt="Horizon Vietnam Travel">
        </a>
        <button class="header__nav-toggle">
            <?= wp_get_attachment_image($_MENU_OPEN_ICON_ID, 'full') ?>
        </button>
    </div>
</header>
<nav class="header__menu-nav">
    <div class="header__nav">
        <a href="<?= home_url() ?>" class="header__logo">
            <img class="header__logo--color" src="/wp-content/uploads/2025/03/header-logo-color.png"
                alt="Horizon Vietnam Travel">
        </a>
        <button class="header__nav-toggle--close">
            <?= wp_get_attachment_image($_MENU_CLOSE_ICON_ID, 'full') ?>
        </button>
    </div>
    <?php if (!empty($MENU_CONST)): ?>
    <ul class="header__menu-list">
        <?php foreach ($MENU_CONST as $menu_item):
                $menu_item_link_url = '';
                $menu_item_link_title = '';
                $menu_item_link_target = '_self';
                if (array_key_exists('link', $menu_item) && $menu_item['link']) {
                    $menu_item_link =  $menu_item['link'];
                    $menu_item_link_url = array_key_exists('url', $menu_item_link) ? $menu_item_link['url'] : '';
                    $menu_item_link_title = array_key_exists('title', $menu_item_link) ? $menu_item_link['title'] : '';
                    $menu_item_link_target = array_key_exists('target', $menu_item_link) ? $menu_item_link['target'] : '_self';
                }
            ?>
        <li class="header__menu-item">
            <a href="<?= $menu_item_link_url ?>" target="<?= $menu_item_link_target ?>" class="header__menu-link">
                <?= $menu_item_link_title ?>
                <?= $menu_item['has_sub_menu'] ? wp_get_attachment_image($_ARROW_DOWN_ICON_ID, 'full') : '' ?>
            </a>
            <?php if ($menu_item['has_sub_menu']): ?>
            <div class="header__sub-menu">
                <ul class="header__sub-menu-list">
                    <?php foreach ($menu_item['sub_menu'] as $sub_menu_item):
                                    $sub_menu_item_link_url = '';
                                    $sub_menu_item_link_title = '';
                                    $sub_menu_item_link_target = '_self';
                                    if (array_key_exists('link', $sub_menu_item) && $sub_menu_item['link']) {
                                        $sub_menu_item_link =  $sub_menu_item['link'];
                                        $sub_menu_item_link_url = array_key_exists('url', $sub_menu_item_link) ? $sub_menu_item_link['url'] : '';
                                        $sub_menu_item_link_title = array_key_exists('title', $sub_menu_item_link) ? $sub_menu_item_link['title'] : '';
                                        $sub_menu_item_link_target = array_key_exists('target', $sub_menu_item_link) ? $sub_menu_item_link['target'] : '_self';
                                    }
                                ?>
                    <li class="header__sub-menu-item">
                        <a href="<?= $sub_menu_item_link_url ?>" target="<?= $sub_menu_item_link_target ?>"
                            class="header__sub-menu-link">
                            <?= $sub_menu_item_link_title ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
    <div class="header__menu-contact">
        <a href="#" class="header__menu-contact-link">
            Contactez-nous
        </a>
        <p class="header__menu-social-label">Follow Us</p>
        <div class="header__menu-social">
            <a href="#" class="header__menu-social-link">
                Facebook
            </a>
            <a href="#" class="header__menu-social-link">
                Youtube
            </a>
            <a href="#" class="header__menu-social-link">
                Instagram
            </a>
        </div>
    </div>
    <div class="header__menu-bg">
        <img src="/wp-content/uploads/2025/03/nui-1.webp" alt="">
    </div>
</nav>