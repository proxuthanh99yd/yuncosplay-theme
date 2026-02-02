<?php 
    get_template_part('template-parts/destination-page/section-banner/index'); 
    get_template_part('template-parts/destination-page/section-about/index');
?>
 <div class="destination-scroll-container">
       <div class="tabs-container active">
            <div class="tabs">
              <button type="button" class="tab active" data-id="suggested-tours">
                Suggested tours
              </button>
              <button type="button" class="tab" data-id="cultures">
                Cultures
              </button>
              <button type="button" class="tab" data-id="top-attractions">
                Top attractions
              </button>
              <button type="button" class="tab" data-id="stays-collection">
                Signature stay
              </button>
              <button type="button" class="tab" data-id="throughout-the-year">
                Best time to visit
              </button>
              <button type="button" class="tab" data-id="insider">
                Inspiration
              </button>
              <button type="button" class="tab" data-id="why-avian-odyssey">
                Why avian odyssey
              </button>
            </div>
      </div>
      <?php
        get_template_part('template-parts/destination-page/section-suggest/index');
        get_template_part('template-parts/destination-page/section-cultures/index');
        get_template_part('template-parts/destination-page/section-attractions/index');
        get_template_part('template-parts/destination-page/section-stays/index');
        get_template_part('template-parts/destination-page/section-year/index');
        get_template_part('template-parts/destination-page/section-insider/index');
        get_template_part('template-parts/components/section-reason/index');
      ?>
</div>
