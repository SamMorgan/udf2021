<div class="change-content section-content-wrap">  
    <div class="lrg-txt"><?php get_content_by_id(18);?></div> 
    <div class="two-cols stack-mob">
        <div class="col change-items"><?php 
            $col_1 = get_field('column_1');
            if($col_1['heading']){
                echo '<h4>'.$col_1['heading'].'</h4>';
            }
            if($col_1['items']){
                echo '<div>';
                foreach($col_1['items'] as $items){ 
                    echo '<div>'.$items['text'].'</div>';
                }
                echo '</div>';
            }
        ?></div>
        <div class="col"><?php 
            $img = get_field('column_2',18);
            if($img){
                $padding = $img['height']/$img['width']*100;
                echo '<div class="imgwrap" style="padding-bottom:'.$padding.'%"><img src="'.$img['url'].'"></div>';
            }
        ?></div>
    </div> 
</div> 