<?php

if( !defined( 'ABSPATH' ) ) exit;  // Exit if accessed directly

// Function for Voucher Payment Successful Shortcode
function wpgv_voucher_successful_shortcode() {
	global $wpdb;
	$return = '';

	$voucher_table 	= $wpdb->prefix . 'giftvouchers_list';
	$setting_table 	= $wpdb->prefix . 'giftvouchers_setting';
	$setting_options = $wpdb->get_row( "SELECT * FROM $setting_table WHERE id = 1" );
	if (isset($_GET['voucheritem'])) {
		$voucheritem = sanitize_text_field($_GET['voucheritem']);
		$voucher_options = $wpdb->get_row( "SELECT * FROM $voucher_table WHERE id = $voucheritem" );
		if((strtotime($voucher_options->voucheradd_time)+3600) < strtotime(current_time('mysql')) ) {
			return '<div class="error"><p>'.__('This URL is invalid. You can not access this page directly.', 'gift-voucher').'</p></div>';
		}
		if(isset($_GET['per_invoice']) && $_GET['per_invoice'] == 1) { 
		} else {
			$voucherrow = $wpdb->get_row( "SELECT * FROM `{$wpdb->prefix}giftvouchers_list` WHERE `id` = $voucheritem AND `pay_method` <> 'Per Invoice'" );
			if($voucherrow) {
				$wpdb->update(
					$voucher_table,
					array( 
						'payment_status' 	=> __('Paid'),
						'voucheradd_time'	=> current_time( 'mysql' )
					),
					array('id' => $voucheritem),
					array( 
						'%s'
					), 
					array( '%d' )
				);
				if(isset($_GET['paymentID'])) {
					add_post_meta( $voucheritem, 'wpgv_paypal_payment_key', $_GET['paymentID'], true );
				}
				WPGV_Gift_Voucher_Activity::record( $voucheritem, 'firsttransact', $voucherrow->amount, 'Voucher payment recieved.' );
			}
		}

		$emailsubject = get_option('wpgv_emailsubject') ? get_option('wpgv_emailsubject') : 'Order Confirmation - Your Order with {company_name} (Voucher Order No: {order_number} ) has been successfully placed!';
		$recipientemailsubject = get_option('wpgv_recipientemailsubject') ? get_option('wpgv_recipientemailsubject') : 'Gift Voucher - Your have received voucher from {company_name}';
		$recipientemailbody = get_option('wpgv_recipientemailbody') ? get_option('wpgv_recipientemailbody') : '<p>Dear <strong>{recipient_name}</strong>,</p><p>You have received gift voucher from <strong>{customer_name}</strong>.</p><p>You can download the voucher from {pdf_link}.</p><p>- For any clarifications please feel free to email us at {sender_email}.</p><p><strong>Warm Regards, <br /></strong> <strong>{company_name}<br />{website_url}</strong></p>';
		if(isset($_GET['per_invoice']) && $_GET['per_invoice'] == 1) {
			$emailbody = get_option('wpgv_emailbodyperinvoice') ? get_option('wpgv_emailbodyperinvoice') : '<p>Dear <strong>{customer_name}</strong>,</p><p>Order successfully placed.</p><p>We are pleased to confirm your order no {order_number}</p><p>Thank you for shopping with <strong>{company_name}</strong>!</p><p>You can download the voucher from {pdf_link}.</p><p>You will pay us directly into bank. Our bank details are below:</p><p><strong>Account Number: </strong>XXXXXXXXXXXX<br /><strong>Bank Code: </strong>XXXXXXXX</p><p>- For any clarifications please feel free to email us at {sender_email}.</p><p><strong>Warm Regards, <br /></strong> <strong>{company_name}<br />{website_url}</strong></p>';
		} else {
			$emailbody = get_option('wpgv_emailbody') ? get_option('wpgv_emailbody') : '<p>Dear <strong>{customer_name}</strong>,</p><p>Order successfully placed.</p><p>We are pleased to confirm your order no {order_number}</p><p>Thank you for shopping with <strong>{company_name}</strong>!</p><p>You can download the voucher from {pdf_link}.</p><p>- For any clarifications please feel free to email us at {sender_email}.</p><p><strong>Warm Regards, <br /></strong> <strong>{company_name}<br />{website_url}</strong></p>';
		}

		$adminemailsubject = get_option('wpgv_adminemailsubject') ? get_option('wpgv_adminemailsubject') : 'New Voucher Order Received from {customer_name}  (Order No: {order_number})!';
		$adminemailbody = get_option('wpgv_adminemailbody') ? get_option('wpgv_adminemailbody') : '<p>Hello, New Voucher Order received.</p><p><strong>Order Id:</strong> {order_number}</p><p><strong>Name:</strong> {customer_name}<br /><strong>Email:</strong> {customer_email}<br /><strong>Address:</strong> {customer_address}<br /><strong>Postcode:</strong> {customer_postcode}</p>';

		$upload = wp_upload_dir();
 		$upload_dir = $upload['basedir'];
		$attachments[0] = $upload_dir.'/voucherpdfuploads/'.$voucher_options->voucherpdf_link.'.pdf';
		$headers = 'Content-type: text/html;charset=utf-8' . "\r\n";
		$headers .= 'From: '.$setting_options->sender_name.' <'.$setting_options->sender_email.'>' . "\r\n";
		$headers .= 'Reply-to: '.$setting_options->sender_name.' <'.$setting_options->sender_email.'>' . "\r\n";

		/* Recipient Mail */
		if($voucher_options->shipping_type != 'shipping_as_post') {
			$recipientsub = wpgv_mailvarstr($recipientemailsubject, $setting_options, $voucher_options);
			$recipientmsg = wpgv_mailvarstr($recipientemailbody, $setting_options, $voucher_options);
			$recipientto = $voucher_options->from_name .'<'.$voucher_options->shipping_email.'>';
			if($voucher_options->buying_for == 'yourself') {
				$recipientto = $voucher_options->from_name .'<'.$voucher_options->email.'>';
			}
			wp_mail( $recipientto, $recipientsub, $recipientmsg, $headers, $attachments );
		}

		$attachments[1] = $upload_dir.'/voucherpdfuploads/'.$voucher_options->voucherpdf_link.'-receipt.pdf';

		/* Buyer Mail */
		$buyersub = wpgv_mailvarstr($emailsubject, $setting_options, $voucher_options);
		$buyermsg = wpgv_mailvarstr($emailbody, $setting_options, $voucher_options);
		$buyerto = $voucher_options->from_name .'<'.$voucher_options->email.'>';
		$mail_sent = wp_mail( $buyerto, $buyersub, $buyermsg, $headers, $attachments );

		if($mail_sent == true) {
			$successpagemessage = get_option('wpgv_successpagemessage') ? get_option('wpgv_successpagemessage') : 'We have got your order! <br>E-Mail Sent Successfully to %s';
			$return .= '<div class="success">'.sprintf(stripslashes($successpagemessage), $voucher_options->email).'</div>';

			if(isset($_GET['per_invoice']) && $_GET['per_invoice'] == 1) {
				$return .= $setting_options->bank_info;
			}

			$toadmin = $setting_options->sender_name.' <'.$setting_options->sender_email.'>';
			$subadmin = wpgv_mailvarstr($adminemailsubject, $setting_options, $voucher_options);
			$bodyadmin = wpgv_mailvarstr($adminemailbody, $setting_options, $voucher_options);
			$headersadmin = 'Content-type: text/html;charset=utf-8' . "\r\n";
			$headersadmin .= 'From: '.$setting_options->sender_name.' <'.$setting_options->sender_email.'>' . "\r\n";
			$headersadmin .= 'Reply-to: '.$voucher_options->from_name.' <'.$voucher_options->email.'>' . "\r\n";

			wp_mail( $toadmin, $subadmin, $bodyadmin, $headersadmin, $attachments );
		}
		else {
			$return .= '<div class="error"><p>'.__('Some Error Occurred From Sending this Email! <br>(Reload and Retry Again!) or Contact Us', 'gift-voucher').'</p></div>';
		}
	} else {
		return '<div class="error"><p>'.__('This URL is invalid. You can not access this page directly.', 'gift-voucher').'</p></div>';
	}
	return $return;
}
add_shortcode( 'wpgv_giftvouchersuccesspage', 'wpgv_voucher_successful_shortcode' );

