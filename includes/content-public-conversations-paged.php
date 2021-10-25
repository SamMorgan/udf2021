<?php $convo_filters = convo_filters();?>
<div class="popup-page" id="public-conversations-paged" style="--section-color:<?php the_field('section_colour',22);?>">
    <div class="section-header h1">
        <?php echo '<h2>'.get_the_title(22).'</h2>';?>
    </div>
    <a href="<?php echo get_permalink(22);?>" class="close-popup close-x"><svg viewBox="0 0 34.94 35.38"><polygon points="34.94 3.65 31.1 0 17.57 13.87 4.18 0.05 0.38 3.84 13.82 17.62 0 31.59 3.74 35.38 17.42 21.41 31.06 35.33 34.9 31.59 21.12 17.62 34.94 3.65"/></svg></a> 
    <div class="section-content">
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
                        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

                        $date_now = date('Y-m-d H:i:s');

                        $events_arr = array(
                            'post_type' => 'event',
                            'paged' => $paged,
                            'order'	=> 'ASC',
                            // 'orderby' => 'meta_value',
                            // 'meta_key' => 'event_date',
                            // 'meta_type'	=> 'DATETIME',
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
                            $query = '?event-status='.$_GET['event-status'];
                            if($compare = $_GET['event-status'] === 'upcoming'){
                                $events_arr = array(
                                    'post_type' => 'event',
                                    'paged' => $paged,
                                    'order'	=> 'ASC',
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
                                    'paged' => $paged,
                                    'order'	=> 'ASC',
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

                        if($events_query->have_posts()) : 
                            while ( $events_query->have_posts() ) : $events_query->the_post(); 
                                include 'event-card.php';  
                            endwhile;
                        endif;
                        wp_reset_query();
                    ?>
                </div>
                <?php 
                    $big = 999999999;
                    $pagination = paginate_links( array(
                        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                        'format' => '?paged=%#%',
                        'prev_next' => false,
                        'type' => 'list',
                        'current' => max( 1, get_query_var('paged') ),
                        'total' =>  $events_query->max_num_pages
                    ) );
                    if($pagination){
                        echo '<nav class="pagination pagination-cont filter-content">'.$pagination.'</nav>';
                    }

                    wp_reset_query();
                ?>     
            </div>              
        </div>    
    </div>    
</div>


