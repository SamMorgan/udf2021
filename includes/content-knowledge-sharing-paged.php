<?php 
    //include 'check-filters.php'; 
    $filter_names = array('type','topic');
    $filters = check_filters($filter_names,10);
?> 
<div class="popup-page" id="knowledge-sharing-paged" style="--section-color:<?php the_field('section_colour',10);?>">
    <div class="section-header h1">
        <?php echo '<h2>'.get_the_title(10).'</h2>';?>
    </div>
    <a href="<?php echo get_permalink(10);?>" class="close-popup close-x"><svg viewBox="0 0 34.94 35.38"><polygon points="34.94 3.65 31.1 0 17.57 13.87 4.18 0.05 0.38 3.84 13.82 17.62 0 31.59 3.74 35.38 17.42 21.41 31.06 35.33 34.9 31.59 21.12 17.62 34.94 3.65"/></svg></a> 
    <div class="section-content">
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
                        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                        $knowledge_arr = array(
                            'post_type' => 'knowledge',
                            'orderby' => 'rand',
                            'order' => 'ASC',
                            'paged' => $paged
                        );
                        if(isset($filters)){  
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
                                }    
                            }    
                            $knowledge_arr = array(
                                'post_type' => 'knowledge',
                                'orderby' => 'rand',
                                'order' => 'ASC',
                                'paged' => $paged,
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
                        $big = 999999999;
                        $pagination = paginate_links( array(
                            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                            'format' => '?paged=%#%',
                            'prev_next' => false,
                            'type' => 'list',
                            'current' => max( 1, get_query_var('paged') ),
                            'total' =>  $knowledge_query->max_num_pages
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


