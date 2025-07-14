<!-- Post Comments -->
<div class="otw_post_content-blog-comment">
  <?php if( !$this->view_data['settings']['otw_pct_meta_icons'] ) : ?>
  <span class="head"><?php esc_html_e('Comments:', 'otw_pctl');?></span>
  <?php else: ?>
  <span class="head"><i class="icon-comments"></i></span>
  <?php endif; ?>
  <a href="<?php echo esc_attr( get_comments_link($post->ID) );?>" title="<?php esc_html_e('Comment on ', 'otw_pctl'); echo $post->post_title;?>"><?php echo esc_html( $post->comment_count );?></a>
</div>
<!-- END Post Comments -->