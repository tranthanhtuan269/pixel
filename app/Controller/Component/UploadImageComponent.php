<?php
	App::uses('File', 'Utility');
	App::uses('Folder', 'Utility');

	/*
	 * @Name UploadImageComponent
	 * @Auth TuanHA
	 *
	 */
	class UploadImageComponent extends Component {
		
		public $dir = '';
		public $allowed_ext = array('.jpg', '.jpeg', '.png', '.gif');
		public $max_size = 2097152;
		public $thumbsizes = array();
		public $result = '';
		
		
		public function __construct(ComponentCollection $collection, $settings = array()) {
			parent::__construct($collection, $settings);
		}
		
		
		public function upload($file = null, $options = array()) {
			if(isset($options['dir'])) {
				$this->dir = $options['dir'];
			}
			if(isset($options['allowed_ext'])) {
				$this->allowed_ext = $options['allowed_ext'];
			}
			if(isset($options['max_size'])) {
				$this->max_size = $options['max_size'];
			}
			if(isset($options['thumbsizes'])) {
				$this->thumbsizes = $options['thumbsizes'];
			}
			
			/*if(!mkdir($this->dir, 0777)) {
				trigger_error('UploadComponent Error: The directory ' . $this->dir . ' does not exist and cannot be created.', E_USER_WARNING);
				return false;
			}*/
			
			if($file != null && $file['name'] != '') {
				$fileArr = $this->splitFilenameAndExt($file['name']);
				$fileName = $fileArr[0] . time() . '.' . $fileArr[1];
				$saveAs = $this->dir . DS . $fileName;
				if(!move_uploaded_file($file['tmp_name'], $saveAs)){
				
				} else {
					$this->result = $fileName;
					// It the file is an image, try to make the thumbnails
					if (count($this->allowed_ext) > 0 && in_array($file['type'], array('image/jpeg', 'image/pjpeg', 'image/png'))) {
						foreach ($this->thumbsizes as $key => $value) {
							$thumbSaveAs = $this->dir . DS . 'thumb.' . $key . '.' . $fileName;
							$this->createthumb($saveAs, $thumbSaveAs, $value['width'], $value['height']);
						}
					}
					return $fileName;
				}
			}
			return '';
		}
		
		
		/*
		 * Delete the $filename inside the $this->dir and the thumbnails.
		 * Returns true if the file is deleted and false otherwise.
		 * @author TuanHA
		 * @return boolean
		 */
		public function deleteFiles($dir = null, $filename = null) {
			if($dir == null) {
				$dir = $this->dir;
			}
			if($filename == null) {
				$filename = $this->result;
			}
			$saveAs = $dir . DS . $filename;
			if(is_file($saveAs) && !unlink($saveAs)) {
				return false;
			}
			$folder = new Folder($dir);
			$files = $folder->find('thumb\.[a-zA-Z0-9]+\.' . $filename);
			foreach($files as $f) 
				unlink($dir . DS . $f);
			return true;
		}
		
		
		/*
		 * Removes the bad characters from the $filename and replace reserved words. It updates the $model->data. 
		 * @author TuanHA
		 * @return string
		 * @param $fileName String
		 */
		function fixName($fileName){
			// updates the filename removing the keywords thumb and default name for the field.
			$fileArr = $this->splitFilenameAndExt($fileName);
			$i = 0;
			while(file_exists($this->dir . DS . $fileArr[0] . '.' . $fileArr[1])){
				$fileArr[0] = $fileArr[0] . $i;
				$i++;
			}
			return $fileArr[0] . '.' . $fileArr[1];
		}
		
		
		function splitFilenameAndExt($filename){
			$parts = explode('.',$filename);
			$ext = $parts[count($parts)-1];
			unset($parts[count($parts)-1]);
			$filename = implode('.',$parts);
			return array($filename, $ext);
		}
		
		
		function createthumb($name, $filename, $new_w, $new_h) {
			App::import('Component', 'Thumb');
			
			/*$system = explode(".", $name);
		   
			if (preg_match("/jpg|jpeg/", $system[1])) {
				$src_img = imagecreatefromjpeg($name);
			}
			 
			if (preg_match("/png/", $system[1])) {
			   $src_img = imagecreatefrompng($name);
			}*/
			
			$size = getimagesize($name);
			if (isset($size[2]) && $size[2] == 1) {
				$src_img = imagecreatefromgif($name);
			} else if (isset($size[2]) && $size[2] == 3) {
				$src_img = imagecreatefrompng($name);
			} else if (isset($size[2]) && $size[2] == 2) {
				$src_img = imagecreatefromjpeg($name);
			} else {
				return false;
			}
		  
			$old_x = imagesx($src_img);
			$old_y = imagesy($src_img);
		  
			if ($old_x >= $old_y) {
				$thumb_w = $new_w;
				$ratio = $old_y / $old_x;
				$thumb_h = floor($ratio * $new_w);
			} else if ($old_x < $old_y) {
				$thumb_h = $new_h;
				$ratio = $old_x / $old_y;
				$thumb_w = floor($ratio * $new_h);
			}

			$dst_img = imagecreatetruecolor($thumb_w, $thumb_h);
			imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);
		  
			if (isset($size[2]) && $size[2] == 3) {
				imagepng($dst_img, $filename);
			} else if (isset($size[2]) && $size[2] == 1) {
				imagegif($dst_img, $filename);
			} else {
				imagejpeg($dst_img, $filename);
			}

			imagedestroy($dst_img);
			imagedestroy($src_img);
		}
		
		
		function createAndroid() {
		
		}
		
		public function sizeToBytes($size){
			if(is_numeric($size)) return $size;
			if(!preg_match('/^[1-9][0-9]* (kb|mb|gb|tb)$/i', $size)){
				trigger_error('UploadFileComponent Error: The max_size option format is invalid.', E_USER_ERROR);
				return 0;
			}
			list($size, $unit) = explode(' ',$size);
			if(strtolower($unit) == 'kb') return $size*1024;
			if(strtolower($unit) == 'mb') return $size*1048576;
			if(strtolower($unit) == 'gb') return $size*1073741824;
			if(strtolower($unit) == 'tb') return $size*1099511627776;
			trigger_error('MeioUploadBehavior Error: The max_size unit is invalid.', E_USER_ERROR);
			return 0;
		}
	
	}
?>
