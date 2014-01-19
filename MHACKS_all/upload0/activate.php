<?php
header("Location: play.html");
$audio_fn = "01 We Are Family.mp3";
$control_file = 'control.txt';
$ts = time()+30;
$py_str= ("python create_timed_play_page.py "."'".$audio_fn."' ".$ts);
exec($py_str);
fwrite(fopen($control_file, 'w'),$ts);
exit;
?>