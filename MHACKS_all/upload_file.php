<?php
$urlprefix = "http://169.254.8.45/MHACKS/";
$allowedExts = array("mp3","m4a");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
if ((($_FILES["file"]["size"] < 31457280)
&& in_array($extension, $allowedExts)))
{
	if ($_FILES["file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    }
	else
    {
    	//echo "Upload: " . $_FILES["file"]["name"] . "<br>";
    	//echo "Type: " . $_FILES["file"]["type"] . "<br>";
    	//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
    	//echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
    	$dircount=0;
		foreach (glob("upload*") as $filename)
		{
    		if(is_dir($filename))
    		{
    			$dircount++;
    		}
		}
    	$file_target= "upload".$dircount."/";
    	mkdir($file_target, 0777);
		move_uploaded_file($_FILES["file"]["tmp_name"],
		$file_target . $_FILES["file"]["name"]);
		$py_str= ("python make_path_script.py "."'".$file_target."'"." "."'".$_FILES["file"]["name"]."'");
        echo $py_str;
        exec($py_str);
		echo "Stored in:    " . $urlprefix. $file_target . "play.php";
		header("Location: ". $urlprefix. $file_target . "controller.html");
    }
}
else
{
	echo "Invalid file";
}
exit;
?>