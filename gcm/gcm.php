<?php
header ( "Content-Type: text/html; charset=utf8" );
// error_reporting(E_ALL);
// ini_set("display_errors", 1);

require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/get_db_info.php"; // root!!
                                                                          
function commentGCM($conn, $post_writer_member_srl, $comment_writer_member_srl, $post_srl, $comment_srl, $comment_content) {
	// Replace with the real server API key from Google APIs
	$apiKey = "Your Api Key";
	$regid = get_member_device_token ( $conn, $post_writer_member_srl );
	
	// Replace with the real client registration IDs
	$registrationIDs = array (
			$regid 
	);
	
	// Message to be sent
	$message = iconv ( "EUC-KR", "UTF-8", "App TEST!!" );
	
	// Set POST variables
	$url = 'https://android.googleapis.com/gcm/send';
	
	$fields = array (
			'registration_ids' => $registrationIDs,
			'data' => array (
					"push_code" => "push_comment",
					"post_srl" => $post_srl,
					"comment_srl" => $comment_srl,
					"comment_content" => $comment_content,
					'post_writer_member' => get_member ( $conn, $post_writer_member_srl ),
					'comment_writer_member' => get_member ( $conn, $comment_writer_member_srl ) 
			) 
	);
	$headers = array (
			'Authorization: key=' . $apiKey,
			'Content-Type: application/json' 
	);
	
	// Open connection
	$ch = curl_init ();
	
	// Set the URL, number of POST vars, POST data
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_POST, true );
	curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
	// curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields));
	
	curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
	// curl_setopt($ch, CURLOPT_POST, true);
	// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, json_encode ( $fields ) );
	
	// Execute post
	$result = curl_exec ( $ch );
	
	// Close connection
	curl_close ( $ch );
	echo $result;
}
?>
