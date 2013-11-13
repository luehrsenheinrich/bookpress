</body>
    
<?php wp_footer(); ?>

<?php // THE FACEBOOK FOOTER ?>
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : '<?php global $_bp_app_id; echo $_bp_app_id; ?>', // App ID from the App Dashboard
      channelUrl : '<?php echo WP_THEME_URL; ?>/channel.php', // Channel File for x-domain communication
      status     : true, // check the login status upon init?
      cookie     : true, // set sessions cookies to allow your server to access the session?
      xfbml      : true  // parse XFBML tags on this page?
    });

    // Additional initialization code such as adding Event Listeners goes here
    FB.Canvas.setSize({ width: 810, height: $("body").outerHeight() });
    setTimeout(function(){
    	    FB.Canvas.setAutoGrow();
    	    FB.Canvas.scrollTo(0,0);
    }, 500);

  };

  // Load the SDK's source Asynchronously
  // Note that the debug version is being actively developed and might 
  // contain some type checks that are overly strict. 
  // Please report such bugs using the bugs tool.
  (function(d, debug){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/<?php _e("en_US", "bp"); ?>/all" + (debug ? "/debug" : "") + ".js";
     ref.parentNode.insertBefore(js, ref);
   }(document, /*debug*/ false));
</script>
</html>
