<?php
	echo $html->css('/swf_upload/css/swfupload',null,array(),false);
	echo $javascript->link('/swf_upload/js/swf_upload/swfupload.js',false);
	echo $javascript->link('/swf_upload/js/swf_upload/multiinstance/fileprogress.js',false);
	echo $javascript->link('/swf_upload/js/swf_upload/multiinstance/handlers.js',false);
	echo $javascript->link('/swf_upload/js/swf_upload/multiinstance/swfupload.queue.js',false);

	$uploadUrl = Router::url(array('action'=>'upload'));
	$uploadButtonUrl = Router::url('/swf_upload/img/Upload-File-Button.gif');
	$swfUrl = Router::url('/swf_upload/js/swf_upload/Flash/swfupload.swf');
	
	$js_code_vars = "

		//swfupload var:
		var suo;
	
	";

 echo $javascript->codeBlock($js_code_vars, array('inline'=>false));
 
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
				file_size_limit : '102400',	// 100MB
				file_types : '*.*',
				file_types_description : 'All Files',
				file_upload_limit : '20',
				file_queue_limit : '0',

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
				button_placeholder_id : 'spanButtonPlaceholder1',
				button_width: 136,
				button_height: 26,
				button_cursor : SWFUpload.CURSOR.HAND,
				button_window_mode : SWFUpload.WINDOW_MODE.TRANSPARENT, 
				
				// Flash Settings
				flash_url : '{$swfUrl}',
				

				custom_settings : {
					progressTarget : 'uploadList',
					cancelButtonId : 'btnCancel1'
				},
				
				// Debug Settings
				debug: true
			});
", true);
?>
<span id="spanButtonPlaceholder1"></span>
<input id="btnCancel1" type="button" value="Cancel Uploads" onclick="cancelQueue(upload1);"/>
<div id="uploadList"></div>					