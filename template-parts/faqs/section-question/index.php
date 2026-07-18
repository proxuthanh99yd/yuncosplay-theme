<?php
$faq_section = get_field('Question');
$list_question = $faq_section['list_question'] ?? [];
$faq_title = ($faq_section['title'] ?? '') ?: 'Giải mã mọi thắc mắc về Yun';
$faq_label = ($faq_section['desc'] ?? '') ?: 'Câu hỏi thường gặp';
?>

<?php if (!empty($list_question)) : ?>
<section id="faq-section" class="faq-section">
  <?= okhub_img('common/mermaid-bg', array('class' => 'faq-section__bg', 'extra' => 'aria-hidden="true"')) ?>

  <div class="faq-section__container">
    <p class="faq-section__label"><?= esc_html($faq_label); ?></p>

    <h2 class="faq-section__title">
      <?= esc_html($faq_title); ?>
    </h2>

    <div class="faq-section__list">
      <?php foreach ($list_question as $item) :
        $question = $item['question'] ?? '';
        $answers = $item['answers'] ?? '';

        if (empty($question) && empty($answers)) {
          continue;
        }
      ?>
        <div class="faq-section__item">
          <button class="faq-section__question" type="button">
            <span><?= esc_html($question); ?></span>

            <svg class="faq-section__icon_ab" xmlns="http://www.w3.org/2000/svg" width="39" height="39" viewBox="0 0 39 39" fill="none">
              <path d="M8.125 12.9998L20.6022 25.1877L32.5 12.9998" stroke="#680103" stroke-width="4" />
            </svg>
          </button>

          <?php if (!empty($answers)) : ?>
            <div class="faq-section__answer">
              <?= wp_kses_post($answers); ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>