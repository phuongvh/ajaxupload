<?php
$succeed = 0;
$error = 0;
$thegoodstuf = '';
if (empty($_FILES)){
	echo 'Upload thất bại';
	return;
}

foreach($_FILES["file"]["error"] as $key => $value) {
	if ($value == UPLOAD_ERR_OK){
		$succeed++;
		$name = $_FILES['file']['name'][$key];
		//took this from: "http://stackoverflow.com/questions/7563658/php-check-file-extension"
		//you can loop through different file types
// 		$file_parts = pathinfo($name);
// 		switch($file_parts['extension'])
// 		{
// 			case "jpg":

// 				//do something with jpg

// 				break;

// 			case "exe":

// 				// do sometinhg with exe

// 				break;

// 			case "": // Handle file extension for files ending in '.'
// 			case NULL: // Handle no file extension
// 				break;
// 		}

		

		// replace file to where you want
		//copy($_FILES['file']['tmp_name'][$key], './upload/'.$name);

		$size = filesize($_FILES['file']['tmp_name'][$key]);
		// make some nice html to send back
		$thegoodstuf .= "
		<br>
		<hr>
		<br>

		<h2>File $succeed - $name</h2>
		<br>
		give some specs:
		<br>
		size: $size bytes
		";
	}
	else{
	$error++;
	}
	}

		echo 'Good lord vader '.$succeed.' files where uploaded with success!<br>';

    if($error){
        echo 'shameful display! '.$error.' files where not properly uploaded!<br>';
    }

    echo '<br>O jeah there was a field containing some usual form data: '. $_REQUEST['some_usual_form_data'];
    echo '<br>O jeah there was a field containing some usual form data: '. $_REQUEST['some_other_usual_form_data'];

    echo $thegoodstuf;