<?php

if( !defined( 'ABSPATH' ) ) exit;  // Exit if accessed directly

global $wpdb;
$setting_table 	= $wpdb->prefix . 'giftvouchers_setting';
$setting_options = $wpdb->get_row( "SELECT * FROM $setting_table WHERE id = 1" );
//print_r($setting_options);
$items = isset($_GET['items']) ? $_GET['items'] : '';
$voucher_code = isset($_GET['voucher_code']) ? $_GET['voucher_code'] : '';
?>


<div class="wrap voucher-page">
	<h1><?php echo __( 'Voucher Table Orders', 'gift-voucher' ) ?></h1><br>
	<div class="content">
		<?php

		$sql = "SELECT * FROM {$wpdb->prefix}giftvouchers_list ORDER BY `id` DESC";
		$orders = $wpdb->get_results( $sql, ARRAY_A );
		//print_r($orders);
		$columns = array();
		$amount = 0;
		foreach($orders as $row) {
			$columns[] = $row['id'];
			$amount += $row['amount'];
		}
		do_wpgv_check_voucher_status();
		$num_fields = count($columns);
		if ( $num_fields > 0 ) { ?>
		<div class="search-voucher">		
			<form action="<?php echo admin_url( 'edit.php' ); ?>">
				<input type="hidden" name="post_type" value="wpgv_voucher_product">
				<input type="hidden" name="page" value="vouchers-lists">
				<?php if($items): ?><input type="hidden" name="items" value="1"><?php endif; ?>
				<input type="hidden" name="search" value="1">
				<input type="text" name="voucher_code" autocomplete="off" placeholder="Search by Gift voucher code" value="<?php echo $voucher_code ?>" style="width: 400px; height: 40px">
				<input type="submit" class="button button-primary search-coupon" value="Search">
			</form>
		</div>
		<?php } ?>
		<div id="post-body" class="metabox-holder">
			<div id="">
				<div class="meta-box-sortables ui-sortable">
					<div class="form-vaucher-amn">
						<div class="flex table-thead">
							<div class="th-thead th-name width-80">Name</div>
							<div class="th-thead th-email width-160">Email</div>
							<div class="th-thead th-date-reg width-160" >Date/Time</div>
							<div class="th-thead th-cart-amount width-80">Gift cart amount</div>
							<div class="th-thead th-remaining-amount width-80" style="color: #961208">Remaining amount</div>
							<div class="th-thead th-service-amount width-160" >Service amount</div>
							<div class="th-thead th-previous-date width-160" >Time & Date of previous Redeem</div>
							<div class="th-thead th-coupon width-160">Coupon</div>

						</div>
						
						<?php
						foreach ($orders as $key) {?>
						<div class="flex table-tbody-amnout">

							<div class="tr-tbody name-table width-80">
								<div class="label-name label-mobile-table">Name:</div>
								<div class="for-name">
									<a href="edit.php?post_type=wpgv_voucher_product&page=vouchers-lists&search=<?php echo $key['id'];?>&voucher_code=<?php echo $key['couponcode'];?>"><?php echo $key['from_name']; ?>		
									</a>
								</div>
							</div>
							<div class="tr-tbody for-email width-160">
								<div class="label-email label-mobile-table">Name:</div>
								<div class="data-info"><?php echo $key['email']; ?></div>
							</div>
							<div class="tr-tbody for-date width-160" >
								<div class="data-info"><?php echo $key['voucheradd_time']; ?></div>
							</div>
							<div class="tr-tbody for-amount width-80"  >
								<div class="label-amnout label-mobile-table">Gift cart amount:</div>
								<div class="sum-amnout data-info" data-id="<?php echo $key['id'];?>">$<span><?php echo $key['amount']; ?></span></div>
							</div>
							<div class="tr-tbody for-count width-80" >
								<div class="label-remaining-count label-mobile-table">Remaining amount:</div>
								<div class="data-info">
									<input type="text" class="remaining-count" data-count="<?php echo $key['id'];?>" disabled style="width: 60px" value="<?php echo $key['remaining_amount']?>"/>	
								</div>
							</div>
							<div class="tr-tbody for-remaining-amount width-160" >
								<div class="label-amnout label-mobile-table">Service amount:</div>
				
								<div class="data-info">
									
									<div class="tr-tbody-amnout">
										<input type="number" name="remaining_aumnout" class="remaining-amount" data-input="<?php echo $key['id'];?>" />
										<input type="button" value="update" class="btn-remaining" data-atr="<?php echo $key['id'];?>" >
									</div>
								</div>
							</div>

							<div class="tr-tbody for-date" data-id="<?php echo $key['id'];?>" style="width: 160px" >

								<div class="data-info">									
									<?php echo $key['remaining_date'];?>
								</div>
								
							</div>
							<div class="tr-tbody for-couponcode width-160">
								<div class="label-coupon label-mobile-table">Coupon:</div>
								<div class="data-info"><?php echo $key['couponcode'];?></div>
							</div>

						</div>
						<?php

					}

					?>
					
				</div>
			</div>
		</div>
	</div>
