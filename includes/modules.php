<?php if( have_rows('modules') ):

    while ( have_rows('modules') ) : the_row();

        if( get_row_layout() == 'single_column' ): ?>
            <div class="lrg-txt single-col-module"><?php the_sub_field('text');?></div>


        <?php elseif( get_row_layout() == 'two_columns' ):?>
            <div class="two-cols stack-mob two-cols-module">
                <div class="col"><?php the_sub_field('column_1');?></div>
                <div class="col"><?php the_sub_field('column_2');?></div>
            </div>  
        
        <?php elseif( get_row_layout() == 'image' ):?>
            <div class="single-img-module">
                <?php 
                    $img = get_sub_field('image');
                    $padding = $img['height']/$img['width']*100;
                    echo '<div class="imgwrap" style="padding-bottom:'.$padding.'%"><img src="'.$img['url'].'"></div>';
                    if($img['caption']){
                        echo '<span class="caption">'.$img['caption'].'</span>';
                    }
                ?>
            </div>        

        <?php elseif( get_row_layout() == 'gallery' ):?>
            <div class="gallery-module">
                <?php 
                    $images = get_sub_field('gallery');
                    $gallery_html = "";
                    //$ratios = array();
                    $ratio_total = 0;
                    if( $images ): 
                        foreach( $images as $image ): 
                            if($image['subtype'] === 'pdf'){
                                $thumb = get_field('pdf_thumbnail',$image['ID']);
                                if($thumb){
                                    $gallery_html .= '<li><a class="imgwrap" href="'.$image['url'].'" target="_blank"><img src="'.$thumb['sizes']['medium'].'"></a></li>';
                                    //$ratios[] = $thumb['height']/$thumb['width']*100;
                                    $ratio_total += $thumb['height']/$thumb['width']*100;
                                }
                            }else{
                                $gallery_html .= '<li><div class="imgwrap"><img src="'.$image['sizes']['medium'].'"></div></li>';
                                //$ratios[] = $image['height']/$image['width']*100;
                                $ratio_total += $image['height']/$image['width']*100;
                            }
                        endforeach;
                    endif; 
                    $av_ratio = $ratio_total / count($images);
                    echo '<ul style="--ratio-padding:'.$av_ratio.'%">'.$gallery_html.'</ul>';
                ?>
            </div> 
        
        <?php elseif( get_row_layout() == 'pull_quote' ):?>
            <div class="pullquote">
                <div class="quote h1"><?php the_sub_field('quote');?></div>
                <?php 
                    $quote_att = get_sub_field('quote_att');
                    if($quote_att){
                        echo '<div class="quote-att">'.$quote_att.'</div>';
                    }
                ?>
            </div>

        <?php elseif( get_row_layout() == 'subheading' ):?>
            <h3 class="subheading"><?php the_sub_field('subheading');?></h3>

        <?php elseif( get_row_layout() == 'cta_button' ):
            $link = get_sub_field('link');
            $text = get_sub_field('text');
            if($link && $text)?>
            <a <?php 
                if(get_field('external_link')){ 
                    echo 'class="cta module" target="_blank"'; 
                }else{
                    echo 'class="cta module open-popup"'; 
                }
                ?> href="<?php echo $link;?>"><?php echo $text;?></a>

        <?php endif;


    endwhile;

endif;