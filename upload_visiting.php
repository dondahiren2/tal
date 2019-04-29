<?php
	include 'config.php';
	require_once 'S3.php';
	date_default_timezone_set('Asia/Kolkata');
	$sdate = date("YmdHis");
	$types = $_POST['type'];
	$tal_id = $_POST['tal_ids'];
	$parts = $_POST['parts'];
	$NameOfFile = $_POST['NameOfFile'];
	$file_name = $_POST['file_name'];
	
	
	$target_file = 'uploads/'.$types.'/'.$tal_id.'/'.$parts.'/';	
	if(file_exists($target_file)){
	} else {
		mkdir($target_file,0755, true);
	}

	// comment by kishan
	
	$image_name  = $_FILES[$file_name]['name'];
	$tmp_name	 = $_FILES[$file_name]['tmp_name'];
	// comment by testing
	$ans = '';
	$ext = '';
	$image_path = ''; 
	$c = count($image_name);
	$allowed =  array('jpg','jpeg','pdf'); 
	for($i=0;$i<$c;$i++){
		$i_name		 = $_FILES[$file_name]['name'][$i];
		$size 		 = $_FILES[$file_name]['size'][$i];
		$type 		 = $_FILES[$file_name]['type'][$i];
		$tmp_name	 = $_FILES[$file_name]['tmp_name'][$i];
		 
		$ext = strtolower(pathinfo($i_name, PATHINFO_EXTENSION));
		
		$file = basename($NameOfFile.'_'.$sdate.$i.'.'.$ext);
		$target_filename = $target_file.$file;
		if(in_array($ext,$allowed)){
			if(move_uploaded_file($tmp_name, $target_filename)){
				$s3 = new S3(awsAccessKey, awsSecretKey);
				if($s3->putObjectFile($target_filename, bucket, $target_filename, S3::ACL_PUBLIC_READ)){
					unlink($target_filename);
					$image_path .= $target_filename.'splits';
					$ans = 1;
				}else{
					$ans = 2;
				}
			} else {
				$ans = 2;
			}
		} else {
			$ans = 3;
		}
	} 
	
?>