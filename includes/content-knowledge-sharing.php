<?php 
    //include 'check-filters.php'; 
    $filter_names = array('type','topic');
    $filters = check_filters($filter_names,10);
?>   
<div class="knowledge-sharing-content section-content-wrap sidebar-layout">  
    <div class="sidebar filters desktop-filters">
        <?php knowledge_filters('type');?>
        <?php knowledge_filters('topic');?>
    </div> 
    <div class="sidebar-layout-main">
        <div class="lrg-txt">
            <?php get_content_by_id(10);?>
        </div>
        <div class="filters mobile-filters">
            <?php knowledge_filters('type');?>
            <?php knowledge_filters('topic');?>
        </div> 
        <div class="filter-content index">
            <?php
                $knowledge_arr = array(
                    'post_type' => 'knowledge',
                    'orderby' => 'rand',
                    'order' => 'ASC'
                );
                //$filter_names = array('type','topic');
                //if(isset($_GET[$filter_names[0]]) || isset($_GET[$filter_names[1]])){
                $query = "";
                if(isset($filters)){    
                    $query = "&";
                    $tax_arrays = array();
                    foreach($filter_names as $filter_name){
                        if(isset($filters[$filter_name])){
                            $selected_filters = explode(',',$filters[$filter_name]);
                            foreach($selected_filters as $selected_filter){
                                $tax_arrays[] = array(
                                    'taxonomy' => $filter_name,
                                    'field'    => 'slug',
                                    'terms'    => array($selected_filter),
                                );
                            }
                            $query .= $filter_name.'='.$filters[$filter_name];      
                        } 
                    }    
                    $knowledge_arr = array(
                        'post_type' => 'knowledge',
                        'orderby' => 'rand',
                        'order' => 'ASC',
                        'posts_per_page' => 6,
                        'tax_query' => array(
                            'relation' => 'AND',
                            $tax_arrays
                        )  
                    );

                } 
                $knowledge_query = new WP_Query($knowledge_arr); 

                if($knowledge_query->have_posts()) : 
                    while ( $knowledge_query->have_posts() ) : $knowledge_query->the_post(); 
                        include 'knowledge-card.php'; 
                    endwhile;
                endif;?>
        </div>      
        <?php 
            $next_link = get_next_posts_link(__('View More', 'textdomain'), $knowledge_query->max_num_pages);
            if($next_link){
                echo '<div class="open-pagination-popup view-more filter-content"><a class="cta" href="'.get_permalink(10).'?view-more=1'.$query.'">View More</a></div>';
            }

            wp_reset_query();
        ?> 
    </div>          
</div>