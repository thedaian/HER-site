UPLOAD FORM
<form enctype="multipart/form-data" action="" method="POST">
<input type="hidden" name="uploader" value="true">
<input type="hidden" name="MAX_FILE_SIZE" value="1024000"/>
Upload Image: <input type="file" name="userfile" accept="image/jpeg, image/png, image/gif"/><br/>
<input type="submit" value="Upload Image"/>
</form><br/>
<?php
if(isset($_POST['uploader'])&&($_POST['uploader']==TRUE))
{
//PHP code to determine the type of a file given
    //8 bytes of header data. Much more accurate than checking
    //the user-supplied Content-Type and of course this is far
    //better than relying on checking the file extension :).

    //(C)2004 r1ch.net. I place this code into the public domain
    //in the hope it is useful to somebody.
		if(empty($_FILES['userfile']['tmp_name'])) {
			error('File has an error.  Please resubmit');
		}
		$size=getimagesize($_FILES['userfile']['tmp_name']);
    //open a file
    $image_data = fopen($_FILES['userfile']['tmp_name'], "rb");

    //grab first 8 bytes, should be enough for most formats
    $header_bytes = fread($image_data, 8);

    //close file
    fclose ($image_data);

    //compare header to known signatures
    if (!strncmp ($header_bytes, "\xFF\xD8", 2))
        $file_format = ".JPEG";
    else if (!strncmp ($header_bytes, "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A", 8))
        $file_format = ".PNG";
    else if (!strncmp ($header_bytes, "GIF", 3))
        $file_format = ".GIF";
    else
        $file_format = "unknown";
    
		if($file_format=="unknown") {
			error('File format unknown.  Only jpeg, png, or gif files are allowed.');
		}

		$uploaddir = 'card_templates/';
		$uploadfile = $uploaddir . $_FILES['userfile']['name'];
		if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
			echo 'File uploaded.';

		} else { echo 'Problem with file upload.  File not uploaded, try again.'; }
}		
?>