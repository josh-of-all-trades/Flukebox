<!DOCTYPE HTML>
<html>
<body>



<audio id="music" source src='Laughing_With.mp3'  type="audio/mpeg">
	Your browser does not support the audio element.
</audio>

<script>
var audio=document.getElementById("music"); 
function playmusic()
{
	music.play();
}

function pausemusic()
{
	music.pause();
}
var musicname = "Laughing_With.mp3";
</script>

<button onclick="playmusic()" type="button">Play On Sync</button>
<button onclick="pausemusic()" type="button">Stop test</button>

<?php
$control_file = 'control.txt';

$cont=True;
while($cont=True)
{
	$data = fread(fopen($control_file, 'r'),1);
	if($data=='1')
	{
		echo "<b><i>let's play music</i></b>";
		echo "<script>playmusic()</script>";
		$cont=False;
	}
}
?>


</body>
</html>