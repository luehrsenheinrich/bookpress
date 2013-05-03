<?php
	get_header();
	
if(have_posts()): the_post(); ?>

	<?php
		the_content();
	?>


<?php endif;

	get_footer();
?>