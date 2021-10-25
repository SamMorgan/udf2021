<div class="education-content section-content-wrap">  
    <div class="lrg-txt"><?php get_content_by_id(14);?></div> 
    <div class="two-cols stack-mob">
        <div class="col"><?php the_field('column_1',14);?></div>
        <div class="col education-providers"><?php 
            $col_2 = get_field('column_2');
            if($col_2['heading']){
                echo '<h4>'.$col_2['heading'].'</h4>';
            }
            if($col_2['education_providers']){
                echo '<ul>';
                foreach($col_2['education_providers'] as $ed_prov){ 
                    echo '<li><a href="'.$ed_prov['link'].'" target="_blank"><div>'.$ed_prov['text'].'</div>
                    <svg viewBox="0 0 29.2 18.5"><path d="M17,0l0,0A13,13,0,0,0,20.56,8.6L0,8.14V10.3l20.56-.47c-1.91,2.37-3.64,5.47-3.64,8.6l0,.07A27,27,0,0,1,29.2,9.29V9.18A27.07,27.07,0,0,1,17,0Z" /></svg></a>
                    </li>';
                }
                echo '</ul>';
            }
        ?></div>
    </div>    
</div> 