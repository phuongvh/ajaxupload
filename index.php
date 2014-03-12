<!DOCTYPE html>
<html>
<head>
<meta charset="utf8">
<title>ajaxupload</title>
<script src="js/jquery-1.11.0.js"></script>
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
            //beforeSend: functionname,
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
    }
}
</script>
</head>
<body>
<form enctype="multipart/form-data" id="myform">    
    <input type="text" name="some_usual_form_data" />
    <br>
    <input type="text" name="some_other_usual_form_data" />
    <br>
    <input type="file" multiple="multiple"  name="file[]" id="image" />
    <br>
    <input type="button" value="Upload files" class="upload" onclick="upload()" />
</form>
<progress value="0" max="100"></progress>
<hr>
<div id="content_here_please"></div>
</body>
</html>