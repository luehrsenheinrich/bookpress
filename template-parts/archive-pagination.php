<div class="pagination">
    <?php 
    	if(function_exists("wp_pagenavi")){
	    	wp_pagenavi();
    	} else {
	    	posts_nav_link();
    	}
    ?>
</div>