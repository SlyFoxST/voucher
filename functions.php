<?php 
add_action( 'wp_ajax_misha', 'test_function' ); // wp_ajax_{ЗНАЧЕНИЕ ПАРАМЕТРА ACTION!!}
add_action( 'wp_ajax_nopriv_misha', 'test_function' );  // wp_ajax_nopriv_{ЗНАЧЕНИЕ ACTION!!}
// первый хук для авторизованных, второй для не авторизованных пользователей
 
function test_function(){
 
	$summa = $_POST['param1'] + $_POST['param2'];
	echo $summa;
 
	die; // даём понять, что обработчик закончил выполнение
}


?>