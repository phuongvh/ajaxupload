<?php
$BASE_URL = "http://" . $_SERVER['HTTP_HOST']."/ajaxupload/";
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf8">
<title>ajaxupload</title>
<script src="<?=$BASE_URL?>js/jquery-1.7.2.js"></script>
<link rel="stylesheet" href="<?=$BASE_URL?>js/themes/base/jquery.ui.all.css">
<script src="<?=$BASE_URL?>js/external/jquery.bgiframe-2.1.2.js"></script>
<script src="<?=$BASE_URL?>js/ui/jquery.ui.core.js"></script>
<script src="<?=$BASE_URL?>js/ui/jquery.ui.widget.js"></script>
<script src="<?=$BASE_URL?>js/ui/jquery.ui.mouse.js"></script>
<script src="<?=$BASE_URL?>js/ui/jquery.ui.draggable.js"></script>
<script src="<?=$BASE_URL?>js/ui/jquery.ui.position.js"></script>
<script src="<?=$BASE_URL?>js/ui/jquery.ui.resizable.js"></script>
<script src="<?=$BASE_URL?>js/ui/jquery.ui.dialog.js"></script>
<script type="text/javascript">

$(function() {
	   $(window).keypress(function(e) {
	       var key = e.which;
	       if(timeoutShowing){
		       if(key==13 || key==32){
	    	    e.stopPropagation();
				return false;
		       }
	       }
	   });
});


var isUploading = false;
var timeoutShowing = false;
var upload = function(){
        var form = new FormData($('#myform')[0]);
		
        $.ajax({
            url: 'action.php',
            type: 'POST',
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                if(myXhr.upload){
                    myXhr.upload.addEventListener('progress',progress, false);
                }
                return myXhr;
            },
            //add beforesend handler to validate or something
            beforeSend: showProcessing,
            success: function (res) {
            	isUploading = false;
                $('#content_here_please').html(res);
            },

            //error: catchError,
            data: form,
            //timeout:3000,

            cache: false,
            contentType: false,
            processData: false
        });
}

function catchError(xhr, status){
	alert(status);
}

function progress(e){
    if(e.lengthComputable){
        //this makes a nice fancy progress bar
        $('progress').attr({value:e.loaded,max:e.total});
        $('#statusPercent').html(Math.round((e.loaded/e.total)*100) + '%')
    }
}

function openDialogCreateFile(){
	$("#frmCreate").dialog({
		autoOpen : false, 
		modal : true, 
		show : "blind", 
		hide : "blind",
		height: 'auto',
		width:'auto',
		draggable: false,
		resizable: false
			});
	$("#frmCreate").dialog("open");
	
}

function showProcessing(xhr){
	isUploading = true;
	$("#processStatus").dialog({
		autoOpen : false, 
		modal : true, 
		show : "blind", 
		hide : "blind",
		height: 'auto',
		width: 'auto',
		draggable: false,
		resizable: false,
		buttons: [{
			text: "Cancel", 
			click: function() {
				 	$( this ).dialog( "close" ); 
				 	xhr.abort();
				 	isUploading = false;
				 }
		}]
			});
	$("#processStatus").dialog("open");

	setTimeout(function(){
		if(isUploading){
			xhr.abort();
			isUploading = false;
			$("#timeoutDialog").dialog({
				autoOpen : false, 
				modal : true, 
				show : "blind", 
				hide : "blind",
				height: 'auto',
				width: 'auto',
				draggable: false,
				resizable: false,
				close: function() {timeoutShowing = false;},
				buttons: [{
					text: "OK",
					css: {'outline':'none'},
					id: 'closeTimeoutBtn',
					click: function() {$( this ).dialog( "close" );}
					 }]
			});
			$("#timeoutDialog").dialog("open");
			timeoutShowing = true;
		}
	}, 1000);

}

function processTimeout(xhr){
	if(isUploading){
		xhr.abort();
		isUploading = false;
	}
}


</script>
</head>
<body>
<button onclick="openDialogCreateFile()">Tạo file</button>
<form enctype="multipart/form-data" id="myform1" >    
    <input type="text" name="some_usual_form_data" />
    <br>
    <input type="text" name="some_other_usual_form_data" />
    <br>
    <input type="file" multiple="multiple"  name="file[]" id="image" />
    <br>
    <input type="button" value="Upload files" class="upload" onclick="upload()" />
</form>
<span id="statusPercent"></span>
<hr>
<div id="content_here_please"></div>

<div id="frmCreate" style="display: none">
<form enctype="multipart/form-data" id="myform">    
    <input type="text" name="some_usual_form_data" />
    <br>
    <input type="text" name="some_other_usual_form_data" />
    <br>
    <input type="file" multiple="multiple"  name="file[]" id="image" />
    <br>
    <input type="button" value="Upload files" class="upload" onclick="upload()" />
</form>
</div>

<div id="processStatus" style="display: none">
	<div>
	<progress value="0" max="100" style="width: 600px"></progress>
	<span id="statusPercent"></span>
	</div>		
</div>

<div id="timeoutDialog" style="display: none">
Upload timeout !
</div>
</body>
</html>
