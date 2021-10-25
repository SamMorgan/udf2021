<?php get_header(); ?>

<?php 
/* 
    Public Interest = 8
    Knowledge Sharing = 10
    Public Conversations = 22
    Research & Advocacy = 12
    Education = 14
    Mentorship & Support = 16
    Change = 18
    Australia = 20 
*/
//global $post;

$pages = get_pages(
    array(
        'sort_column' => 'menu_order',
        'include' => array(8,10,12,14,16,18,20,22)
    )
);

if(isset($_COOKIE['openPages'])) {
    $open_pages_paths = explode(',',$_COOKIE['openPages']);
    $open_pages = array();
    foreach($open_pages_paths as $open_page_path){
        $open_pages[] = get_page_by_path($open_page_path)->ID;
    } 
} ?>

<div class="mobile-header section-header"><span class="h1">Urban Design</span></div>

<?php foreach($pages as $page) { ?>
    <section id="<?php echo $page->post_name;?>" class="section-wrap<?php 
        if(is_page($page->ID) || is_singular('event') && $page->ID === 22 || $open_pages && in_array($page->ID,$open_pages)){ echo ' open';}?>" style="--section-color:<?php the_field('section_colour',$page->ID);?>">
        <div class="section-header h1">
            <a class="section-link" href="<?php echo get_permalink($page->ID);?>">
                <?php if($page->ID === 20){
                    //echo '<h1>'.$page->post_title.'</h1>';
                    echo '<h1><span>Urban Design </span>Forum Australia</h1>';
                }else{ 
                    echo '<h2>'.$page->post_title.'</h2>'; 
                }?>
            </a>
            <a class="close-section" href="<?php echo home_url();?>" data-path="/<?php echo $page->post_name;?>/"></a>    
        </div> 
        <div class="section-content">
            <?php 
                if($open_pages && in_array(8,$open_pages) && $page->ID === 8 || $page->ID === 8 && is_page(8)){
                    include 'includes/content-public-interest.php';
                }
                if($open_pages && in_array(10,$open_pages) && $page->ID === 10 || $page->ID === 10 && is_page(10)){
                    include 'includes/content-knowledge-sharing.php';
                }
                //if($open_pages && in_array(22,$open_pages) && $page->ID === 22 || $page->ID === 22 && is_page(22) || $page->ID === 22 && is_singular('event')){
                if($open_pages && in_array(22,$open_pages) && $page->ID === 22 || $page->ID === 22 && is_page(22)){    
                    include 'includes/content-public-conversations.php';
                }
                if($open_pages && in_array(12,$open_pages) && $page->ID === 12 || $page->ID === 12 && is_page(12)){
                    include 'includes/content-research-advocacy.php';
                }
                if($open_pages && in_array(14,$open_pages) && $page->ID === 14 || $page->ID === 14 && is_page(14)){
                    include 'includes/content-education.php';
                }
                if($open_pages && in_array(16,$open_pages) && $page->ID === 16 || $page->ID === 16 && is_page(16)){
                    include 'includes/content-mentorship-support.php';
                }
                if($open_pages && in_array(18,$open_pages) && $page->ID === 18 || $page->ID === 18 && is_page(18)){
                    include 'includes/content-change.php';
                }
                if($open_pages && in_array(20,$open_pages) && $page->ID === 20 || $page->ID === 20 && is_page(20) || $post->post_parent === 20){
                    include 'includes/content-udf-australia.php';
                }
            ?>
        </div>       
    </section>
<?php } ?> 

<?php if(is_singular('event')){ ?>
    <div class="popup-page event-content<?php if(is_singular('event')){ echo ' open'; }?>" style="--section-color:<?php the_field('section_colour',22);?>">
    
        <div class="section-content-wrap">
            <a href="<?php echo get_permalink(22);?>" class="close-popup close-x"><svg viewBox="0 0 34.94 35.38"><polygon points="34.94 3.65 31.1 0 17.57 13.87 4.18 0.05 0.38 3.84 13.82 17.62 0 31.59 3.74 35.38 17.42 21.41 31.06 35.33 34.9 31.59 21.12 17.62 34.94 3.65"/></svg></a> 
            <?php include 'includes/content-event.php';?>
        </div>    
    
    </div>
<?php }?>

<?php if(is_page() && !in_array($post->ID, array(8,10,12,14,16,18,20,22))){ ?>
<div class="popup-page subpage-content<?php //if(is_page() && !in_array($post->ID, array(8,10,12,14,16,18,20,22))){ echo ' open'; }?>" style="--section-color:<?php 
    $color = get_field('section_colour',$post->ID) ? get_field('section_colour',$post->ID) : '#e6e6e6';
    echo $color;
    ?>">
    <?php if($post->post_parent){ echo '<h1 class="section-header">'.get_the_title($post->post_parent).'</h1>';}?>
    <a href="<?php echo get_permalink($post->post_parent);?>" class="close-popup close-x"><svg viewBox="0 0 34.94 35.38"><polygon points="34.94 3.65 31.1 0 17.57 13.87 4.18 0.05 0.38 3.84 13.82 17.62 0 31.59 3.74 35.38 17.42 21.41 31.06 35.33 34.9 31.59 21.12 17.62 34.94 3.65"/></svg></a> 
    <div class="section-content-wrap">
        <?php while (have_posts()) : the_post(); ?>
            <div class="lrg-txt"><?php the_content();?></div>
            <?php include 'includes/modules.php';?>
        <?php endwhile;?>      
    </div>    
</div>
<?php } ?>

<?php if(is_paged() || $_GET['view-more']){
    if($post->ID === 10){
        include 'includes/content-knowledge-sharing-paged.php';
    }
    if($post->ID === 22){
        include 'includes/content-public-conversations-paged.php';
    }
}?>

        
<?php get_footer(); ?>