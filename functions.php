<?php
/*
 * Enable post thumbnail support
 */
  add_theme_support( 'post-thumbnails' );

  //set_post_thumbnail_size( 600, 400, true ); // Normal post thumbnails
  //add_image_size( 'banner-thumb', 566, 250, true ); // Small thumbnail size
  add_image_size( 'project-thumb', 468, 614, true ); // Square thumbnail used by sharethis and facebook  


/*
 * Enable Wordpress features
 */
  
  // Enable styling of Admin
  //add_editor_style('css/editor-style.css'); 
   
    // Turn on menus
  register_nav_menus(
    array(
      //'main_menu' => 'Main Menu',
      'footer_menu' => 'Footer Menu',
    )
  );

    // admin bar off //
    add_action("user_register", "set_user_admin_bar_false_by_default", 10, 1);
    function set_user_admin_bar_false_by_default($user_id) {
        update_user_meta( $user_id, 'show_admin_bar_front', 'false' );
        update_user_meta( $user_id, 'show_admin_bar_admin', 'false' );
    }
    // Set WordPress theme varibles
  // if ( ! isset( $content_width ) ) {
  //  $content_width = 720;
  // }
  // function set_content_width() {
  //  global $content_width;
  //  if ( is_single() ) {
  //    $content_width = 720;   
  //  } else {
  //    $content_width = 720;
  //  }
  // }
  // add_action( 'template_redirect', 'set_content_width' );
    
    // Excerpts for pages
   // add_post_type_support( 'page', 'excerpt' );     



    function enqueue_scripts_styles() {

        wp_enqueue_script( 'js', get_template_directory_uri() . "/dist/main.js", array('jquery'), filemtime( get_stylesheet_directory() . '/dist/main.js' ), true );

        // wp_localize_script( 'js', 'sitevars', array(
        //     'ajaxurl'   => admin_url( 'admin-ajax.php' ),
        //     'homeurl'   => home_url()
        //     )
        // );    
     
        wp_enqueue_style( 'style', get_template_directory_uri() . "/dist/main.css", array(), filemtime( get_stylesheet_directory() . '/dist/main.css' ) );
    }
    add_action( 'wp_enqueue_scripts', 'enqueue_scripts_styles' );



    add_filter('body_class','custom_class_names');
    function custom_class_names($classes) {
        
        // Mobile detects
        switch (true) {         
            case wp_is_mobile() :
                $classes[] = 'is-mobile';                
                break;
            
            default :
                $classes[] = 'not-mobile';                            
                break;
        }

        global $post;
        if ( isset( $post ) ) {
            $classes[] = $post->post_name;
            
            $post_data = get_post($post->post_parent);
            $parent_slug = $post_data->post_name;
            $classes[] = 'parent-'.$parent_slug;
        }        

        return $classes;
    }



    
    add_filter( 'acf/fields/wysiwyg/toolbars' , 'my_toolbars'  );
    function my_toolbars( $toolbars ){
        // Uncomment to view format of $toolbars
        /*
        echo '< pre >';
            print_r($toolbars);
        echo '< /pre >';
        die;
        */

        // Add a new toolbar called "Very Simple"
        // - this toolbar has only 1 row of buttons
        $toolbars['Very Simple' ] = array();
        $toolbars['Very Simple' ][1] = array('link', 'unlink', 'bullist', 'bold');

        // Edit the "Full" toolbar and remove 'code'
        // - delet from array code from http://stackoverflow.com/questions/7225070/php-array-delete-by-value-not-key
        if( ($key = array_search('code' , $toolbars['Full' ][2])) !== false )
        {
            unset( $toolbars['Full' ][2][$key] );
        }

        // remove the 'Basic' toolbar completely
        unset( $toolbars['Basic' ] );

        // return $toolbars - IMPORTANT!
        return $toolbars;
    }


    function get_content_by_id($postid){
        $content_post = get_post($postid);
        $content = $content_post->post_content;
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]&gt;', $content);
        echo $content; 
    } 
  
    
// add_action('admin_head', 'custom_admin_css');

// function custom_admin_css() {
//   echo '<style>
//   /* ACF "repeat-horizontal" class, display repeaters in horizontal columns */
//   .repeat-horizontal .acf-repeater tbody {
//       display: flex;
//       flex-direction: row;
//   }
//   .repeat-horizontal .acf-repeater tr.acf-row:not(.acf-clone) {
//       width: 100%;
//   }
//   .repeat-horizontal .acf-repeater tr.acf-row:not(.acf-clone) td.acf-fields {
//       width: 100% !important; /* important is necessary because it gets overwritten on drag&drop  */
//   }
//   </style>';
// }





