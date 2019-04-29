<?php
	include 'config.php';
	//require_once 'S3.php';
	include 's3/vendor/autoload.php';
	use Aws\S3\S3Client;
	use Aws\S3\Exception\S3Exception;
	// AWS Info
	$bucketName = bucket;
	$IAM_KEY = awsAccessKey;
	$IAM_SECRET = awsSecretKey;
// ohh this is new s3 sample // not this
	//hollaaaaaaaaaaaa
	date_default_timezone_set('Asia/Kolkata');
	$sdate = date("YmdHis");
	$types = $_POST['type'];
	$tal_id = $_POST['tal_ids'];
	$parts = $_POST['parts'];
	$NameOfFile = $_POST['NameOfFile'];
	$file_name = $_POST['file_name'];
	$unique_id = $_POST['unique_id'];
	$folder_type = $_POST['folder_type'];
	$department = $_POST['department'];
	
	if($folder_type == 'personal' && $department == 'Team'){
		$target_file = 'uploads/'.$types.'/'.$tal_id.'/'.$department.'/'.$unique_id.'/'.$parts.'/';
	} else {
		$target_file = 'uploads/'.$types.'/'.$tal_id.'/'.$parts.'/';
	}
	
 
	if(file_exists($target_file)){
	} else {
		mkdir($target_file,0755, true);
	}
  	
 
	$image_name  = $_FILES[$file_name]['name'];
	$size 		 = $_FILES[$file_name]['size'];
	$type 		 = $_FILES[$file_name]['type'];
	$tmp_name	 = $_FILES[$file_name]['tmp_name'];

	$allowed =  array('jpg','jpeg','pdf','mp4');
	$ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
	$file = basename($NameOfFile.'_'.$sdate.'.'.$ext);
	$target_filename = $target_file.$file;
  
	if(in_array($ext,$allowed)){ 
		try { 
			$s3 = S3Client::factory(
				array(
					'credentials' => array(
						'key' => awsAccessKey,
						'secret' => awsSecretKey
					),
					'version' => 'latest',
					'region'  => region
				)
			);
		} catch (Exception $e) { 
			echo 2;
		}

		// Add it to S3
		try { 
			if($s3->putObject(
				array(
					'Bucket'=> bucket,
					'Key' =>  $target_filename,
					'SourceFile' => $tmp_name, 
		    	    'ACL'    => 'public-read'
				)
			)){
				echo $target_filename;
			}
			
		} catch (S3Exception $e) { 
			echo 3;
		} catch (Exception $e) { 
			echo 3;
		}

		/*if(move_uploaded_file($tmp_name, $target_filename)){
			$s3 = new S3(awsAccessKey, awsSecretKey);
			if($s3->putObjectFile($target_filename, bucket, $target_filename, S3::ACL_PUBLIC_READ)){
				unlink($target_filename);
				echo $target_filename.'splits'.$ext;
			}else{
				echo 2;
			}
		} else {
			echo 2;
		}*/

	} else {
		echo 4;
	}
	
?>