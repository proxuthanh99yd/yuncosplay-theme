<?php
$reason = get_field('section_reasons', 'option');
$title = isset($reason['title']) ? $reason['title'] : '';
$items = isset($reason['reason_items']) ? $reason['reason_items'] : [];

$bg_deco_id = 1443;
$bg_deco_mb_id = 1968;
$phone_icon_id = 1359;

$total = count($items);
?>


<section id="why-avian-odyssey" class="destination-reason">
	<div class="destination-reason_img-wrapper">
		<?= wp_get_attachment_image($bg_deco_id, 'full', false, array('class' => 'destination-reason_img')) ?>
		<?= wp_get_attachment_image($bg_deco_mb_id, 'full', false, array('class' => 'destination-reason_img-mb')) ?>
	</div>
	<div class="destination-reason_container">
		<h2 class="destination-reason_title"><?= $title ?></h2>
		<div class="destination-reason_cards">
			<?php foreach($items as $index => $item): ?>
			<?php 
			$is_last = ($index === $total - 1);
			$icon = isset($item['icon']) ? $item['icon'] : '';
			$item_title = isset($item['title']) ? $item['title'] : '';
			$descs = isset($item['description_items']) ? $item['description_items'] : [];
			?>
			<div class="destination-reason_card">
				<div class="destination-reason_card-icon-wrapper">
					<?= wp_get_attachment_image($icon, 'full', false, array('class' => 'destination-reason_card-icon')) ?>
				</div>
				<div class="destination-reason_card-content">
					<h3 class="destination-reason_card-title"><?= $item_title ?></h3>
					<ul class="destination-reason_card-list">
						<?php foreach($descs as $desc): ?>
						<?php 
						$description_types = $desc['description_types'] ?? 'text';
						$description_text = '';
						$description_link = null;

						if($description_types === 'text') {
							$description_text = $desc['description_item'];
						} else if($description_types === 'link_contact') {
							$description_link = $desc['description_link'];
						}
						?>
						<?php if($description_types === 'text'): ?>
						<li class="destination-reason_card-item"><?= $description_text ?></li>
						<?php elseif($description_types === 'link_contact'): ?>
						<li class="destination-reason_card-item">
							<div class="destination-reason_card-contact">
								<?php if(!empty($description_link['contact_page']) && !empty($description_link['contact_page']['url']) ) :?>
								<a
								   href="<?= $description_link['contact_page']['url']; ?>"
								   class="destination-reason_card-link compound-avian-button compound-avian-button--lg"
								   >
									<div class="compound-avian-button__content">
										<span class="compound-avian-button__content-text">
											<?= $description_link['contact_page']['title'] ?? "Answering your queries"; ?>
										</span>
									</div>
								</a>
								<?php endif; ?>
								<div class="destination-reason_card-item-wrapper">
									<span class="destination-reason_card-phone-label">
										<?= $description_link['contact_text'] ?? '' ?>
									</span>
									<?php if(!empty($description_link['contact_phone']) && !empty($description_link['contact_phone']['url'])) :?>
									<a href="<?=$description_link['contact_phone']['url'];?>" class="destination-reason_card-phone-link">
										<?= wp_get_attachment_image($phone_icon_id, 'full', false, array('class' => 'destination-reason_card-phone')) ?>
										<span><?= $description_link['contact_phone']['title']; ?></span>
									</a>
									<?php endif; ?>
								</div>
							</div>
						</li>
						<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<div class="destination-reason_cards-mb swiper">
			<div class="swiper-wrapper">
				<?php foreach($items as $index => $item): ?>
				<?php 
				$is_last = ($index === $total - 1);
				$icon = isset($item['icon']) ? $item['icon'] : '';
				$item_title = isset($item['title']) ? $item['title'] : '';
				$descs = isset($item['description_items']) ? $item['description_items'] : [];
				?>
				<div class="swiper-slide">
					<div class="destination-reason_card">
						<div class="destination-reason_card-icon-wrapper">
							<?= wp_get_attachment_image($icon, 'full', false, array('class' => 'destination-reason_card-icon')) ?>
						</div>
						<div class="destination-reason_card-content">
							<h3 class="destination-reason_card-title"><?= $item_title ?></h3>
							<ul class="destination-reason_card-list">
								<?php foreach($descs as $desc): ?>
								<?php 
								$description_types = $desc['description_types'] ?? 'text';
								$description_text = '';
								$description_link = null;

								if($description_types === 'text') {
									$description_text = $desc['description_item'];
								} else if($description_types === 'link_contact') {
									$description_link = $desc['description_link'];
								}
								?>
								<?php if($description_types === 'text'): ?>
								<li class="destination-reason_card-item"><?= $description_text ?></li>
								<?php elseif($description_types === 'link_contact'): ?>
								<li class="destination-reason_card-item">
									<div class="destination-reason_card-contact">
										<?php if(!empty($description_link['contact_page']) && !empty($description_link['contact_page']['url']) ) :?>
										<a
										   href="<?= $description_link['contact_page']['url']; ?>"
										   class="destination-reason_card-link compound-avian-button"
										   >
											<div class="compound-avian-button__content">
												<span class="compound-avian-button__content-text">
													<?= $description_link['contact_page']['title'] ?? "Answering your queries"; ?>
												</span>
											</div>
										</a>
										<?php endif; ?>
										<div class="destination-reason_card-item-wrapper">
											<span class="destination-reason_card-phone-label">
												<?= $description_link['contact_text'] ?? '' ?>
											</span>
											<?php if(!empty($description_link['contact_phone']) && !empty($description_link['contact_phone']['url'])) :?>
											<a href="<?=$description_link['contact_phone']['url'];?>" class="destination-reason_card-phone-link">
												<?= wp_get_attachment_image($phone_icon_id, 'full', false, array('class' => 'destination-reason_card-phone')) ?>
												<span><?= $description_link['contact_phone']['title']; ?></span>
											</a>
											<?php endif; ?>
										</div>
									</div>
								</li>
								<?php endif; ?>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<div class="destination-reason_pagination swiper-pagination"></div>
</section>