function knowledge_post_type() {

	$labels = array(
		'name'                  => _x( 'Knowledge', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Knowledge', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Knowledge', 'text_domain' ),
		'name_admin_bar'        => __( 'Knowledge', 'text_domain' ),
		'archives'              => __( 'Item Archives', 'text_domain' ),
		'attributes'            => __( 'Item Attributes', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
		'all_items'             => __( 'All Items', 'text_domain' ),
		'add_new_item'          => __( 'Add New Item', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New Item', 'text_domain' ),
		'edit_item'             => __( 'Edit Item', 'text_domain' ),
		'update_item'           => __( 'Update Item', 'text_domain' ),
		'view_item'             => __( 'View Item', 'text_domain' ),
		'view_items'            => __( 'View Items', 'text_domain' ),
		'search_items'          => __( 'Search Item', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
		'items_list'            => __( 'Items list', 'text_domain' ),
		'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Knowledge', 'text_domain' ),
		'description'           => __( 'Post Type Description', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail' ),
		'taxonomies'            => array( 'topic' ),
		'hierarchical'          => true,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'knowledge', $args );

}
add_action( 'init', 'knowledge_post_type', 0 );


function events_post_type() {

	$labels = array(
		'name'                  => _x( 'Events', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Event', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Events', 'text_domain' ),
		'name_admin_bar'        => __( 'Event', 'text_domain' ),
		'archives'              => __( 'Event Archives', 'text_domain' ),
		'attributes'            => __( 'Event Attributes', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
		'all_items'             => __( 'All Events', 'text_domain' ),
		'add_new_item'          => __( 'Add New Events', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New Event', 'text_domain' ),
		'edit_item'             => __( 'Edit Event', 'text_domain' ),
		'update_item'           => __( 'Update Event', 'text_domain' ),
		'view_item'             => __( 'View Event', 'text_domain' ),
		'view_items'            => __( 'View Events', 'text_domain' ),
		'search_items'          => __( 'Search Events', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
		'items_list'            => __( 'Items list', 'text_domain' ),
		'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Event', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail' ),
		//'taxonomies'            => array( 'topic' ),
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'event', $args );

}
add_action( 'init', 'events_post_type', 0 );



function report_post_type() {

	$labels = array(
		'name'                  => _x( 'Reports', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Report', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Reports', 'text_domain' ),
		'name_admin_bar'        => __( 'Reports', 'text_domain' ),
		'archives'              => __( 'Item Archives', 'text_domain' ),
		'attributes'            => __( 'Item Attributes', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
		'all_items'             => __( 'All Items', 'text_domain' ),
		'add_new_item'          => __( 'Add New Item', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New Item', 'text_domain' ),
		'edit_item'             => __( 'Edit Item', 'text_domain' ),
		'update_item'           => __( 'Update Item', 'text_domain' ),
		'view_item'             => __( 'View Item', 'text_domain' ),
		'view_items'            => __( 'View Items', 'text_domain' ),
		'search_items'          => __( 'Search Item', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
		'items_list'            => __( 'Items list', 'text_domain' ),
		'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'report', 'text_domain' ),
		'description'           => __( 'Post Type Description', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail' ),
		'taxonomies'            => array( 'topic' ),
		'hierarchical'          => true,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'report', $args );

}
add_action( 'init', 'report_post_type', 0 );



function newsletter_post_type() {

	$labels = array(
		'name'                  => _x( 'Newsletters', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Newsletter', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Newsletters', 'text_domain' ),
		'name_admin_bar'        => __( 'Newsletter', 'text_domain' ),
		'archives'              => __( 'Newsletter Archives', 'text_domain' ),
		'attributes'            => __( 'Newsletter Attributes', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
		'all_items'             => __( 'All Newsletters', 'text_domain' ),
		'add_new_item'          => __( 'Add New Newsletters', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New Newsletter', 'text_domain' ),
		'edit_item'             => __( 'Edit Newsletter', 'text_domain' ),
		'update_item'           => __( 'Update Newsletter', 'text_domain' ),
		'view_item'             => __( 'View Newsletter', 'text_domain' ),
		'view_items'            => __( 'View Newsletters', 'text_domain' ),
		'search_items'          => __( 'Search Newsletters', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
		'items_list'            => __( 'Items list', 'text_domain' ),
		'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Newsletter', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'thumbnail' ),
		//'taxonomies'            => array( 'topic' ),
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		//'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'capability_type'       => 'page',
	);
	register_post_type( 'newsletter', $args );

}
add_action( 'init', 'newsletter_post_type', 0 );



function type_tax() {

	$labels = array(
		'name'                       => _x( 'Types', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Type', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Type', 'text_domain' ),
		'all_items'                  => __( 'All Types', 'text_domain' ),
		'parent_item'                => __( 'Parent Type', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Type:', 'text_domain' ),
		'new_item_name'              => __( 'New Type Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Type', 'text_domain' ),
		'edit_item'                  => __( 'Edit Type', 'text_domain' ),
		'update_item'                => __( 'Update Type', 'text_domain' ),
		'view_item'                  => __( 'View Type', 'text_domain' ),
		'separate_items_with_commas' => __( 'SeparateTypes with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove Types', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Types', 'text_domain' ),
		'search_items'               => __( 'Search Types', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'No Types', 'text_domain' ),
		'items_list'                 => __( 'Types list', 'text_domain' ),
		'items_list_navigation'      => __( 'Types list navigation', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'type', array( 'knowledge' ), $args );

}
add_action( 'init', 'type_tax', 0 );



function topic_tax() {

	$labels = array(
		'name'                       => _x( 'Topics', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Topic', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Topic', 'text_domain' ),
		'all_items'                  => __( 'All Topics', 'text_domain' ),
		'parent_item'                => __( 'Parent Topic', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Topic:', 'text_domain' ),
		'new_item_name'              => __( 'New Topic Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Topic', 'text_domain' ),
		'edit_item'                  => __( 'Edit Topic', 'text_domain' ),
		'update_item'                => __( 'Update Topic', 'text_domain' ),
		'view_item'                  => __( 'View Topic', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate topics with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove topics', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Topics', 'text_domain' ),
		'search_items'               => __( 'Search Topics', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'No topics', 'text_domain' ),
		'items_list'                 => __( 'Topics list', 'text_domain' ),
		'items_list_navigation'      => __( 'Topics list navigation', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'topic', array( 'knowledge' ), $args );

}
add_action( 'init', 'topic_tax', 0 );


function check_filters($filter_names,$page_id){
	if(isset($_GET[$filter_names[0]]) || isset($_GET[$filter_names[1]])){
        $filters = array();
        foreach($filter_names as $filter_name){
            if($_GET[$filter_name]){
                $filters[$filter_name] = $_GET[$filter_name];
            }
        }
		return $filters;    
    }else{
        if(!is_page($page_id) && isset($_COOKIE['filters-knowledge-sharing'])) {
            $url = parse_url($_COOKIE['filters-knowledge-sharing']);
            if($url['query']){
                parse_str($url['query'], $filters);	
            }
        } 
		return $filters;   
    }
}

function knowledge_filters($filter){ ?>
    <div class="filters-wrap">
        <span class="label">Sort by <?php echo $filter;?></span>
        <div class="filters-list">
        <?php 
            $type_query = new WP_Term_Query( 
                array( 
                    'taxonomy' => $filter,
                    'include' => $type_ids 
                ) 
            );
			$filter_names = array('type','topic');


			$filters = check_filters($filter_names,10);

            if ( ! empty( $type_query->terms ) ) {
                foreach ( $type_query->terms as $type ) {
                    $query = '';
                    $active = '';

                    // $filter_names = array('type','topic');
                    // if(isset($_GET[$filter_names[0]]) || isset($_GET[$filter_names[1]])){
					if(isset($filters)){  	
                        $type_query = "";
                        $topic_query = "";
                        $prefix = "?";

                        foreach($filter_names as $filter_name){
                            if(isset($filters[$filter_name])){
                                $curr_types = explode(',',$filters[$filter_name]);
                                $index = array_search($type->slug, $curr_types);
                                if($index !== false){
                                    $active = ' active';
                                    unset($curr_types[$index]);
                                    if(count($curr_types) > 0){
                                        $query .= $prefix.$filter_name.'='.implode(',',$curr_types);
                                        $prefix = "&"; 
                                    }
                                }else{
                                    $query .= $prefix.$filter_name.'='.implode(',',$curr_types).','.$type->slug;
                                    $prefix = "&";
                                }
                                
                            }else{
                                if($filter === $filter_name){
                                    $query .= $prefix.$filter_name.'='.$type->slug;
                                    $prefix = "&";
                                }
                            }                                
                        }
                    }else{
                        $query = '?'.$filter.'='.$type->slug;
                    }

					if(isset($_GET['view-more'])){
						$query .= $prefix.'&view-more=1';
					}

                    echo '<a class="filter'.$active.'" href="'.get_permalink(10).$query.'">'.$type->name.'</a>';
                } 
            }                
        ?>
        </div> 
    </div>
<?php }


function convo_filters(){
	$filter_names = array('event-status');
    $filter = check_filters($filter_names,22);

    $upcoming_filter = '<a class="filter" href="'.get_permalink(22).'?event-status=upcoming">Upcoming</a>';
    if($filter && $filter['event-status'] === 'upcoming'){ 
        $upcoming_filter = '<a class="filter active" href="'.get_permalink(22).'">Upcoming</a>';
    }
    $past_filter = '<a class="filter" href="'.get_permalink(22).'?event-status=past">Past</a>';
    if($filter && $filter['event-status'] === 'past'){ 
        $past_filter = '<a class="filter active" href="'.get_permalink(22).'">past</a>';
    }
    return '<div class="filters-wrap"><span class="label">Sort by type:</span><div class="filters-list">'.$upcoming_filter.$past_filter.'</div></div>';
}


/**
 * Filter the excerpt length to 30 words.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
function wp_example_excerpt_length( $length ) {
    return 18;
}
add_filter( 'excerpt_length', 'wp_example_excerpt_length');

function new_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');