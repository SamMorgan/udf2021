<?php $convo_filters = convo_filters();?>
<div class="public-conversations-content section-content-wrap sidebar-layout">  
    <div class="sidebar filters">
        <?php echo $convo_filters;?>    
    </div> 
    <div class="sidebar-layout-main">
        <div class="lrg-txt filter-content">
            <?php get_content_by_id(22);?>
        </div> 
        <div class="filters mobile-filters">
            <div class="filters-wrap">
                <?php echo $convo_filters;?> 
            </div>    
        </div> 
        <div class="filter-content index">
            <?php 
                // $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

                $date_now = date('Y-m-d H:i:s');

                $events_arr = array(
                    'post_type' => 'event',
                    'paged' => 1,
                    'order'	=> 'ASC',
                    // 'orderby' => 'meta_value',
                    // 'meta_key' => 'event_date',
                    //'meta_type'	=> 'DATETIME',
                    'orderby' => 'menu_order',
                    'meta_query'  => array(
                        'relation' => 'OR',
                        array(
                            'key' => 'event_date',
                            'type' => 'DATETIME', // MySQL needs to treat date meta values as numbers
                            'value' => $date_now, // Today in ACF datetime format
                            'compare' => '>=', // Greater than or equal to value
                        ),
                        array(
                            'relation' => 'AND',
                            array(
                                'key' => 'event_date',
                                'type' => 'DATETIME', // MySQL needs to treat date meta values as numbers
                                'value' => $date_now, // Today in ACF datetime format
                                'compare' => '<', // Greater than or equal to value
                            ),
                            array(
                                'key' => 'display_past_event',
                                'value'   => '1',
                                'compare' => '='
                            )
                        )    
                    )
                );

                $query = '';

                if(isset($_GET['event-status'])){
                    $query = '&event-status='.$_GET['event-status'];
                    if($compare = $_GET['event-status'] === 'upcoming'){
                        $events_arr = array(
                            'post_type' => 'event',
                            'paged' => 1,
                            'order'	=> 'ASC',
                            // 'orderby' => 'meta_value',
                            // 'meta_key' => 'event_date',
                            // 'meta_type'	=> 'DATETIME',
                            'orderby' => 'menu_order',
                            'meta_query'  => array(
                                array(
                                    'key' => 'event_date',
                                    'type' => 'DATETIME', // MySQL needs to treat date meta values as numbers
                                    'value' => $date_now, // Today in ACF datetime format
                                    'compare' => '>=', // Greater than or equal to value
                                ),
                            )
                        );
                    }else{    
                        $events_arr = array(
                            'post_type' => 'event',
                            'paged' => 1,
                            'order'	=> 'ASC',
                            // 'orderby' => 'meta_value',
                            // 'meta_key' => 'event_date',
                            // 'meta_type'	=> 'DATETIME',
                            'orderby' => 'menu_order',
                            'meta_query'  => array(
                                'relation' => 'AND',
                                array(
                                    'key' => 'event_date',
                                    'type' => 'DATETIME', // MySQL needs to treat date meta values as numbers
                                    'value' => $date_now, // Today in ACF datetime format
                                    'compare' => '<', // Greater than or equal to value
                                ),
                                array(
                                    'key' => 'display_past_event',
                                    'value'   => '1',
                                    'compare' => '='
                                )
                            )
                        );
                    }    
                } 

                $events_query = new WP_Query($events_arr); 

                if($events_query->have_posts()) : 
                    while ( $events_query->have_posts() ) : $events_query->the_post(); 
                        include 'event-card.php'; 
                    endwhile;
                endif;
                wp_reset_query();
            ?>
        </div>
        <?php $next_link = get_next_posts_link(__('View More', 'textdomain'), $events_query->max_num_pages);
            if($next_link){
                //echo '<div class="open-pagination-popup view-more filter-content">'.$next_link.'</div>';
                echo '<div class="open-pagination-popup view-more filter-content"><a class="cta" href="'.get_permalink(22).'?view-more=1'.$query.'">View More</a></div>';
            }
        ?>    
    </div>              
</div>    

