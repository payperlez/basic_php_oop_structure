<?php
	/*
	 * PHP File uploading Class
	 *
	 * @author Desmond Evans - iamdesmondjr@gmail.com http://www.iamdesmondjr.com
	 * @version 1.0
	 * @date July 26, 2019
	 */

	@session_start();
	// require_once('DAL.php');

	class FileUploader{

		private $_fileName;
		private $_fileSize;
		private $_fileTmp;
		private $_fileType;
		private $_fileExt;
		private $_extensions;
		private $_destination;
		
		function __construct($_fileName, $_fileSize, $_fileTmp, $_fileType, $_fileExt, $_destination){
			# code...
			// parent::__construct();
			$this->_fileName = $_fileName;
			$this->_fileSize = $_fileSize;
			$this->_fileTmp = $_fileTmp;
			$this->_fileType = $_fileType;
			$this->_fileExt = $_fileExt;
			$this->_destination = $_destination;
		}

		public function uploader(){
			$this->extensions();
			if (in_array($this->_fileExt, $this->_extensions) == false) {
				return $this->alert = $this->alert('please check file extention to be: jpeg, jpg, png, gif', 'error');
			}else{
				if (empty($this->alert) == true) {
					# code...
					if ($this->_fileSize <= 2097152) {
						# code...
						$this->move();
						//return $this->alert = true;
					}else{
						return $this->alert = false;
					}

				}else{
					return $this->alert = false;	
				}
			}
		}

		public function extensions(){
			return $this->_extensions = (['jpeg', 'jpg', 'png', 'gif']);
		}

		public function move(){
			$this->_move = move_uploaded_file($this->_fileTmp, $this->_destination.$this->_fileName);
			
			if ($this->_move) {
				return $this->alert = true;
			}else{
				return $this->alert = false;
			}
		}
	}

?>