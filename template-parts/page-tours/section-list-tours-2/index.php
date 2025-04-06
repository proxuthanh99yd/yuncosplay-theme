<?php
$_COMPASS_ICON = 57;
$_LOCATION_ICON = 63;
$_ARROW_LEFT_ICON = 56;
?>

<section id="list-tours" class="list-tours list-tours--2">
    <div class="list-tours__header">
        <div class="heading list-tours__title">
            <h2>Voyage multipays</h2>
        </div>
    </div>
    <div class="list-tours__nav">
        <custom-dropdown class="list-tours__dropdown">
            <span slot="placeholder">
                <img src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/Group.svg" alt="icon"
                    width="16" style="
                    margin-right: 0.5rem;
                    width: 1.5rem;
                    height: 1.5rem;
                    "> Selon vos envies
            </span>
            <custom-option value="vn">
                <img hidden src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/Group.svg"
                    alt="icon" width="16" style="
                    margin-right: 0.5rem;
                    width: 1.5rem;
                    height: 1.5rem;
                    "> Lune de miel
            </custom-option>
            <custom-option value="us">
                <img hidden src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/Group.svg"
                    alt="icon" width="16" style="
                    margin-right: 0.5rem;
                    width: 1.5rem;
                    height: 1.5rem;
                    "> Voyage en famille
            </custom-option>
            <custom-option value="jp">
                <img hidden src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/Group.svg"
                    alt="icon" width="16" style="
                    margin-right: 0.5rem;
                    width: 1.5rem;
                    height: 1.5rem;
                    "> Aventure et trekking
            </custom-option>
            <custom-option value="kr">
                <img hidden src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/Group.svg"
                    alt="icon" width="16" style="
                    margin-right: 0.5rem;
                    width: 1.5rem;
                    height: 1.5rem;
                    "> Visite culturelle
            </custom-option>
            <custom-option value="sg">
                <img hidden src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/Group.svg"
                    alt="icon" width="16" style="
                    margin-right: 0.5rem;
                    width: 1.5rem;
                    height: 1.5rem;
                    "> Visite des Charites
            </custom-option>
        </custom-dropdown>
        <custom-dropdown class="list-tours__dropdown">
            <span slot="placeholder">
                <img src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/clock.svg" alt="icon"
                    width="16" style="
                    margin-right: 0.5rem;
                    width: 1.5rem;
                    height: 1.5rem;
                    "> Selon la durée
            </span>
            <custom-option value="vn">
                <img hidden src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/clock.svg"
                    alt="icon" width="16" style="
                    margin-right: 0.5rem;
                    width: 1.5rem;
                    height: 1.5rem;
                    "> Lune de miel
            </custom-option>
        </custom-dropdown>
        <custom-dropdown class="list-tours__dropdown">
            <span slot="placeholder">
                <img src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/Group.svg" alt="icon"
                    width="16" style="
                    margin-right: 0.5rem;
                    width: 1.5rem;
                    height: 1.5rem;
                    "> Selon vos envies
            </span>
            <custom-option value="vn">
                <img hidden src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/Group.svg"
                    alt="icon" width="16" style="
                    margin-right: 0.5rem;
                    width: 1.5rem;
                    height: 1.5rem;
                    "> Lune de miel
            </custom-option>
            <custom-option value="us">
                <img hidden src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/Group.svg"
                    alt="icon" width="16" style="
                    margin-right: 0.5rem;
                    width: 1.5rem;
                    height: 1.5rem;
                    "> Voyage en famille
            </custom-option>
            <custom-option value="jp">
                <img hidden src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/Group.svg"
                    alt="icon" width="16" style="
                    margin-right: 0.5rem;
                    width: 1.5rem;
                    height: 1.5rem;
                    "> Aventure et trekking
            </custom-option>
            <custom-option value="kr">
                <img hidden src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/Group.svg"
                    alt="icon" width="16" style="
                    margin-right: 0.5rem;
                    width: 1.5rem;
                    height: 1.5rem;
                    "> Visite culturelle
            </custom-option>
            <custom-option value="sg">
                <img hidden src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/Group.svg"
                    alt="icon" width="16" style="
                    margin-right: 0.5rem;
                    width: 1.5rem;
                    height: 1.5rem;
                    "> Visite des Charites
            </custom-option>
        </custom-dropdown>
        <div class="list-tours__search-container">
            <img src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/search-normal.svg" alt=""
                class="list-tours__search-icon">
            <input type="text" class="list-tours__search" placeholder="Rechercher dans le List">
        </div>
        <?php
        $order_style = [
            'strong' => 'color: var(--titile, #1C1C1C);
                        text-overflow: ellipsis;
                        font-family: Matter;
                        font-size: 0.875rem;
                        font-style: normal;
                        font-weight: 700;
                        line-height: 100%; /* 1.3125rem */
                        margin-right: 0.2rem;',
            'normal' => 'color: var(--body---sub, #767676);
                        text-overflow: ellipsis;
                        font-family: Matter;
                        font-size: 0.875rem;
                        font-style: normal;
                        font-weight: 500;
                        line-height: 100%;',
            'regular' => 'color: var(--Body-text, #333);
                        font-family: Matter;
                        font-size: 0.875rem;
                        font-style: normal;
                        font-weight: 500;
                        line-height: 100%; /* 1.225rem */'
        ];
        ?>
        <custom-dropdown class="list-tours__dropdown-order">
            <span slot="placeholder">
                <span style="<?= $order_style['strong'] ?>">Filtrer par: </span>
                <span style="<?= $order_style['normal'] ?>">Tout</span>
            </span>
            <custom-option value="Newest First">
                <span style="<?= $order_style['strong'] ?>" hidden>Filtrer par: </span>
                <span style="<?= $order_style['regular'] ?>">Newest First</span>
            </custom-option>
            <custom-option value="Oldest First">
                <span style="<?= $order_style['strong'] ?>" hidden>Filtrer par: </span>
                <span style="<?= $order_style['regular'] ?>">Oldest First</span>
            </custom-option>
            <custom-option value="Price: Low to High">
                <span style="<?= $order_style['strong'] ?>" hidden>Filtrer par: </span>
                <span style="<?= $order_style['regular'] ?>">Price: Low to High</span>
            </custom-option>
            <custom-option value="Price: High to Low">
                <span style="<?= $order_style['strong'] ?>" hidden>Filtrer par: </span>
                <span style="<?= $order_style['regular'] ?>">Price: High to Low</span>
            </custom-option>
        </custom-dropdown>
    </div>
    <div hidden class="list-tours__nav--mb">
        <div class="list-tours__nav--mb__filter">
            <img src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/filter-lines.svg" alt="">
        </div>
        <div class="list-tours__search-container">
            <img src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/search-normal.svg" alt=""
                class="list-tours__search-icon">
            <input type="text" class="list-tours__search" placeholder="Rechercher dans le List">
        </div>
    </div>
    <div class="list-tours__body">
        <?php $max = wp_is_mobile() ? 6 : 12;
	for ($i = 0; $i < $max; $i++): ?>
        <div class="customized-trip__card">
            <div class="customized-trip__card-image">
                <img class="customized-trip__card-image-main" src="/wp-content/uploads/2025/03/customize-trip-item.webp"
                    alt="">
                <span class="customized-trip__card-image-icon">
                    <?= wp_get_attachment_image($_COMPASS_ICON, 'full') ?>
                    <span>Aventure</span>
                </span>
            </div>
            <div class="customized-trip__card-overlay"></div>
            <div class="customized-trip__card-content">
                <h3 class="customized-trip__card-title">Vietnam en 13 jours – Pure Évasion</h3>
                <p class="customized-trip__card-duration">13 jours- 10 nuits</p>
                <p class="customized-trip__card-location">
                    <?= wp_get_attachment_image($_LOCATION_ICON, 'full') ?>
                    <span>Hanoi - Tuan Chau - Lan Ha Bay - Ha Giang - Cao Bang - Hanoi</span>
                </p>
                <button class="customized-trip__card-button">
                    <span>Découvrir</span>
                    <?= wp_get_attachment_image($_ARROW_LEFT_ICON, 'full') ?>
                </button>
            </div>
            <a class="customized-trip__card-link" href=""></a>
        </div>
        <?php endfor; ?>
    </div>
    <div class="list-tours__footer">
        <button class="list-tours__load-more">Afficher la suite</button>
        <nav class="pagination">
            <span class="pagination__nav pagination__nav--prev" href="">
                <img class="pagination__nav-icon"
                    src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/03/chevron-left-double.svg"
                    alt="">
            </span>
            <ul class="pagination__list">
                <li class="pagination__item active">
                    1
                </li>
                <li class="pagination__item">
                    2
                </li>
                <li class="pagination__item">
                    3
                </li>
                <li class="pagination__item dots">
                    ...
                </li>
                <li class="pagination__item">
                    10
                </li>
            </ul>
            <span class="pagination__nav pagination__nav--next" href="">
                <img class="pagination__nav-icon"
                    src="https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/03/chevron-right-double.svg"
                    alt="">
            </span>
        </nav>
    </div>
