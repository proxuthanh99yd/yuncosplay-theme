<?php
/**
 * Thank You Page – hiển thị thông tin từ query string sau khi gửi form contact.
 * URL mẫu: /thankyou?destinations=...&month_num=...&year_num=...&first_name=...&last_name=...&email=...&phone=...&tour_name=...&budget_min=...&budget_max=...&comments=...&accept_video=...
 */

$get = function ($key, $default = '') {
    return isset($_GET[$key]) ? sanitize_text_field(wp_unslash($_GET[$key])) : $default;
};

$first         = $get('first_name');
$last          = $get('last_name');
$email         = $get('email');
$phone         = $get('phone');
$phone_n       = $get('phone_national');
$month         = $get('month_num');
$year          = $get('year_num');
$duration      = $get('duration');
$number        = $get('number');
$tour          = $get('tour_name');
$dests         = $get('destinations');
$budget_min    = $get('budget_min');
$budget_max    = $get('budget_max');
$budget_later  = $get('budget_later');
$comments      = $get('comments');
$accept_video  = $get('accept_video');

$name = trim($first . ' ' . $last);

// Tháng năm: mm/yyyy
$month_year = '';
if ($month !== '' && $year !== '') {
    $m = intval($month);
    $y = intval($year);
    if ($m >= 1 && $m <= 12 && $y > 0) {
        $month_year = sprintf('%02d/%d', $m, $y);
    }
}

// Duration: nếu chỉ là số thì thêm "week(s)"
$duration_display = '';
if ($duration) {
    $duration_display = (is_numeric($duration) && (int) $duration > 0)
        ? ((int) $duration === 1 ? '1 week' : $duration . ' weeks')
        : $duration;
}

// Số người: giữ nguyên từ URL (vd: "2 people")
$participants = $number;

// Phone: kết hợp mã quốc gia nếu có
$phone_display = '';
if ($phone_n && $phone) {
    $phone_n = ltrim($phone_n, '+');
    $phone_display = '+' . $phone_n . ' ' . $phone;
} elseif ($phone) {
    $phone_display = $phone;
}
// if ($phone_n && $phone) {
//     $phone_display = trim($phone_n . ' ' . $phone);
// } elseif ($phone) {
//     $phone_display = $phone;
// }

// Budget: min–max, "Decide later", hoặc cả hai
$budget_display = '';
$has_range = $budget_min !== '' || $budget_max !== '';
$later = $budget_later && strtolower($budget_later) === 'yes';
if ($has_range) {
    $parts = array_filter([$budget_min, $budget_max]);
    $budget_display = implode(' – ', $parts);
    if (preg_match('/^\d+$/', $budget_min) || preg_match('/^\d+$/', $budget_max)) {
        $budget_display .= ' USD';
    }
    if ($later) {
        $budget_display .= ' (Decide later)';
    }
} elseif ($later) {
    $budget_display = 'Decide later';
}

// Accept video: hiển thị Yes/No
$accept_video_display = '';
if ($accept_video) {
    $accept_video_display = strtolower($accept_video) === 'yes' ? 'Yes' : $accept_video;
}

// Danh sách field: chỉ hiển thị khi có giá trị
$fields = [
    ['label' => 'Name', 'value' => $name],
    ['label' => 'Destinations', 'value' => $dests],
    ['label' => 'Month / Year', 'value' => $month_year],
    ['label' => 'Duration', 'value' => $duration_display],
    ['label' => 'Number of Participants', 'value' => $participants],
    ['label' => 'Budget', 'value' => $budget_display],
    ['label' => 'Phone Number', 'value' => $phone_display],
    ['label' => 'Email', 'value' => $email],
    ['label' => 'Tour Name', 'value' => $tour],
    ['label' => 'Comments', 'value' => $comments],
    ['label' => 'Accept video call', 'value' => $accept_video_display],
];
$fields = array_filter($fields, function ($f) { return $f['value'] !== '' && $f['value'] !== null; });

// Specialist & Office hours: lấy từ ACF của trang Contact (nếu có)
$contact_page = get_page_by_path('contact');
$specialist   = null;
$office       = null;
if ($contact_page) {
    $others = get_field('page_contact_others', $contact_page->ID);
    if (!empty($others['specialist'])) {
        $specialist = $others['specialist'];
    }
    if (!empty($others['office_hours'])) {
        $office = $others['office_hours'];
    }
}

$background_mobile_id = 2055;
?>

