<?php
$section_when_to_go = get_field('when_to_go');
$when_to_go_title = $section_when_to_go['title'] ?? ''; 
$when_to_go_description = $section_when_to_go['description'] ?? ''; 
$when_to_go_months = $section_when_to_go['months'] ?? []; 

$MONTHS = [
	'january'   => 'January',
	'february'  => 'February',
	'march'     => 'March',
	'april'     => 'April',
	'may'       => 'May',
	'june'      => 'June',
	'july'      => 'July',
	'august'    => 'August',
	'september' => 'September',
	'october'   => 'October',
	'november'  => 'November',
	'december'  => 'December',
];

$STATUS_CLASS_MAP = [
	'best'    => 'best',
	'good'    => 'good',
	'average' => 'avg',
];


?>
<div class="detail-tour-bg-wrap__bg"></div>
<section data-nav-target="tour-when-to-visit" class="when-to-go" id="tour-itinerary">
	<h2 class="when-to-go__heading">
		<?= $when_to_go_title; ?>
	</h2>

	<div class="when-to-go__months" aria-hidden="true">
		<?php foreach ($MONTHS as $key => $label): 
		$status_value = $when_to_go_months[$key] ?? 'average';
		$status_class = $STATUS_CLASS_MAP[$status_value] ?? 'avg';
		?>
		<span class="when-to-go__month when-to-go__month--<?= esc_attr($status_class) ?>">
			<?= esc_html($label) ?>
		</span>
		<?php endforeach; ?>
	</div>

	<p class="when-to-go__desc">
		<?= $when_to_go_description; ?>
	</p>

	<div class="when-to-go__legend" aria-hidden="true">
		<div class="when-to-go__legend-item">
			<span class="when-to-go__legend-dot when-to-go__legend-dot--best"></span>
			Best time to visit
		</div>

		<div class="when-to-go__legend-item">
			<span class="when-to-go__legend-dot when-to-go__legend-dot--avg"></span>
			Average time to visit
		</div>

		<div class="when-to-go__legend-item">
			<span class="when-to-go__legend-dot when-to-go__legend-dot--good"></span>
			Good time to visit
		</div>
	</div>
</section>

