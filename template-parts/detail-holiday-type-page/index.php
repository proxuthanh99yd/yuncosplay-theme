<?php
get_template_part('template-parts/detail-holiday-type-page/section-banner/index');
?>
<div class="scroll-container">
  <div class="tabs-container active">
    <div class="tabs">
      <button type="button" class="tab active" data-id="introduction">
        Introduction
      </button>
      <button type="button" class="tab" data-id="finest-beach">
        Finest Beach
      </button>
      <button type="button" class="tab" data-id="beach-getaway">
        Beach Getaway
      </button>
      <button type="button" class="tab" data-id="resort-collection">
        Resort Collection
      </button>
      <button type="button" class="tab" data-id="when-to-go">
        Best time to visit
      </button>
      <button type="button" class="tab" data-id="coastal-inspirations">
        Coastal Inspirations
      </button>
      <button type="button" class="tab" data-id="why-avian-odyssey">
        Why Avian Odyssey
      </button>
    </div>
  </div>
  <?php
    get_template_part("template-parts/detail-holiday-type-page/section-introduction/index");
    get_template_part("template-parts/detail-holiday-type-page/section-finest-beach/index");
    get_template_part("template-parts/detail-holiday-type-page/section-beach-getaway/index");
    get_template_part("template-parts/detail-holiday-type-page/section-resort-collection/index");
    get_template_part("template-parts/detail-holiday-type-page/section-when-to-go/index");
    get_template_part("template-parts/detail-holiday-type-page/section-coastal-inspirations/index");
    get_template_part("template-parts/components/section-reason/index");
  ?>
</div>