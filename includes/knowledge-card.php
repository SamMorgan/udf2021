<article class="knowledge-card">
    <a href="<?php the_field('link');?>" target="_blank">
        <?php            
            if(has_post_thumbnail()){
                $thumb_data = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' ); 
                echo '<div class="imgwrap" style="--bg-img:url('.$thumb_data[0].')"><img src="'.$thumb_data[0].'"></div>';
            }

            $types = wp_get_post_terms( $post->ID, 'type', array( 'fields' => 'all' ));
            if($types){
                echo '<div class="filters">';
                foreach($types as $type){
                    echo '<span class="filter">'.$type->name.'</span>';
                }
                echo '</div>';
            }
        ?> 
        <div class="details">
            <h4><?php the_title();?></h4>
            <?php 
                $date_loc = get_field('date_location');
                if($date_loc){
                    echo '<h5>'.$date_loc.'</h5>';
                }
            ?>
            <?php the_excerpt();?>
        </div>
    </a>        
</article> 