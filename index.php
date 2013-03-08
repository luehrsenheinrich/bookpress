<?php get_header(); ?>

<div class="container the_loop">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    
    	<article <?php post_class("span12"); ?>>
        	<h2 class="headline"><?php the_title(); ?></h2>
        	<div class="the_content"><?php the_content(); ?></div>
        	<div class="the_meta">
        		<div class="infos">
        			 <?php echo __("by", "lh")." ".get_the_author()." ".__("at")." ".get_the_date(); ?>
        		</div>
        		<div class="comments">
        			<i class="icon-comment">
        			</i>
        			<span class="comments_number"><?php comments_number(); ?></span>
        		</div>
        	</div>
        </article>
    
    <?php endwhile; endif; ?>
</div>

<?php get_footer(); ?>