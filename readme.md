# SWFUpload Helper Plugin for CakePHP 1.3 #

Upload a single or multiple files like a pro using the widely popular SWFUpload package. Your file(s) will smoothly upload and report progress with a progress bar simmilar to gmail's file upload.

A full working example is included and can be viewed at /your_app/swf_upload/swf_upload/.

SWFUpload Documentation  for further development can be found here: http://swfupload.org/documentation/development

## Installation ##

The plugin is pretty easy to set up, There is no database schema. Just add this plugin folder to app/plugins/swf_upload. 

To get it working out of the box, download jquery at http://jquery.com, add it to your app/webroot/js folder, Make sure you include the jQuery library in your layout:  

	<?php echo $this->Html->script('jquery.file.name'); ?>

and add the JsHelper writeBuffer just above the </body> tag:

	<?php echo $js->writeBuffer();?>

Note: you can use any javascript library with this plugin other than jQuery. When you add the helper to your controller add the library name just as you would the Js Helper like so:

	var $helpers = array('SwfUpload.SwfUpload=>array('Mootools'));
	
Otherwise, jQuery is the default library, thus simply include the helper like so:

	var $helpers = array('SwfUpload.SwfUpload');

## How to use it ##

Please look at the code in swf_upload/view/swf_upload/index.ctp to see the helper methods demo. the three main methods are:

		echo $swfUpload->button('uploadUrl' => array('action'=>'your_upload_action'));
	  	echo $swfUpload->cancelButton();
	  	echo $swfUpload->progressTarget();
	
The button method generates the swf used for uploading. a mass of options can be viewed in the helper itself in views/helpers/swf_upload.php. The debugMode option that is set by default in the demo displays an detailed output window at the bottom of the page.  

The progressTarget generates a dom element for the progress bars and notices. 

The plugin itself is already capable of:

* Single or multi-file ajax/swf upload. no page refreshes.
* A demo app for out of the box play-ability

To circumvent the "302" Error due to session validation you can do the following per Adam Royle http://blogs.bigfish.tv/adam/2008/04/01/cakephp-12-sessions-and-swfupload/

Pass the session id as a url param (in this case the first url param) in your view:
	
	echo $swfUpload->button(array('uploadUrl' => array('controller'=>'your_controller','action'=>'your_action',$this->Session->id())));		


and do something like this in your target controller:
	
	var $components = array('Session');
	
	function beforeFilter(){	
		if ($this->params['controller'] == 'your_controller' && $this->params['your_action']== 'add') {
			$this->Session->id($this->params['pass'][0]);
			$this->Session->start();
		}
		parent::beforeFilter();
	}

			

## ToDos ##

* add tokens/sessions for secure uploads (looks like cookies would be sent to swf in swf_upload/webroot/js/swf_upload/plugins/swfupload.cookies.js)
* add option to toggle multi/singe file upload dialog
