<?php 
    $newsletter_archive = get_page_by_path( 'newsletter-archive' ); 
?>
<div id="newsletter-archive" class="popup-page newsletter-archive-content" style="--section-color:<?php 
    $color = get_field('section_colour',$newsletter_archive->ID) ? get_field('section_colour',$newsletter_archive->ID) : '#e6e6e6';
    echo $color;
    ?>">
    <?php if($post->post_parent){ echo '<h1 class="section-header">'.get_the_title($post->post_parent).'</h1>';}?>
    <a href="<?php if($post->post_parent){ 
            echo get_permalink($post->post_parent);
        }else{
            echo home_url();
        }?>" class="close-popup close-x">
        <svg viewBox="0 0 34.94 35.38">
            <polygon points="34.94 3.65 31.1 0 17.57 13.87 4.18 0.05 0.38 3.84 13.82 17.62 0 31.59 3.74 35.38 17.42 21.41 31.06 35.33 34.9 31.59 21.12 17.62 34.94 3.65"/>
        </svg>
    </a>  
    <div class="section-content-wrap"> 
        <h1><?php 
                echo get_the_title($newsletter_archive->ID);
            ?></h1>
        <div class="lrg-txt">
            <?php get_content_by_id($newsletter_archive->ID);?>
        </div>
        <div class="section-content index-wrap">
            <div class="filter-content index">
                <?php
                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                    $newsletter_arr = array(
                        'post_type' => 'newsletter',
                        'orderby' => 'menu_order',
                        'order' => 'ASC',
                        'paged' => $paged,
                        'posts_per_page' => 16
                    );


                    $newsletter_query = new WP_Query($newsletter_arr); 

                    if($newsletter_query->have_posts()) : 
                        while ( $newsletter_query->have_posts() ) : $newsletter_query->the_post(); ?>
                            <article class="newletter-card">
                                <a href="<?php the_field('newsletter_pdf');?>" target="_blank">
                                    <?php            
                                        if(has_post_thumbnail()){
                                            $thumb_data = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' ); 
                                            echo '<div class="imgwrap" style="--bg-img:url('.$thumb_data[0].')"><img src="'.$thumb_data[0].'"></div>';
                                        }
                        
                                    ?>
                                    <h4><?php the_title();?></h4>
                                </a>        
                            </article> 
                        <?php endwhile;
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
                    'total' =>  $newsletter_query->max_num_pages
                ) );
                if($pagination){
                    echo '<nav class="pagination pagination-cont filter-content">'.$pagination.'</nav>';
                }

                wp_reset_query();
            ?>
        </div>
    </div>                   
</div>