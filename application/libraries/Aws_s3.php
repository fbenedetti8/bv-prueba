<?php

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class Aws_s3 {

    // ****************************************************************//
    // S3 - Simple Storage Service
		// ****************************************************************//
		protected $AWS_VERSION;
		protected $AWS_REGION;
		protected $AWS_ACCESS_KEY_ID;
		protected $AWS_SECRET_KEY;
		protected $AWS_BUCKET_NAME;
		protected $AWS_ACL;
		protected $S3;

		public function __construct()
    {
				$CI = $this->CI = &get_instance();
				$this->AWS_VERSION = $CI->config->item('AWSVersion'); 
				$this->AWS_REGION = $CI->config->item('AWSRegion');
				$this->AWS_ACCESS_KEY_ID = $CI->config->item('AWSAccessKeyId');
				$this->AWS_SECRET_KEY = $CI->config->item('AWSSecretKey');
				$this->AWS_BUCKET_NAME = $CI->config->item('AWSBucket');
				$this->AWS_ACL = $CI->config->item('AWSAcl');
				$this->S3 = $this->S3Client();
		}

    function S3Client(){
        $s3 = new S3Client([
            'version' => $this->AWS_VERSION,
            'region'  => $this->AWS_REGION,
            'credentials' => [
                'key'    => $this->AWS_ACCESS_KEY_ID,
                'secret' => $this->AWS_SECRET_KEY,
            ]
				]);
        return $s3;
    }

		/*
    function send_files_to_s3($folder, $bucket){
        $CI =& get_instance();
        $CI->load->model('S3_model', 's3');
        $s3 = $this->S3Client();
                
        try {

            $files = scandir($folder);
            
            foreach($files as $file) {
                if($file != '.' && $file != '..') {
                    
                    $object_info = array(
                        'Bucket' => $bucket,
                        'Key' => $folder . $file,
                        'SourceFile' => $folder . $file,
                        'StorageClass' => $CI->config->item('aws_storage_class'),
                        'ACL' => $CI->config->item('aws_acl')
                    );

                    $result = $s3->putObject($object_info);
                }
            }
            return true;

        } catch (S3Exception $e) {
            // Catch an S3 specific exception.
            $CI->s3->insert_error_file($bucket, $folder, $e->getMessage());
            return false;
        }

    }*/

    function send_file_to_s3($local_path_file, $bucket_path_file){

			try {
					$result = $this->S3->putObject(
							array(
									'Bucket' => $this->AWS_BUCKET_NAME,
									'Key' => $bucket_path_file,
									'SourceFile' => $local_path_file,
									'ContentLength' => filesize($local_path_file),
									'StorageClass' => 'REDUCED_REDUNDANCY', /* STANDARD */
									'ACL' => $this->AWS_ACL
									/*'ContentType' => 'img/png'*/
							)
					);

					$data = array(
						'filename' => $local_path_file,
						'status' => $result["@metadata"]["statusCode"],
						'url' => $result['ObjectURL']
					);

					return $data;

      } catch (S3Exception $e) {
					return false;
      }

    }

    function delete_file_from_s3($bucket, $key){
        $CI =& get_instance();
        $CI->load->model('S3_model', 's3');
        $s3 = $this->S3Client();

        try {

            $s3->deleteObject(array(
                'Bucket' => $bucket,
                'Key' => $key
            ));

            return true;

        } catch (S3Exception $e) {
            // Catch an S3 specific exception.
            $CI->s3->insert_error_file($bucket, $key, $e->getMessage());
            return false;
        }

    }

    function get_s3_object($filename){
				
				$result = false;

				try {
						$result = $this->S3->getObject(
								array(
										'Bucket' => $this->AWS_BUCKET_NAME,
										'Key' => $filename
								)
						);

				} catch (Exception $ex) {
						$result = false;
				}

				return $result;

    }

    function sync_folders_with_s3($bucket, $path){
        $CI =& get_instance();
        $CI->load->model('S3_model', 's3');
        $s3 = $this->S3Client();

        try {
            $options = array(
                'params'      => array('ACL' => 'public-read'),
                'concurrency' => 20,
                'debug'       => true,
                'allow_resumable' => true
            );
            $s3->uploadDirectory($path, $bucket, 'sites/default/files/', $options);
            return true;

        } catch (S3Exception $e) {
            // Catch an S3 specific exception.
            $CI->s3->insert_error_file($bucket, $path, $e->getMessage());
            return false;
        }

    }



}

?>
