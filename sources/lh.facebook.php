<?php

/**
 * lh_fb_function class.
 * Available after action wp, prio 9999
 */
class lh_fb_toolset {
	
	private $signed_request, $fb_secret, $fb_appid;
	
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @param mixed $fb_appid
	 * @param mixed $fb_secret
	 * @return void
	 */
	public function __construct(){
		$this->fb_appid = FB_APP_ID;
		$this->fb_secret = FB_APP_SECRET;
		
		$this->load_signed_request();
	}
	
	/**
	 * get_signed_request function.
	 * 
	 * @access public
	 * @return void
	 */
	public function get_signed_request(){
		return $this->signed_request;
	}
	
	/**
	 * load_signed_request function.
	 * 
	 * @access public
	 * @return void
	 */
	public function load_signed_request(){
		if(isset($_POST['signed_request'])){ // First visit of the user, retrive and store that stuff!
			$this->signed_request = $this->parse_signed_request($_POST['signed_request'], $this->fb_secret);
			if($this->signed_request){
				setcookie('bp_signed_request', serialize($this->signed_request));
				var_dump(serialize($this->signed_request));
			}
		} elseif(isset($_COOKIE['bp_signed_request'])){
			var_dump( unserialize ( stripslashes( $_COOKIE['bp_signed_request'] ) ) );
			$this->signed_request = unserialize( stripslashes( $COOKIE['bp_signed_request'] ) );
		} else {
			$this->signed_request = "test";
		}
	}
	
	/**
	 * parse_signed_request function.
	 * 
	 * @access private
	 * @param mixed $signed_request
	 * @param mixed $secret
	 * @return void
	 */
	private function parse_signed_request($signed_request, $secret) {
		list($encoded_sig, $payload) = explode('.', $signed_request, 2); 
				
		// decode the data
		$sig = $this->base64_url_decode($encoded_sig);
		$data = json_decode($this->base64_url_decode($payload), true);
		
		// confirm the signature
		$expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
		if ($sig !== $expected_sig) {
			error_log('Bad Signed JSON signature!');
			return null;
		}
		
		return $data;
	}
	
	/**
	 * base64_url_decode function.
	 * 
	 * @access private
	 * @param mixed $input
	 * @return void
	 */
	private function base64_url_decode($input) {
		return base64_decode(strtr($input, '-_', '+/'));
	}
}