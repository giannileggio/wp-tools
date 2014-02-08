<?php
class WP_Tools {

	var $scripts;

	var $styles;

	var $logo;
	
	/**
	 * [init description]
	 * @return true
	 */
	function init () {

		add_action( 'init', array( $this,'init_sessions' ), 1 );
		add_action( 'wp_logout', array( $this,'go_home' ) );
		add_action( 'wp_head', array( $this,'load_js' ) );
		add_action( 'wp_head', array( $this,'load_css' ) );
		add_action( 'login_enqueue_scripts', array( $this, 'my_login_logo' ) );
		add_action( 'init', array( $this, 'check_blog_public') );
		if($this->is_dev()) {
			add_action( 'wp_footer', array( $this, 'log_queries') );			
		}

		add_filter( 'login_headerurl', array( $this,'my_login_logo_url') );
		add_filter( 'login_headertitle', array( $this,'my_login_logo_url_title') );
		add_filter( 'post_mime_types', array( $this,'add_mime_types') );
		return true;
	}

	/*
	* loads the flexible content template
	 */
	static function flexible_contents( $content, $post_id ) {
		while( has_sub_field( $content, $post_id ) ) {
			$layout = get_row_layout();
			if ( file_exists( get_template_directory() . '/partials/flexible_contents/' . $layout . ".php") ) {
				include(locate_template( 'partials/flexible_contents/' . $layout . ".php" ));				
			}
		}
	}

	function log_queries() {
		global $wpdb;
		if ($wpdb->queries) : ?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					console.log("Queries : <?php echo count($wpdb->queries); ?>");
				});
			</script>
		<?php 
		endif;
	}

	/*
	check if we are in development environment
	 */
	function is_dev() {
		return (@$_SERVER['PHP_ENV'] == 'development');
	}

	/*
	check if we are in staging enviroment
	 */
	function is_staging() {
		if ( ( @$_SERVER['PHP_ENV'] == 'staging' ) || strpos($_SERVER['HTTP_HOST'], 'lucidity.us') ) {
			return true;
		}
		return false;
	}

	/*
	check if we are in production
	 */
	function is_production() {
		if ( !$this->is_dev() && !$this->is_staging() ) {
			return true;
		} else {
			return false;
		}
	}

	function add_mime_types() {
		$post_mime_types['application/pdf'] = array( __( 'PDFs' ), __( 'Manage PDFs' ), _n_noop( 'PDF <span class="count">(%s)</span>', 'PDFs <span class="count">(%s)</span>' ) );
		 
    return $post_mime_types;		
	}


	/*
	* checks if the blog is set to be public
	 */
	function check_blog_public() {
		$public = get_option('blog_public');
		if ( $this->is_production() && $public == 0 ) {
			update_option('blog_public', 1);
		} elseif ( ( $this->is_dev() || $this->is_staging() ) && $public == 1) {
			update_option('blog_public', 0);
		}
	}

	/**
	 * Replace logo on login page
	 * @return [type]
	 */
	function my_login_logo() { 
		if (isset($this->logo)) {
		?>
	    <style type="text/css">
	        body.login div#login h1 a {
	            background-image: url(<?php echo get_bloginfo( 'template_directory' ) ?>/assets/img/<?php echo $this->logo; ?>);
	            background-size: 274px 95px;
	            padding-bottom: 30px;
	            height: 95px;
	        }
	    </style>
		<?php }
	}	

	function my_login_logo_url() {
	    return get_bloginfo( 'url' );
	}	

	function my_login_logo_url_title() {
	    return get_bloginfo( 'title' );
	}	

	// initialize session
	function init_sessions() {
	    if (!session_id()) {
	        session_start();
	    }
	}

	// redirect to home when logging out
	function go_home(){
	  wp_redirect( home_url() );
	  exit();
	}	

	/**
	 * Load css files
	 * @return true
	 */
	function load_css() {
		if(is_array($this->styles)) {
			foreach ($this->styles as $key => $style) {
				wp_enqueue_style( $key, PLUGIN_URL . 'css/' . $style, false, null );
			}
		}
		return true;
	}

	// load js files
	function load_js () {
		foreach ($this->scripts as $name => $script) {
			wp_register_script ( $name, PLUGIN_URL . 'js/' . $script, false, null, false );
			wp_enqueue_script ( $name );
		}
	}
}
