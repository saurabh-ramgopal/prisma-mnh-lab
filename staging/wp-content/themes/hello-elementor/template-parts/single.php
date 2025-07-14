<?php
/**
 * The template for displaying singular post-types: posts, pages and user-defined custom post types.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

while ( have_posts() ) :
	the_post();
	?>

<main id="content" <?php post_class( 'site-main' ); ?>>

	<?php if ( apply_filters( 'hello_elementor_page_title', true ) ) : ?>
		<header class="page-header pr-page__header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header>
	<?php endif; ?>

	<div class="page-content">

				<?php
				// Get the current post ID
				$post_id = get_the_ID();

				// Define the category ID you want to display categories for
				$desired_category_id = 10; // Replace with your specific category ID

				// Check if the current post is in the desired category
				if (has_category($desired_category_id)) {
					// Get the categories for the current post
					$parent_category_id = 6; // Replace with your desired parent category ID
						$categories = get_the_category();

						// Filter out categories that are children of the specified parent category
						$child_categories = array_filter($categories, function ($category) use ($parent_category_id) {
							return $category->parent == $parent_category_id;
						});
					
						$child_categories_work = array_filter($categories, function ($category) use ($desired_category_id) {
							return $category->parent == $desired_category_id;
						});
					
						echo '<div class="e-con e-flex"><div class="pr-post__single-work e-con-inner">';
						echo '<div class="pr-post__single-work-left">';
						echo  '<div class="pr-categories__heading">Study Description</div>';
							the_content();
					echo '<ul class="post-categories post-categories--grey">';
							foreach ($child_categories_work as $category) {


						echo '<li class="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</li>';
					}
						echo '</ul>';
						echo '</div>';
						echo '<div class="pr-categories__list">';
						echo '<div class="pr-categories__heading">Participating Study Locations</div>';
						echo '<ul class="post-categories">';

						// Loop through child categories
						// Loop through child categories
					foreach ($child_categories as $category) {


						echo '<li class="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</li>';
					}
					echo '</ul>';
					echo '</div>';
					echo '</div></div>';
					

				} else {
					echo '<div class="pr-post__content-section">';
					the_content();
					echo '</div>';
				}
				?>
		<div class="post-tags">
			<?php the_tags( '<span class="tag-links">' . esc_html__( 'Tagged ', 'hello-elementor' ), null, '</span>' ); ?>
		</div>
		<?php wp_link_pages(); ?>
	</div>

	<?php comments_template(); ?>

</main>

	<?php
endwhile;