// Function for Voucher Payment Cancel Shortcode
function wpgv_voucher_cancel_shortcode() {
	global $wpdb;
	$return = '';
	$voucher_table 	= $wpdb->prefix . 'giftvouchers_list';
	if (isset($_GET['voucheritem'])) {
		$cancelpagemessage = get_option('wpgv_cancelpagemessage') ? get_option('wpgv_cancelpagemessage') : 'You cancelled your order. Please place your order again from <a href="'.get_site_url().'/gift-voucher">here</a>.';
		$voucheritem = sanitize_text_field($_GET['voucheritem']);
		$wpdb->delete( $voucher_table, array( 'id' => $voucheritem ), array( '%d' ) );
		$return .= stripslashes($cancelpagemessage);
	}
	return $return;
}
add_shortcode( 'wpgv_giftvouchercancelpage', 'wpgv_voucher_cancel_shortcode' );

//Function for Stripe Success Page
function wpgv_stripe_success_page_shortcode() {
	global $wpdb;
	$voucher_table 	= $wpdb->prefix . 'giftvouchers_list';
	$setting_table 	= $wpdb->prefix . 'giftvouchers_setting';
	$setting_options = $wpdb->get_row( "SELECT * FROM $setting_table WHERE id = 1" );

	//check whether stripe token is not empty
	if(!empty($_GET['sessionid'])) {
    	$orderid = $_GET['voucheritem'];

    	$voucher_options = $wpdb->get_row( "SELECT * FROM $voucher_table WHERE id = $orderid" );

    	if((strtotime($voucher_options->voucheradd_time)+3600) < strtotime(current_time('mysql')) ) {
			return '<div class="error"><p>'.__('This URL is invalid. You can not access this page directly.', 'gift-voucher').'</p></div>';
		}

    	//include Stripe PHP library
    	// if(!class_exists('\Stripe\Checkout\Session')) {
    	// 	require_once( WPGIFT__PLUGIN_DIR .'/library/stripe-php/init.php');
    	// }
    
    	//set api key
    	$stripe = array(
      		"publishable_key" => $setting_options->stripe_publishable_key,
      		"secret_key"      => $setting_options->stripe_secret_key,
    	);

    	\Stripe\Stripe::setApiKey($stripe['secret_key']);
    	\Stripe\Stripe::setVerifySslCerts(false);

		$checkout_session = \Stripe\Checkout\Session::retrieve($_GET['sessionid']);

		//retrieve charge details
    	$sessionJson = $checkout_session->jsonSerialize();

    	\Stripe\PaymentIntent::update(
  			$sessionJson['payment_intent'],
  			['metadata' => ['order_id' => $orderid]]
		);

    	// To create a requires_capture PaymentIntent, see our guide at: 
		// https://stripe.com/docs/payments/payment-intents/use-cases#separate-auth-capture
		$intent = \Stripe\PaymentIntent::retrieve($sessionJson['payment_intent']);

    	//retrieve charge details
    	$intentJson = $intent->jsonSerialize();
    	$chargeJson = $intentJson['charges']['data'][0];

    	//check whether the charge is successful
    	if($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1) {
        	//order details
        	$amount = $chargeJson['amount'];
        	$balance_transaction = $chargeJson['balance_transaction'];
        	$currency = $chargeJson['currency'];
        	$status = $chargeJson['status'];
        	$date = date("Y-m-d H:i:s");
        
        	//if order inserted successfully
        	if($status == 'succeeded') {

				$wpdb->update(
					$voucher_table,
					array( 
						'payment_status' 	=> __('Paid'),
						'voucheradd_time'	=> current_time( 'mysql' )
					),
					array('id' => $orderid, 'pay_method' => __('Stripe')),
					array( 
						'%s'
					), 
					array('%d', '%s')
				);
				add_post_meta( $orderid, 'wpgv_stripe_session_key', $_GET['sessionid'], true );

				$voucherrow = $wpdb->get_row( "SELECT * FROM `{$wpdb->prefix}giftvouchers_list` WHERE `id` = $orderid" );
				WPGV_Gift_Voucher_Activity::record( $orderid, 'firsttransact', $voucherrow->amount, 'Voucher payment recieved.' );

				$emailsubject = get_option('wpgv_emailsubject') ? get_option('wpgv_emailsubject') : 'Order Confirmation - Your Order with {company_name} (Voucher Order No: {order_number} ) has been successfully placed!';
				$emailbody = get_option('wpgv_emailbody') ? get_option('wpgv_emailbody') : '<p>Dear <strong>{customer_name}</strong>,</p><p>Order successfully placed.</p><p>We are pleased to confirm your order no {order_number}</p><p>Thank you for shopping with <strong>{company_name}</strong>!</p><p>You can download the voucher from {pdf_link}.</p><p>- For any clarifications please feel free to email us at {sender_email}.</p><p><strong>Warm Regards, <br /></strong> <strong>{company_name}<br />{website_url}</strong></p>';
				$recipientemailsubject = get_option('wpgv_recipientemailsubject') ? get_option('wpgv_recipientemailsubject') : 'Gift Voucher - Your have received voucher from {company_name}';
				$recipientemailbody = get_option('wpgv_recipientemailbody') ? get_option('wpgv_recipientemailbody') : '<p>Dear <strong>{recipient_name}</strong>,</p><p>You have received gift voucher from <strong>{customer_name}</strong>.</p><p>You can download the voucher from {pdf_link}.</p><p>- For any clarifications please feel free to email us at {sender_email}.</p><p><strong>Warm Regards, <br /></strong> <strong>{company_name}<br />{website_url}</strong></p>';
				$adminemailsubject = get_option('wpgv_adminemailsubject') ? get_option('wpgv_adminemailsubject') : 'New Voucher Order Received from {customer_name}  (Order No: {order_number})!';
				$adminemailbody = get_option('wpgv_adminemailbody') ? get_option('wpgv_adminemailbody') : '<p>Hello, New Voucher Order received.</p><p><strong>Order Id:</strong> {order_number}</p><p><strong>Name:</strong> {customer_name}<br /><strong>Email:</strong> {customer_email}<br /><strong>Address:</strong> {customer_address}<br /><strong>Postcode:</strong> {customer_postcode}</p>';

				$upload = wp_upload_dir();
 				$upload_dir = $upload['basedir'];
				$attachments[0] = $upload_dir.'/voucherpdfuploads/'.$voucher_options->voucherpdf_link.'.pdf';
				$headers = 'Content-type: text/html;charset=utf-8' . "\r\n";
				$headers .= 'From: '.$setting_options->sender_name.' <'.$setting_options->sender_email.'>' . "\r\n";
				$headers .= 'Reply-to: '.$setting_options->sender_name.' <'.$setting_options->sender_email.'>' . "\r\n";

				/* Recipient Mail */
				if($voucher_options->shipping_type != 'shipping_as_post') {
					$recipientsub = wpgv_mailvarstr($recipientemailsubject, $setting_options, $voucher_options);
					$recipientmsg = wpgv_mailvarstr($recipientemailbody, $setting_options, $voucher_options);
					$recipientto = $voucher_options->from_name .'<'.$voucher_options->shipping_email.'>';
					if($voucher_options->buying_for == 'yourself') {
						$recipientto = $voucher_options->from_name .'<'.$voucher_options->email.'>';
					}
					wp_mail( $recipientto, $recipientsub, $recipientmsg, $headers, $attachments );
				}

				$attachments[1] = $upload_dir.'/voucherpdfuploads/'.$voucher_options->voucherpdf_link.'-receipt.pdf';

				/* Buyer Mail */
				$buyersub = wpgv_mailvarstr($emailsubject, $setting_options, $voucher_options);
				$buyermsg = wpgv_mailvarstr($emailbody, $setting_options, $voucher_options);
				$buyerto = $voucher_options->from_name .'<'.$voucher_options->email.'>';
				$mail_sent = wp_mail( $buyerto, $buyersub, $buyermsg, $headers, $attachments );

				if($mail_sent == true) {
					$successpagemessage = get_option('wpgv_successpagemessage') ? get_option('wpgv_successpagemessage') : 'We have got your order! <br>E-Mail Sent Successfully to %s';
					$statusMsg = '<div class="success">'.sprintf(stripslashes($successpagemessage), $voucher_options->email).'</div>';

					$toadmin = $setting_options->sender_name.' <'.$setting_options->sender_email.'>';
					$subadmin = wpgv_mailvarstr($adminemailsubject, $setting_options, $voucher_options);
					$bodyadmin = wpgv_mailvarstr($adminemailbody, $setting_options, $voucher_options);
					$headersadmin = 'Content-type: text/html;charset=utf-8' . "\r\n";
					$headersadmin .= 'From: '.$setting_options->sender_name.' <'.$setting_options->sender_email.'>' . "\r\n";
					$headersadmin .= 'Reply-to: '.$voucher_options->from_name.' <'.$voucher_options->email.'>' . "\r\n";

					wp_mail( $toadmin, $subadmin, $bodyadmin, $headersadmin, $attachments );
				} else {
					$statusMsg = '<div class="error"><p>'.__('Some Error Occurred From Sending this Email! <br>(Reload and Retry Again!) or Contact Us', 'gift-voucher').'</p></div>';
				}
        	} else {
	            $statusMsg = __("Transaction has been failed", 'gift-voucher');
        	}
    	} else {
	        $statusMsg = __("Transaction has been failed!", 'gift-voucher');
	    }
	} else {
	    $statusMsg = "Form submission error.......";
	}

	return $statusMsg;
}
add_shortcode( 'wpgv_stripesuccesspage', 'wpgv_stripe_success_page_shortcode' );

