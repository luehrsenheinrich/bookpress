<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<div class="row">
	<article <?php post_class("span12"); ?>>
		<div class="article_wrapper">
        	<?php if(has_post_thumbnail()): ?>
        		<div class="post_thumbnail">
        			<a href="<?php the_permalink(); ?>">
	        			<?php the_post_thumbnail(); ?>
        			</a>
        			<h2 class="headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        		</div>
        	<?php else: ?>
        	    <h2 class="headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        	<?php endif; ?>
        	<div class="the_content"><?php echo lh_shorten_text(strip_tags(get_the_content()), 350); ?></div>
        	<div class="the_meta clearfix">
        		<div class="infos">
        			 <?php echo __("by", "lh")." ".get_the_author()." ".__("at")." ".get_the_date(); ?>
        		</div>
        		<?php if(comments_open()): ?>
	        		<div class="comments">
	        			<i class="icon-comment"></i> 
	        			<a href="<?php comments_link() ?>">
		        			<span class="comments_number"><?php comments_number(); ?></span>
	        			</a>
	        		</div>
        		<?php endif; ?>
        	</div>
		</div>
    </article>
</div>
<?php endwhile; endif; ?>