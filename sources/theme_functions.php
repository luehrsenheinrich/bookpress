<?php
/*
 * Luehrsen // Heinrich - Wordpress Theme Functions
 *
 * A useful collection of great functions of daily usage in wordpress theme development.
 *
 */


 
/**
 * Echoes custom text in the admin footer, is called by "admin_footer_text" filter
 *
 * @author Hendrik Luehrsen
 * @since 1.0
 *
 * @return void
 */
function lh_admin_footer() {
	echo "Made with love by <a href='http://www.luehrsen-heinrich.de' target='_blank'>Luehrsen // Heinrich</a>. Powered by <a href='http://www.wordpress.org' target='_blank'>Wordpress</a>.";
}
add_filter('admin_footer_text', 'lh_admin_footer'); //change admin footer text



/**
 * Gracefully shortens text whithout cutting words
 *
 * @author Hendrik Luehrsen
 * @since 1.0
 *
 * @param $str string The text, that shall be shortened
 * @param $length int The length to which the text should be shortened
 * @param $minword int The minimum amount of words, that shall be displayed
 *
 * @return The shortened string with "..." attatched. 
 */
function lh_shorten_text($str, $length, $minword = 3)
{
    $sub = '';
    $len = 0;
   
    foreach (explode(' ', $str) as $word)
    {
        $part = (($sub != '') ? ' ' : '') . $word;
        $sub .= $part;
        $len += strlen($part);
       
        if (strlen($word) > $minword && strlen($sub) >= $length)
        {
            break;
        }
    }
   	
	if($len < strlen($str) and substr($sub, strlen($sub)-1) != "."){
		$end = " ...";
	}
	else{
		$end = NULL;
	}
	
    return $sub . $end ;
}



/**
 * Deactivates certain menu items from wordpress administration
 * Called by wordpress action "admin_init"
 *
 * @author Hendrik Luehrsen
 * @since 1.0
 *
 */
function lh_remove_menu_pages() {
	//remove_menu_page('link-manager.php');	
}
add_action( 'admin_init', 'lh_remove_menu_pages' );



/**
 * Changes the wordpress toolbar the way we need it
 * Called by wordpress action "admin_bar_menu"
 *
 * @author Hendrik Luehrsen
 * @since 1.0
 *
 */
function lh_change_toolbar($wp_toolbar) {
	$wp_toolbar->remove_node('wp-logo'); 
}
add_action('admin_bar_menu', 'lh_change_toolbar', 999);  


/**
 * Edit the .htacces File and add our needs
 *
 * @author Hendrik Luehrsen
 * @since 1.0
 *
 * @param string $rules The predefined wordpress rules
 *
 * @return string The new rules
 */
function lh_htaccess_contents( $rules )
{
$my_content = <<<EOD
\n# BEGIN L//H Content
<IfModule mod_deflate.c>
 SetOutputFilter DEFLATE
</IfModule>
# END L//H Content \n\n\n
EOD;
    return $my_content . $rules;
}
add_filter('mod_rewrite_rules', 'lh_htaccess_contents');




//
// STYLING THE LOGIN PAGE
//


/*
 * Add some css code to change the default logo
 * Called by action "login_enqueue_scripts".
 *
 * @author Hendrik Luehrsen
 * @since 3.1
 *
 * @return string The CSS Code for the head
 */
function lh_login_logo() { ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?php echo get_bloginfo( 'template_directory' ) ?>/img/lh_logo.png);
			background-size: 274px 41px;
			background-repeat: no-repeat;
			background-position: center center;
            padding-bottom: 30px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'lh_login_logo' );


/**
 * Change the login logo url
 *
 * @author Hendrik Luehrsen
 * @since 3.1
 *
 * @return string The new url
 */
function lh_login_logo_url() {
    return "http://www.luehrsen-heinrich.de";
}
add_filter( 'login_headerurl', 'lh_login_logo_url' );


/**
 * Change the login logo title
 *
 * @author Hendrik Luehrsen
 * @since 3.1
 *
 * @return string The new title
 */
function lh_login_logo_url_title() {
    return 'Luehrsen // Heinrich - Agentur für Medienkommunikation';
}
add_filter( 'login_headertitle', 'lh_login_logo_url_title' );


/**
 * Check for the RAW HTML Option and deactivate the filters if needed
 *
 * @author Hendrik Luehrsen
 * @since 3.1
 */
function lh_check_raw_html($content){
	global $post;
	$raw_html = (bool) get_post_meta($post->ID, "_lh_raw_html", true);
	
	if($raw_html){
		remove_filter('the_content', 'wpautop');
		remove_filter('the_content', 'wptexturize');
		remove_filter('the_content', 'convert_chars');
		remove_filter('the_content', 'convert_smilies');
	}
	
	return $content;
}
add_filter( 'the_content', 'lh_check_raw_html', 1);

/**
 * Check for the RAW HTML Option and deactivate tinymce if needed
 *
 * @author Hendrik Luehrsen
 * @since 3.1
 */
function lh_disable_editor(){
	global $post;
	$raw_html = (bool) get_post_meta($post->ID, "_lh_raw_html", true);
	if($raw_html){
		add_filter("user_can_richedit", "__return_false");
	}
}
add_action( 'edit_form_after_title', 'lh_disable_editor' );

/**
 * Check for the edit page and see, if we need to implement codemirror
 */
function lh_activate_codemirror(){
	global $post, $pagenow;
	$raw_html = (bool) get_post_meta($post->ID, "_lh_raw_html", true);
	$post_types = array("page", "post");
	if($pagenow == "post.php" & in_array($post->post_type, $post_types)){
		?>
		<script type="text/javascript">
			var initLessEditor = true;
		<?php if($raw_html): ?>
			var initHtmlEditor = true;
		<?php endif ?>
		</script>
		<?php
		
		if($raw_html){
			wp_enqueue_script("cm-xml", WP_JS_URL.'/codemirror/mode/xml.js', NULL, 1, true);
			wp_enqueue_script("cm-css", WP_JS_URL.'/codemirror/mode/css.js', NULL, 1, true);
			wp_enqueue_script("cm-javascript", WP_JS_URL.'/codemirror/mode/javascript.js', NULL, 1, true);
			wp_enqueue_script("cm-htmlmixed", WP_JS_URL.'/codemirror/mode/htmlmixed.js', NULL, 1, true);
			wp_enqueue_script("cm-closetag", WP_JS_URL.'/codemirror/addon/edit/closetag.js', NULL, 1, true);
		}
	}
}
add_action( 'admin_head', 'lh_activate_codemirror' );


//
// EOF
//