function wpgv_check_voucher_balance_shortcode() {
	$voucher_code = '';
	if(isset($_REQUEST['voucher_code'])) {
		$voucher_code = $_REQUEST['voucher_code'];
	} ?>
	<form action="" method="post">
        <input type="text" name="voucher_code" autocomplete="off" placeholder="<?= __('Search by Gift voucher code', 'gift-voucher'); ?>" value="<?= $voucher_code ?>" style="width: 400px;" required>
        <input type="submit" class="button button-primary" value="<?= __('Check Balance', 'gift-voucher'); ?>">
    </form>
<?php 
	if($voucher_code) {
		global $wpdb;
		$gift_voucher = new WPGV_Gift_Voucher( $voucher_code );
        if ( $gift_voucher->get_id() ) {
		?>
		<style type="text/css">
			.wpgv-balance-activity-negative {
    			color: #f00;
			}
			.wpgv-balance-activity-table {
    			font-size: 14px;
			}
			.wpgv-balance-activity-table td, 
			.wpgv-balance-activity-table th {
    			padding: 10px;
			}
		</style>
		<h4>
			<strong><?= __('Current Voucher Balance:', 'gift-voucher'); ?> <?= wpgv_price_format( $gift_voucher->get_balance() ); ?></strong>
		</h4>
		<table class="wpgv-balance-activity-table">
    		<tr>
        		<th><?php _e( 'Date', 'gift-voucher' ); ?></th>
        		<th><?php _e( 'Action', 'gift-voucher' ); ?></th>
        		<th><?php _e( 'Note', 'gift-voucher' ); ?></th>
        		<th><?php _e( 'Amount', 'gift-voucher' ); ?></th>
        		<th><?php _e( 'Balance', 'gift-voucher' ); ?></th>
    		</tr>
    		<?php
        	$running_balance = $gift_voucher->get_balance();
        	foreach ( $gift_voucher->get_activity() as $activity ) {
            ?>
            <tr>
                <td>
                    <?= date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $activity->activity_date ) ); ?>
                </td>
                <td>
                    <?= esc_html( ucwords( $activity->action ) ); ?>
                </td>
                <td>
                    <?= esc_html( $activity->note ); ?>
                </td>
                <td class="wpgv-balance-activity <?php echo ( $activity->amount < 0 ) ? 'wpgv-balance-activity-negative' : ''; ?>">
                    <?php
                        if ( $activity->amount != 0 ) {
                            echo wpgv_price_format( $activity->amount );
                        }
                    ?>
                </td>
                <td class="wpgv-balance-activity">
                    <?php echo wpgv_price_format( $running_balance ); ?>
                </td>
            </tr>
            <?php
        	}
    		?>
		</table>
		<?php
		} else {
            echo __( 'This is a invalid voucher code.', 'gift-voucher' );
	}
}
}
add_shortcode( 'wpgv-check-voucher-balance', 'wpgv_check_voucher_balance_shortcode' );