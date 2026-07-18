<section id="hero-section">
  <div class="custom-breadcrumb-container">
    <nav class="breadcrumb">
      <a href="/">Trang chủ</a>
      <span class="dot active"></span>
      <span class="current">Danh sách tin tức</span>
    </nav>
  </div>
  <h1 class="title">Danh sách tin tức</h1>
  <?php $blog_line_id = IS_MOBILE ? (get_field('blog_line_mb', 'option') ?: 10058) : (get_field('blog_line_pc', 'option') ?: 10056); ?>
  <?= wp_get_attachment_image($blog_line_id, 'full', false, ['class' => 'hero-section__line']) ?>
</section>