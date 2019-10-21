<?php
// namespace Sofort\SofortLib;

if( !defined( 'ABSPATH' ) ) exit;  // Exit if accessed directly

function wpgv__doajax_item_pdf_save_func() {
	$catid = sanitize_text_field(base64_decode($_POST['catid']));
	$itemid = sanitize_text_field(base64_decode($_POST['itemid']));
	$buyingfor = sanitize_text_field(base64_decode($_POST['buyingfor']));
	$for = sanitize_text_field(base64_decode($_POST['yourname']));
	$from = isset($_POST['recipientname']) ? sanitize_text_field(base64_decode($_POST['recipientname'])) : '';
	$value = sanitize_text_field(base64_decode($_POST['totalprice']));
	$message = sanitize_textarea_field(base64_decode($_POST['recipientmessage']));
	$code = $_POST['couponcode'];
	$shipping = sanitize_text_field(base64_decode($_POST['shipping']));
	$shipping_email = isset($_POST['shipping_email']) ? sanitize_email(base64_decode($_POST['shipping_email'])) : '';
	$firstname = isset($_POST['firstname']) ? sanitize_text_field(base64_decode($_POST['firstname'])) : '';
	$lastname = isset($_POST['lastname']) ? sanitize_text_field(base64_decode($_POST['lastname'])) : '';
	$receipt_email = isset($_POST['receipt_email']) ? sanitize_email(base64_decode($_POST['receipt_email'])) : '';
	$address = isset($_POST['address']) ? sanitize_text_field(base64_decode($_POST['address'])) : '';
	$pincode = isset($_POST['pincode']) ? sanitize_text_field(base64_decode($_POST['pincode'])) : '';
	$shipping_method = isset($_POST['shipping_method']) ? base64_decode($_POST['shipping_method']) : '';
	$paymentmethod = sanitize_text_field(base64_decode($_POST['paymentmethod']));

	global $wpdb;
	$voucher_table 	= $wpdb->prefix . 'giftvouchers_list';
	$setting_table 	= $wpdb->prefix . 'giftvouchers_setting';
	$setting_options = $wpdb->get_row( "SELECT * FROM $setting_table WHERE id = 1" );
	$image = get_attached_file(get_post_thumbnail_id($itemid)) ? get_attached_file(get_post_thumbnail_id($itemid)) : get_option('wpgv_demoimageurl');
	$voucher_bgcolor = wpgv_hex2rgb($setting_options->voucher_bgcolor);
	$voucher_color = wpgv_hex2rgb($setting_options->voucher_color);
	
	$price = get_post_meta( $itemid, 'price', true );
	$special_price = get_post_meta( $itemid, 'special_price', true );
	$value = ($special_price) ? $special_price : $price;

	$currency = wpgv_price_format($value);
	
	$wpgv_hide_expiry = get_option('wpgv_hide_expiry') ? get_option('wpgv_hide_expiry') : 'yes';
	$wpgv_customer_receipt = get_option('wpgv_customer_receipt') ? get_option('wpgv_customer_receipt') : 0;
	$wpgv_expiry_date_format = get_option('wpgv_expiry_date_format') ? get_option('wpgv_expiry_date_format') : 'd.m.Y';
	$wpgv_enable_pdf_saving = get_option('wpgv_enable_pdf_saving') ? get_option('wpgv_enable_pdf_saving') : 0;

	if($wpgv_hide_expiry == 'no') {
    	$expiry = __('No Expiry', 'gift-voucher' );
	} else {
		$expiry = ($setting_options->voucher_expiry_type == 'days') ? date($wpgv_expiry_date_format,strtotime('+'.$setting_options->voucher_expiry.' days',time())) . PHP_EOL : $setting_options->voucher_expiry;
	}

	$upload = wp_upload_dir();
 	$upload_dir = $upload['basedir'];
 	$curr_time = time();
 	$upload_dir = $upload_dir . '/voucherpdfuploads/'.$curr_time.$_POST['couponcode'].'.pdf';
 	$upload_url = $curr_time.$_POST['couponcode'];

	$formtype = 'item';
	$preview = false;

	if ($setting_options->is_style_choose_enable) {
		$voucher_style = sanitize_text_field(base64_decode($_POST['style']));
		$style_image = get_post_meta($itemid, 'style'.($voucher_style+1).'_image', true);
		$image_attributes = get_attached_file( $style_image );
		$image = ($image_attributes) ? $image_attributes : get_option('wpgv_demoimageurl');
		$stripeimage = (wp_get_attachment_image_src($style_image)) ? wp_get_attachment_image_src($style_image) : get_option('wpgv_demoimageurl');
	} else {
		$voucher_style = $setting_options->voucher_style;
		$style_image = get_post_meta($itemid, 'style1_image', true);
		$image_attributes = get_attached_file( $style_image );
		$image = ($image_attributes) ? $image_attributes : get_option('wpgv_demoimageurl');
		$stripeimage = (wp_get_attachment_image_src($style_image)) ? wp_get_attachment_image_src($style_image) : get_option('wpgv_demoimageurl');
	}

	switch ($voucher_style) {
		case 0:
			require_once( WPGIFT__PLUGIN_DIR .'/templates/pdfstyles/style1.php');
        	break;
		case 1:
	    	require_once( WPGIFT__PLUGIN_DIR .'/templates/pdfstyles/style2.php');
    	    break;
		case 2:
	    	require_once( WPGIFT__PLUGIN_DIR .'/templates/pdfstyles/style3.php');
    	    break;
		default:
	    	require_once( WPGIFT__PLUGIN_DIR .'/templates/pdfstyles/style1.php');
    	    break;
	}

	if($wpgv_enable_pdf_saving) {
		$pdf->Output($upload_dir,'F');
	} else {
		$pdf->Output('F',$upload_dir);
	}

	$wpdb->insert(
		$voucher_table,
		array(
			'order_type'		=> 'items',
			'itemcat_id' 		=> $catid,
			'item_id' 			=> $itemid,
			'buying_for'		=> $buyingfor,
			'from_name' 		=> $for,
			'to_name' 			=> $from,
			'amount'			=> $value,
			'message'			=> $message,
			'shipping_type'		=> $shipping,
			'shipping_email'	=> $shipping_email,
			'firstname'			=> $firstname,
			'lastname'			=> $lastname,
			'email'				=> $receipt_email,
			'address'			=> $address,
			'postcode'			=> $pincode,
			'shipping_method'	=> $shipping_method,
			'pay_method'		=> $paymentmethod,
			'expiry'			=> $expiry,
			'couponcode'		=> $code,
			'voucherpdf_link'	=> $upload_url,
			'voucheradd_time'	=> current_time( 'mysql' ),
			'payment_status'	=> 'Not Pay'
		)
	);
	
	$lastid = $wpdb->insert_id;
	WPGV_Gift_Voucher_Activity::record( $lastid, 'create', '', 'Voucher ordered by '.$for.', Message: '.$message );

	//Customer Receipt
	if($wpgv_customer_receipt) {
		$email = $receipt_email;
		$upload_dir = $upload['basedir'];
		$receiptupload_dir = $upload_dir . '/voucherpdfuploads/'.$curr_time.$_POST['couponcode'].'-receipt.pdf';
		require_once( WPGIFT__PLUGIN_DIR .'/templates/pdfstyles/receipt.php');
		if($wpgv_enable_pdf_saving) {
			$receipt->Output($receiptupload_dir,'F');
		} else {
			$receipt->Output('F',$receiptupload_dir);
		}
	}

    $preshipping_methods = explode(',', $setting_options->shipping_method);
    foreach ($preshipping_methods as $method) {
        $preshipping_method = explode(':', $method);
        if(trim($preshipping_method[1]) == $shipping_method) {
        	$value += trim($preshipping_method[0]);
        	break;
        }
    }
	$currency = wpgv_price_format($value);

	$success_url = get_site_url() .'/voucher-payment-successful/?voucheritem='.$lastid;
	$cancel_url = get_site_url() .'/voucher-payment-cancel/?voucheritem='.$lastid;
	$notify_url = get_site_url() .'/voucher-payment-successful/?voucheritem='.$lastid;

	if ($paymentmethod == 'Paypal') {

		$querystring = '<div class="wpgvmodaloverlay"><div class="wpgvmodalcontent"><h4>'.get_the_title($itemid).'</h4><div id="paypal-button-container"></div></div></div>';

		$querystring .= '<script>
        	// Render the PayPal button into #paypal-button-container
        	paypal.Buttons({
				// Set up the transaction
            	createOrder: function(data, actions) {
	                return actions.order.create({
    	                purchase_units: [{
        	                amount: {
            	                value: "'.$value.'"
	                        }
    	                }]
        	        });
            	},

	            // Finalize the transaction
    	        onApprove: function(data, actions) {
        	        return actions.order.capture().then(function(details) {
            	        console.log(details);
            	        window.location.replace("'.get_site_url() .'/voucher-payment-successful/?voucheritem='.$lastid.'&paymentID="+details.id);
                	});
            	}

	        }).render("#paypal-button-container");
		</script>';

	    echo $querystring;
		
	} elseif($paymentmethod == 'Sofort') {

		$Sofortueberweisung = new Sofortueberweisung($setting_options->sofort_configure_key);

		$Sofortueberweisung->setAmount($value);
		$Sofortueberweisung->setCurrencyCode($setting_options->currency_code);

		$Sofortueberweisung->setReason($setting_options->reason_for_payment, $lastid);
		$Sofortueberweisung->setSuccessUrl($success_url, true);
		$Sofortueberweisung->setAbortUrl($cancel_url);
		$Sofortueberweisung->setNotificationUrl($notify_url);

		$Sofortueberweisung->sendRequest();

		if($Sofortueberweisung->isError()) {
			//SOFORT-API didn't accept the data
			echo $Sofortueberweisung->getError();
		} else {
			//buyer must be redirected to $paymentUrl else payment cannot be successfully completed!
			$paymentUrl = $Sofortueberweisung->getPaymentUrl();
			echo $paymentUrl;
		}
	} elseif ($paymentmethod == 'Stripe') {
		$stripesuccesspageurl = get_option('wpgv_stripesuccesspage');

    	//set api key
    	$stripe = array(
      		"publishable_key" => $setting_options->stripe_publishable_key,
      		"secret_key"      => $setting_options->stripe_secret_key,
    	);
        
        $camount = ($value)*100;
        $stripeemail = ($receipt_email) ? $receipt_email : $shipping_email;

    	\Stripe\Stripe::setApiKey($stripe['secret_key']);

    	$session = \Stripe\Checkout\Session::create([
  			'payment_method_types' => ['card'],
  			'line_items' => [[
    			'name' => get_the_title($itemid),
    			'images' => [$stripeimage],
    			'amount' => $camount,
    			'currency' => $setting_options->currency_code,
    			'quantity' => 1,
  			]],
  			'success_url' => get_page_link($stripesuccesspageurl) . '/?voucheritem='.$lastid.'&sessionid={CHECKOUT_SESSION_ID}',
  			'cancel_url' => $cancel_url,
		]);

		$stripesuccesspageurl = get_option('wpgv_stripesuccesspage');
		$stripeemail = ($email) ? $email : $shipping_email;
		echo '<script type="text/javascript">
    			var stripe = Stripe("'.$stripe['publishable_key'].'");
    			stripe.redirectToCheckout({
			    	sessionId: "'.$session["id"].'"
    			}).then(function (result) {
    				console.log(result.error.message);
    			});
  			</script>';
	} elseif($paymentmethod == 'Per Invoice') {
		echo $success_url.'&per_invoice=1';
	}

	wp_die();
}
add_action('wp_ajax_nopriv_wpgv_doajax_item_pdf_save_func', 'wpgv__doajax_item_pdf_save_func');
add_action('wp_ajax_wpgv_doajax_item_pdf_save_func', 'wpgv__doajax_item_pdf_save_func');