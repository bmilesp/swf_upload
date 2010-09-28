<?php 

class SwfUploadController extends SwfUploadAppController{
	
	var $uses = array();
	var $helpers = array('Javascript','Js'=> array('Jquery'));
	
	
	function index(){
			
	}
	
	function upload(){
		$this->autoRender = false;
		echo $this->params['form']['Filedata']['name']." Upload Complete<br>";
	}
	
}

?>