</section>
<nav class="list-tours__filter-popup">
    <form class="list-tours__filter-form">
        <div class="list-tours__filter-header">
            <span class="list-tours__filter-header-line"></span>
            <span class="list-tours__filter-header-text">Filter</span>
            <button class="list-tours__filter-header-close"><img src="<?= $_CLOSE_ARROW_ICON ?>" alt=""></button>
        </div>
        <div class="list-tours__filter-body">
            <div class="list-tours__filter-box">
                <span class="list-tours__filter-label">Selon vos envies</span>
                <div class="list-tours__filter-items">
                    <input class="list-tours__filter-item-checkbox" hidden type="checkbox" name="filter"
                        id="Lune de miel" value="Lune de miel" hidden>
                    <label for="Lune de miel" class="list-tours__filter-item">
                        Lune de miel
                        <img src=" https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/marker.svg"
                            alt="">
                    </label>
                    <input class="list-tours__filter-item-checkbox" hidden type="checkbox" name="filter"
                        id="Voyage en famille" value="Voyage en famille" hidden>
                    <label for="Voyage en famille" class="list-tours__filter-item">
                        Voyage en famille
                        <img src=" https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/marker.svg"
                            alt="">
                    </label>
                    <input class="list-tours__filter-item-checkbox" hidden type="checkbox" name="filter"
                        id="Aventure et trekking" value="Aventure et trekking" hidden>
                    <label for="Aventure et trekking" class="list-tours__filter-item">
                        Aventure et trekking
                        <img src=" https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/marker.svg"
                            alt="">
                    </label>
                    <input class="list-tours__filter-item-checkbox" hidden type="checkbox" name="filter"
                        id="Visite culturelle" value="Visite culturelle" hidden>
                    <label for="Visite culturelle" class="list-tours__filter-item">
                        Visite culturelle
                        <img src=" https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/marker.svg"
                            alt="">
                    </label>
                    <input class="list-tours__filter-item-checkbox" hidden type="checkbox" name="filter"
                        id="Visite gastronomique" value="Visite gastronomique" hidden>
                    <label for="Visite gastronomique" class="list-tours__filter-item">
                        Visite gastronomique
                        <img src=" https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/marker.svg"
                            alt="">
                    </label>
                    <input class="list-tours__filter-item-checkbox" hidden type="checkbox" name="filter"
                        id="Visite des Charites" value="Visite des Charites" hidden>
                    <label for="Visite des Charites" class="list-tours__filter-item">
                        Visite des Charites
                        <img src=" https://horizonvietnamtravel.okhub-tech.com/wp-content/uploads/2025/04/marker.svg"
                            alt="">
                    </label>
                </div>
            </div>
            <div class="list-tours__filter-box list-tours__filter-box--sort">
                <span class="list-tours__filter-label">Sort by:</span>
                <div class="list-tours__filter-items">
                    <label class="list-tours__filter-item">
                        <input hidden type="radio" name="sort" id="Tous" value="Tous" checked>
                        <label for="Tous" class="list-tours__filter-item-radio"></label>
                        <span>Tous</span>
                    </label>
                    <label class="list-tours__filter-item">
                        <input hidden type="radio" name="sort" id="Le" value="Le plus récent">
                        <label for="Le" class="list-tours__filter-item-radio"></label>
                        <span>Le plus récent</span>
                    </label>
                    <label class="list-tours__filter-item">
                        <input hidden type="radio" name="sort" id="ancien" value="Le plus ancien">
                        <label for="ancien" class="list-tours__filter-item-radio"></label>
                        <span>Le plus ancien</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="list-tours__filter-footer">
            <button type="submit" class="list-tours__filter-footer-apply">Charger plus</button>
            <button class="list-tours__filter-footer-cancel">Tout effacer</button>
        </div>
    </form>
</nav>
<div class="list-tours__filter-overlay">
</div>