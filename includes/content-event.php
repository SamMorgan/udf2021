    <div class="past-events-heading">
        <h2>Past Events</h2>
    </div> 
    <?php while (have_posts()) : the_post(); ?>
        <h1><?php the_title();?></h1> 
        <h2><?php the_field('host');?><br><?php the_field('visible_date');?></h2>
        <div class="lrg-txt"><?php the_content();?></div>
        <?php include 'modules.php';?>
    <?php endwhile;?>      
    

