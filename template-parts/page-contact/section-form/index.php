<?php
$tour_slug = sanitize_title($_GET['tour']);
$destination_slug = sanitize_title($_GET['destination']);
$tour = get_page_by_path($tour_slug, OBJECT, 'tour');
if ($tour_slug && !$tour && !is_front_page()) {
    wp_redirect(home_url('/'), 301);
    exit;
}
$taxonomy_destination = get_terms([
    'taxonomy' => 'destination',
    'parent' => 0,
    'hide_empty' => false,
]);
print_r($destination_slug);
$destinations = [];
foreach ($taxonomy_destination as $destination) {
    $destinations[] = [
        "name" => $destination->name,
        "isChecked" => $destination->slug == $destination_slug
    ];
}
// Convert destinations to JSON for JavaScript
$destinations_json = json_encode($destinations);
?>

<?php
$banner = get_field('page_contact_banner');
$title = isset($banner['title']) ? trim($banner['title']) : '';
$subtitle = isset($banner['subtitle']) ? trim($banner['subtitle']) : '';
$subtitle_mb = isset($banner['subtitle_mb']) ? trim($banner['subtitle_mb']) : 'Use the enquiry form below';
?>
<section class="section-banner">
    <?= wp_get_attachment_image(1681, 'full', false, [
        'class' => 'section-banner__bg'
    ]) ?>
    <h1 class="section-banner__title"><?= $title ?></h1>
    <p class="section-banner__subtitle" data-text="<?= IS_MOBILE ? $subtitle_mb : $subtitle ?>"></p>
