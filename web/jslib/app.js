function b64toBlob(b64Data, contentType, sliceSize) {
	contentType = contentType || '';
	sliceSize = sliceSize || 512;

	var byteCharacters = atob(b64Data);
	var byteArrays = [];

	for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
		var slice = byteCharacters.slice(offset, offset + sliceSize);

		var byteNumbers = new Array(slice.length);
		for (var i = 0; i < slice.length; i++) {
			byteNumbers[i] = slice.charCodeAt(i);
		}

		var byteArray = new Uint8Array(byteNumbers);

		byteArrays.push(byteArray);
	}

	var blob = new Blob(byteArrays, {type: contentType});
	return blob;
}

var recordingStarted = false;
var recordingStoped = true;
var recordingPaused = true;
var startTime = 0;

function startRecording(enableEcho) {
	if(recordingStarted)
		Fr.voice.stop();
	
	startTime = new Date().getTime();
	console.log(startTime);
	
	recordingStarted = true;
	recordingStoped = false;
	recordingPaused = false;
	
	Fr.voice.record(enableEcho, function() {

		analyser = Fr.voice.context.createAnalyser();
		analyser.fftSize = 16384;
		analyser.minDecibels = -90;
		analyser.maxDecibels = -10;
		analyser.smoothingTimeConstant = 0.85;
		Fr.voice.input.connect(analyser);
	      
		var bufferLength = analyser.frequencyBinCount;
		var dataArray = new Uint8Array(bufferLength);
	      
		WIDTH = 500, HEIGHT = 200;
		canvasCtx = $("canvas#analyser")[0].getContext("2d");
		canvasCtx.clearRect(0, 0, WIDTH, HEIGHT);
	      
		function draw() {
			
			if(!recordingStoped) {
				
				drawVisual = requestAnimationFrame(draw);
				analyser.getByteTimeDomainData(dataArray);
				canvasCtx.fillStyle = 'rgb(255, 255, 255)';
				canvasCtx.fillRect(0, 0, WIDTH, HEIGHT);
				canvasCtx.lineWidth = 2;
				canvasCtx.strokeStyle = 'rgb(77, 144, 254)';
			  
				canvasCtx.beginPath();
				var sliceWidth = WIDTH * 1.0 / bufferLength;
				var x = 0;
				for(var i = 0; i < bufferLength; i++) {
					var v = dataArray[i] / 128.0;
					var y = v * HEIGHT/2;
		  
					if(i === 0) {
						canvasCtx.moveTo(x, y);
					} else {
						canvasCtx.lineTo(x, y);
					}
		  
					x += sliceWidth;
				}
				canvasCtx.lineTo(WIDTH, HEIGHT/2);
				canvasCtx.stroke();
			} else
				canvasCtx.clearRect(0, 0, WIDTH, HEIGHT);
				
			if(recordingPaused)
				canvasCtx.clearRect(0, 0, WIDTH, HEIGHT);
		};
		draw();
	});
}

function stopRecording() {
	Fr.voice.pause();
	recordingStoped = true;
}

function showUploadDialog() {
	var w = window.innerWidth;
	var h = window.innerHeight;
	
	var udPosLeft = Math.floor((w - (new Number($("div#uploadDialog").css("width").replace("px",""))))/2);
	var udPosTop = Math.floor((h - (new Number($("div#uploadDialog").css("height").replace("px",""))))/2);
	
	$("div#uploadDialog").css("top", udPosTop+"px");
	$("div#uploadDialog").css("left", udPosLeft+"px");
	
	$("span#upload_error").fadeOut(0);
	$("span#upload_success").fadeOut(0);
	$("div#shade").fadeIn(500);
	$("div#uploadDialog").fadeIn(500);
	
	if(startTime != 0) {
		var d = new Date(startTime);
		$("input#upload_filename").val("rec_"+d.getDate()+"_"+(d.getMonth()+1)+"_"+d.getFullYear()+"-"+d.getHours()+"_"+d.getMinutes());
	}
}

function hideUploadDialog() {
	$("div#shade").fadeOut(500);
	$("div#uploadDialog").fadeOut(500);
}

function uploadRecording(name,filename,description) {
	Fr.voice.export(function(base64){
		console.log(base64);
		$.ajax({
			url: "./phplib/upload.php",
			type: 'POST',
			data: {
				'name': name,
				'filename': filename+'.wav',
				'description': description,
				'time': startTime,
				'data' : base64
			},
			success: function(res) {
				if(res == "E00") {
					$("span#upload_error").html("Nahrávka s tímto názvem již existuje!");
					$("span#upload_error").fadeIn(100);
				} else if(res=="E01") {
					$("span#upload_error").html("Soubor s tímto názvem již existuje!");
					$("span#upload_error").fadeIn(100);
				} else {
					$("span#upload_success").html("Soubor byl úspěšně nahrán!");
					$("span#upload_success").fadeIn(100);
					
					setTimeout(function() {
						hideUploadDialog();
					}, 1000);
				}
				if($("input#upload_name").val() == "") {
					
				}
			}
		});
	}, "base64");
}

