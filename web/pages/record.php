<!--      * page requirements *     -->
<script src="jslib/recorder.js"></script>
<script src="jslib/Fr.voice.js"></script>
<script src="jslib/jquery.js"></script>
<script src="jslib/app.js"></script>
<!-- -------------------------------------------- -->		

<h1 class="pagename">Nahrávání</h1>
<div>
	<img id="startrecording_btn" src="./res/icons/Microphone%20Filled-50.png" /><!--
	--><img id="pauserecording_btn" class="disabled" src="./res/icons/Pause-48.png" /><!--
	--><img id="playrecording_btn" class="disabled" src="./res/icons/Play-48.png" /><!--
	--><img id="downloadrecording_btn" class="disabled"src="./res/icons/Download-48.png" /><!--
	--><img id="uploadrecording_btn" class="disabled" src="./res/icons/Upload-48.png" />
</div>

<canvas id="analyser" height="200" width="500"></canvas>

<audio controls src="" id="audio"></audio>