</section>
<section class="section-form" style="--bg:url(<?= wp_get_attachment_image_url(1674) ?>);">
    <div class="section-form__container">
        <?php if (IS_MOBILE &&  $tour_slug && $tour): ?>
            <div class="section-form__tour">
                <?= get_the_post_thumbnail($tour->ID, 'full',  [
                    'class' => 'section-form__tour-image'
                ]) ?>
                <a class="section-form__tour-remove" href="/contact">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                        <path
                            d="M0.25365 9.65496C-0.0822647 9.99088 -0.0891201 10.5873 0.260505 10.9369C0.616985 11.2865 1.2134 11.2797 1.54246 10.9506L5.60086 6.89224L9.6524 10.9438C9.99516 11.2865 10.5847 11.2865 10.9344 10.9369C11.284 10.5804 11.284 9.99773 10.9412 9.65496L6.88967 5.60343L10.9412 1.54504C11.284 1.20227 11.2908 0.612707 10.9344 0.263082C10.5847 -0.0865429 9.99516 -0.0865429 9.6524 0.256226L5.60086 4.30776L1.54246 0.256226C1.2134 -0.0796875 0.61013 -0.0933983 0.260505 0.263082C-0.0891201 0.612707 -0.0822647 1.21598 0.25365 1.54504L4.30519 5.60343L0.25365 9.65496Z"
                            fill="#630F3F" />
                    </svg>
                </a>
                <div class="section-form__tour-content">
                    <?= wp_get_attachment_image(1193, 'full', false, [
                        'class' => 'section-form__tour-bg'
                    ]) ?>
                    <div class="section-form__tour-content-inner">
                        <p class="section-form__tour-itinerary">Interested in the itinerary:</p>
                        <h3 class="section-form__tour-title">
                            <?= $tour->post_title ?>
                        </h3>
                        <hr class="section-form__tour-title-line">
                        <div class="section-form__tour-detail">
                            <div class="section-form__tour-departing">
                                <p class="section-form__tour-label">Departing from</p>
                                <p class="section-form__tour-value">
                                    <?= get_field('leaving_from', $tour->ID) ?>
                                </p>
                            </div>
                            <div class="section-form__tour-price">
                                <p class="section-form__tour-label">Price from</p>
                                <p class="section-form__tour-value">$<?= get_field('tour_price', $tour->ID) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <form class="section-form__left" x-data="contactForm" @submit.prevent="submitForm()">
            <div class="section-form__plan">
                <h2 class="section-form__plan-title">Your travel plans</h2>
                <hr class="section-form__plan-title__line">
                <div class="section-form__group">
                    <label for="tour" class="section-form__label section-form__label--required">
                        Which destination would
                        you like to go?
                    </label>
                    <?php if (!IS_MOBILE): ?>
                        <div class="section-form__destination" x-data="destinationSelector" @keydown.escape="closeDropdown"
                            @click.outside="closeDropdown">
                            <button type="button" class="section-form__select-destination" @click="toggleDropdown">
                                <span class="section-form__no-select-destination"
                                    x-show="selectedDestinations.length === 0">
                                    Select destinations
                                </span>
                                <template x-for="destination in selectedDestinations" :key="destination">
                                    <span class="section-form__select-destination-text section-form__selected-destination"
                                        @click.stop="toggleDestination(destination)">
                                        <span x-text="destination"></span>
                                        <?= wp_get_attachment_image(1190, 'thumbnail', false, [
                                            'data-nolazy' => 1
                                        ]) ?>
                                    </span>
                                </template>
                                <?= wp_get_attachment_image(1188, 'full', false, [
                                    'class' => 'section-form__select-destination-arrow',
                                    'x-bind:class' => "{'section-form__select-destination-arrow--rotated': isOpen}",
                                    'data-nolazy' => 1
                                ]) ?>
                            </button>
                            <div style="display: none;" class="section-form__option-destination" x-show="isOpen"
                                x-transition>
                                <?php foreach ($destinations as $destination): ?>
                                    <div class="section-form__option-destination-item">
                                        <input id="destination-<?php echo esc_attr($destination['name']); ?>"
                                            name="destination[]" value="<?php echo esc_attr($destination['name']); ?>"
                                            type="checkbox" class="section-form__control" hidden x-model="selectedDestinations"
                                            <?= $destination['isChecked'] ? "checked" : "" ?>>
                                        <label for="destination-<?php echo esc_attr($destination['name']); ?>"
                                            class="section-form__option-destination-checkbox">
                                            <?= wp_get_attachment_image(1189, 'thumbnail', false, [
                                                'data-nolazy' => 1
                                            ]) ?>
                                        </label>
                                        <label for="destination-<?php echo esc_attr($destination['name']); ?>"
                                            class="section-form__option-destination-label">
                                            <?php echo esc_html($destination['name']); ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="section-form__destination" x-data="destinationDrawer" @keydown.escape="closeDropdown"
                            @click.outside="closeDropdown">
                            <div class="section-form__drawer-overlay"
                                x-bind:class="{'section-form__drawer-overlay--open': isOpen}" @click="closeDropdown"></div>
                            <button type="button" class="section-form__select-destination" @click="toggleDropdown">
                                <span class="section-form__no-select-destination"
                                    x-show="selectedDestinations.length === 0">
                                    Select destinations
                                </span>
                                <template x-for="destination in selectedDestinations" :key="destination">
                                    <span class="section-form__select-destination-text section-form__selected-destination"
                                        @click.stop="toggleDestination(destination)">
                                        <span x-text="destination"></span>
                                        <?= wp_get_attachment_image(1190, 'thumbnail', false, [
                                            'data-nolazy' => 1
                                        ]) ?>
                                    </span>
                                </template>
                                <?= wp_get_attachment_image(1188, 'full', false, [
                                    'class' => 'section-form__select-destination-arrow',
                                    'x-bind:class' => "{'section-form__select-destination-arrow--rotated': isOpen}",
                                    'data-nolazy' => 1
                                ]) ?>
                            </button>
                            <div class="section-form__option-destination"
                                x-bind:class="{'section-form__option-destination--open': isOpen}">
                                <div class="section-form__drawer-header">
                                    <p class="section-form__drawer-title">Where would you like to go?</p>
                                    <div class="section-form__drawer-search">
                                        <input type="text" class="section-form__drawer-search-input"
                                            placeholder="Enter search content" x-model="searchTerm">
                                        <?= wp_get_attachment_image(1195, 'thumbnail', false, [
                                            'class' => 'section-form__drawer-search-icon'
                                        ]) ?>
                                    </div>
                                    <button @click="closeDropdown" type="button" class="section-form__drawer-close">
                                        <?= wp_get_attachment_image(1191, 'thumbnail', false, [
                                            'class' => 'section-form__drawer-close-icon'
                                        ]) ?>
                                    </button>
                                </div>
                                <?php foreach ($destinations as $destination): ?>
                                    <div class="section-form__option-destination-item"
                                        x-show="isVisible('<?php echo esc_js($destination['name']); ?>')">
                                        <input id="destination-<?php echo esc_attr($destination['name']); ?>"
                                            name="destination[]" value="<?php echo esc_attr($destination['name']); ?>"
                                            type="checkbox" class="section-form__control" hidden x-model="selectedDestinations"
                                            <?= $destination['isChecked'] ? "checked" : "" ?>>
                                        <label for="destination-<?php echo esc_attr($destination['name']); ?>"
                                            class="section-form__option-destination-checkbox">
                                            <?= wp_get_attachment_image(1189, 'thumbnail', false, [
                                                'data-nolazy' => 1
                                            ]) ?>
                                        </label>
                                        <label for="destination-<?php echo esc_attr($destination['name']); ?>"
                                            class="section-form__option-destination-label">
                                            <?php echo esc_html($destination['name']); ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div x-show="window.contactForm?.errors?.destinations"
                        x-text="window.contactForm?.errors?.destinations" class="section-form__error"></div>
                </div>
                <div class="section-form__group section-form__group--col-3 section-form__group--xsm-col-2">
                    <div class="section-form__group">
                        <label for="tour" class="section-form__label section-form__label--required">
                            When would you like to go
                        </label>
                        <?php
                        $select_month = get_field('select_month');
                        if (!IS_MOBILE): ?>
                            <div class="section-form__select-option" x-data="monthSelector" @keydown.escape="closeDropdown"
                                @click.outside="closeDropdown">
                                <input type="hidden" name="month" x-bind:value="selected">
                                <button type="button" class="section-form__select" @click="toggleDropdown">
                                    <span class="section-form__no-selected" x-show="!selected">
                                        Select a month
                                    </span>
                                    <span class="section-form__selected-text" x-show="selected" x-text="selected"></span>
                                    <?= wp_get_attachment_image(1188, 'full', false, [
                                        'class' => 'section-form__select-arrow',
                                        'x-bind:class' => "{'section-form__select-arrow--rotated': isOpen}",
                                        'data-nolazy' => 1
                                    ]) ?>
                                </button>
                                <div style="display: none;" class="section-form__options" x-show="isOpen" x-transition>
                                    <?php foreach ($select_month as $month): ?>
                                        <div class="section-form__option-item" @click="select('<?= $month['label'] ?>')">
                                            <?= $month['label'] ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="section-form__select-option" x-data="monthDrawer" @keydown.escape="closeDropdown"
                                @click.outside="closeDropdown">
                                <div class="section-form__drawer-overlay"
                                    x-bind:class="{'section-form__drawer-overlay--open': isOpen}" @click="closeDropdown">
                                </div>
                                <input type="hidden" name="month" x-bind:value="selected">
                                <button type="button" class="section-form__select" @click="toggleDropdown">
                                    <span class="section-form__no-selected" x-show="!selected">
                                        Select a month
                                    </span>
                                    <span class="section-form__selected-text" x-show="selected" x-text="selected"></span>
                                    <?= wp_get_attachment_image(1188, 'full', false, [
                                        'class' => 'section-form__select-arrow',
                                        'x-bind:class' => "{'section-form__select-arrow--rotated': isOpen}",
                                        'data-nolazy' => 1
                                    ]) ?>
                                </button>
                                <div class="section-form__options" x-bind:class="{'section-form__option--open': isOpen}">
                                    <div class="section-form__drawer-header">
                                        <p class="section-form__drawer-title">Select a month</p>
                                        <div class="section-form__drawer-search">
                                            <input type="text" class="section-form__drawer-search-input"
                                                placeholder="Search a month..." x-model="searchTerm">
                                            <?= wp_get_attachment_image(1195, 'thumbnail', false, [
                                                'class' => 'section-form__drawer-search-icon'
                                            ]) ?>
                                        </div>
                                        <button @click="closeDropdown" type="button" class="section-form__drawer-close">
                                            <?= wp_get_attachment_image(1191, 'thumbnail', false, [
                                                'class' => 'section-form__drawer-close-icon'
                                            ]) ?>
                                        </button>
                                    </div>
                                    <?php foreach ($select_month as $month): ?>
                                        <div class="section-form__option-item" @click="select('<?= $month['label'] ?>')"
                                            x-bind:class="{'section-form__option-item--active': selected == '<?= $month['label'] ?>'}"
                                            x-show="isVisible('<?= $month['label'] ?>')">
                                            <?= $month['label'] ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div x-show="window.contactForm?.errors?.month" x-text="window.contactForm?.errors?.month"
                            class="section-form__error"></div>
                    </div>
                    <div class="section-form__group">
                        <?php
                        $select_year = get_field('select_year');
                        if (!IS_MOBILE): ?>
                            <div class="section-form__select-option" x-data="yearSelector" @keydown.escape="closeDropdown"
                                @click.outside="closeDropdown">
                                <input type="hidden" name="year" x-bind:value="selected">
                                <button type="button" class="section-form__select" @click="toggleDropdown">
                                    <span class="section-form__no-selected" x-show="!selected">
                                        Select a year
                                    </span>
                                    <span class="section-form__selected-text" x-show="selected" x-text="selected"></span>
                                    <?= wp_get_attachment_image(1188, 'full', false, [
                                        'class' => 'section-form__select-arrow',
                                        'x-bind:class' => "{'section-form__select-arrow--rotated': isOpen}",
                                        'data-nolazy' => 1
                                    ]) ?>
                                </button>
                                <div style="display: none;" class="section-form__options" x-show="isOpen" x-transition>
                                    <?php foreach ($select_year as $year): ?>
                                        <div class="section-form__option-item" @click="select('<?= $year['label'] ?>')">
                                            <?= $year['label'] ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="section-form__select-option" x-data="yearDrawer" @keydown.escape="closeDropdown"
                                @click.outside="closeDropdown">
                                <div class="section-form__drawer-overlay"
                                    x-bind:class="{'section-form__drawer-overlay--open': isOpen}" @click="closeDropdown">
                                </div>
                                <input type="hidden" name="year" x-bind:value="selected">
                                <button type="button" class="section-form__select" @click="toggleDropdown">
                                    <span class="section-form__no-selected" x-show="!selected">
                                        Select a year
                                    </span>
                                    <span class="section-form__selected-text" x-show="selected" x-text="selected"></span>
                                    <?= wp_get_attachment_image(1188, 'full', false, [
                                        'class' => 'section-form__select-arrow',
                                        'x-bind:class' => "{'section-form__select-arrow--rotated': isOpen}",
                                        'data-nolazy' => 1
                                    ]) ?>
                                </button>
                                <div class="section-form__options" x-bind:class="{'section-form__option--open': isOpen}">
                                    <div class="section-form__drawer-header">
                                        <p class="section-form__drawer-title">Select a year</p>
                                        <div class="section-form__drawer-search">
                                            <input type="text" class="section-form__drawer-search-input"
                                                placeholder="Search year..." x-model="searchTerm">
                                            <?= wp_get_attachment_image(1195, 'thumbnail', false, [
                                                'class' => 'section-form__drawer-search-icon'
                                            ]) ?>
                                        </div>
                                        <button @click="closeDropdown" type="button" class="section-form__drawer-close">
                                            <?= wp_get_attachment_image(1191, 'thumbnail', false, [
                                                'class' => 'section-form__drawer-close-icon'
                                            ]) ?>
                                        </button>
                                    </div>
                                    <?php foreach ($select_year as $year): ?>
                                        <div class="section-form__option-item" @click="select('<?= $year['label'] ?>')"
                                            x-bind:class="{'section-form__option-item--active': selected == '<?= $year['label'] ?>'}"
                                            x-show="isVisible('<?= $year['label'] ?>')">
                                            <?= $year['label'] ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div x-show="window.contactForm?.errors?.year" x-text="window.contactForm?.errors?.year"
                            class="section-form__error"></div>
                    </div>
                    <div class="section-form__group section-form__group--xsm-col-span-2">
                        <label for="tour" class="section-form__label section-form__label--required">
                            How long for?
                        </label>
                        <input type="text" name="duration" class="section-form__control" placeholder="Duration of trip"
                            x-model="formData.duration" @input="validateInput('duration')">
                        <div x-show="window.contactForm?.errors?.duration" x-text="window.contactForm?.errors?.duration"
                            class="section-form__error"></div>
                    </div>
                </div>
                <div class="section-form__group">
                    <label for="tour" class="section-form__label section-form__label--required">
                        How many people are travelling?
                    </label>
                    <?php
                    $select_number = get_field('select_number');
                    if (!IS_MOBILE): ?>
                        <div class="section-form__select-option" x-data="numberSelector" @keydown.escape="closeDropdown"
                            @click.outside="closeDropdown">
                            <input type="hidden" name="number" x-bind:value="selected">
                            <button type="button" class="section-form__select" @click="toggleDropdown">
                                <span class="section-form__no-selected" x-show="!selected">
                                    Select a number
                                </span>
                                <span class="section-form__selected-text" x-show="selected" x-text="selected"></span>
                                <?= wp_get_attachment_image(1188, 'full', false, [
                                    'class' => 'section-form__select-arrow',
                                    'x-bind:class' => "{'section-form__select-arrow--rotated': isOpen}",
                                    'data-nolazy' => 1
                                ]) ?>
                            </button>
                            <div style="display: none;" class="section-form__options" x-show="isOpen" x-transition>
                                <?php foreach ($select_number as $number): ?>
                                    <div class="section-form__option-item" @click="select('<?= $number['label'] ?>')">
                                        <?= $number['label'] ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="section-form__select-option" x-data="numberDrawer" @keydown.escape="closeDropdown"
                            @click.outside="closeDropdown">
                            <div class="section-form__drawer-overlay"
                                x-bind:class="{'section-form__drawer-overlay--open': isOpen}" @click="closeDropdown"></div>
                            <input type="hidden" name="number" x-bind:value="selected">
                            <button type="button" class="section-form__select" @click="toggleDropdown">
                                <span class="section-form__no-selected" x-show="!selected">
                                    Select a number
                                </span>
                                <span class="section-form__selected-text" x-show="selected" x-text="selected"></span>
                                <?= wp_get_attachment_image(1188, 'full', false, [
                                    'class' => 'section-form__select-arrow',
                                    'x-bind:class' => "{'section-form__select-arrow--rotated': isOpen}",
                                    'data-nolazy' => 1
                                ]) ?>
                            </button>
                            <div class="section-form__options" x-bind:class="{'section-form__option--open': isOpen}">
                                <div class="section-form__drawer-header">
                                    <p class="section-form__drawer-title">Select number of people</p>
                                    <div class="section-form__drawer-search">
                                        <input type="text" class="section-form__drawer-search-input"
                                            placeholder="Search number..." x-model="searchTerm">
                                        <?= wp_get_attachment_image(1195, 'thumbnail', false, [
                                            'class' => 'section-form__drawer-search-icon'
                                        ]) ?>
                                    </div>
                                    <button @click="closeDropdown" type="button" class="section-form__drawer-close">
                                        <?= wp_get_attachment_image(1191, 'thumbnail', false, [
                                            'class' => 'section-form__drawer-close-icon'
                                        ]) ?>
                                    </button>
                                </div>
                                <?php foreach ($select_number as $number): ?>
                                    <div class="section-form__option-item" @click="select('<?= $number['label'] ?>')"
                                        x-bind:class="{'section-form__option-item--active': selected == '<?= $number['label'] ?>'}"
                                        x-show="isVisible('<?= $number['label'] ?>')">
                                        <?= $number['label'] ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div x-show="window.contactForm?.errors?.number" x-text="window.contactForm?.errors?.number"
                        class="section-form__error"></div>
                </div>
                <div class="section-form__group">
                    <label for="tour" class="section-form__label section-form__label--required">
                        How much would you like to spend per person?
                    </label>
                    <div x-data="budgetSlider" x-init="init()" class="budget-slider">
                        <div x-ref="slider"></div>
                        <input type="hidden" name="budget_min" :value="min" :disabled="window.contactForm?.formData?.budget_later">
                        <input type="hidden" name="budget_max" :value="max" :disabled="window.contactForm?.formData?.budget_later">
                    </div>
                </div>
                <div class="section-form__group section-form__group--checkbox">
                    <div class="section-form__checkbox">
                        <input hidden type="checkbox" name="budget-later" id="budget-later" value="yes"
                            x-model="formData.budget_later">
                        <label for="budget-later">
                            <?= wp_get_attachment_image(1189, 'thumbnail', false, [
                                'data-nolazy' => 1
                            ]) ?>
                        </label>
                    </div>
                    <label for="budget-later" class="section-form__label section-form__label--checkbox">
                        Your travel consultant can prepare a proposal first. You may decide your budget later
                    </label>
                </div>
                <div class="section-form__group">
                    <label for="comments" class="section-form__label section-form__label--required">
                        Any other comments or requests?
                    </label>
                    <textarea class="section-form__control section-form__control--textarea" name="comments"
                        id="comments" x-model="formData.comments" @input="validateInput('comments')"
                        placeholder="E.g. Number of travellers, duration, travel dates, level of accommodation"></textarea>
                    <div x-show="window.contactForm?.errors?.comments" x-text="window.contactForm?.errors?.comments"
                        class="section-form__error"></div>
                </div>
            </div>
            <div class="section-form__detail">
                <h2 class="section-form__detail-title">
                    Your details
                    <span>Required</span>
                </h2>
                <hr class="section-form__detail-title-line">
                <div class="section-form__alert">
                    <?= wp_get_attachment_image(1680, 'thumbnail', false, [
                        'data-nolazy' => 1
                    ]) ?>
                    <p>
                        Avian Odyssey takes the security and privacy of your data very seriously. Please read our
                        privacy policy for further details.
                    </p>
                </div>
                <div class="section-form__group section-form__group--col-2 section-form__group--xsm-col-2">
                    <div class="section-form__group">
                        <label for="first-name" class="section-form__label section-form__label--required">
                            Your Name
                        </label>
                        <input type="text" name="first_name" id="first-name" class="section-form__control"
                            placeholder="First name" x-model="formData.first_name" @input="validateInput('first_name')">
                        <div x-show="window.contactForm?.errors?.first_name"
                            x-text="window.contactForm?.errors?.first_name" class="section-form__error"></div>
                    </div>
                    <div class="section-form__group">
                        <input type="text" name="last-name" id="last-name" class="section-form__control"
                            placeholder="Last name" x-model="formData.last_name">
                    </div>
                </div>
                <div class="section-form__group section-form__group--col-2 section-form__group--xsm-col-2">
                    <div class="section-form__group section-form__group--xsm-col-span-2">
                        <label for="email" class="section-form__label section-form__label--required">
                            Email Address
                        </label>
                        <input type="email" name="email" id="email" class="section-form__control"
                            placeholder="yourname@email.com" x-model="formData.email" @input="validateInput('email')">
                        <div x-show="window.contactForm?.errors?.email" x-text="window.contactForm?.errors?.email"
                            class="section-form__error"></div>
                    </div>
                    <div class="section-form__group section-form__group--xsm-col-span-2">
                        <label for="confirm-email" class="section-form__label section-form__label--required">
                            Confirm Email Address
                        </label>
                        <input type="email" name="confirm_email" id="confirm-email" class="section-form__control"
                            placeholder="Confirm email address" x-model="formData.confirm_email"
                            @input="validateInput('confirm_email')">
                        <div x-show="window.contactForm?.errors?.confirm_email"
                            x-text="window.contactForm?.errors?.confirm_email" class="section-form__error"></div>
                    </div>
                </div>
                <div class="section-form__group section-form__group--phone">
                    <div class="section-form__group">
                        <label for="phone" class="section-form__label section-form__label--required">
                            Telephone
                        </label>
                        <div class="section-form__group-phone">
                            <?php if (!IS_MOBILE): ?>
                                <div class="section-form__select-option" x-data="phoneNationalSelector"
                                    @keydown.escape="closeDropdown" @click.outside="closeDropdown">
                                    <input type="hidden" name="phone-national" x-bind:value="selected">
                                    <button type="button" class="section-form__select" @click="toggleDropdown">
                                        <img x-show="selected" :src="getSelectedCountryFlag()"
                                            :alt="getSelectedCountryName()" class="section-form__select-flag"
                                            src="https://flagcdn.com/vn.svg">
                                        <span class="section-form__selected-text" x-show="!selected">
                                            +84
                                        </span>
                                        <span class="section-form__selected-text" x-show="selected"
                                            x-html="getSelectedDisplay()"></span>
                                        <?= wp_get_attachment_image(1188, 'full', false, [
                                            'class' => 'section-form__select-arrow',
                                            'x-bind:class' => "{'section-form__select-arrow--rotated': isOpen}",
                                            'data-nolazy' => 1
                                        ]) ?>
                                    </button>
                                    <div style="display: none;" class="section-form__options" x-show="isOpen" x-transition>
                                        <!-- Search input -->
                                        <div class="section-form__search-container">
                                            <img src="/wp-content/uploads/2026/01/form-input-bg.png" alt="">
                                            <input type="text" x-model="searchTerm" placeholder="Search..."
                                                class="section-form__search-input" @keydown.stop>
                                        </div>

                                        <!-- Loading state -->
                                        <div x-show="isLoading" class="section-form__option-item">
                                            <span class="section-form__option-name">Loading countries...</span>
                                        </div>

                                        <!-- No results -->
                                        <div x-show="!isLoading && filteredCountries.length === 0 && searchTerm"
                                            class="section-form__option-item">
                                            <span class="section-form__option-name">No countries found</span>
                                        </div>

                                        <!-- Country options -->
                                        <template x-for="(country, index) in filteredCountries"
                                            :key="country.idd.fullCode + '-' + country.name.common">
                                            <div class="section-form__option-item" @click="select(country)">
                                                <img :src="country.flags.png || '/placeholder-flag.png'"
                                                    :alt="country.name.common" class="section-form__option-flag">
                                                <span class="section-form__option-name" x-text="country.name.common"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="section-form__select-option" x-data="phoneDrawer"
                                    @keydown.escape="closeDropdown" @click.outside="closeDropdown">
                                    <div class="section-form__drawer-overlay"
                                        x-bind:class="{'section-form__drawer-overlay--open': isOpen}"
                                        @click="closeDropdown"></div>
                                    <input type="hidden" name="phone-national" x-bind:value="selected">
                                    <button type="button" class="section-form__select" @click="toggleDropdown">
                                        <img x-show="selected" :src="getSelectedCountryFlag()"
                                            :alt="getSelectedCountryName()" class="section-form__select-flag"
                                            src="https://flagcdn.com/vn.svg">
                                        <span class="section-form__selected-text" x-show="!selected">
                                            +84
                                        </span>
                                        <span class="section-form__selected-text" x-show="selected"
                                            x-html="getSelectedDisplay()"></span>
                                        <?= wp_get_attachment_image(1188, 'full', false, [
                                            'class' => 'section-form__select-arrow',
                                            'x-bind:class' => "{'section-form__select-arrow--rotated': isOpen}",
                                            'data-nolazy' => 1
                                        ]) ?>
                                    </button>
                                    <div class="section-form__options"
                                        x-bind:class="{'section-form__option--open': isOpen}">
                                        <div class="section-form__drawer-header">
                                            <p class="section-form__drawer-title">Select country code</p>
                                            <div class="section-form__drawer-search">
                                                <input type="text" class="section-form__drawer-search-input"
                                                    placeholder="Search country..." x-model="searchTerm">
                                                <?= wp_get_attachment_image(1195, 'thumbnail', false, [
                                                    'class' => 'section-form__drawer-search-icon'
                                                ]) ?>
                                            </div>
                                            <button @click="closeDropdown" type="button" class="section-form__drawer-close">
                                                <?= wp_get_attachment_image(1191, 'thumbnail', false, [
                                                    'class' => 'section-form__drawer-close-icon'
                                                ]) ?>
                                            </button>
                                        </div>

                                        <!-- Loading state -->
                                        <div x-show="isLoading" class="section-form__option-item">
                                            <span class="section-form__option-name">Loading countries...</span>
                                        </div>

                                        <!-- No results -->
                                        <div x-show="!isLoading && filteredCountries.length === 0 && searchTerm"
                                            class="section-form__option-item">
                                            <span class="section-form__option-name">No countries found</span>
                                        </div>

                                        <!-- Country options -->
                                        <template x-for="(country, index) in filteredCountries"
                                            :key="country.idd.fullCode + '-' + country.name.common">
                                            <div class="section-form__option-item" @click="select(country)">
                                                <img :src="country.flags.png || '/placeholder-flag.png'"
                                                    :alt="country.name.common" class="section-form__option-flag">
                                                <span class="section-form__option-name" x-text="country.name.common"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <input type="text" name="phone" id="phone" class="section-form__control"
                                placeholder="Phone number" x-model="formData.phone" @input="validateInput('phone')">
                            <div x-show="window.contactForm?.errors?.phone" x-text="window.contactForm?.errors?.phone"
                                class="section-form__error"></div>
                        </div>
                        <p>We may need to contact you to discuss your travel plans in greater detail</p>
                    </div>
                </div>
                <div class="section-form__group section-form__group--accept">
                    <div class="section-form__group--accept-icon">
                        <?= wp_get_attachment_image(1682, 'thumbnail', false, [
                            'data-nolazy' => 1
                        ]) ?>
                    </div>
                    <div class="section-form__checkbox">
                        <input hidden type="checkbox" name="accept-video" id="accept-video" value="yes"
                            x-model="formData.accept_video">
                        <label for="accept-video">
                            <?= wp_get_attachment_image(1189, 'thumbnail', false, [
                                'data-nolazy' => 1
                            ]) ?>
                        </label>
                    </div>
                    <label for="accept-video" class="section-form__label section-form__label--checkbox">
                        Arrange a video appointment. Talk to a specialist about your travel plans on a video call, with
                        no software to download and at a time that suits you.
                    </label>
                </div>
            </div>
            <div class="section-form__button">
                <button type="submit" :disabled="isSubmitting" class="compound-avian-button">
                    <span class="compound-avian-button__content">
                        <span class="compound-avian-button__content-text" x-show="!isSubmitting">Send Enquiry</span>
                        <span class="compound-avian-button__content-text" x-show="isSubmitting" style="display:none;">Sending...</span>
                    </span>
                </button>
            </div>

            <!-- Toast Notifications -->
            <div id="toast-container" class="toast-container" x-data="globalToast"
                :class="toast.visible ? 'toast-container--open' : ''">
                <div id="toast-message" class="toast-message" x-text="toast.message"
                    :class="toast.type === 'success' ? 'toast-message--success' : 'toast-message--error'"
                    @click="hideToast()">
                </div>
            </div>
        </form>
        <div class="section-form__right">
            <?php if (!IS_MOBILE &&  $tour_slug && $tour): ?>
                <div class="section-form__tour">
                    <?= get_the_post_thumbnail($tour->ID, 'full',  [
                        'class' => 'section-form__tour-image'
                    ]) ?>
                    <a class="section-form__tour-remove" href="/contact">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path
                                d="M0.25365 9.65496C-0.0822647 9.99088 -0.0891201 10.5873 0.260505 10.9369C0.616985 11.2865 1.2134 11.2797 1.54246 10.9506L5.60086 6.89224L9.6524 10.9438C9.99516 11.2865 10.5847 11.2865 10.9344 10.9369C11.284 10.5804 11.284 9.99773 10.9412 9.65496L6.88967 5.60343L10.9412 1.54504C11.284 1.20227 11.2908 0.612707 10.9344 0.263082C10.5847 -0.0865429 9.99516 -0.0865429 9.6524 0.256226L5.60086 4.30776L1.54246 0.256226C1.2134 -0.0796875 0.61013 -0.0933983 0.260505 0.263082C-0.0891201 0.612707 -0.0822647 1.21598 0.25365 1.54504L4.30519 5.60343L0.25365 9.65496Z"
                                fill="#630F3F" />
                        </svg>
                    </a>
                    <div class="section-form__tour-content">
                        <?= wp_get_attachment_image(1193, 'full', false, [
                            'class' => 'section-form__tour-bg'
                        ]) ?>
                        <div class="section-form__tour-content-inner">
                            <p class="section-form__tour-itinerary">Interested in the itinerary:</p>
                            <h3 class="section-form__tour-title">
                                <?= $tour->post_title ?>
                            </h3>
                            <hr class="section-form__tour-title-line">
                            <div class="section-form__tour-detail">
                                <div class="section-form__tour-departing">
                                    <p class="section-form__tour-label">Departing from</p>
                                    <p class="section-form__tour-value">
                                        <?= get_field('leaving_from', $tour->ID) ?>
                                    </p>
                                </div>
                                <div class="section-form__tour-price">
                                    <p class="section-form__tour-label">Price from</p>
                                    <p class="section-form__tour-value">$<?= get_field('tour_price', $tour->ID) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php
            $page_contact_others = get_field('page_contact_others');
            $specialist = $page_contact_others['specialist']
            ?>
            <div class="section-form__specialist">
                <div class="section-form__specialist-images">
                    <?php foreach ($specialist['specialist'] as $item): ?>
                        <?= wp_get_attachment_image($item['ID'], 'thumbnail', false, [
                            'class' => 'section-form__specialist-image'
                        ]) ?>
                    <?php endforeach; ?>
                </div>
                <p class="section-form__specialist-title">
                    <?= $specialist['title'] ?>
                </p>
                <p class="section-form__specialist-description">
                    <?= $specialist['description'] ?>
                </p>
                <div class="section-form__specialist-contact">
                    <a href="<?= $specialist['contact']['url'] ?>" target="<?= $specialist['contact']['target'] ?>"
                        class="section-form__specialist-link">
                        <?= wp_get_attachment_image($specialist['contact_icon']['ID'], 'thumbnail', false, [
                            'class' => "section-form__specialist-icon"
                        ]) ?>
                        <p class="section-form__specialist-platform"><?= $specialist['contact_label'] ?></p>
                        <span class="section-form__specialist-number">
                            <?= $specialist['contact']['title'] ?>
                        </span>
                    </a>
                </div>
                <hr class="section-form__specialist-divider">
            </div>
            <?php
            $office_hours = $page_contact_others['office_hours'];
            ?>
            <div class="section-form__opening">
                <?= wp_get_attachment_image($office_hours['icon']['ID'], 'thumbnail', false, [
                    'class' => "section-form__opening-icon"
                ]) ?>
                <p class="section-form__opening-title">
                    <?= $office_hours['title'] ?>
                </p>
                <div class="section-form__opening-hours">
                    <?php foreach ($office_hours['office_hours'] as $label): ?>
                        <p class="section-form__opening-hour">
                            <?= $label['label'] ?>
                        </p>
                    <?php endforeach; ?>
                </div>
            </div>
            <?= wp_get_attachment_image(1194, 'full', false, [
                'class' => 'section-form__bg'
            ]) ?>
        </div>
    </div>
</section>