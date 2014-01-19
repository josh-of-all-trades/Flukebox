<?php
header("Location: play.html");
$control_file = 'control.txt';
while(time_nanosleep(0, 200000000))
{
	$data = fread(fopen($control_file, 'r'),1);
	if($data!='-')
	{
		exit;
	}
}
?>
