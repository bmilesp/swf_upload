<?php

class SwfUploadHelper extends AppHelper {
	
	
	/**
	 * 
	 * @var helpers
	 */
	var $helpers = array('Html','Javascript');
	
	
	/**
	 * 
	 * @var Js Helper to be initialized in __construct()
	 */
	var $Js = null;
	
	/**
	 * 
	 * @var array  options: see constructor
	 */
	var $options = null;
	
	/**
	 * @private var dom id for use with cancelButton()
	 */
	var $__cancelButtonDomId = null;
	
	
	/**
	 *  @private var dom id for progress indicators for use with progressTarget()
	 */
	var $__progressTargetDomId = null;
	
	/**
	 * if you use another Js library make sure in your controller
	 * you pass the library of choice over to the SwfUpload Helper in your controller 
	 * like so:
	 *  var $helpers = array('Js'=>array('Mootools'), SwfUpload.SwfUpload=>array('Mootools));
	 *  
	 *  else defaults to Jquery library.
	 *  
	 * @param $settings = array eg: array('Jquery'). pass your Js Library of choice, Jquery is default 
	 *  @param $options = array() override params for css and js paths
	 * 
	 */
	function __construct($libSettings = array(),$options = array()){
		$defaultOptions =array(
				'jsDir' => '/swf_upload/js/swf_upload/',
				'cssDir' => '/swf_upload/css/',
				'defaultCssFileName' => 'swfupload'//mixed: if false then do not use pre-packages css
			);
		$this->options = array_merge($defaultOptions,$options);
		$className = 'Jquery';
		if (is_array($libSettings) && isset($libSettings[0])) {
			$className = $libSettings[0];
		} elseif (is_string($libSettings)) {
			$className = $libSettings;
		}
		$this->helpers['Js'] = array($className);
		parent::__construct();
	}
	
	/**
	 *  must have $scripts_for_layout echoed somewhere in your layout for this helper to work
	 */
	function beforeRender(){
		if($this->options['defaultCssFileName']){
			$this->Html->css("{$this->options['cssDir']}{$this->options['defaultCssFileName']}",null,array('inline'=>false));
		}
		$this->Javascript->link("{$this->options['jsDir']}swfupload.js",false);
		$this->Javascript->link("{$this->options['jsDir']}multiinstance/fileprogress.js",false);
		$this->Javascript->link("{$this->options['jsDir']}multiinstance/handlers.js",false);
		$this->Javascript->link("{$this->options['jsDir']}multiinstance/swfupload.queue.js",false);
	}
	
	/**
	 * 
	 * @param $options 'progressTargetDomId' is the Dom element Id you wish to display progress output
	 * 				   'cancelButtonDomId' is internal unless conflicts occur, or if you want to define your own styles
	 * 
	 */
	function button($options = array()){
		
		//see swfUpload documentation for further explanation of options
		$defaultOptions = array(
			'progressTargetDomId' => 'uploadList',
			'cancelButtonDomId' => 'btnCancel1',
			'uploadUrl' => array('action'=>'upload'),
			'uploadButtonUrl' => '/swf_upload/img/Upload-File-Button.gif',
			'swfUrl' => '/swf_upload/js/swf_upload/Flash/swfupload.swf',
			'buttonSpanId' => 'spanButtonPlaceholder1',
			'width' => 136,
			'height' => 26,
			'file_size_limit' => 102400,	// 100MB
			'file_types' => '*.*',
			'file_types_description' => 'All Files',
			'file_upload_limit' => 20,
			'file_queue_limit' => 0,
			'debugMode'=>'false'
		);
		
		$buttonOptions = array_merge($defaultOptions,$options);
		$this->__cancelButtonDomId = $buttonOptions['cancelButtonDomId'];
		$this->__progressTargetDomId = $buttonOptions['progressTargetDomId'];
		
		$uploadUrl = Router::url($buttonOptions['uploadUrl']);
		$uploadButtonUrl = Router::url($buttonOptions['uploadButtonUrl']);
		$swfUrl = Router::url($buttonOptions['swfUrl']);
		
		$js_code_vars = "
			//swfupload var:
			var suo;
		";
 		$this->Javascript->codeBlock($js_code_vars, array('inline'=>false));
 		
 		$this->Js->domReady("	
			///SWFUpload:
			
			// Check to see if SWFUpload is available
			if (typeof(SWFUpload) === 'undefined') {
				return;
			}
			
			// Instantiate a SWFUpload Instance ; post_params can have the session_id
			suo = new SWFUpload({
				// Backend Settings
				upload_url: '{$uploadUrl}',
				//post_params: {},

				// File Upload Settings
				file_size_limit : '{$buttonOptions['file_size_limit']}',	// 100MB
				file_types : '{$buttonOptions['file_types']}',
				file_types_description : '{$buttonOptions['file_types_description']}',
				file_upload_limit : '{$buttonOptions['file_upload_limit']}',
				file_queue_limit : '{$buttonOptions['file_queue_limit']}',

				// Event Handler Settings (all my handlers are in the Handler.js file)
				file_dialog_start_handler : fileDialogStart,
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,
				debug_handler : debugHandler,

				// Button Settings
				button_image_url : '{$uploadButtonUrl}',
				button_placeholder_id : '{$buttonOptions['buttonSpanId']}',
				button_width: {$buttonOptions['width']},
				button_height: {$buttonOptions['height']},
				button_cursor : SWFUpload.CURSOR.HAND,
				button_window_mode : SWFUpload.WINDOW_MODE.TRANSPARENT, 
				
				// Flash Settings
				flash_url : '{$swfUrl}',
				

				custom_settings : {
					progressTarget : '{$buttonOptions['progressTargetDomId']}',
					cancelButtonId : '{$buttonOptions['cancelButtonDomId']}'
				},
				
				// Debug Settings
				debug: {$buttonOptions['debugMode']}
			});
		", true);
		
 		return $this->output("<span id='{$buttonOptions['buttonSpanId']}'></span>");
	}
	
	
	/**
	 * 
	 * @param $options
	 */
	function cancelButton($options = array()){
		
		$defaultOptions = array('domType'=>'input');
		
		$options = array_merge($defaultOptions,$options);
		return $this->output("<{$options['domType']} id='{$this->__cancelButtonDomId}' 
									 type='button' value='Cancel Uploads' 
									 onclick='cancelQueue(suo);' />");
	}
	
	/**
	 * 
	 * @param $options
	 */
	function progressTarget($options = array()){
		
		$defaultOptions = array('type'=>'div');
		
		$options = array_merge($defaultOptions,$options);
		return $this->output("<{$options['type']} id='{$this->__progressTargetDomId}'></{$options['type']}>");
	}
	
 }?>