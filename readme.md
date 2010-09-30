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

	var $helpers = array('SwfUpload.SwfUpload);

## How to use it ##

Please look at the code in swf_upload/view/swf_upload/index.ctp to see the helper methods demo. the three main methods are:

	$swfUpload->button($options=>array());
	$swfUpload->cancelButton($options=>array());
	$swfUpload->outputTarget($options=>array());
	
The button method generates the swf used for uploading. a mass of options can be viewed in the helper itself in views/helpers/swf_upload.php. The debugMode option that is set by default in the demo displays an detailed output window at the bottom of the page.  

The outputTarget generates a dom element for the progress bars and notices. 

The plugin itself is already capable of:

* Single or multi-file ajax/swf upload. no page refreshes.
* A demo app for out of the box play-ability

## ToDos ##

* add tolkens/sessions for secure uploads
* add option to toggle multi/singe file upload dialog