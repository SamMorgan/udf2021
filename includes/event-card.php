<article class="event-card">
    <?php if(strtotime(get_field('event_date',$post->ID,false)) <= time()) { 
        echo '<a class="open-popup" href="'.get_permalink().'">';
    }else{ ?>
        <a href="<?php the_field('external_link');?>" target="_blank">
    <?php }?>             
        <?php if(has_post_thumbnail()){
            $thumb_data = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' ); 
            echo '<div class="imgwrap" style="--bg-img:url('.$thumb_data[0].')"><img src="'.$thumb_data[0].'"></div>';
        }else{
            echo '<div class="imgwrap placeholder"></div>'; 
        }
        ?>
        <div class="filters">  
            <?php if(strtotime(get_field('event_date',$post->ID,false)) <= time()) { 
                echo '<span class="filter">Past</span>';
            }else{
                echo '<span class="filter">Upcoming</span>';
            } ?>
        </div>    
        <div class="details">  
            <h4><?php the_title();?></h4>
            <h5><?php the_field('host');?></h5>
            <h5><?php the_field('visible_date');?></h5>
            <?php the_excerpt();?>
        </div>    
    </a>    
</article>