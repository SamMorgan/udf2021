<div class="mentorship-support-content section-content-wrap">  
    <div class="lrg-txt"><?php get_content_by_id(16);?></div>
    <div class="two-cols stack-mob">
        <div class="col"><?php the_field('column_1',16);?></div>
        <div class="col"><?php 
            $img = get_field('column_2',16);
            if($img){
                $padding = $img['height']/$img['width']*100;
                echo '<div class="imgwrap tinted-img" style="padding-bottom:'.$padding.'%"><img src="'.$img['url'].'"></div>';
            }
        ?></div>
    </div> 

    <a class="cta module" href="<?php echo get_permalink(20);?>#become-a-member">Become a Member</a>   
</div> 