







                                                                                                                                                                <script>(function() {with (this[2]) {with (this[1]) {with (this[0]) {return function(event) {audioT[0]=(new Date().getTime())/1000;audioT[1]=this.currentTime;document.getElementById("position").innerHTML=audioT[1];
};}}}})</script>


<script>
var audio=document.getElementById("music");
function playmusic()
{
	audio.play();
	var ret = (new Date().getTime());
	return ret;
}

function setcurrentTime(t)
{
	if(is_loaded && callCount >= dataSizeLimit){
	time_lastset = (new Date().getTime())/1000;
	audio.currentTime = t;
	time_lastset = ((new Date().getTime())/1000+time_lastset)/2;
	audioT_lastset = t;
	console.log(t)
	console.log('changed current time')
	changed_hack=true;
	sync_count++;
	}else{
		document.getElementById('debug').innerHTML='<br>tried but audio not yet loaded<br>'
		console.log('tried to change current time but failed because is_loaded='+is_loaded+' and callCount='+callCount+' and dataSizeLimit = '+dataSizeLimit);
	}
}


function audio_loaded() {
	is_loaded=true;
	playlag = (new Date().getTime());
	playlag = playmusic()-playlag;
	settimelag = (new Date().getTime());
	setcurrentTime(0);
	settimelag = (new Date().getTime()) - settimelag;
	console.log(playlag);
	console.log(settimelag);
	document.getElementById("setlag").innerHTML+=settimelag;
	//document.getElementById('debug').innerHTML+="<br>AUDIO LOADED"
}</script>







<script>

//console.log(audio)

// function playmusic()
// {
// 	audio.play();
// 	var ret = (new Date().getTime());
// 	return ret;
// }
function pausemusic()
{
	audio.pause();
}

var changed_hack = false;
var is_loaded=false;
var playlag = 0;
var settimelag = 0;
audio_loaded_callcount=0;
// function audio_loaded() {
// 	is_loaded=true;
// 	playlag = (new Date().getTime());
// 	playlag = playmusic()-playlag;
// 	settimelag = (new Date().getTime());
// 	setcurrentTime(0);
// 	settimelag = (new Date().getTime()) - settimelag;
// 	console.log(playlag);
// 	console.log(settimelag);
// 	document.getElementById("setlag").innerHTML+=settimelag;
// 	//document.getElementById('debug').innerHTML+="<br>AUDIO LOADED"
// }

// function setcurrentTime(t)
// {
// 	if(is_loaded){
// 	audio.currentTime = t;
// 	//console.log(t)
// 	return (new Date().getTime());
// 	}
// 	//else{
// 	//	//document.getElementById('debug').innerHTML="<br>Audio not yet loaded<br>"
// 	//}

// }

var time_lastset = (new Date().getTime())/1000;
var audioT_lastset = 0;

var min_syncs=5;
var sync_count=0;

function reset_sync(){
	 sync_count=0;
	 fixed=false;
	 var offsets = new Array();
	 var newoffsets = new Array();
	 loadit();
}


var reset_once=false;
var pausedit = false;
var fixed=false;
var newoffset = 0;
var offsets = new Array();
var newoffsets = new Array();
function loadit() {
	if(fixed && !reset_once){reset_sync(); console.log('resetting sync HARD'); reset_once=true;}
	console.log("syncing");
	console.log(callCount - dataSizeLimit);
	playmusic();
	//document.getElementById('debug').innerHTML+=playmusic();
	//var playlag = (new Date().getTime());
	//playlag = (playmusic()-playlag)/1000;
	//document.getElementById('debug').innerHTML="playlag: "+playlag+"<br>"
	//document.getElementById('debug').innerHTML+="avgdt: "+avgdt+"  Tstart: "+Tstart;
	//var corrected_start = avgdt+Tstart-playlag;
	//document.getElementById('debug').innerHTML+="<br>corrected_start: "+corrected_start
	//document.getElementById('debug').innerHTML+="<br>java time: "+(new Date().getTime())/1000
	//while((new Date().getTime())/1000 <= corrected_start){setcurrentTime(0);};
	if(is_loaded){
		if(audioT_lastset<0){
			pausemusic();
			pausedit = true;
		}else if(pausedit == true){
			playmusic();
			pausedit  = false;
		}

		//lagTimes = new Array();
		//audioT_lastset = (audioT[1]+audioT_lastset)/2;
		//time_lastset = (audioT[0]+time_lastset)/2;
		lagTimes.splice(0,1);
		if(callCount >= dataSizeLimit && changed_hack){
		callCount = callCount - 15;
		changed_hack = false;
		document.getElementById('dtstatus').innerHTML="<font color='red'>synchronizing with server</font>";
		dtWrapper();
		}
		var tset = (new Date().getTime())/1000-Tstart-avgdt;//average!
		var offsetter = tset-audioT_lastset-(new Date().getTime())/1000+time_lastset;
		offsets.push(offsetter); //possibly switch order, make shift by average?
		//also maybe need to find average fail offset
		//console.log(Math.abs(offsetter));
		if(offsets.length>4 & audioT_lastset > 0)
		{
			offsets.splice(0,1);
			if(Math.abs(avg(offsets))<=.01 && Math.max.apply(null,offsets) < .04 && Math.min.apply(null,offsets) > -.04 && sync_count > min_syncs){
				fixed=true;
			}
		}

		//if(newoffsets.length>2){newoffsets.splice(0,1);}
		if(sync_count <= min_syncs || fixed == false){
			//var t0 = (new Date().getTime());
			//settimelag = setcurrentTime(audioT+offsetter+settimelag)-t0;
			setcurrentTime(tset+avg(newoffsets));//audioT+offsetter+avg(newoffsets));
			if(callCount >=dataSizeLimit &&  changed_hack){
			callCount = callCount - 15;
			changed_hack = false;
			dtWrapper();
			newoffset = ((audioT[0]-Tstart-avgdt))-audioT[1]+avg(newoffsets);
			if(sync_count>5 && Math.abs(newoffset - offsets[4]) <= .01 &&Math.abs(newoffset - offsets[3]) <= .01 &&Math.abs(newoffset - offsets[2]) <= .01 && sync_count > 3) {newoffsets.push(newoffset);}
			document.getElementById("setlag").innerHTML=(new Date().getTime())/1000-Tstart-avgdt-audioT_lastset;
			document.getElementById("offset").innerHTML="was "+offsetter;
			document.getElementById("newoffset").innerHTML="offset failed by "+newoffset;
			document.getElementById("avgoffsets").innerHTML="avg post-offset: "+avg(newoffsets)+";  avg pre-offset: "+avg(offsets);
			fixed=false;
			document.getElementById("fixed").innerHTML="";
			}
		}
		else{
			document.getElementById("fixed").innerHTML="<font color='green'>SYNC COMPLETE</font>";
			if(offsetter != NaN)
			{
				document.getElementById("offset").innerHTML=offsetter;
				document.getElementById("newoffset").innerHTML="last offset:  "+newoffset;
			}
		}
		//settimelag=Math.max(tset-audioT,0);
		//console.log(tset);
	}else{
		document.getElementById('dtstatus').innerHTML="<font color='purple'>loading music</font>";
		pausemusic();
	}
	// sync_count++;
	//document.getElementById('debug').innerHTML+="<br>playing"
	}

//document.getElementById('qr').onclick="loadit()";
$('#qr').click(reset_sync);
setInterval(loadit, 1000);
</script>
