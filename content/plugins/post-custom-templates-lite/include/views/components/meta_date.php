<!-- Post Date -->
<div class="otw_post_content-blog-date">
  
  <?php if( !$this->view_data['settings']['otw_pct_meta_icons'] ) : ?>
  <span class="head"><?php esc_html_e('Posted:', 'otw_pctl');?></span>
  <?php else: ?>
  <span class="head"><i class="icon-time"></i></span>
  <?php endif; ?>

  <a href="<?php echo get_day_link(get_the_time('Y', $post), get_the_time('m', $post), get_the_time('d', $post)); ?>" data-date="<?php echo date('Y-m-d', strtotime($post->post_date));?>">
    <?php echo date_i18n( get_option('date_format') ,strtotime($post->post_date) ); ?>
  </a>
</div>
<!-- END Post Date -->