<?php
/* Fire our meta box setup function on the post editor screen. */
add_action( 'load-post.php', 'lh_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'lh_post_meta_boxes_setup' );

/**
 * Set up all the actions for calling and saving stuff
 * Called by "load-{type}.php" action
 *
 * @author Hendrik Luehrsen
 * @since 1.0
 */
function lh_post_meta_boxes_setup() {

	/* Add meta boxes on the 'add_meta_boxes' hook. */
	add_action( 'add_meta_boxes', 'lh_add_post_meta_boxes' );
	add_action( 'save_post', 'lh_box_save', 10, 2 );
}

/**
 * Define the meta boxes which are to be added
 * Called by "add_meta_boxes" action
 *
 * @author Hendrik Luehrsen
 * @since 1.0
 */
function lh_add_post_meta_boxes() {
	
	$post_types = array("post", "page");
	foreach($post_types as $p){
		add_meta_box(
			'raw_html_box',											// Unique ID
			esc_html__( 'Editing Options', 'wp2fb' ),				// Title
			'raw_html_box',											// Callback function
			$p,														// Admin page (or post type)
			'side',													// Context
			'default'												// Priority
		);
		
		add_meta_box(
			'less_css_box',											// Unique ID
			esc_html__( 'LESS / CSS Code', 'wp2fb' ),				// Title
			'less_css_box',											// Callback function
			$p,														// Admin page (or post type)
			'normal',												// Context
			'default'												// Priority
		);
	}
	
	add_meta_box(
		'facebook_settings',									// Unique ID
		esc_html__( 'Facebook Settings', 'wp2fb' ),				// Title
		'facebook_settings',									// Callback function
		$p,														// Admin page (or post type)
		'side',													// Context
		'default'												// Priority
	);

}

///
/// BOXES   ====================================
///


/**
 * Function to renter the "Editing Options"-Metabox contents
 *
 * @author Hendrik Luehrsen
 * @since 1.0
 *
 * @param object $object The post/page object
 * @param object $box The metabox object
 *
 * @return void
 */
function raw_html_box($object, $box){
	wp_nonce_field( basename( __FILE__ ), 'lh_data_nonce' ); 
	$raw_html = (bool) get_post_meta($object->ID, "_lh_raw_html", true);
	
	?>
	<p>
        <input type="checkbox" id="raw_html" name="lh_raw_html" value="1" <?=checked($raw_html)?>>
        <label for="raw_html"><b><?=__("Activate raw html mode?", "wp2fb")?></b></label>
    </p>
	<?php
}


/**
 * Function to renter the "LESS CSS Code"-Metabox contents
 *
 * @author Hendrik Luehrsen
 * @since 1.0
 *
 * @param object $object The post/page object
 * @param object $box The metabox object
 *
 * @return void
 */
function less_css_box($object, $box){
	wp_nonce_field( basename( __FILE__ ), 'lh_data_nonce' );
	$styles = get_post_meta($object->ID, "_styles", true);
	if(!is_array($styles)){
		$styles = array();
	}
	?>
	<p>
		<textarea name="styles[less]" class="large-text" id="less-textarea" rows="5" placeholder="<?php _e("Put CSS styles here", "wp2fb"); ?>"><?=$styles['less']?></textarea>
	</p>
	<p>
		<?php _e('You can use basic CSS, <a href="http://www.lesscss.org" target="_blank">LESS</a> and all the mixins of <a href="http://getbootstrap.com" target="_blank">Twitter Bootstrap</a>.', "wp2fb"); ?><br />
		<?php _e('The styles used here will only appear on this page.', "wp2fb"); ?>
	</p>
	<?php
}


/**
 * Function to renter the "Facebook Settings"-Metabox contents
 *
 * @author Hendrik Luehrsen
 * @since 1.0
 *
 * @param object $object The post/page object
 * @param object $box The metabox object
 *
 * @return void
 */
function facebook_settings($object, $box){
	wp_nonce_field( basename( __FILE__ ), 'lh_data_nonce' );
	$fb_settings = get_post_meta($object->ID, "_fb_settings", true);
	if(!is_array($fb_settings)){
		$fb_settings = array();
	}
	?>
	<p>
		<label for="facebook_appid"><?php _e("Facebook App ID", "wp2fb"); ?></label>
		<input type="text" class="widefat" name="fb_settings[appid]" id="facebook_appid" placeholder="<?php _e("12345ABCDEF", "wp2fb"); ?>" />
	</p>
	<p>
		<label for="facebook_secret"><?php _e("Facebook App Secret", "wp2fb"); ?></label>
		<input type="text" class="widefat" name="fb_settings[secret]" id="facebook_secret" placeholder="<?php _e("12345ABCDEF", "wp2fb"); ?>" />
	</p>
	<p>
		<a href="#add_page_tab" onclick="open_add_page_tab_dialog()"><?php _e("Add Page Tab to Facebook Page", "wp2fb"); ?></a>
	</p>
	<?php
}



///
/// TOOLS	====================================
///


/* Save the meta box's post metadata. */
function lh_box_save( $post_id, $post ) {
	/*
	 * lh_save_post_meta($post_id, $post, 'lh_data_nonce', 'post_value_name', '_meta_value_name');
	 */
	 
	 lh_save_post_meta($post_id, $post, 'lh_data_nonce', 'lh_raw_html', '_lh_raw_html');
	 
	 // Save the Less & CSS Stuff
	 if(isset($_POST['styles']['less']) && $_POST['styles']['less'] != ""){
	 	try {
			$less = new lessc;
			$less->setFormatter("compressed");
			$_POST['styles']['css'] = $less->compile($_POST['styles']['less']);
			lh_save_post_meta($post_id, $post, 'lh_data_nonce', 'styles', '_styles');
		} catch(Exception $e){
			wp_die($e->getMessage());
			die();
		}
	 }
	 
}


/**
 * Actually save the metadata from the meta boxes
 * With help from Justin Tadlock, http://wp.smashingmagazine.com/2011/10/04/create-custom-post-meta-boxes-wordpress/
 *
 * @author Justin Tadlock
 * @author Hendrik Luehrsen
 *
 * @since 1.0
 */
function lh_save_post_meta( $post_id, $post, $nonce_name, $post_value, $meta_key, $override_meta_value = NULL ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST[$nonce_name] ) || !wp_verify_nonce( $_POST[$nonce_name], basename( __FILE__ ) ) )
		return $post_id;

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

	/* Get the posted data and sanitize it for use as an HTML class. */
	if(isset($_POST[$post_value])){
		$new_meta_value = ($_POST[$post_value]);
	} elseif($override_meta_value) {
		$new_meta_value = $meta_value;
	}
	
	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && '' == $meta_value )
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value && $new_meta_value != $meta_value )
		update_post_meta( $post_id, $meta_key, $new_meta_value );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value && $meta_value )
		delete_post_meta( $post_id, $meta_key, $meta_value );
}