<?php

if( !defined( 'ABSPATH' ) ) exit;  // Exit if accessed directly

/**
* WPGiftVoucherAdminPages Class for add Admin Pages in Menu
*/
class WPGiftVoucherAdminPages
{
	// class instance
	static $instance;

	// Voucher WP_List_Table object
	public $vouchers_obj;

	public function __construct()
	{
		add_filter( 'set-screen-option', array( __CLASS__, 'set_screen' ), 10, 3 );
		add_action( 'admin_menu', array( $this, 'plugin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_register_assets' ) );
	}

	public static function set_screen( $status, $option, $value ) {
		return $value;
	}

	/**
	 * Admin CSS and JS Files
	 */
	function admin_register_assets( $hook )
	{
		wp_enqueue_style( 'wp-color-picker' ); 
		wp_enqueue_style( 'voucher-style', WPGIFT__PLUGIN_URL.'/assets/css/admin-style.css');
		wp_enqueue_script('voucher-script', WPGIFT__PLUGIN_URL  . '/assets/js/admin-script.js', array( 'wp-color-picker' ), '1.0.0', true);
		wp_localize_script( 'voucher-script', 'WPGiftAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
	}

    /**
	 * Voucher Menu page
	 */
    public function plugin_menu() 
    {
    	$templatehook = add_submenu_page( 'edit.php?post_type=wpgv_voucher_product', __('Voucher Templates', 'gift-voucher' ), __('Voucher Templates', 'gift-voucher' ), 'manage_options', 'voucher-templates', array( $this, 'voucher_template' ));
    	add_submenu_page( NULL, __('Add New Template', 'gift-voucher' ), __('Add New Template', 'gift-voucher' ), 'manage_options', 'new-voucher-template', array( $this, 'new_voucher_template' ));
    	add_submenu_page( NULL, __('View Voucher Details', 'gift-voucher' ), __('View Voucher Details', 'gift-voucher' ), 'manage_options', 'view-voucher-details', array( $this, 'view_voucher_details' ));
    	add_submenu_page('edit.php?post_type=wpgv_voucher_product', __('Table Orders', 'gift-voucher' ), __('Table Orders', 'gift-voucher' ), 'manage_options', 'table', array( $this, 'table' ));
    	add_submenu_page( 'edit.php?post_type=wpgv_voucher_product', __('Settings', 'gift-voucher' ), __('Settings', 'gift-voucher' ), 'manage_options', 'voucher-setting', array( $this, 'voucher_settings' ));
    	$hook = add_submenu_page('edit.php?post_type=wpgv_voucher_product', __('Gift Voucher Orders', 'gift-voucher' ), __('Gift Voucher Orders', 'gift-voucher' ), 'manage_options', 'vouchers-lists', array( $this, 'voucher_list' ) );
    	add_action( "load-$hook", array( $this, 'screen_option_voucher' ) );
    	add_action( "load-$templatehook", array( $this, 'screen_option_template' ) );
    }

		/**
	 * Voucher Table Orders
	 */
		public function table()
		{
			require_once WPGIFT__PLUGIN_DIR . '/include/voucher_table.php';
		}


	/**
	 * Voucher List page
	 */
	public function voucher_list()
	{
		require_once WPGIFT__PLUGIN_DIR . '/include/voucher_list.php';
	}

	/**
	 * Voucher List page
	 */
	public function check_voucher_balance()
	{
		echo '<h3>'.__('Check Voucher Balance', 'gift-voucher').'</h3>';
		echo do_shortcode( ' [wpgv-check-voucher-balance] ' );
	}

	/**
	 * Method for view details of an voucher
	 */
	public function view_voucher_details() 
	{
		require_once WPGIFT__PLUGIN_DIR . '/include/view_voucher_details.php';
	}

	/**
	 * Voucher settings page
	 */
	public function voucher_settings() 
	{
		require_once WPGIFT__PLUGIN_DIR . '/include/voucher_settings.php';
	}

	/**
	 * Voucher Template page
	 */
	public function voucher_template()
	{
		require_once WPGIFT__PLUGIN_DIR . '/include/voucher_template.php';
	}

	/**
	 * Add Voucher Template page
	 */
	public function new_voucher_template()
	{
		require_once WPGIFT__PLUGIN_DIR . '/include/new_voucher_template.php';
	}

	/**
	 * Redeem Voucher page
	 */
	public function redeem_voucher()
	{
		require_once WPGIFT__PLUGIN_DIR . '/include/redeem_voucher.php';
	}

	/**
	 * Plugin License page
	 */	
	public function wpgv_license_page()
	{
		require_once WPGIFT__PLUGIN_DIR . '/include/wpgv_license_page.php';
	}

	/**
	 * Method for export vouchers in xls
	 */
	function export_orders()
	{
		if(is_admin()) {
			global $wpdb;
			if(isset($_POST["tbl_name"])){
				$tablename = sanitize_text_field($_POST["tbl_name"]);
				$sql = "SHOW TABLES";
				$table_list  = $wpdb->get_results($sql,ARRAY_N);
				$IsValidTableName = 0;
				foreach($table_list as $table_name){
					foreach ($table_name as $singlevalue){
						if($singlevalue == $tablename){
							$IsValidTableName = 1;
						}
					}
				}
				if($IsValidTableName==1){
					$filename = "export-orders";
					$cols = $wpdb->get_results("DESCRIBE $tablename");
					$cols = $wpdb->get_results("SELECT * FROM $tablename");
					$sql = $wpdb->get_results("SELECT COUNT(*) FROM $tablename");
					ob_clean();
					header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
                    header("Content-Disposition: attachment; filename= ".$filename."-".date('d-m-y').".xls");  //File name extension was wrong
                    header("Expires: 0");
                    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                    header("Cache-Control: private",false);
                    echo "<html>";
                    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
                    echo "<body>";
                    echo "<table>";
                    print("<tr>");
                    foreach ($cols as $col) {
                    	echo "<th  style='border: thin solid; background-color: #83b4d8;'>" . $col->Field . "</th>";
                    }
                    print("</tr>");
                    while($row = mysql_fetch_row($result)){
                    	$output = '';
                    	$output = "<tr>";
                    	for($j=0; $j<mysql_num_fields($result); $j++){
                    		if(!isset($row[$j]))
                    			$output .= "<td>NULL\t</td>";
                    		else
                    			$output .= "<td style='border: thin solid;'>$row[$j]\t</td>";
                    	}
                    	$output .= "</tr>";
                    	$output = preg_replace("/\r\n|\n\r|\n|\r/", ' ', $output);
                    	print(trim($output));
                    }
                    echo "</table>";
                    echo "</body>";
                    echo "</html>";
                }
                else{
                	echo __('Invalid Request.', 'gift-voucher' );
                }
            }
            ?>
            <form action="" method="POST" style="display: inline-block;padding: 0 10px;float:right;">
            	<input type="hidden" name="tbl_name" value="<?php echo $wpdb->prefix . "giftvouchers_list"; ?>"/>
            	<input class="button button-primary exportbtn" name="exportbtn" type="submit" name="table_display" value="<?php echo __('Export All Orders', 'gift-voucher' ) ?>"/>
            </form>
            <?php 
        } 
    }

	/**
	 * Screen options for voucher list
	 */
	public function screen_option_voucher()
	{
		$option = 'per_page';
		$args   = array(
			'label'   => __('Gift Vouchers', 'gift-voucher'),
			'default' => 20,
			'option'  => 'vouchers_per_page'
		);

		add_screen_option( $option, $args );

		$this->vouchers_obj = new WPGV_Voucher_List();
	}

	/**
	 * Screen options for voucher templates
	 */
	public function screen_option_template()
	{
		$option = 'per_page';
		$args = array(
			'label'   => __('Voucher Templates', 'gift-voucher'),
			'default' => 20,
			'option'  => 'templates_per_page'
		);

		add_screen_option( $option, $args );

		$this->vouchers_obj = new WPGV_Voucher_Template();
	}

	/** Singleton instance */
	public static function get_instance() 
	{
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

/**
 * Method for get image url by id (Only for Template Page)
 */
function wpgv_get_image_url($ids)
{
	$images = $ids ? json_decode($ids) : [''];
	foreach ($images as $key => $value) {
		if($value):
			$image_attributes = wp_get_attachment_image_src( $value, 'voucher-thumb' );
			?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$('.image_src<?php echo $key; ?>').attr('src', '<?php echo $image_attributes[0]; ?>').show();
					$('.remove_image<?php echo $key; ?>').show();
				});
			</script>
			<?php
		endif;
	}
}

add_action( 'admin_post_save_voucher_settings_option', 'process_voucher_settings_options' );