<section class="thankyou">
    
    <?= wp_get_attachment_image($background_mobile_id, 'full', false, array( 'class' => 'thankyou_background-mobile')) ?>
    
    <div class="thankyou__banner">
        <h1 class="thankyou__banner-title">We have received your quote request.</h1>
        <p class="thankyou__banner-subtitle">Thank you for trusting us, we will contact you as soon as possible.</p>
    </div>

    <div class="thankyou__container">
        <div class="thankyou__main">
            <div class="thankyou__card">
                <div class="thankyou__card-header">
                    <h2 class="thankyou__card-title">Confirm information</h2>
                    <?= wp_get_attachment_image(2021, 'thumbnail', false, ['class' => 'thankyou__card-icon']) ?>
                </div>
                <div class="thankyou__card-body">
                    <dl class="thankyou__details">
                        <?php foreach ($fields as $f): ?>
                        <div class="thankyou__row">
                            <dt class="thankyou__label"><?= esc_html($f['label']) ?></dt>
                            <dd class="thankyou__value"><?= esc_html($f['value']) ?></dd>
                        </div>
                        <?php endforeach; ?>
                    </dl>
                </div>
            </div>
            <div class="thankyou__card-footer">
                <a href="<?= esc_url(home_url('/')) ?>" class="thankyou__btn compound-avian-button">
                    <p class="thankyou__btn-text">KEEP EXPLORING</p>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                        <path d="M10.8223 14.1148C10.6798 14.1148 10.5373 14.0623 10.4248 13.9498C10.2073 13.7323 10.2073 13.3723 10.4248 13.1548L14.5798 8.99984L10.4248 4.84484C10.2073 4.62734 10.2073 4.26734 10.4248 4.04984C10.6423 3.83234 11.0023 3.83234 11.2198 4.04984L15.7723 8.60234C15.9898 8.81984 15.9898 9.17984 15.7723 9.39734L11.2198 13.9498C11.1073 14.0623 10.9648 14.1148 10.8223 14.1148Z" fill="white"/>
                        <path d="M15.2475 9.5625H2.625C2.3175 9.5625 2.0625 9.3075 2.0625 9C2.0625 8.6925 2.3175 8.4375 2.625 8.4375H15.2475C15.555 8.4375 15.81 8.6925 15.81 9C15.81 9.3075 15.555 9.5625 15.2475 9.5625Z" fill="white"/>
                    </svg>
                </a>
            </div>
        </div>

        <aside class="thankyou__sidebar">
            <?php if ($specialist): ?>
                <div class="thankyou__specialist">
                    <div class="thankyou__specialist-images">
                        <?php foreach ((array) ($specialist['specialist'] ?? []) as $item): ?>
                            <?php
                            $img_id = is_array($item) ? ($item['ID'] ?? null) : $item;
                            if ($img_id): ?>
                                <?= wp_get_attachment_image((int) $img_id, 'thumbnail', false, ['class' => 'thankyou__specialist-image']) ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <h3 class="thankyou__specialist-title"><?= esc_html($specialist['title'] ?? 'Contact a Vietnam Specialist') ?></h3>
                    <p class="thankyou__specialist-desc"><?= esc_html($specialist['description'] ?? '') ?></p>
                    <?php if (!empty($specialist['contact']['url'])): ?>
                        <a href="<?= esc_url($specialist['contact']['url']) ?>" target="<?= esc_attr($specialist['contact']['target'] ?? '_blank') ?>" rel="noopener" class="thankyou__specialist-link">
                            <?php if (!empty($specialist['contact_icon']['ID'])): ?>
                                <?= wp_get_attachment_image((int) $specialist['contact_icon']['ID'], 'thumbnail', false, ['class' => 'thankyou__specialist-icon']) ?>
                            <?php endif; ?>
                            <span class="thankyou__specialist-platform"><?= esc_html($specialist['contact_label'] ?? 'WHATSAPP') ?></span>
                            <span class="thankyou__specialist-number"><?= esc_html($specialist['contact']['title'] ?? '') ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="thankyou__specialist">
                    <h3 class="thankyou__specialist-title">Contact a Vietnam Specialist</h3>
                    <p class="thankyou__specialist-desc">Designed for the best Vietnam experience, follow our route for optimal results.</p>
                    <a href="https://wa.me/572958246" target="_blank" rel="noopener" class="thankyou__specialist-link">
                        <span class="thankyou__specialist-platform">WHATSAPP</span>
                        <span class="thankyou__specialist-number">( 00572958246 )</span>
                    </a>
                </div>
            <?php endif; ?>

            <hr class="thankyou__divider">

            <?php if ($office): ?>
                <div class="thankyou__opening">
                    <?php if (!empty($office['icon']['ID'])): ?>
                        <?= wp_get_attachment_image((int) $office['icon']['ID'], 'thumbnail', false, ['class' => 'thankyou__opening-icon']) ?>
                    <?php endif; ?>
                    <h3 class="thankyou__opening-title"><?= esc_html($office['title'] ?? 'Office Hours') ?></h3>
                    <div class="thankyou__opening-hours">
                        <?php foreach ((array) ($office['office_hours'] ?? []) as $row): ?>
                            <p class="thankyou__opening-hour"><?= esc_html($row['label'] ?? '') ?></p>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="thankyou__opening">
                    <h3 class="thankyou__opening-title">Office Hours</h3>
                    <div class="thankyou__opening-hours">
                        <p class="thankyou__opening-hour">Monday - Friday: 08:30am - 11:00pm</p>
                        <p class="thankyou__opening-hour">Saturday: 08:30am - 12:00pm</p>
                        <p class="thankyou__opening-hour">Sunday: Closed (excluding national holidays)</p>
                    </div>
                </div>
            <?php endif; ?>
        </aside>
    </div>
</section>
