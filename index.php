<?php get_header(); ?>

<div class="container">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    
    	<div <?php post_class("span12 media"); ?>>
        	<h3 class="media-heading"><?php the_title(); ?></h3>
        	<div class="media-body"><?php the_content(); ?></div>
        </div>
    
    <?php endwhile; endif; ?>
</div>

<?php get_footer(); ?>