</div>


<?php
$num_fields = count($columns);
if ( $num_fields > 0 ) { ?>
<!-- <div class="total-unused">
	<div class="count"><span><?php //echo $num_fields ?></span><?php// echo __('Unused Gift Vouchers', 'gift-voucher'); ?></div>
	<div class="amount"><span><?php// echo wpgv_price_format($amount) ?></span><?php //echo __('Total Unused Voucher Amount', 'gift-voucher'); ?></div>

</div> -->
<?php } ?>

</div>
<script type="text/javascript">

	jQuery(function($){

		var today = new Date();
		var d = String(today.getDate()).padStart(2, '0');
        var m = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var y = today.getFullYear();
        var t = today.getTime();
        var time = today.getHours()+':'+today.getMinutes()+':'+today.getSeconds();
        
        today = y + '-' + m + '-' + d + ' ' + time;
        console.log(today);



        $('.btn-remaining').click(function(){
				/*	$(".sum-amnout" ).each(function( index ) {
				if(id == $(this).attr('data-id')){
					console.log("yes");
				}
			})*/;

			var id = $(this).attr('data-atr');
			

			$('.remaining-count').each(function(index){

				if($(this).attr('data-count') == id){
					var count = $(this).val();
					// console.log(count);
					$('.remaining-amount').each(function(index){						
						if($(this).attr('data-input') == id && count != ''){
							var srvice = $(this).val();

							$.ajax({
								url: '<?php echo admin_url("admin-ajax.php") ?>',
								type: 'POST',
								data: {
									action: 'calc',
									count: count,
									srvice: srvice,
									today: today,
									id: id
								},
								success: function( data ) {
									$(".remaining-count").each(function(index){
										if($(this).attr('data-count') == id){
											$(this).val(data);
											$('.for-date').each(function(){
												if($(this).attr('data-id') == id){
													$(this).text(today);
												}
											});
											
										}
									});

								}
							});
						}
						else if(count == ''){
							$(".sum-amnout").each(function(index){
								var data_id = $(this).attr("data-id");

								if(data_id == id){
									var count = $(this).text().replace(/[^0-9 ]/g, "");
									$('.remaining-amount').each(function(index){
										var srvice = $(this).val();
										if($(this).attr('data-input') == id && srvice != ''){

											$.ajax({
												url: '<?php echo admin_url("admin-ajax.php") ?>',
												type: 'POST',
												data: {
													action: 'calc',
													count: count,
													srvice: srvice,
													today: today,
													id: id
												},
												success: function( data ) {
							//console.log(id);
							$(".remaining-count").each(function(index){
								if($(this).attr('data-count') == id){
									$(this).val(data);
									$('.for-date').each(function(){
										if($(this).attr('data-id') == id){
											$(this).text(today);
										}
									});
								}
							});


									//console.log(id);
									//res.val(data);

								}
							});
										};

									});

								}
							});
						}
					});

				}

			});


		// если элемент – ссылка, то не забываем:
		// return false;
	});
    });
</script>

