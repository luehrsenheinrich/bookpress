<?php
	get_header();
	
if(have_posts()): the_post(); ?>

	<div class="the_content container">
	<?php
		the_content();
	?>
	</div>


<?php endif;

	get_footer();
?>