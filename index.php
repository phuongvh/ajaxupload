<!DOCTYPE html>
<html>
<head>
<meta charset="utf8">
<title>ajaxupload</title>

<link rel="stylesheet" href="js/themes/base/jquery.ui.all.css">
<script src="js/jquery-1.7.2.js"></script>
	<script src="js/external/jquery.bgiframe-2.1.2.js"></script>
	<script src="js/ui/jquery.ui.core.js"></script>
	<script src="js/ui/jquery.ui.widget.js"></script>
	<script src="js/ui/jquery.ui.mouse.js"></script>
	<script src="js/ui/jquery.ui.draggable.js"></script>
	<script src="js/ui/jquery.ui.position.js"></script>
	<script src="js/ui/jquery.ui.resizable.js"></script>
	<script src="js/ui/jquery.ui.dialog.js"></script>
<script type="text/javascript">

var upload = function(){
        // Get the form data. This serializes the entire form. pritty easy huh!
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
                $('#content_here_please').html(res);
            },
            //add error handler for when a error occurs if you want!
            //error: errorfunction,
            data: form,
            // this is the important stuf you need to overide the usual post behavior
            cache: false,
            contentType: false,
            processData: false
        });
}

// Yes outside of the .ready space becouse this is a function not an event listner!
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
		width:'auto'
			});
	$("#frmCreate").dialog("open");
	
}

function showProcessing(xhr){
	$("#processStatus").dialog({
		autoOpen : false, 
		modal : true, 
		show : "blind", 
		hide : "blind",
		height: 'auto',
		width: 'auto'
			});
	$("#processStatus").dialog("open");
	
	var to = setTimeout(function(){
		clearTimeout(to);
		xhr.abort();
		$("#timeoutDialog").dialog({
			autoOpen : false, 
			modal : true, 
			show : "blind", 
			hide : "blind",
			height: 'auto',
			width: 'auto'
				});
	}, 11)
}

</script>
</head>
<body>
<button onclick="openDialogCreateFile()">Tạo file</button>
<form enctype="multipart/form-data" id="myform" >    
    <input type="text" name="some_usual_form_data" />
    <br>
    <input type="text" name="some_other_usual_form_data" />
    <br>
    <input type="file" multiple="multiple"  name="file[]" id="image" />
    <br>
    <input type="button" value="Upload files" class="upload" onclick="upload()" />
</form>
</progress><span id="statusPercent"></span>
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
	<progress value="0" max="100" style="width: 600px">
</div>

<div id="timeoutDialog" style="display: none">
Upload timeout !
</div>
</body>
</html>
