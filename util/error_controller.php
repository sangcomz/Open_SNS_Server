<?php
function error($msg) {
	$array = array (
			'stat' => 0,
			'response' => array (
					'Error' => $msg 
			) 
	);
	header ( 'Content-type: application/json' );
	echo json_encode ( $array );
	return;
}
?>