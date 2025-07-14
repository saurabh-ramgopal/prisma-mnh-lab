<?php

/* Function to enqueue stylesheet from parent theme */

function child_enqueue__parent_scripts() {

    wp_enqueue_style( 'parent', get_template_directory_uri().'/style.css' );

}
add_action( 'wp_enqueue_scripts', 'child_enqueue__parent_scripts' );

// our work page filter
function category_tag_dropdown_filter_and_posts() {
    // Get all categories
    $categories = get_categories();
    // Filter out the specific category and its subcategories
    $categories = array_filter($categories, function($category) {
        return $category->name == 'location' || $category->parent == get_cat_ID('location');
    });

    // Get all tags
    $tags = get_tags();
	
	echo '<div class="pr-work__wrap">';
    echo '<form action="" method="get" class="pr-work__form">';
    echo '<select name="category" id="category">';
    echo '<option value="all" '; 
    if (!isset($_GET['category']) || $_GET['category'] == 'all') echo 'selected';
    echo '>All Location</option>';
    foreach ( $categories as $category ) {
        echo '<option value="' . $category->term_id . '" '; 
        if (isset($_GET['category']) && $_GET['category'] == $category->term_id) echo 'selected';
        echo '>' . $category->name . '</option>';
    }
    echo '</select>';

    echo '<select name="tag" id="tag">';
    echo '<option value="all" '; 
    if (!isset($_GET['tag']) || $_GET['tag'] == 'all') echo 'selected';
    echo '>All Topics</option>';
    foreach ( $tags as $tag ) {
        echo '<option value="' . $tag->term_id . '" '; 
        if (isset($_GET['tag']) && $_GET['tag'] == $tag->term_id) echo 'selected';
        echo '>' . $tag->name . '</option>';
    }
    echo '</select>';

    echo '<input type="submit" value="Filter">';
    echo '</form>';

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 9,
        'paged' => $paged,
        'category__in' => array(get_cat_ID('location')), // Show posts from 'location' category by default
    );

    if(isset($_GET['category']) && $_GET['category'] != 'all') {
        $args['category__in'] = array($_GET['category']);
    }

    if(isset($_GET['tag']) && $_GET['tag'] != 'all') {
        $args['tag__in'] = array($_GET['tag']);
    }

    $query = new WP_Query($args);

	echo '<div class="pr-card__work-row">';
    if($query->have_posts()) {
        while($query->have_posts()) {
            $post = $query->next_post();
			echo '<div class="pr-card__work-col">';
            echo '<div class="post-card pr-card__work">';
            echo '<h2 class="pr-card__title pr-card__title-bold"><a href="' . get_the_permalink($post) . '">' . get_the_title($post) . '</a></h2>';
            echo '<p class="pr-card__para">' . wp_trim_words(get_the_excerpt($post), 60) . '</p>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo 'No posts found';
    }
	echo '</div>';
	echo '</div>';
	

    // Add pagination
    echo '<div class="pagination">';
    echo paginate_links(array(
        'total' => $query->max_num_pages,
        'current' => $paged,
        'prev_text' => __('« Previous'),
        'next_text' => __('Next »'),
    ));
    echo '</div>';

    wp_reset_postdata();
}
// Create a shortcode for the dropdown filter and displaying the posts
add_shortcode('category_tag_filter_and_posts', 'category_tag_dropdown_filter_and_posts');

//function enqueue_custom_script() {
//    wp_enqueue_script('custom-script', get_template_directory_uri() . '/custom.js', array('jquery'), '1.0', true);
//}
//add_action('wp_enqueue_scripts', 'enqueue_custom_script');

