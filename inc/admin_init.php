<?php








//ON INIT

add_action('admin_init','wp_rss_multi_importer_start');

function wp_rss_multi_importer_start () {
	
register_setting('wp_rss_multi_importer_options', 'rss_import_items');
register_setting('wp_rss_multi_importer_categories', 'rss_import_categories');	
register_setting('wp_rss_multi_importer_item_options', 'rss_import_options');	 
register_setting('wp_rss_multi_importer_template_item', 'rss_template_item');	 
register_setting('wp_rss_multi_importer_feed_options', 'rss_feed_options');	 
register_setting('wp_rss_multi_importer_admin_options', 'rss_admin_options');	 
add_settings_section( 'wp_rss_multi_importer_main', '', 'wp_section_text', 'wprssimport' );  

}

add_action('init', 'ilc_farbtastic_script');
function ilc_farbtastic_script() {
  wp_enqueue_style( 'farbtastic' );
  wp_enqueue_script( 'farbtastic' );
}




function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function isMobileForWordPress() {
	global $isMobileDevice;
    if(isMobile()){
       $isMobileDevice=1;
		}else{
 			$isMobileDevice=0;
		}
		return $isMobileDevice;
}

add_action('init', 'isMobileForWordPress', 1);






add_action('admin_menu','wp_rss_multi_importer_menu');

function wp_rss_multi_importer_menu () {
add_options_page('WP RSS Multi-Importer','RSS Multi-Importer','manage_options','wp_rss_multi_importer_admin', 'wp_rss_multi_importer_display');
}




add_action( 'widgets_init', 'src_load_widgets');  //load widget

function src_load_widgets() {
register_widget('WP_Multi_Importer_Widget');
}




function wp_section_text() {
    echo '<div class="postbox"><h3><label for="title">Usage Details</label></h3><div class="inside"><H4>Step 1:</H4><p>Enter a name and the full URL (with http://) for each of your feeds. The name will be used to identify which feed produced the link (see the Attribution Label option below). Click Save Settings.</p><H4>Step 2:</H4><p>Go to the tab called <a href="/wp-admin/options-general.php?page=wp_rss_multi_importer_admin&tab=setting_options">Setting Options</a>, choose options and click Save Settings.</p><H4>Step 3:</H4><p>Put this shortcode, [wp_rss_multi_importer], on the page you wish to have the feed.</p>';
    echo '<p>You can also assign each feed to a category. Go to the Category Options tab, enter as many categories as you like.</p><p>Then you can restrict what shows up on a given page by using this shortcode, like [wp_rss_multi_importer category="2"] (or [wp_rss_multi_importer category="1,2"] to have two categories) on the page you wish to have only show feeds from those categories.</p></div></div>';

}




function wp_rss_multi_importer_display( $active_tab = '' ) {

	
		
?>
	
	<div class="wrap">
		<?php
		$wprssmi_admin_options = get_option( 'rss_admin_options' );
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'wp_rss_multi_importer_admin'  && $wprssmi_admin_options['dismiss_slug'] != "true" ) {
		?>
		<div id="message" class="updated fade">
			<h3>If you find this plugin helpful, let others know by <a target="_blank" href="http://wordpress.org/extend/plugins/wp-rss-multi-importer/">rating it here</a>. That way, it will help others determine whether or not they should try out the plugin. Thank you.</h3>
		<form method="post" action="options.php">		
			<?php 
			settings_fields('wp_rss_multi_importer_admin_options');
			?>
			<input type="hidden" name="rss_admin_options[dismiss_slug]" value="true">
			<input type="submit" value="Dismiss This Message" name="submit">
			</form>
		
			</div>
	<?php
}
?>
		<div id="icon-themes" class="icon32"></div>
		<h2>WP RSS Multi-Importer Options</h2>
		<?php settings_errors(); ?>
		
		<?php if( isset( $_GET[ 'tab' ] ) ) {
			$active_tab = $_GET[ 'tab' ];
		} else if( $active_tab == 'setting_options' ) {
				$active_tab = 'setting_options';
		} else if( $active_tab == 'category_options' ) {
			$active_tab = 'category_options';
		} else if( $active_tab == 'style_options' ) {
			$active_tab = 'style_options';
		} else if( $active_tab == 'template_options' ){
				$active_tab = 'template_options';
		} else if( $active_tab == 'feed_options' ){
				$active_tab = 'feed_options';
		} else if( $active_tab == 'more_options' ){
			$active_tab = 'more_options';
		} else { $active_tab = 'items_list';	
			
		} // end if/else ?>
		
		<h2 class="nav-tab-wrapper">
			<a href="?page=wp_rss_multi_importer_admin&tab=items_list" class="nav-tab <?php echo $active_tab == 'items_list' ? 'nav-tab-active' : ''; ?>"><?php  _e("RSS Feeds")?></a>
				<a href="?page=wp_rss_multi_importer_admin&tab=setting_options" class="nav-tab <?php echo $active_tab == 'setting_options' ? 'nav-tab-active' : ''; ?>"><?php  _e("Setting Options")?></a>
			<a href="?page=wp_rss_multi_importer_admin&tab=category_options" class="nav-tab <?php echo $active_tab == 'category_options' ? 'nav-tab-active' : ''; ?>"><?php  _e("Category Options")?></a>
			<a href="?page=wp_rss_multi_importer_admin&tab=style_options" class="nav-tab <?php echo $active_tab == 'style_options' ? 'nav-tab-active' : ''; ?>"><?php  _e("Style Options")?></a>
				<a href="?page=wp_rss_multi_importer_admin&tab=template_options" class="nav-tab <?php echo $active_tab == 'template_options' ? 'nav-tab-active' : ''; ?>"><?php  _e("Template Options")?></a>
				<a href="?page=wp_rss_multi_importer_admin&tab=feed_options" class="nav-tab <?php echo $active_tab == 'feed_options' ? 'nav-tab-active' : ''; ?>"><?php  _e("Export Feed Options")?></a>
				<a href="?page=wp_rss_multi_importer_admin&tab=more_options" class="nav-tab <?php echo $active_tab == 'more_options' ? 'nav-tab-active' : ''; ?>"><?php  _e("Help & More...")?></a>
		</h2>

			<?php
			
				if( $active_tab == 'items_list' ) {
						
			wp_rss_multi_importer_items_page();
			
		} else if ( $active_tab == 'setting_options' ) {

				wp_rss_multi_importer_options_page();
			
		} else if ( $active_tab == 'category_options' ) {
			
			wp_rss_multi_importer_category_page();
			
		} else if ( $active_tab == 'style_options' ) {
			
			wp_rss_multi_importer_style_tags();
			
		} else if ( $active_tab == 'template_options' ) {
				
			wp_rss_multi_importer_template_page();	
			
		} else if ( $active_tab == 'feed_options' ) {
				
			wp_rss_multi_importer_feed_page();	
			
			
			
				
				} else {
						wp_rss_multi_importer_more_page();
				
				} // end if/else  	
				
				
			
			?>
	</div>
	
<?php
} 




?>