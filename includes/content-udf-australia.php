<div class="udf-australia-content section-content-wrap sidebar-layout">  
    <nav class="subpage-nav sidebar">
        <div class="toggle-menu-mob">Jump to menu</div>
        <ul> 
            <li><a href="#about-us">About us</a></li>
            <li><a href="#our-history">Our History</a></li>
            <li><a href="#become-a-member">Become a Member</a></li>
            <li><a href="#people">People</a></li>
            <li><a href="#our-supporters">Our Supporters</a></li>
        </ul>    
    </nav>  
    <div class="sidebar-layout-main"> 
        <section id="about-us">
            <?php 
                $about_us = get_field('about_us',20);
                $about_us_heading = $about_us['heading']; 
                $about_us_text = $about_us['text'];
                $about_us_cta_link = $about_us['cta_link']; 
                $about_us_cta_text = $about_us['cta_text']; 

                if($about_us_heading){
                    echo '<h2 class="sub-heading">'.$about_us_heading.'</h2>';
                }
                if($about_us_text){
                    echo '<div class="lrg-txt">'.$about_us_text.'</div>';
                }
                if($about_us_cta_link && $about_us_cta_text){
                    echo '<a class="cta open-popup" href="'.$about_us_cta_link.'">'.$about_us_cta_text.'</a>';
                }
            ?>
        </section> 
        <section id="our-history">
            <?php 
                $history = get_field('history',20);
                $history_heading = $history['heading']; 
                $history_text = $history['text'];
                $history_cta_link = $history['cta_link']; 
                $history_cta_text = $history['cta_text']; 

                if($history_heading){
                    echo '<h2 class="sub-heading">'.$history_heading.'</h2>';
                }
                if($history_text){
                    echo '<div class="lrg-txt">'.$history_text.'</div>';
                }
                if($history_cta_link && $history_cta_text){
                    echo '<a class="cta open-popup" href="'.$history_cta_link.'">'.$history_cta_text.'</a>';
                }
            ?>
        </section>
        <section id="become-a-member">
            <?php 
                $member = get_field('become_a_member',20);
                $member_heading = $member['heading']; 
                $member_text = $member['text'];
                $member_col_1 = $member['column_1'];
                $member_paypal_links = $member['paypal_links'];

                if($member_heading){
                    echo '<h2 class="sub-heading">'.$member_heading.'</h2>';
                }
                if($member_text){
                    echo '<div class="lrg-txt">'.$member_text.'</div>';
                }

            ?>
            <div class="two-cols stack-mob">
                <div class="col richtext">
                    <?php echo $member_col_1;?>
                </div>
                <div class="col">
                    <?php 
                        foreach( $member_paypal_links as $member_paypal_link ) {
                            $heading = $member_paypal_link['heading'];
                            $details = $member_paypal_link['details'];
                            $link = $member_paypal_link['link'];?>
                            <div class="paypal-link">
                                <a href="<?php echo $link;?>" target="_blank">
                                    <h6><?php echo $heading;?></h6>
                                    <?php echo $details;?>
                                </a>
                            </div>   
                            
                        <?php }
                    ?>
                </div>
            </div>        
        </section> 
        <section id="people">
                <?php 
                    $people = get_field('people',20);
                    $people_heading = $people['heading'];
                    $people_leadership = $people['leadership']; 
                    $people_committee = $people['committee']; 

                    if($people_heading){
                        echo '<h2 class="sub-heading">'.$people_heading.'</h2>';
                    }
                ?>
                <div class="two-cols">
                <div class="col">
                    <?php 
                        if( $people_leadership ) {
                            echo '<h4>Leadership</h4>';
                            $list = "";
                            foreach( $people_leadership as $leaders ) {
                                $portrait = $leaders['portrait'];
                                $name = $leaders['name'];
                                $id = sanitize_title($name);
                                $role = $leaders['role'];
                                $bio = $leaders['bio'];
                                $list .= '<li class="richtext"><a class="open-panel" href="#'.$id.'">'.$name.'</a>, '.$role.'</li>';?>
                                <div class="panel profile" id="<?php echo $id;?>">
                                    <div class="content">
                                        <div class="lrg-txt"><?php echo $bio;?></div>
                                        <?php
                                        if($portrait){ 
                                            echo '<div class="portrait">';
                                            if($portrait['caption']){
                                                echo '<span class="caption">'.$portrait['caption'].'</span>';
                                            }
                                            $ratio = $portrait['height']/$portrait['width'] * 100;
                                            echo '<div class="imgwrap" style="padding-bottom:'.$ratio.'%"><img src="'.$portrait['url'].'"></div>';
                                            echo '</div>';
                                        }    
                                        ?> 
                                    </div> 
                                    <a href="#" class="close-panel close-x"><svg viewBox="0 0 34.94 35.38"><polygon points="34.94 3.65 31.1 0 17.57 13.87 4.18 0.05 0.38 3.84 13.82 17.62 0 31.59 3.74 35.38 17.42 21.41 31.06 35.33 34.9 31.59 21.12 17.62 34.94 3.65"/></svg></a>
                                </div>
                            <?php }
                            echo '<ul>'.$list.'</ul>';
                        }
                    ?> 
                </div>
                <div class="col">       
                    <?php if($people_committee){
                        echo $people_committee;
                    }?>
                </div>
        </section> 
        <section id="our-supporters">
            <?php 
                $sup = get_field('supporters',20);
                $sup_heading = $sup['heading'];
                $sup_text = $sup['text'];
                $sup_logos = $sup['logos'];
                $sup_list = $sup['supporters_list'];
                $sup_list_head = $sup_list['heading'];
                $sup_list_sups = $sup_list['supporters'];


                if($sup_heading){
                    echo '<h2 class="sub-heading">'.$sup_heading.'</h2>';
                }
                if($sup_text){
                    echo '<div class="lrg-txt">'.$sup_text.'</div>';
                }
                foreach( $sup_logos as $sup_logo ) {
                    $heading = $sup_logo['heading'];
                    $logos = $sup_logo['logos'];?>
                    <div class="logo-group">
                        <h6><?php echo $heading;?><h6>
                        <?php if( $logos ): ?>
                        <ul>
                            <?php foreach( $logos as $logo ): ?>
                                <li><img src="<?php echo $logo['url'];?>" alt="<?php echo $logo['caption'];?>"></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>   
                    </div>
                <?php }

                if($sup_list_head && $sup_list_sups){
                    echo '<div class="supporters-list"><h4>'.$sup_list_head.'</h4><div class="med-txt">'.$sup_list_sups.'</div></div>';
                }
            ?>
        </section> 
        <section id="contact">
            <?php 
                $contact = get_field('contact',20);
                $contact_heading = $contact['heading'];
                $contact_text = $contact['text'];
                $contacts = $contact['contacts'];


                if($contact_heading){
                    echo '<h2 class="sub-heading">'.$contact_heading.'</h2>';
                }
                if($contact_text){
                    echo '<div class="lrg-txt">'.$contact_text.'</div>';
                }
                if($contacts){
                    echo '<ul class="med-txt contacts">';
                        foreach( $contacts as $contact ) {
                            $heading = $contact['heading'];
                            $email = $contact['email'];
                            echo '<li class="two-cols"><strong class="col">'.$heading.'</strong><span class="col"><a href="mailto:'.$email.'">'.$email.'</a></span></li>';   
                        }
                    echo '</ul>';
                }    


            ?>
        </section>
    </div>        
</div>    