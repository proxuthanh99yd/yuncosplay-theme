<?php
$image_thumb = wp_is_mobile() ? 123 : 124;
$image_bg = 122;
$icon_mail = 121;
$icon_down = 117;
$links = [
    [
        "title" => "CIRCUITS",
        "children" => [
            ["link" => "/circuit-incontournables", "title" => "Les incontournables"],
            ["link" => "/circuit-famille", "title" => "Voyage en famille"],
            ["link" => "/circuit-autrement", "title" => "Voyage autrement"],
            ["link" => "/circuit-detente-plage", "title" => "Détente et plage"],
            ["link" => "/circuit-hors-sentiers", "title" => "Hors des sentiers battus"],
            ["link" => "/circuit-noces", "title" => "Voyage de noce"]
        ]
    ],
    [
        "title" => "DESTINATION",
        "children" => [
            ["link" => "/destination-hanoi", "title" => "Hanoi capitale"],
            ["link" => "/destination-ninh-binh", "title" => "Ninh Binh"],
            ["link" => "/destination-halong", "title" => "La baie d'Halong"],
            ["link" => "/destination-hoi-an", "title" => "Hoi An"],
            ["link" => "/destination-saigon", "title" => "Saigon"],
            ["link" => "/destination-mekong", "title" => "Delta du Mékong"],
            ["link" => "/destination-phu-quoc", "title" => "Phu Quoc"]
        ]
    ],
    [
        "title" => "CULTURE",
        "children" => [
            ["link" => "/culture-cuisine", "title" => "Cuisine vietnamienne"],
            ["link" => "/culture-croyance", "title" => "Croyance"],
            ["link" => "/culture-contes-legendes", "title" => "Contes et légendes"],
            ["link" => "/culture-vietnamiens", "title" => "Les Vietnamiens"],
            ["link" => "/culture-habits", "title" => "Habits traditionnaux"]
        ]
    ]
];
$contact = [
    "title" => "CONTACT",
    "children" => [
        ["link" => "tel:+ 84 32 9111 811 ", "title" => "+ 84 32 9111 811 ", "icon" => 118],
        ["link" => "mailto:horizonvietnam@gmail.com", "title" => "@horizonvietnam@gmail.com", "icon" => 125],
        ["link" => "/", "title" => "9è étage, bâtiment An Phu, 24 Hoang Quoc Viet, Cau Giay, Hanoi", "icon" => 119],
    ]
];
?>
<footer class="footer">
    <div class="footer-thumnail">
        <div class="footer-thumnail__overlay"></div>

        <div class="footer-form">
            <h2>Inscrivez-vous à notre <strong>newsletter</strong></h2>
            <div class="form">
                <?php echo do_shortcode('[contact-form-7 id="89f4955" title="Footer form"]'); ?>
            </div>
            <div class="messages-form"></div>
        </div>

        <?= wp_get_attachment_image($image_thumb, 'full', false, [
            'class' => 'footer-thumnail__img',
        ]) ?>
    </div>
    <div class="footer-body">
        <?= wp_get_attachment_image($image_bg, 'full', false, [
            'class' => 'footer-background',
        ]) ?>
        <div class="line-vertical"></div>
        <div class="line-horizontal"></div>
        <div class="line-vertical--small"></div>
        <div class="footer-body__logo">
            <a href="/">
                <?= wp_get_attachment_image(120, 'full', false, [
                    'class' => 'footer-logo',
                ]) ?>
            </a>
            <p>Horizon dévoile l’infini des possibles : chaque voyage sculpte votre âme au gré des merveilles du monde.
            </p>
        </div>
        <div class="footer-body__content">
            <div class="footer-body__links--item">
                <?php foreach ($links as $link): ?>
                <div class="footer-body__links--group acordion">
                    <div class="footer-body__links--item__title">
                        <span><?= $link["title"] ?></span>
                        <?= wp_get_attachment_image($icon_down, 'full', false, [
                                'class' => 'footer-body__links--item__title__icon',
                            ]) ?>
                    </div>
                    <ul class="footer-body__links--item__list">
                        <?php foreach ($link["children"] as $child): ?>
                        <li class="footer-body__links--item__list__item">
                            <a href="<?= $child["link"] ?>"><?= $child["title"] ?></a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endforeach; ?>

                <?php if (!empty($contact)): ?>
                <div class="footer-body__links--group">
                    <div class="footer-body__links--item__title"><?= $contact["title"] ?> </div>
                    <ul class="footer-body__links--item__list">
                        <?php foreach ($contact["children"] as $item): ?>
                        <li class="footer-body__links--item__list__item">
                            <a href="<?= $item["link"] ?>">
                                <?= wp_get_attachment_image($item["icon"], 'full', false, []) ?>
                                <?= $item["title"] ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
            <div class="footer-body__social">
                <div class="footer-body__social__title">Follow Us</div>
                <div class="footer-body__social__list">
                    <a href="">Facebook</a>
                    <a href="">Youtube</a>
                    <a href="">Instagram</a>
                </div>
                <span>© 2025 Horizon Vietnam Travel</span>
            </div>
        </div>


    </div>
</footer>