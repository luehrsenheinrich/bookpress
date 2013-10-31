<?php get_header(); ?>

<div class="container the_loop">
    <?php get_template_part("template-parts/loop"); ?>
    
    <?php get_template_part("template-parts/archive-pagination"); ?>
</div>

<?php get_footer(); ?>