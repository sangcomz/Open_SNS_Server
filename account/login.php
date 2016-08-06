<?php 
header("Content-Type: text/html; charset=utf8");

require_once $_SERVER[DOCUMENT_ROOT]."/open_sns/util/error_controller.php";//root!!
require_once $_SERVER[DOCUMENT_ROOT]."/open_sns/util/db_connector.php";//root!!
require_once $_SERVER[DOCUMENT_ROOT]."/open_sns/util/get_db_info.php";//root!!
$conn = db_connector();


$tbl_name="s_member"; // Table name

$member_srl=$_REQUEST['member_srl'];
$member_name=$_REQUEST['member_name'];
$member_pwd=$_REQUEST['member_pwd'];


if($member_srl != ""){

	$sql="SELECT * FROM $tbl_name WHERE member_srl='$member_srl'";
	
	$result=mysqli_query($conn, $sql);
	
	// Mysql_num_row is counting table row
	
	$count=mysqli_num_rows($result);
	
	if ($count>0){
				$row = mysqli_fetch_row($result);
				$array = array(
				  'stat' => 1,
			  'response' => array('member_srl' => $row[0], 'member_name' => $row[1],
			   'member_profile' => $row[3], 'member_profile_bg' => $row[4],
			    'setting_push_on_off' => get_setting_push_on_off($conn, $row[0]),
			     'setting_searchable' => get_setting_searchable($conn, $row[0]))
				);
				
				header('Content-type: application/json');
			  	echo json_encode( $array );
	}else{
		error("no member_srl");
		return;
	}
}else if($member_name != "" && $member_pwd != ""){
	$sql="SELECT * FROM $tbl_name WHERE member_name='$member_name' and member_pwd='$member_pwd'";
	
	$result=mysqli_query($conn, $sql);
	
	// Mysql_num_row is counting table row
	
	$count=mysqli_num_rows($result);
	
	if ($count>0){
			$row = mysqli_fetch_row($result);
			$array = array(
			  'stat' => 1,
			  'response' => array('member_srl' => $row[0], 'member_name' => $row[1],
			   'member_profile' => $row[3], 'member_profile_bg' => $row[4],
			    'setting_push_on_off' => get_setting_push_on_off($conn, $row[0]),
			     'setting_searchable' => get_setting_searchable($conn, $row[0]))
			);
				
				header('Content-type: application/json');
			  	echo json_encode( $array );
	}else{
		error("no member_name or member_pwd");
		return;
	}

}else{
	error("no parameter");
	return;
}










   mysqli_close($conn);
?>