// Publication Page Filter
function category_tag_year_dropdown_filter_and_posts() {
    // Retrieve publication categories
    $publication_categories = get_terms(array(
        'taxonomy' => 'publication-categories',
        'hide_empty' => false, // Set to true if you only want to retrieve non-empty categories
    ));
    
    $publication_studies = get_terms(array(
        'taxonomy' => 'publication-studies',
        'hide_empty' => false, // Set to true if you only want to retrieve non-empty categories
    ));
	
	$publication_year = get_terms(array(
        'taxonomy' => 'post-publication-year',
        'hide_empty' => false, // Set to true if you only want to retrieve non-empty categories
    ));

    // Retrieve tags
    $tags = get_terms(array(
        'taxonomy' => 'post_tag',
        'hide_empty' => false,
    ));

    // Get unique years from publication_year field
//     $posts = get_posts(array(
//         'numberposts' => -1,
//         'post_type' => 'publication',
//         'meta_key' => 'publication_year',
//         'orderby' => 'meta_value_num',
//         'order' => 'DESC'
//     ));

//     $years = array();
//     foreach($posts as $post) {
//         $year = get_field('publication_year', $post->ID);
//         if($year && !in_array($year, $years)) {
//             $years[] = $year;
//         }
//     }
    
    echo '<div class="pr-work__wrap">';
    echo '<form action="" method="get" class="pr-work__form">';
    
    // Tag dropdown
     echo '<select name="tag" id="tag">';
    echo '<option value="all" ' . (isset($_GET['tag']) && $_GET['tag'] == 'all' ? 'selected' : '') . '>All Studies</option>';
    foreach ( $publication_studies as $category ) {
        echo '<option value="' . $category->term_id . '" ' . (isset($_GET['tag']) && $_GET['tag'] == $category->term_id ? 'selected' : '') . '>' . $category->name . '</option>';
    }
    echo '</select>';
    
    // Publication Year dropdown
         echo '<select name="pub_year" id="pub_year">';
		echo '<option value="all" ' . (isset($_GET['pub_year']) && $_GET['pub_year'] == 'all' ? 'selected' : '') . '>All Year</option>';
		foreach ( $publication_year as $category ) {
			echo '<option value="' . $category->term_id . '" ' . (isset($_GET['pub_year']) && $_GET['pub_year'] == $category->term_id ? 'selected' : '') . '>' . $category->name . '</option>';
		}
		echo '</select>';
//     echo '<select name="year" id="year">';
//     echo '<option value="all" ' . (isset($_GET['year']) && $_GET['year'] == 'all' ? 'selected' : '') . '>All publication Year</option>';
//     foreach ( $years as $year ) {
//         echo '<option value="' . $year . '" ' . (isset($_GET['year']) && $_GET['year'] == $year ? 'selected' : '') . '>' . $year . '</option>';
//     }
//     echo '</select>';
    
    // Publication Category dropdown
    echo '<select name="category" id="category">';
    echo '<option value="all" ' . (isset($_GET['category']) && $_GET['category'] == 'all' ? 'selected' : '') . '>All Topics</option>';
    foreach ( $publication_categories as $category ) {
        echo '<option value="' . $category->term_id . '" ' . (isset($_GET['category']) && $_GET['category'] == $category->term_id ? 'selected' : '') . '>' . $category->name . '</option>';
    }
    echo '</select>';
    
    echo '<input type="submit" value="Filter">';
    echo '</form>';
    echo '</div>';

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    $args = array(
        'post_type' => 'publication',
        'posts_per_page' => 6,
        'paged' => $paged,
    );

    if(isset($_GET['category']) && $_GET['category'] != 'all') {
        $args['tax_query'][] = array(
            'taxonomy' => 'publication-categories',
            'field' => 'id',
            'terms' => $_GET['category'],
        );
    }

    if(isset($_GET['tag']) && $_GET['tag'] != 'all') {
        $args['tax_query'][] = array(
            'taxonomy' => 'publication-studies',
            'field' => 'id',
            'terms' => $_GET['tag'],
        );
    }
	
	if(isset($_GET['pub_year']) && $_GET['pub_year'] != 'all') {
        $args['tax_query'][] = array(
            'taxonomy' => 'post-publication-year',
            'field' => 'id',
            'terms' => $_GET['pub_year'],
        );
    }

//     if(isset($_GET['year']) && $_GET['year'] != 'all') {
//         $args['meta_query'] = array(
//             array(
//                 'key' => 'publication_year',
//                 'value' => $_GET['year'],
//                 'compare' => '=',
//             )
//         );
//     }

    $query = new WP_Query($args);

    if($query->have_posts()) {
        while($query->have_posts()) {
            $query->the_post();
            echo '<div class="post-card pr-card__work">';
            echo '<h2 class="pr-card__title pr-card__title-bold"><a target="_blank" href="' . get_post_meta(get_the_ID(), 'external_link', true) . '">' . get_the_title() . '</a></h2>';
            echo '<p class="pr-card__para">' . wp_trim_words( get_the_excerpt(), 42, '...' ) . '</p>';
            echo '</div>';
        }
    } else {
        echo 'No posts found';
    }

    // Add pagination
    echo '<div class="pagination">';
    echo paginate_links(array(
        'total' => $query->max_num_pages,
        'current' => $paged,
        'prev_text' => __('« Previous'),
        'next_text' => __('Next »'),
    ));
    echo '</div>';

    wp_reset_postdata();
}

// Create a shortcode for the dropdown filter and displaying the posts
add_shortcode('category_tag_year_filter_and_posts', 'category_tag_year_dropdown_filter_and_posts');



