<div class="research-advocacy-content section-content-wrap">  
    <div class="lrg-txt filter-content">
        <?php get_content_by_id(12);?>
    </div> 
    
    <?php 
        $reports_arr = array(
            'post_type' => 'report',
            'paged' => $paged,
            'order'	=> 'ASC',
            'posts_per_page' => -1,
        );

        $reports_query = new WP_Query($reports_arr); 

        if($reports_query->have_posts()) : ?>
            <div class="reports index<?php if($reports_query->post_count === 1){ echo ' single-report'; }?>">
                <?php while ( $reports_query->have_posts() ) : $reports_query->the_post(); ?>
                    <article class="report">
                        <a href="<?php the_field('pdf');?>">
                            <div class="report-cover">
                                <?php if(has_post_thumbnail()){
                                    $thumb_data = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); 
                                    echo '<div class="imgwrap" style="--bg-img:url('.$thumb_data[0].')"><img src="'.$thumb_data[0].'"></div>';
                                }else{
                                    echo '<div class="imgwrap placeholder"></div>'; 
                                }?> 
                            </div>                
                            <div class="details">  
                                <h4><?php the_title();?></h4>
                                <div class="excerpt"><?php the_excerpt();?></div>
                                <div class="dl-pdf">→ <a href="'.$pdf.'" target="_blank">Download PDF</a></div>
                            </div> 
                        </a>       
                    </article>   
                <?php endwhile;?>
            </div>          
        <?php endif;
        wp_reset_query();
    ?>
   
</div>    