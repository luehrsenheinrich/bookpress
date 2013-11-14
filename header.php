<?php
	global $post;
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php wp_title(); ?></title>
    
    <?php wp_head(); ?>
    
    <?php
    	if(is_singular() && !is_front_page()){
	    	$styles = get_post_meta($post->ID, "_styles", true);
	    	if(isset($styles['css']) && $styles['css'] != ""){
		    	?>
		    		<!-- Post/Page Styles -->
		    		<style type="text/css">
		    			<?=$styles['css']?>
		    		</style>
		    	<?
	    	}
    	}
    ?>

</head>
<body <?php body_class(); ?>>