// Press release page
function press_post_shortcode($atts) {
    ob_start();
    $query = new WP_Query(array(
        'post_type' => 'press',
        'posts_per_page' => 5,
        'paged' => get_query_var('paged') ? get_query_var('paged') : 1) 
    );

    while ($query->have_posts()) {
        $query->the_post();
        $external_link = get_post_meta(get_the_ID(), 'external_article_link', true);
        $published_in = get_post_meta(get_the_ID(), 'published_in', true);
        $published_date = get_post_meta(get_the_ID(), 'published_date', true);
        $formatted_date = date("F d Y", strtotime($published_date));
        ?>
        <div class="post-card pr-card__story">
			<div class="pr-d-md-flex">
				<div class="post-card-left pr-shrink-0 line-height-0">
					<?php if (has_post_thumbnail()) : ?>
						<a href="<?php echo $external_link; ?>" target="_blank" class="pr-card__story-img-wrap">
							<img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>">
						</a>
					<?php endif; ?>
				</div>
				<div class="post-card-right">
					<p class="pr-post__external-date">Published In <span class="channel"><?php echo $published_in; ?></span>  <span class="date"><?php echo $formatted_date; ?></span></p>
					<h2 class="pr-card__title pr-card__title pr-card__title-extrabold pr-card__title-story mb-2"><a href="<?php echo $external_link; ?>" target="_blank"><?php the_title(); ?></a></h2>
					
					 <p class="pr-card__para"><?php echo wp_trim_words( get_the_excerpt(), 22, '...' ) ?></p>
				</div>
			</div>
        </div>
        <?php
    }

    // Add pagination
    echo '<div class="pagination">';
    echo paginate_links(array(
        'total' => $query->max_num_pages,
        'current' => max(1, get_query_var('paged')),
        'prev_text' => __('« Previous'),
        'next_text' => __('Next »'),
    ));
    echo '</div>';

    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('press_posts', 'press_post_shortcode');

// Stories from the field
function stories_post_shortcode($atts) {
    ob_start();
    $query = new WP_Query(array(
        'post_type' => 'stories',
        'posts_per_page' => 5,
        'paged' => get_query_var('paged') ? get_query_var('paged') : 1) 
    );

    while ($query->have_posts()) {
        $query->the_post();
        $author_name = get_the_author();
        ?>
        <div class="post-card pr-card__story">
			<div class="pr-d-md-flex">
				<div class="post-card-left pr-shrink-0 line-height-0">
					<?php if (has_post_thumbnail()) : ?>
						<a class="pr-card__story-img-wrap" href="<?php the_permalink(); ?>" target="_blank">
							<img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>">
						</a>
					<?php endif; ?>
				</div>
				<div class="post-card-right">
					<div class=""><h2 class="pr-card__title pr-card__title pr-card__title-bold pr-card__title-story"><a href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a></h2></div>
					<p class="pr-card__story-by">By: <span><?php echo $author_name; ?></span></p>
					<p class="pr-card__para"><?php echo wp_trim_words( get_the_excerpt(), 55, '...' ); ?></p>
				</div>
			</div>
        </div>
        <?php
    }

    // Add pagination
    echo '<div class="pagination">';
    echo paginate_links(array(
        'total' => $query->max_num_pages,
        'current' => max(1, get_query_var('paged')),
        'prev_text' => __('« Previous'),
        'next_text' => __('Next »'),
    ));
    echo '</div>';

    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('stories_posts', 'stories_post_shortcode');

// partner-and-funding page
function partners_and_funders_shortcode($atts) {
    ob_start();
    $query = new WP_Query(array(
        'post_type' => 'partners-and-funder',
        'posts_per_page' => 10,
        'paged' => get_query_var('paged') ? get_query_var('paged') : 1) 
    );

    echo '<div class="grid-container-pf pr-partner__container pr-gap-40">';

    while ($query->have_posts()) {
        $query->the_post();
        $terms = get_the_terms(get_the_ID(), 'partners-and-founder-location');
        $term_name = $terms[0]->name;
		$term_class_name = strtolower(str_replace(' ', '-', $term_name));
        ?>
        <div class="grid-item-pf pr-card__wrap">
			<div class="pr-d-flex pr-items-start pr-content-between pr-card__title-wrap">
				<h2 class="pr-card__title pr-card__title-partners"><span><?php the_title(); ?></span></h2>
				<p class="pr-shrink-0 pr-cate pr-cate--<?php echo str_replace(',', '', $term_class_name); ?>"> <?php echo $term_name; ?></p>
			</div>
            <p class="pr-card__para pr-card__para--grey"><?php echo get_the_excerpt(); ?></p>
        </div>
        <?php
    }

    echo '</div>';

    // Add pagination
    echo '<div class="pagination">';
    echo paginate_links(array(
        'total' => $query->max_num_pages,
        'current' => max(1, get_query_var('paged')),
        'prev_text' => __('« Previous'),
        'next_text' => __('Next »'),
    ));
    echo '</div>';

    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('partners_and_funders_posts', 'partners_and_funders_shortcode');


// Opportunities page
function opportunities_shortcode($atts) {
    ob_start();
    $query = new WP_Query(array(
        'post_type' => 'opportunities',
        'posts_per_page' => 10,
        'paged' => get_query_var('paged') ? get_query_var('paged') : 1) 
    );

    echo '<div class="grid-container-pf pr-partner__container pr-gap-40">';

    while ($query->have_posts()) {
        $query->the_post();
        $terms = get_the_terms(get_the_ID(), 'partners-and-founder-location');
        $term_name = $terms[0]->name;
		$term_class_name = strtolower(str_replace(' ', '-', $term_name));
        ?>
        <div class="grid-item-pf pr-card__wrap">
			<div class="pr-d-flex pr-items-start pr-content-between pr-card__title-wrap">
				<h2 class="pr-card__title pr-card__title-partners"><a href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a></h2>
<!-- 				<p class="pr-shrink-0 pr-cate pr-cate--<?php echo str_replace(',', '', $term_class_name); ?>"> <?php echo $term_name; ?></p> -->
			</div>
            <p class="pr-card__para pr-card__para--grey"><?php echo wp_trim_words( get_the_excerpt(), 15, '...' ); ?></p>
        </div>
        <?php
    }

    echo '</div>';

    // Add pagination
    echo '<div class="pagination">';
    echo paginate_links(array(
        'total' => $query->max_num_pages,
        'current' => max(1, get_query_var('paged')),
        'prev_text' => __('« Previous'),
        'next_text' => __('Next »'),
    ));
    echo '</div>';

    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('opportunities_posts', 'opportunities_shortcode');


// Single partners view
function single_partners_function($atts) {
	
	$atts = shortcode_atts(array(
        'location' => 'US', // Default location if not provided
    ), $atts);

    // Get the location parameter from the shortcode
    $location = sanitize_text_field($atts['location']);

	
	ob_start();
    $query = new WP_Query(array(
        'post_type' => 'partners-and-funder',
        'posts_per_page' => -1, // Show all posts for filtering
        'paged' => get_query_var('paged') ? get_query_var('paged') : 1
    ));

    echo '<div class="pr-partner__container pr-gap-40">';

    // Arrays to keep track of posts displayed for each condition
    $posts_displayed_kintambo = array();
    $posts_displayed_ghana = array();

    while ($query->have_posts()) {
        $query->the_post();
        $terms = get_the_terms(get_the_ID(), 'partners-and-founder-location');
        
        if ($terms && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $term_name = $term->name;
                
                // Filter posts based on location
                if ($location === 'kintambo' && $term_name === 'United States' && empty($posts_displayed_kintambo)) {
                    ?>
                    <div class="grid-item-pf pr-card__wrap">
                        <div class="pr-d-flex pr-items-start pr-content-between pr-card__title-wrap">
                            <h2 class="pr-card__title pr-card__title-partners"><span><?php the_title(); ?></span></h2>
                        </div>
                        <p class="pr-card__para pr-card__para--grey"><?php echo get_the_excerpt(); ?></p>
                    </div>
                    <?php
                    $posts_displayed_kintambo[] = get_the_ID(); // Add post ID to the array
                } elseif ($location === 'ghana' && $term_name === 'Ghana' && empty($posts_displayed_ghana)) {
                    ?>
                    <div class="grid-item-pf pr-card__wrap">
                        <div class="pr-d-flex pr-items-start pr-content-between pr-card__title-wrap">
                            <h2 class="pr-card__title pr-card__title-partners"><span><?php the_title(); ?></span></h2>
                        </div>
                        <p class="pr-card__para pr-card__para--grey"><?php echo get_the_excerpt(); ?></p>
                    </div>
                    <?php
                    $posts_displayed_ghana[] = get_the_ID(); // Add post ID to the array
                } elseif ($location === 'vellore' && $term_name === 'Vellore, IN' && empty($posts_displayed_ghana)) {
                    ?>
                    <div class="grid-item-pf pr-card__wrap">
                        <div class="pr-d-flex pr-items-start pr-content-between pr-card__title-wrap">
                            <h2 class="pr-card__title pr-card__title-partners"><span><?php the_title(); ?></span></h2>
                        </div>
                        <p class="pr-card__para pr-card__para--grey"><?php echo get_the_excerpt(); ?></p>
                    </div>
                    <?php
                    $posts_displayed_ghana[] = get_the_ID(); // Add post ID to the array
                } elseif ($location === 'haryana' && $term_name === 'Haryana, IN' && empty($posts_displayed_ghana)) {
                    ?>
                    <div class="grid-item-pf pr-card__wrap">
                        <div class="pr-d-flex pr-items-start pr-content-between pr-card__title-wrap">
                            <h2 class="pr-card__title pr-card__title-partners"><span><?php the_title(); ?></span></h2>
                        </div>
                        <p class="pr-card__para pr-card__para--grey"><?php echo get_the_excerpt(); ?></p>
                    </div>
                    <?php
                    $posts_displayed_ghana[] = get_the_ID(); // Add post ID to the array
                } elseif ($location === 'pakistan' && $term_name === 'Pakistan' && empty($posts_displayed_ghana)) {
                    ?>
                    <div class="grid-item-pf pr-card__wrap">
                        <div class="pr-d-flex pr-items-start pr-content-between pr-card__title-wrap">
                            <h2 class="pr-card__title pr-card__title-partners"><span><?php the_title(); ?></span></h2>
                        </div>
                        <p class="pr-card__para pr-card__para--grey"><?php echo get_the_excerpt(); ?></p>
                    </div>
                    <?php
                    $posts_displayed_ghana[] = get_the_ID(); // Add post ID to the array
                } elseif ($location === 'zambia' && $term_name === 'Zambia' && empty($posts_displayed_ghana)) {
                    ?>
                    <div class="grid-item-pf pr-card__wrap">
                        <div class="pr-d-flex pr-items-start pr-content-between pr-card__title-wrap">
                            <h2 class="pr-card__title pr-card__title-partners"><span><?php the_title(); ?></span></h2>
                        </div>
                        <p class="pr-card__para pr-card__para--grey"><?php echo get_the_excerpt(); ?></p>
                    </div>
                    <?php
                    $posts_displayed_ghana[] = get_the_ID(); // Add post ID to the array
                }
            }
        }
    }

    echo '</div>';

    wp_reset_postdata();
    return ob_get_clean();
	
}

add_shortcode('single_partners_post', 'single_partners_function');


// Selected categories for our work post
function selected_categories_function($atts) {
    // Get the categories for the current post
    $categories = get_the_terms(get_the_ID(), 'category'); // Retrieves only categories assigned to the post
	

    // Check if there are any categories
    if ($categories && !is_wp_error($categories)) {
        // Initialize an array to store selected categories
        $selected_categories = array();

        // Loop through each category
        foreach ($categories as $category) {
			
		$term_class_name = strtolower(str_replace(' ', '-', $category->name));
			
			
            // Add the category name with a link to the category archive page to the selected categories array
            $selected_categories[] = '<a class="pr-shrink-0 pr-cate pr-cate--' . str_replace(',', '', $term_class_name) . '" href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
        }

		// Return the selected category names as a comma-separated string
		return '<div class="post-categories pr-d-flex pr-flex-wrap pr-work__post-page">' . implode($selected_categories) . '</div>';

    }
}

add_shortcode('selected_categories_post', 'selected_categories_function');



// Get the tagged names list for the current post
function tagged_names_list_function($atts) {
    // Get the tags for the current post
    $tags = get_the_tags();

    // Check if there are any tags
    if ($tags) {
        // Initialize an array to store tag names
        $tag_names = array();

        // Loop through each tag
        foreach ($tags as $tag) {
            // Add the tag name to the tag names array
            $tag_names[] = '<a class="pr-tag pr-tag--default" href="' . esc_url(get_tag_link($tag->term_id)) . '">' . esc_html($tag->name) . '</a>';
        }

        // Return the tag names as a comma-separated string
        return '<div class="post-tags pr-d-flex pr-flex-wrap">' . implode($tag_names) . '</div>';
    }
}

add_shortcode('tagged_names_list', 'tagged_names_list_function');


// post excerpt
function custom_post_excerpt_shortcode() {
    // Get the post excerpt
    $excerpt = get_the_excerpt();

    // If no excerpt is set, generate one from post content
    if (empty($excerpt)) {
        $excerpt = wp_trim_words(get_the_content(), 20); // Adjust the number of words as needed
    }

    // Return the excerpt
    return $excerpt;
}
add_shortcode('post_excerpt', 'custom_post_excerpt_shortcode');