$(document).ready(function() {
	$("canvas#analyser").fadeOut(0);
	$("audio#audio").fadeOut(0);
	
	//showUploadDialog();
	
	$("img#startrecording_btn").click(function() {				
		if (this.classList.contains("recording")) {
			this.classList.remove("recording");
			stopRecording();
			
			var pauseBtn = $("img#pauserecording_btn").toArray()[0];
			if (!pauseBtn.classList.contains("disabled"))
				pauseBtn.classList.add("disabled");
			
			var playBtn = $("img#playrecording_btn").toArray()[0];
			if (playBtn.classList.contains("disabled"))
				playBtn.classList.remove("disabled");
			
			var downloadBtn = $("img#downloadrecording_btn").toArray()[0];
			if (downloadBtn.classList.contains("disabled"))
				downloadBtn.classList.remove("disabled");
			
			var uploadBtn = $("img#uploadrecording_btn").toArray()[0];
			if (uploadBtn.classList.contains("disabled"))
				uploadBtn.classList.remove("disabled");
			
			$("canvas#analyser").fadeOut(500);
		} else {
			this.classList.add("recording");
			startRecording(false);
			
			var pauseBtn = $("img#pauserecording_btn").toArray()[0];
			if (pauseBtn.classList.contains("disabled"))
				pauseBtn.classList.remove("disabled");
			
			$("img#pauserecording_btn").attr("src", "./res/icons/Pause-48.png");
			
			var playBtn = $("img#playrecording_btn").toArray()[0];
			if (!playBtn.classList.contains("disabled"))
				playBtn.classList.add("disabled");
			
			var downloadBtn = $("img#downloadrecording_btn").toArray()[0];
			if (!downloadBtn.classList.contains("disabled"))
				downloadBtn.classList.add("disabled");
			
			var uploadBtn = $("img#uploadrecording_btn").toArray()[0];
			if (!uploadBtn.classList.contains("disabled"))
				uploadBtn.classList.add("disabled");
			
			$("canvas#analyser").fadeIn(500);
			$("audio#audio").fadeOut(500);
		}
	});
	
	$("img#pauserecording_btn").click(function() {				
		if (!this.classList.contains("disabled")) {
			if (this.classList.contains("paused")) {
				recordingPaused = false;
				this.classList.remove("paused");
				Fr.voice.resume();
				$(this).attr("src", "./res/icons/Pause-48.png");
			} else {
				recordingPaused = true;
				this.classList.add("paused");
				Fr.voice.pause();
				$(this).attr("src", "./res/icons/Record-48.png");
			}
		}
	});
	
	$("img#playrecording_btn").click(function() {				
		if (!this.classList.contains("disabled")) {
			Fr.voice.export(function(url){
				$("#audio").attr("src", url);
				$("#audio")[0].play();
			}, "URL");
			$("audio#audio").fadeIn(500);
		}
	});
	
	$("img#downloadrecording_btn").click(function() {				
		if (!this.classList.contains("disabled")) {
			Fr.voice.export(function(base64){
				base64 = base64.replace("data:","");
				base64 = base64.replace("base64,","");
				var data_format = base64.split(";");
				var contentType = data_format[0];
				var b64Data = data_format[1];
				var byteCharacters = atob(b64Data);
				
				var byteNumbers = new Array(byteCharacters.length);
				for (var i = 0; i < byteCharacters.length; i++) {
				    byteNumbers[i] = byteCharacters.charCodeAt(i);
				}
				
				var blob = b64toBlob(b64Data, contentType);
				var blobUrl = URL.createObjectURL(blob);

				window.location = blobUrl;
			}, "base64");
		}
	});
	
	$("img#uploadrecording_btn").click(function() {				
		if (!this.classList.contains("disabled")) {
			showUploadDialog();
		}
	});
	
	$("button#upload_close_btn").click(function() {
		hideUploadDialog();
	});
	
	$("button#upload_btn").click(function() {
		$("span#upload_error").fadeOut(0);
		$("span#upload_success").fadeOut(0);
		
		if($("input#upload_name").val() == "") {
			$("span#upload_error").html("Chybí název nahrávky!");
			$("span#upload_error").fadeIn(100);
			return;
		}
		if($("input#upload_filename").val() == "") {
			$("span#upload_error").html("Chybí název souboru!");
			$("span#upload_error").fadeIn(100);
			return;
		}
		uploadRecording(
			$("input#upload_name").val(),
			$("input#upload_filename").val(),
			$("textarea#upload_description").val()
		);
	});
});
