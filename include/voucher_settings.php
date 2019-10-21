<?php

if( !defined( 'ABSPATH' ) ) exit;  // Exit if accessed directly

		global $wpdb;
		$setting_table_name = $wpdb->prefix . 'giftvouchers_setting';

   		if ( !current_user_can( 'manage_options' ) )
   		{
      		wp_die( 'You are not allowed to be on this page.' );
   		}

   		if ( isset($_POST['company_name']) ) {
      		// Check that nonce field
   	  		wp_verify_nonce( $_POST['voucher_settings_verify'], 'voucher_settings_verify' );

		    $is_woocommerce_enable	= sanitize_text_field( $_POST['is_woocommerce_enable'] );
		    $is_style_choose_enable	= sanitize_text_field( $_POST['is_style_choose_enable'] );
		    $company_name 	 		= sanitize_text_field( $_POST['company_name'] );
      		$sofort_configure_key 	= sanitize_text_field( $_POST['sofort_configure_key'] );
      		$reason_for_payment 	= sanitize_text_field( $_POST['reason_for_payment'] );
      		$sender_name 			= sanitize_text_field( $_POST['sender_name'] );
      		$sender_email 	 		= sanitize_email( $_POST['sender_email'] );
      		$currency_code			= sanitize_text_field($_POST['currency_code']);
      		$currency 		 		= sanitize_text_field( $_POST['currency'] );
      		$paypal 		 		= sanitize_text_field( $_POST['paypal'] );
      		$sofort 		 		= sanitize_text_field( $_POST['sofort'] );
      		$stripe 		 		= sanitize_text_field( $_POST['stripe'] );
      		$paypal_client_id = sanitize_text_field( $_POST['paypal_client_id'] );
      		$stripe_publishable_key = sanitize_text_field( $_POST['stripe_publishable_key'] );
      		$stripe_webhook_key 	= sanitize_text_field( $_POST['stripe_webhook_key'] );
      		$stripe_secret_key 		= sanitize_text_field( $_POST['stripe_secret_key'] );
      		$voucher_bgcolor 		= sanitize_text_field( substr($_POST['voucher_bgcolor'],1) );
      		$voucher_color 			= sanitize_text_field( substr($_POST['voucher_color'],1) );
      		$template_col 			= sanitize_text_field( $_POST['template_col'] );
      		$voucher_min_value		= sanitize_text_field( $_POST['voucher_min_value'] );
      		$voucher_max_value		= sanitize_text_field( $_POST['voucher_max_value'] );
      		$voucher_expiry_type	= sanitize_text_field( $_POST['voucher_expiry_type'] );
      		$voucher_expiry			= sanitize_text_field( $_POST['voucher_expiry'] );
      		$voucher_terms_note		= sanitize_text_field( $_POST['voucher_terms_note'] );
      		$currency_position 		= sanitize_text_field( $_POST['currency_position'] );
      		$test_mode 		 		= sanitize_text_field( $_POST['test_mode'] );
      		$per_invoice 		 	= sanitize_text_field( $_POST['per_invoice'] );
      		$custom_loader 		 	= sanitize_text_field( $_POST['custom_loader'] );
      		$buying_for 			= sanitize_text_field( $_POST['buying_for'] );
      		$hide_price 			= sanitize_text_field( $_POST['hide_price'] );
      		$hide_expiry 			= sanitize_text_field( $_POST['hide_expiry'] );
      		$expiry_date_format 	= sanitize_text_field( $_POST['expiry_date_format'] );
      		$post_shipping 			= sanitize_text_field( $_POST['post_shipping'] );
      		$preview_button 		= sanitize_text_field( $_POST['preview_button'] );
      		$enable_pdf_saving 		= sanitize_text_field( $_POST['enable_pdf_saving'] );
      		$shipping_method 		= sanitize_text_field( $_POST['shipping_method'] );
      		$wpgvtermstext			= $_POST['wpgvtermstext'];
      		$bank_info 		 		= $_POST['bank_info'];
      		$email_subject 		 	= $_POST['email_subject'];
      		$email_body 		 	= $_POST['email_body'];
      		$email_body_per_invoice	= $_POST['email_body_per_invoice'];
      		$recipient_email_subject = $_POST['recipient_email_subject'];
      		$recipient_email_body   = $_POST['recipient_email_body'];
      		$admin_email_subject 	= $_POST['admin_email_subject'];
      		$admin_email_body 		= $_POST['admin_email_body'];
      		$demo_image 			= $_POST['demo_image'];
      		$cancelpagemessage		= $_POST['cancelpagemessage'];
      		$successpagemessage		= $_POST['successpagemessage'];
      		$wpgv_custom_css		= $_POST['wpgv_custom_css'];
      		$pdf_footer_url			= $_POST['pdf_footer_url'];
      		$pdf_footer_email		= $_POST['pdf_footer_email'];
      		$stripe_alternative_text = $_POST['stripe_alternative_text'];
      		$customer_receipt 		= $_POST['customer_receipt'];

			$voucher_styles = array();
			foreach ($_POST['voucher_style'] as $value) {
				$voucher_styles[] = $value;
			}

		   	$wpdb->update(
				$setting_table_name,
				array( 
					'is_woocommerce_enable'	=> $is_woocommerce_enable,
					'is_style_choose_enable'	=> $is_style_choose_enable,
					'voucher_style'			=> json_encode($voucher_styles),
					'company_name' 			=> $company_name,
					'sofort_configure_key' 	=> $sofort_configure_key,
					'reason_for_payment' 	=> $reason_for_payment,
					'sender_name' 			=> $sender_name,
					'sender_email' 			=> $sender_email,
					'paypal'				=> $paypal,
					'sofort'				=> $sofort,
					'stripe'				=> $stripe,
					'stripe_publishable_key'=> $stripe_publishable_key,
					'stripe_secret_key'		=> $stripe_secret_key,
					'currency_code'			=> $currency_code,
					'currency' 				=> $currency,
					'voucher_bgcolor' 		=> $voucher_bgcolor,
					'voucher_color' 		=> $voucher_color,
					'template_col' 			=> $template_col,
					'voucher_min_value' 	=> $voucher_min_value,
					'voucher_max_value' 	=> $voucher_max_value,
					'voucher_expiry_type'	=> $voucher_expiry_type,
					'voucher_expiry' 		=> $voucher_expiry,
					'voucher_terms_note' 	=> $voucher_terms_note,
					'currency_position' 	=> $currency_position,
					'test_mode' 			=> $test_mode,
					'per_invoice' 			=> $per_invoice,
					'bank_info' 			=> $bank_info,
					'custom_loader' 		=> $custom_loader,
					'post_shipping'			=> $post_shipping,
					'shipping_method'		=> $shipping_method,
					'preview_button'		=> $preview_button,
					'pdf_footer_url'		=> $pdf_footer_url,
					'pdf_footer_email'		=> $pdf_footer_email
				),
				array('id'=>1)
			);
    		update_option('wpgv_paypal_client_id', $paypal_client_id);
    		update_option('wpgv_stripe_webhook_key', $stripe_webhook_key);
    		update_option('wpgv_termstext', $wpgvtermstext);
    		update_option('wpgv_buying_for', $buying_for);
    		update_option('wpgv_hide_price', $hide_price);
    		update_option('wpgv_hide_expiry', $hide_expiry);
    		update_option('wpgv_expiry_date_format', $expiry_date_format);
    		update_option('wpgv_emailsubject', $email_subject);
    		update_option('wpgv_emailbody', stripslashes(wp_filter_post_kses(addslashes($email_body))));
    		update_option('wpgv_emailbodyperinvoice', stripslashes(wp_filter_post_kses(addslashes($email_body_per_invoice))));
    		update_option('wpgv_recipientemailsubject', $recipient_email_subject);
    		update_option('wpgv_recipientemailbody', stripslashes(wp_filter_post_kses(addslashes($recipient_email_body))));
    		update_option('wpgv_adminemailsubject', $admin_email_subject);
    		update_option('wpgv_adminemailbody', stripslashes(wp_filter_post_kses(addslashes($admin_email_body))));
    		update_option('wpgv_demoimageurl', $demo_image);
    		update_option('wpgv_successpagemessage', $successpagemessage);
    		update_option('wpgv_cancelpagemessage', $cancelpagemessage);
    		update_option('wpgv_enable_pdf_saving', $enable_pdf_saving);
    		update_option('wpgv_custom_css', $wpgv_custom_css);
    		update_option('wpgv_stripe_alternative_text', $stripe_alternative_text);
    		update_option('wpgv_customer_receipt', $customer_receipt);
			if($stripe && !get_option('wpgv_stripesuccesspage')) {
				$stripeSuccessPage = array(
      				'post_title'    => 'Stripe Payment Success Page',
      				'post_content'  => '[wpgv_stripesuccesspage]',
      				'post_status'   => 'publish',
      				'post_author'   => get_current_user_id(),
      				'post_type'     => 'page',
      				'comment_status' => 'closed',
      				'ping_status'    => 'closed',
    			);
    			$stripeSuccessPage_id = wp_insert_post( $stripeSuccessPage, '' );
    			update_option('wpgv_stripesuccesspage', $stripeSuccessPage_id);
			}
			$settype = 'updated';
			$setmessage = __('Your Settings Saved Successfully.', 'gift-voucher');
			add_settings_error(
    	    	'wooenc_settings_updated',
	        	esc_attr( 'settings_updated' ),
        		$setmessage,
        		$settype
	    	);
   		}
		$wpgv_buying_for = get_option('wpgv_buying_for') ? get_option('wpgv_buying_for') : 'both';
		$wpgv_hide_price = get_option('wpgv_hide_price') ? get_option('wpgv_hide_price') : 0;
		$wpgv_enable_pdf_saving = get_option('wpgv_enable_pdf_saving') ? get_option('wpgv_enable_pdf_saving') : 0;
		$wpgv_customer_receipt = get_option('wpgv_customer_receipt') ? get_option('wpgv_customer_receipt') : 0;
		$wpgv_hide_expiry = get_option('wpgv_hide_expiry') ? get_option('wpgv_hide_expiry') : 'yes';
		$wpgv_expiry_date_format = get_option('wpgv_expiry_date_format') ? get_option('wpgv_expiry_date_format') : 'd.m.Y';
		$wpgv_termstext = get_option('wpgv_termstext') ? get_option('wpgv_termstext') : 'I hereby accept the terms and conditions, the revocation of the privacy policy and confirm that all information is correct.';
		$wpgv_custom_css = get_option('wpgv_custom_css') ? get_option('wpgv_custom_css') : '';
		$stripepageurl = get_option('wpgv_stripesuccesspage') ? get_page_link(get_option('wpgv_stripesuccesspage')) : '';
		$emailsubject = get_option('wpgv_emailsubject') ? get_option('wpgv_emailsubject') : 'Order Confirmation - Your Order with {company_name} (Voucher Order No: {order_number} ) has been successfully placed!';
		$emailbody = get_option('wpgv_emailbody') ? get_option('wpgv_emailbody') : '<p>Dear <strong>{customer_name}</strong>,</p><p>Order successfully placed.</p><p>We are pleased to confirm your order no {order_number}</p><p>Thank you for shopping with <strong>{company_name}</strong>!</p><p>You can download the voucher from {pdf_link}.</p><p>- For any clarifications please feel free to email us at {sender_email}.</p><p><strong>Warm Regards, <br /></strong> <strong>{company_name}<br />{website_url}</strong></p>';
		$emailbodyperinvoice = get_option('wpgv_emailbodyperinvoice') ? get_option('wpgv_emailbodyperinvoice') : '<p>Dear <strong>{customer_name}</strong>,</p><p>Order successfully placed.</p><p>We are pleased to confirm your order no {order_number}</p><p>Thank you for shopping with <strong>{company_name}</strong>!</p><p>You can download the voucher from {pdf_link}.</p><p>You will pay us directly into bank. Our bank details are below:</p><p><strong>Account Number: </strong>XXXXXXXXXXXX<br /><strong>Bank Code: </strong>XXXXXXXX</p><p>- For any clarifications please feel free to email us at {sender_email}.</p><p><strong>Warm Regards, <br /></strong> <strong>{company_name}<br />{website_url}</strong></p>';
		$recipientemailsubject = get_option('wpgv_recipientemailsubject') ? get_option('wpgv_recipientemailsubject') : 'Gift Voucher - Your have received voucher from {company_name}';
		$recipientemailbody = get_option('wpgv_recipientemailbody') ? get_option('wpgv_recipientemailbody') : '<p>Dear <strong>{recipient_name}</strong>,</p><p>You have received gift voucher from <strong>{customer_name}</strong>.</p><p>You can download the voucher from {pdf_link}.</p><p>- For any clarifications please feel free to email us at {sender_email}.</p><p><strong>Warm Regards, <br /></strong> <strong>{company_name}<br />{website_url}</strong></p>';
		$adminemailsubject = get_option('wpgv_adminemailsubject') ? get_option('wpgv_adminemailsubject') : 'New Voucher Order Received from {customer_name}  (Order No: {order_number})!';
		$adminemailbody = get_option('wpgv_adminemailbody') ? get_option('wpgv_adminemailbody') : '<p>Hello, New Voucher Order received.</p><p><strong>Order Id:</strong> {order_number}</p><p><strong>Name:</strong> {customer_name}<br /><strong>Email:</strong> {customer_email}<br /><strong>Amount:</strong> {amount}</p>';
		$demoimageurl = get_option('wpgv_demoimageurl') ? get_option('wpgv_demoimageurl') : WPGIFT__PLUGIN_URL.'/assets/img/demo.png';
		$cancelpagemessage = get_option('wpgv_cancelpagemessage') ? get_option('wpgv_cancelpagemessage') : 'You cancelled your order. Please place your order again from <a href="'.get_site_url().'/gift-voucher">here</a>.';
		$successpagemessage = get_option('wpgv_successpagemessage') ? get_option('wpgv_successpagemessage') : 'We have got your order! <br>E-Mail Sent Successfully to %s.<br>This link will be invalid after 1 hour.';
		$wpgv_stripe_alternative_text = get_option('wpgv_stripe_alternative_text') ? get_option('wpgv_stripe_alternative_text') : 'Stripe';
		$wpgv_paypal_client_id = get_option('wpgv_paypal_client_id') ? get_option('wpgv_paypal_client_id') : '';
		$wpgv_stripe_webhook_key = get_option('wpgv_stripe_webhook_key') ? get_option('wpgv_stripe_webhook_key') : '';
		$options = $wpdb->get_row( "SELECT * FROM $setting_table_name WHERE id = 1" );
		$voucher_styles = $options->voucher_style ? json_decode($options->voucher_style) : [''];
		?>
		<?php
		if (isset($_GET['action']) && $_GET['action'] == 'create_default_pages') { ?>
		<div class="wrap wpgiftv-settings">
			<h1><?php echo __( 'Pages Created', 'gift-voucher' ); ?></h1>
			<p><?php echo __( 'Created total 6 plugin pages. These pages can be viewed in Pages Menu:', 'gift-voucher' ); ?></p>
			<?php 
			$createdpages = wpgv_create_plugin_pages();
			foreach ($createdpages[0] as $page) {
				$slug = get_post_field( 'post_name', $page );
				echo get_site_url().'/'.$slug;
				echo '<br>';
			} ?>
			<p><a href="<?php echo admin_url( 'edit.php' ); ?>?post_type=wpgv_voucher_product&page=voucher-setting" class="button button-primary">Back to plugin settings</a></p>
			<p>If you read about those pages, click on the <a href="https://www.codemenschen.at/docs/wordpress-gift-vouchers-documentation/plugin-pages/" target="_blank">link</a> for documentation.</p>
		</div>
		<?php } else { ?>
		<div class="wrap wpgiftv-settings">
			<h1><?php echo __( 'Settings', 'gift-voucher' ); ?></h1>
			<hr>
			<?php settings_errors(); ?>
			<div class="image-banner" style="margin-bottom: 10px;">
				<a href="https://www.codemenschen.at/plugins/wordpress-gift-voucher-plugin" target="_blank"><img src="<?php echo WPGIFT__PLUGIN_URL.'/assets/img/banner1.png'; ?>" style="width: 100%;"></a>
			</div>
	<div class="wpgiftv-row">
		<div class="wpgiftv-col75">
			<div class="white-box">
				<a class="button button-large button-primary alignright" href="<?php echo admin_url( 'edit.php' ); ?>?post_type=wpgv_voucher_product&page=voucher-setting&action=create_default_pages">Create Plugin's Default Pages</a>
				<div class="nav-tab-wrapper">
					<a class="nav-tab nav-tab-active" href="#general"><?php echo __( 'General Settings', 'gift-voucher' ) ?></a>
					<a class="nav-tab" href="#payment"><?php echo __( 'Payment Settings', 'gift-voucher' ) ?></a>
					<a class="nav-tab" href="#email"><?php echo __( 'Email Settings', 'gift-voucher' ) ?></a>
					<a class="nav-tab" href="#custom"><?php echo __( 'Custom CSS', 'gift-voucher' ) ?></a>
				</div>
			<form method="post" name="voucher-settings" id="voucher-settings" action="<?php echo admin_url( 'edit.php' ); ?>?post_type=wpgv_voucher_product&page=voucher-setting">
				<input type="hidden" name="action" value="save_voucher_settings_option" />
				<?php $nonce = wp_create_nonce( 'voucher_settings_verify' ); ?>
				<input type="hidden" name="voucher_settings_verify" value="<?php echo($nonce); ?>">
				<table class="form-table tab-content tab-content-active" id="general">
					<tbody>
						<tr><th colspan="2" style="padding-bottom:0;padding-top: 0;"><h3><?php echo __( 'General Settings', 'gift-voucher' ); ?></h3></th></tr>
						<tr style="display: none;">
							<th scope="row">
								<label for="is_woocommerce_enable"><?php echo __( 'WooCommerce', 'gift-voucher' ); ?></label>
								<p class="description">If enable then customers can redeem their vouchers on WooCommerce checkout</p>
							</th>
							<td>
								<select name="is_woocommerce_enable" id="is_woocommerce_enable" class="regular-text">
									<option value="1" <?php echo ($options->is_woocommerce_enable == 1) ? 'selected' : ''; ?>>Enable</option>
									<option value="0" <?php echo (!$options->is_woocommerce_enable) ? 'selected' : ''; ?>>Disable</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="is_style_choose_enable"><?php echo __( 'Can customers choose voucher styles?', 'gift-voucher' ); ?></label>
								<p class="description">If enable then customers can choose the voucher styles from bottom styles you enabled</p>
							</th>
							<td>
								<select name="is_style_choose_enable" id="is_style_choose_enable" class="regular-text">
									<option value="1" <?php echo ($options->is_style_choose_enable == 1) ? 'selected' : ''; ?>>Yes</option>
									<option value="0" <?php echo (!$options->is_style_choose_enable) ? 'selected' : ''; ?>>No</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="voucher_style"><?php echo __( 'Voucher Style', 'gift-voucher' ); ?> (Can select multiple)</label>
								<p class="description">Demo: <a href="<?= WPGIFT__PLUGIN_URL.'/assets/img/style1.png' ?>" target="_blank">Style 1</a>, <a href="<?= WPGIFT__PLUGIN_URL.'/assets/img/style2.png' ?>" target="_blank">Style 2</a>, <a href="<?= WPGIFT__PLUGIN_URL.'/assets/img/style3.png' ?>" target="_blank">Style 3</a></p>
							</th>
							<td>
								<select name="voucher_style[]" id="voucher_style" multiple="multiple" class="regular-text">
									<option value="0" <?php echo in_array(0, $voucher_styles) ? 'selected' : ''; ?>>Style 1</option>
									<option value="1" <?php echo in_array(1, $voucher_styles) ? 'selected' : ''; ?>>Style 2</option>
									<option value="2" <?php echo in_array(2, $voucher_styles) ? 'selected' : ''; ?>>Style 3</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="company_name"><?php echo __( 'Company Name', 'gift-voucher' ); ?> <span class="description">(required)</span></label>
							</th>
							<td>
								<input name="company_name" type="text" id="company_name" value="<?php echo esc_html( stripslashes($options->company_name) ); ?>" class="regular-text" aria-required="true" required="required">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="currency_code"><?php echo __( 'Currency Code', 'gift-voucher'  ); ?> <span class="description">(required)</span></label>
								<p class="description"><a href="https://developer.paypal.com/docs/integration/direct/rest/currency-codes/" target="_blank">Click Here</a> to check valid currency codes</p>
							</th>
							<td>
								<input name="currency_code" type="text" id="currency_code" value="<?php echo esc_html( $options->currency_code ); ?>" class="regular-text" aria-required="true" required="required">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="currency"><?php echo __( 'Currency Symbol', 'gift-voucher'  ); ?> <span class="description">(required)</span></label>
							</th>
							<td>
								<input name="currency" type="text" id="currency" value="<?php echo esc_html( $options->currency ); ?>" class="regular-text" aria-required="true" required="required">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="currency_position"><?php echo __( 'Currency Position', 'gift-voucher' ); ?> </label>
							</th>
							<td>
								<select name="currency_position" class="regular-text" id="currency_position">
									<option value="Left" <?php echo ($options->currency_position == 'Left') ? 'selected' : ''; ?>>Left</option>
									<option value="Right" <?php echo ($options->currency_position == 'Right') ? 'selected' : ''; ?>>Right</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="voucher_bgcolor"><?php echo __( 'Voucher Background Color', 'gift-voucher' ); ?> <span class="description">(required)</span></label>
							</th>
							<td>
								<input name="voucher_bgcolor" type="text" id="voucher_bgcolor" value="#<?php echo esc_html( $options->voucher_bgcolor ); ?>" class="regular-text" aria-required="true">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="voucher_color"><?php echo __( 'Voucher Text Color', 'gift-voucher' ); ?> <span class="description">(required)</span></label>
							</th>
							<td>
								<input name="voucher_color" type="text" id="voucher_color" value="#<?php echo esc_html( $options->voucher_color ); ?>" class="regular-text" aria-required="true">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="template_col"><?php echo __( 'Templates Columns', 'gift-voucher'  ); ?></label>
								<p class="description"><?php echo __( 'How many templates show in a row. (Gift Voucher Shortcode)', 'gift-voucher'  ); ?></p>
							</th>
							<td>
								<select name="template_col" class="regular-text" id="template_col">
									<option value="3" <?php echo ($options->template_col == 3) ? 'selected' : ''; ?>>3</option>
									<option value="4" <?php echo ($options->template_col == 4) ? 'selected' : ''; ?>>4</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="voucher_min_value"><?php echo __( 'Minimum Voucher Value', 'gift-voucher'  ); ?></label>
								<p class="description"><?php echo __( 'Leave 0 if no minimum value', 'gift-voucher'  ); ?></p>
							</th>
							<td>
								<input name="voucher_min_value" type="number" step="0.01" id="voucher_min_value" value="<?php echo esc_html( $options->voucher_min_value ); ?>" class="regular-text" aria-required="true">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="voucher_max_value"><?php echo __( 'Maximum Voucher Value', 'gift-voucher'  ); ?></label>
							</th>
							<td>
								<input name="voucher_max_value" type="number" step="0.01" id="voucher_max_value" value="<?php echo esc_html( $options->voucher_max_value ); ?>" class="regular-text" aria-required="true">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="hide_expiry"><?php echo __( 'Add expiry in voucher', 'gift-voucher'  ); ?></label>
							</th>
							<td>
								<select name="hide_expiry" id="hide_expiry" class="regular-text">
									<option value="yes" <?php echo ($wpgv_hide_expiry == 'yes') ? 'selected' : ''; ?>>Yes</option>
									<option value="no" <?php echo ($wpgv_hide_expiry == 'no') ? 'selected' : ''; ?>>No</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="voucher_expiry_type"><?php echo __( 'Voucher Expiry Type', 'gift-voucher'  ); ?></label>
								<p class="description"><?php echo __( 'Select the type of voucher expiration?', 'gift-voucher'  ); ?></p>
							</th>
							<td>
								<select name="voucher_expiry_type" class="regular-text" id="template_col">
									<option value="days" <?php echo ($options->voucher_expiry_type == 'days') ? 'selected' : ''; ?>>Days</option>
									<option value="fixed" <?php echo ($options->voucher_expiry_type == 'fixed') ? 'selected' : ''; ?>>Fixed Date</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="voucher_expiry"><?php echo __( 'Voucher Expiry Value', 'gift-voucher'  ); ?></label>
								<p class="description"><?php echo __( 'Example: (Days: 60, Fixed Date: 20.05.2018)' ); ?></p>
							</th>
							<td>
								<input name="voucher_expiry" type="text" id="voucher_expiry" value="<?php echo esc_html( $options->voucher_expiry ); ?>" class="regular-text" aria-required="true">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="expiry_date_format"><?php echo __( 'Expiry date format', 'gift-voucher'  ); ?></label>
							</th>
							<td>
								<input name="expiry_date_format" type="text" id="expiry_date_format" value="<?php echo esc_html( $wpgv_expiry_date_format ); ?>" class="regular-text" aria-required="true">
								<p class="description"><a href="http://php.net/manual/en/function.date.php#refsect1-function.date-parameters" target="_blank">Click Here</a> to check valid date formats</p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="admin_email_body"><?php echo __( 'Terms and Condition Checkbox Text', 'gift-voucher' ); ?></label>
							</th>
							<td>
								<?php wp_editor( stripslashes($wpgv_termstext), 'wpgvtermstext', array( 'wpautop' => true, 'media_buttons' => false, 'textarea_rows' => 5 ) ); ?>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="voucher_terms_note"><?php echo __( 'Voucher Terms Note', 'gift-voucher'  ); ?></label>
								<p class="description"><?php echo __( 'Terms note in voucher order page', 'gift-voucher'  ); ?></p>
							</th>
							<td>
								<textarea name="voucher_terms_note" id="voucher_terms_note" class="regular-text" aria-required="true" rows="4"><?php echo esc_html( stripslashes($options->voucher_terms_note) ); ?></textarea>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="buying_for"><?php echo __( 'Buying for', 'gift-voucher'  ); ?></label>
							</th>
							<td>
								<select name="buying_for" class="regular-text" id="buying_for">
									<option value="both" <?php echo ($wpgv_buying_for == 'both') ? 'selected' : ''; ?>>Both</option>
									<option value="someone_else" <?php echo ($wpgv_buying_for == 'someone_else') ? 'selected' : ''; ?>>Someone Else</option>
									<option value="yourself" <?php echo ($wpgv_buying_for == 'yourself') ? 'selected' : ''; ?>>Yourself</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="hide_price"><?php echo __( 'Hide price from voucher', 'gift-voucher'  ); ?></label>
							</th>
							<td>
								<select name="hide_price" class="regular-text" id="hide_price">
									<option value="1" <?php echo ($wpgv_hide_price == 1) ? 'selected' : ''; ?>>Yes</option>
									<option value="0" <?php echo ($wpgv_hide_price == 0) ? 'selected' : ''; ?>>No</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="post_shipping"><?php echo __( 'Post Shipping', 'gift-voucher'  ); ?></label>
							</th>
							<td>
								<select name="post_shipping" class="regular-text" id="post_shipping">
									<option value="1" <?php echo ($options->post_shipping == 1) ? 'selected' : ''; ?>>Yes</option>
									<option value="0" <?php echo ($options->post_shipping == 0) ? 'selected' : ''; ?>>No</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="shipping_method"><?php echo __( 'Shipping Method for Post Shipping', 'gift-voucher'  ); ?></label>
								<p class="description">Method Format -> value : name </p>
							</th>
							<td>
								<textarea name="shipping_method" type="text" id="shipping_method" class="regular-text" rows="4"><?php echo esc_html( stripslashes($options->shipping_method) ); ?></textarea>
								<p class="description"><?php echo __( 'Multiple methods seperate by comma(,)', 'gift-voucher' ); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="preview_button"><?php echo __( 'Voucher preview Button', 'gift-voucher' ); ?></label>
								<p class="description">If enable then preview button will show in the voucher booking forms</p>
							</th>
							<td>
								<select name="preview_button" class="regular-text" id="preview_button">
									<option value="1" <?php echo ($options->preview_button == 1) ? 'selected' : ''; ?>>Enable</option>
									<option value="0" <?php echo ($options->preview_button == 0) ? 'selected' : ''; ?>>Disable</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="enable_pdf_saving"><?php echo __( 'Change PDF Save Option', 'gift-voucher' ); ?></label>
								<p class="description">If you are getting an error on checkout then enable this pdf saving option.</p>
							</th>
							<td>
								<select name="enable_pdf_saving" class="regular-text" id="enable_pdf_saving">
									<option value="1" <?php echo ($wpgv_enable_pdf_saving == 1) ? 'selected' : ''; ?>>Enable</option>
									<option value="0" <?php echo ($wpgv_enable_pdf_saving == 0) ? 'selected' : ''; ?>>Disable</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="demo_image"><?php echo __( 'Add Your Custom Demo Image', 'gift-voucher'  ); ?></label>
								<p class="description">Default Image - check <a href="<?= WPGIFT__PLUGIN_URL.'/assets/img/demo.png' ?>" target="_blank">here</a></p>
							</th>
							<td>
								<input name="demo_image" type="text" id="demo_image" value="<?php echo esc_html( $demoimageurl ); ?>" class="regular-text">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="custom_loader"><?php echo __( 'Add Your Custom Loader URL', 'gift-voucher'  ); ?></label>
								<p class="description">Default - check <a href="<?= WPGIFT__PLUGIN_URL.'/assets/img/loader.gif' ?>" target="_blank">here</a></p>
							</th>
							<td>
								<input name="custom_loader" type="text" id="custom_loader" value="<?php echo esc_html( $options->custom_loader ); ?>" class="regular-text">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="successpagemessage"><?php echo __( 'Successful Page Message', 'gift-voucher' ); ?></label>
								<p class="description"><?php echo __( 'Message appear after payment successful.', 'gift-voucher' ); ?></p>
							</th>
							<td>
								<?php wp_editor( stripslashes($successpagemessage), 'successpagemessage', array('wpautop' => false, 'media_buttons' => false, 'textarea_rows' => 5 ) ); ?>
								<p>%s - <?php echo __('Display the email address of the customer', 'gift-voucher'); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="cancelpagemessage"><?php echo __( 'Order Cancellation Message', 'gift-voucher' ); ?></label>
								<p class="description"><?php echo __( 'Message appear after order cancelled', 'gift-voucher' ); ?></p>
							</th>
							<td>
								<?php wp_editor( stripslashes($cancelpagemessage), 'cancelpagemessage', array('wpautop' => false, 'media_buttons' => false, 'textarea_rows' => 5 ) ); ?>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="pdf_footer_url"><?php echo __( 'Website URL on PDF in Footer', 'gift-voucher'  ); ?></label>
							</th>
							<td>
								<input name="pdf_footer_url" type="text" id="pdf_footer_url" value="<?php echo esc_html( $options->pdf_footer_url ); ?>" class="regular-text">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="pdf_footer_email"><?php echo __( 'Email on PDF in Footer', 'gift-voucher'  ); ?></label>
							</th>
							<td>
								<input name="pdf_footer_email" type="text" id="pdf_footer_email" value="<?php echo esc_html( $options->pdf_footer_email ); ?>" class="regular-text">
							</td>
						</tr>
					</tbody>
				</table>
				<table id="payment" class="form-table tab-content">
					<tbody>
						<tr><th colspan="2" style="padding-bottom:0"><h3><?php echo __( 'Payment Settings', 'gift-voucher'  ); ?></h3></th></tr>
						<tr>
							<th scope="row">
								<label for="paypal"><?php echo __( 'Paypal Enable', 'gift-voucher'  ); ?></label>
							</th>
							<td>
								<select name="paypal" class="regular-text" id="paypal">
									<option value="1" <?php echo ($options->paypal == 1) ? 'selected' : ''; ?>>Yes</option>
									<option value="0" <?php echo ($options->paypal == 0) ? 'selected' : ''; ?>>No</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="test_mode"><?php echo __( 'Paypal Testmode', 'gift-voucher'  ); ?></label>
							</th>
							<td>
								<select name="test_mode" class="regular-text" id="test_mode">
									<option value="1" <?php echo ($options->test_mode == 1) ? 'selected' : ''; ?>>Yes</option>
									<option value="0" <?php echo ($options->test_mode == 0) ? 'selected' : ''; ?>>No</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="paypal_client_id"><?php echo __( 'PayPal Live Client ID', 'gift-voucher'  ); ?></label>
								<p class="description"><?php echo __( 'Read the documentation of how to create PayPal live client ID.', 'gift-voucher' ); ?><br><a href="https://www.codemenschen.at/docs/wordpress-gift-vouchers-documentation/plugin-settings/payment-settings/" target="_blank">Click Here</a></p>
							</th>
							<td>
								<input name="paypal_client_id" type="text" id="paypal_client_id" value="<?php echo $wpgv_paypal_client_id; ?>" class="regular-text">
							</td>
						</tr>
						<tr><th colspan="2"><hr></th></tr>
						<tr>
							<th scope="row">
								<label for="stripe"><?php echo __( 'Stripe Enable', 'gift-voucher'  ); ?></label>
							</th>
							<td>
								<select name="stripe" class="regular-text" id="stripe">
									<option value="1" <?php echo ($options->stripe == 1) ? 'selected' : ''; ?>>Yes</option>
									<option value="0" <?php echo ($options->stripe == 0) ? 'selected' : ''; ?>>No</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="stripe_alternative_text"><?php echo __( 'Stripe Text', 'gift-voucher'  ); ?></label>
								<p class="description"><?php echo __( 'This will show on frontend Form', 'gift-voucher' ); ?></p>
							</th>
							<td>
								<input name="stripe_alternative_text" type="text" id="stripe_alternative_text" value="<?php echo stripslashes( $wpgv_stripe_alternative_text ); ?>" class="regular-text">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="stripe_publishable_key"><?php echo __( 'Stripe Publishable key', 'gift-voucher'  ); ?></label>
								<p class="description"><?php echo __( 'Collect the Publishable API key from below link.', 'gift-voucher' ); ?><br><a href="https://dashboard.stripe.com/account/apikeys" target="_blank">Click Here</a></p>
							</th>
							<td>
								<input name="stripe_publishable_key" type="text" id="stripe_publishable_key" value="<?php echo esc_html( $options->stripe_publishable_key ); ?>" class="regular-text">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="stripe_secret_key"><?php echo __( 'Stripe Secret Key', 'gift-voucher'  ); ?></label>
								<p class="description"><?php echo __( 'Collect the Secret API key from below link.', 'gift-voucher' ); ?><br><a href="https://dashboard.stripe.com/account/apikeys" target="_blank">Click Here</a></p>
							</th>
							<td>
								<input name="stripe_secret_key" type="text" id="stripe_secret_key" value="<?php echo esc_html( $options->stripe_secret_key ); ?>" class="regular-text">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="stripe_webhook_url"><?php echo __( 'Stripe Webhook URL', 'gift-voucher'  ); ?></label>
							</th>
							<td>
								<input name="stripe_webhook_url" type="text" id="stripe_webhook_url" value="<?php echo WPGIFT__PLUGIN_URL.'/include/stripewebhook.php'; ?>" class="regular-text" readonly>
								<p class="description"><?php echo __( 'Copy this url and paste in Stripe Webhook Endpoint URL.', 'gift-voucher' ); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="stripe_webhook_key"><?php echo __( 'Stripe Webhook Signing secret key', 'gift-voucher'  ); ?></label>
								<p class="description"><?php echo __( 'Collect the Webhook Signing secret key from below link.', 'gift-voucher' ); ?><br><a href="https://dashboard.stripe.com/account/webhooks" target="_blank">Click Here</a></p>
							</th>
							<td>
								<input name="stripe_webhook_key" type="text" id="stripe_webhook_key" value="<?php echo esc_html( $wpgv_stripe_webhook_key ); ?>" class="regular-text">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="stripe_checkoutpage"><?php echo __( 'Stripe Checkout Page', 'gift-voucher'  ); ?></label>
							</th>
							<td>
								<input name="stripe_checkoutpage" type="text" id="stripe_checkoutpage" value="<?php echo $stripepageurl; ?>" class="regular-text" readonly>
								<p class="description"><?php echo __( 'This page is automatically created for you when you enable stripe payment method.', 'gift-voucher' ); ?></p>
							</td>
						</tr>
						<tr><th colspan="2"><hr></th></tr>
						<tr>
							<th scope="row">
								<label for="sofort"><?php echo __( 'Sofort Enable', 'gift-voucher'  ); ?></label>
							</th>
							<td>
								<select name="sofort" class="regular-text" id="sofort">
									<option value="1" <?php echo ($options->sofort == 1) ? 'selected' : ''; ?>>Yes</option>
									<option value="0" <?php echo ($options->sofort == 0) ? 'selected' : ''; ?>>No</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="sofort_configure_key"><?php echo __( 'Sofort Configuration Key', 'gift-voucher' ); ?></label>
								<p class="description"><?php echo __( 'Enter your configuration key. you only can create a new configuration key by creating a new Gateway project in your account at sofort.com.', 'gift-voucher' ); ?></p>
							</th>
							<td>
								<input name="sofort_configure_key" type="text" id="sofort_configure_key" value="<?php echo esc_html( $options->sofort_configure_key ); ?>" class="regular-text" aria-describedby="paypal-description">
								<p class="description"><?php echo __( 'This key is used for Sofort Payment.', 'gift-voucher' ); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="reason_for_payment"><?php echo __( 'Reason for Payment', 'gift-voucher'  ); ?></label>
								<p class="description"><?php echo __( 'Reason for payment from Sofort.', 'gift-voucher'  ); ?></p>
							</th>
							<td>
								<input name="reason_for_payment" type="text" id="reason_for_payment" value="<?php echo esc_html( $options->reason_for_payment ); ?>" class="regular-text" aria-describedby="paypal-description">
							</td>
						</tr>
						<tr><th colspan="2"><hr></th></tr>
						<tr>
							<th scope="row">
								<label for="per_invoice"><?php echo __( 'Bank Transfer Enable', 'gift-voucher'  ); ?></label>
								<p class="description"><?php echo __('With this payment method user don\'t have to pay immediately, They can directly transfer amount to your bank.', 'gift-voucher'  ); ?></p>
							</th>
							<td>
								<select name="per_invoice" class="regular-text" id="per_invoice">
									<option value="1" <?php echo ($options->per_invoice == 1) ? 'selected' : ''; ?>>Yes</option>
									<option value="0" <?php echo ($options->per_invoice == 0) ? 'selected' : ''; ?>>No</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="bank_info"><?php echo __( 'Bank Details', 'gift-voucher'  ); ?></label>
								<p class="description"><?php echo __( 'This details will show to user who would pay as per invoice.', 'gift-voucher'  ); ?></p>
							</th>
							<td>
								<?php wp_editor( stripslashes($options->bank_info), 'bank_info', array( 'wpautop' => false, 'media_buttons' => false, 'textarea_rows' => 5 ) ); ?>
							</td>
						</tr>
					</tbody>
				</table>
				<table id="email" class="form-table tab-content">
					<tbody>
						<tr><th colspan="2" style="padding-bottom:0"><h3><?php echo __( 'Email Settings', 'gift-voucher'  ); ?></h3></th></tr>
						<tr>
							<th scope="row">
								<label for="sender_name"><?php echo __( 'Sender Name', 'gift-voucher' ); ?></label>
								<p class="description"><?php echo __( 'For emails send by this plugin.', 'gift-voucher' ); ?></p>
							</th>
							<td>
								<input name="sender_name" type="text" id="sender_name" value="<?php echo esc_html( $options->sender_name ); ?>" class="regular-text" aria-describedby="sendername-description">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="sender_email"><?php echo __( 'Sender Email', 'gift-voucher' ); ?></label>
								<p class="description"><?php echo __( 'For emails send by this plugin.', 'gift-voucher' ); ?></p>
							</th>
							<td>
								<input name="sender_email" type="email" id="sender_email" value="<?php echo esc_html( $options->sender_email ); ?>" class="regular-text" aria-describedby="senderemail-description">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="customer_receipt"><?php echo __( 'Send Customer Receipt', 'gift-voucher' ); ?></label>
							</th>
							<td>
								<select name="customer_receipt" id="customer_receipt" class="regular-text">
									<option value="1" <?php echo ($wpgv_customer_receipt == 1) ? 'selected' : ''; ?>>Enable</option>
									<option value="0" <?php echo ($wpgv_customer_receipt == 0) ? 'selected' : ''; ?>>Disable</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="email_subject"><?php echo __( 'Buyer Email Subject', 'gift-voucher' ); ?></label>
								<p class="description"><?php echo __( 'Subject for emails send to customers.', 'gift-voucher' ); ?></p>
							</th>
							<td>
								<?php wp_editor( stripslashes($emailsubject), 'email_subject', array( 'media_buttons' => false, 'textarea_rows' => 5 ) ); ?>
								<p class="description">{company_name} {website_url} {sender_email} {sender_name} {order_number} {order_type} {amount} {customer_name} {recipient_name} {customer_email} {coupon_code} {pdf_link} {payment_method} {payment_status} {receipt_link}</p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="email_body"><?php echo __( 'Buyer Email Body', 'gift-voucher' ); ?></label>
								<p class="description"><?php echo __( 'Body message for emails send to customers.', 'gift-voucher' ); ?></p>
							</th>
							<td>
								<?php wp_editor( stripslashes($emailbody), 'email_body', array( 'wpautop' => false, 'media_buttons' => false ) ); ?>
								<p class="description">{company_name} {website_url} {sender_email} {sender_name} {order_number} {order_type} {amount} {customer_name} {recipient_name} {customer_email} {coupon_code} {pdf_link} {payment_method} {payment_status} {receipt_link}</p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="email_body"><?php echo __( 'Buyer Email Body for Bank Transfer', 'gift-voucher' ); ?></label>
								<p class="description"><?php echo __( 'This email body is used when customer select payment as per bank transfer.', 'gift-voucher' ); ?></p>
							</th>
							<td>
								<?php wp_editor( stripslashes($emailbodyperinvoice), 'email_body_per_invoice', array( 'wpautop' => false, 'media_buttons' => false ) ); ?>
								<p class="description">{company_name} {website_url} {sender_email} {sender_name} {order_number} {order_type} {amount} {customer_name} {recipient_name} {customer_email} {coupon_code} {pdf_link} {payment_method} {payment_status} {receipt_link}</p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="email_subject"><?php echo __( 'Recipient Email Subject', 'gift-voucher' ); ?></label>
								<p class="description"><?php echo __( 'Subject for emails send to recipient.', 'gift-voucher' ); ?></p>
							</th>
							<td>
								<?php wp_editor( stripslashes($recipientemailsubject), 'recipient_email_subject', array( 'media_buttons' => false, 'textarea_rows' => 5 ) ); ?>
								<p class="description">{company_name} {website_url} {sender_email} {sender_name} {order_number} {order_type} {amount} {customer_name} {recipient_name} {customer_email} {coupon_code} {pdf_link} {payment_method} {payment_status} {receipt_link}</p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="email_body"><?php echo __( 'Recipient Email Body', 'gift-voucher' ); ?></label>
								<p class="description"><?php echo __( 'Body message for emails send to recipient.', 'gift-voucher' ); ?></p>
							</th>
							<td>
								<?php wp_editor( stripslashes($recipientemailbody), 'recipient_email_body', array( 'wpautop' => false, 'media_buttons' => false ) ); ?>
								<p class="description">{company_name} {website_url} {sender_email} {sender_name} {order_number} {order_type} {amount} {customer_name} {recipient_name} {customer_email} {coupon_code} {pdf_link} {payment_method} {payment_status} {receipt_link}</p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="admin_email_subject"><?php echo __( 'Admin Email Subject', 'gift-voucher' ); ?></label>
								<p class="description"><?php echo __( 'Subject for emails send to customers.', 'gift-voucher' ); ?></p>
							</th>
							<td>
								<?php wp_editor( $adminemailsubject, 'admin_email_subject', array( 'media_buttons' => false, 'textarea_rows' => 5 ) ); ?>
								<p class="description">{company_name} {website_url} {sender_email} {sender_name} {order_number} {order_type} {amount} {customer_name} {recipient_name} {customer_email} {coupon_code} {pdf_link} {payment_method} {payment_status} {receipt_link}</p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="admin_email_body"><?php echo __( 'Admin Email Body', 'gift-voucher' ); ?></label>
								<p class="description"><?php echo __( 'Body message for emails send to customers.', 'gift-voucher' ); ?></p>
							</th>
							<td>
								<?php wp_editor( stripslashes($adminemailbody), 'admin_email_body', array('wpautop' => false, 'media_buttons' => false ) ); ?>
								<p class="description">{company_name} {website_url} {sender_email} {sender_name} {order_number} {order_type} {amount} {customer_name} {recipient_name} {customer_email} {coupon_code} {pdf_link} {payment_method} {payment_status} {receipt_link}</p>
							</td>
						</tr>
					</tbody>
				</table>
				<table id="custom" class="form-table tab-content">
					<tbody>
						<tr><th colspan="2" style="padding-bottom:0"><h3><?php echo __( 'Custom CSS', 'gift-voucher'  ); ?></h3></th></tr>
						<tr>
							<td colspan="2">
								<textarea name="wpgv_custom_css" id="wpgv_custom_css" class="regular-text" aria-required="true" rows="4" style="width: 100%;height: 200px;"><?php echo stripslashes( $wpgv_custom_css ); ?></textarea>
							</td>
						</tr>
					</tbody>
				</table>
				<p class="submit"><?php submit_button( __( 'Save Settings', 'gift-voucher'), 'primary', 'submit', false ); ?></p>
			</form></div></div>

		<div class="wpgiftv-col25">
			<div class="white-box rating-box">
				<h2>Rate Our Plugin</h2>
				<div class="star-ratings">
					<span class="dashicons dashicons-star-filled"></span>
					<span class="dashicons dashicons-star-filled"></span>
					<span class="dashicons dashicons-star-filled"></span>
					<span class="dashicons dashicons-star-filled"></span>
					<span class="dashicons dashicons-star-filled"></span>
				</div>
				<p>Did WordPress Gift Voucher Plugin help you out? Please leave a 5-star review. Thank you!</p>
				<a href="https://wordpress.org/support/plugin/gift-voucher/reviews/#new-post" target="_blank" class="button button-primary">Write a review</a>
			</div>
			<div class="white-box">
				<h2>Gift Cards (Gift Vouchers and Packages)</h2>
				<h4>Changelog</h4>
				<p>See what's new in <a href="https://wordpress.org/plugins/gift-voucher/#developers" target="_blank">version <?php echo WPGIFT_VERSION; ?></a>.</p>
				<h4>Resources</h4>
				<ul>
					<li><a href="https://codemenschen.at" target="_blank"><i aria-hidden="true" class="dashicons dashicons-external"></i> Website</a></li>
					<li><a href="https://codemenschen.at/docs/wordpress-gift-vouchers-documentation/" target="_blank"><i aria-hidden="true" class="dashicons dashicons-external"></i> Documentation</a></li>
					<li><a href="https://www.codemenschen.at/submit-ticket/" target="_blank"><i aria-hidden="true" class="dashicons dashicons-external"></i> Support</a></li>
					<li><a href="https://www.codemenschen.at/plugins/wordpress-gift-voucher-plugin/" target="_blank"><i aria-hidden="true" class="dashicons dashicons-external"></i> Pro</a></li>
				</ul>
			</div>
			<div class="white-box">
				<h2>Having Issues?</h2>
				<p>Need a helping hand? Please ask for help on the <a href="https://www.codemenschen.at/submit-ticket/" target="_blank">Support forum</a>. Be sure to mention your WordPress version and give as much additional information as possible.</p>
				<a href="https://www.codemenschen.at/submit-ticket/?page=tickets&section=create-ticket" class="button button-primary" target="_blank">Submit your question</a>
			</div>
			<div class="white-box">
				<h2>Customization Service</h2>
				<p>We are a European Company. To hire our agency to help you with this plugin installation or any other customization or requirements please contact us through our site <a href="https://www.codemenschen.at/contact" target="_blank">contact form</a> or email <a href="mailto:gdpr@codemenschen.at">gdpr@codemenschen.at</a> directly.</p>
				<a href="https://www.codemenschen.at/contact" class="button button-primary" target="_blank">Hire Us Now</a>
			</div>
			<div class="image-banner" style="margin-bottom: 10px;">
				<a href="https://www.codemenschen.at/plugins/wordpress-gift-voucher-plugin" target="_blank"><img src="<?php echo WPGIFT__PLUGIN_URL.'/assets/img/banner2.png'; ?>" style="width: 100%;"></a>
			</div>
		</div>
		</div>
		<span class="wpgiftv-disclaimer">Thank you for using <b>WordPress Gift Voucher</b>.</span>
		</div>
	<?php } ?>