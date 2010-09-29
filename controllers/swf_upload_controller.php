<?php 

/* for demo purposes */

class SwfUploadController extends SwfUploadAppController{
	
	var $uses = array();
	var $helpers = array('Javascript','SwfUpload.SwfUpload');
		
	function index(){}
	
	function upload(){
		$this->autoRender = false;
		echo $this->params['form']['Filedata']['name']." Upload Complete<br>";
	}
	
}

?>