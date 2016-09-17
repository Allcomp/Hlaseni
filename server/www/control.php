<?php
    header('Content-Type: text/html; charset=utf-8');
    define("ABS_PATH", "http://localhost/dsc");
    require_once("./phplib/ClassLoader.class.php");
    require_once("./config.php");

    $classLoader = new ClassLoader();
    $classLoader->run();
    $database = new Database(new DatabaseConfig($_CONFIG['dbhost'], $_CONFIG['dbuser'], $_CONFIG['dbpass'], $_CONFIG['dbname']));
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Dynamic Smart Control</title>
        <meta charset="utf-8" />
        <script src="jslib/jquery-2.1.4.min.js"></script>
        <script src="jslib/jquery-ui.min.js"></script>
        <script src="jslib/Room.js"></script>
        <script src="jslib/Control.js"></script>
        <script src="jslib/Macro.js"></script>
        <script src="jslib/DataStore.js"></script>
        <link rel="stylesheet" type="text/css" href="css/main.css" />
        <link rel="stylesheet" type="text/css" href="css/switches.css" />
        <link rel="stylesheet" type="text/css" href="css/inputsRange.css" />
        <link rel="stylesheet" type="text/css" href="css/radioButtons.css" />
        <link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css" />
	<style>
	.ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active, a.ui-button:active, .ui-button:active, .ui-button.ui-state-active:hover {
		background: #2ecc71;
		border: 1px solid #27ae60;
	}
	.ui-state-highlight, .ui-widget-content .ui-state-highlight, .ui-widget-header .ui-state-highlight {
		background: #4D4E4F;
		border: 1px solid #292929;
		color: #E3E3E3;
		font-weight: bold;
	}
	</style>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0' name='viewport' />
        <?php include("./phplib/loadJavascriptData.php"); ?>
        <script>

            var currentMenu = 0;

            var rangeUpdateCounter = -1;
	    
	    var securitySystems = [];
	    
	    /* String decoder */
	    var decoderMap = {};
	    decoderMap["?chid=0?"] = "á";
	    decoderMap["?chid=0L?"] = "á";
	    decoderMap["?chid=1?"] = "č";
	    decoderMap["?chid=1L?"] = "Č";
	    decoderMap["?chid=2?"] = "ď";
	    decoderMap["?chid=2L?"] = "Ď";
	    decoderMap["?chid=3?"] = "ě";
	    decoderMap["?chid=3L?"] = "Ě";
	    decoderMap["?chid=4?"] = "é";
	    decoderMap["?chid=4L?"] = "É";
	    decoderMap["?chid=5?"] = "í";
	    decoderMap["?chid=5L?"] = "Í";
	    decoderMap["?chid=6?"] = "ň";
	    decoderMap["?chid=6L?"] = "Ň";
	    decoderMap["?chid=7?"] = "ó";
	    decoderMap["?chid=7L?"] = "Ó";
	    decoderMap["?chid=8?"] = "ř";
	    decoderMap["?chid=8L?"] = "Ř";
	    decoderMap["?chid=9?"] = "š";
	    decoderMap["?chid=9L?"] = "Š";
	    decoderMap["?chid=10?"] = "ť";
	    decoderMap["?chid=10L?"] = "Ť";
	    decoderMap["?chid=11?"] = "ú";
	    decoderMap["?chid=11L?"] = "Ú";
	    decoderMap["?chid=12?"] = "ů";
	    decoderMap["?chid=12L?"] = "Ů";
	    decoderMap["?chid=13?"] = "ž";
	    decoderMap["?chid=13L?"] = "Ž";
	    
	    function wstr_decode(str) {
		for(var key in decoderMap)
			str = str.replace(key, decoderMap[key]);
		return str;
	    }
	    /*-------------------------*/

            function requestUpdateOutputs() {
                var outputString = "";

                var roomControlItemElems = $("div.roomControls div.item div.controls input").toArray();

                for(var i = 0; i < roomControlItemElems.length; i++) {
                    var input = $(roomControlItemElems[i]);
                    if($(input).attr("type") == "range") {
                        rangeUpdateCounter++;
                        if(rangeUpdateCounter > 5)
                            rangeUpdateCounter = 0;
                        if(rangeUpdateCounter != 0)
                            continue;
                    }
                    if($(input).attr("type") == "radio") {
                        var inputs = $(input).parent().parent().find("div.radioContainer").toArray();
                        if(inputs.length == 3) {
                            var out1 = $(inputs[0]).attr("data-output");
                            var out2 = $(inputs[2]).attr("data-output");
                            outputString += "-" + out1 + "-" + out2;
                        }
                        continue;
                    }
                    if($(input).attr("type") == "number")
                        continue;
                    if($(input).parent().parent().attr("class") == "tempactive")
                        continue;
                    var output = $(input).attr("data-output");
                    outputString += "-" + output;
                }
				
                if(outputString == "")
                    return;

                outputString = outputString.substr(1);

                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/updateOutputs/states/' + outputString;
                document.getElementsByTagName('head')[0].appendChild(script);
            }

            function requestUpdateOutputsLastUsed() {
                var outputString = "";

                var roomControlItemElems = $("div.lastUsedControls div.item div.controls input").toArray();

                for(var i = 0; i < roomControlItemElems.length; i++) {
                    var input = $(roomControlItemElems[i]);
                    if($(input).attr("type") == "range") {
                        rangeUpdateCounter++;
                        if(rangeUpdateCounter > 5)
                            rangeUpdateCounter = 0;
                        if(rangeUpdateCounter != 0)
                            continue;
                    }
                    if($(input).attr("type") == "radio") {
                        var inputs = $(input).parent().parent().find("div.radioContainer").toArray();
                        if(inputs.length == 3) {
                            var out1 = $(inputs[0]).attr("data-output");
                            var out2 = $(inputs[2]).attr("data-output");
                            outputString += "-" + out1 + "-" + out2;
                        }
                        continue;
                    }
                    if($(input).attr("type") == "number")
                        continue;
                    if($(input).parent().parent().attr("class") == "tempactive")
                        continue;
                    var output = $(input).attr("data-output");
                    outputString += "-" + output;
                }
				
                if(outputString == "")
                    return;

                outputString = outputString.substr(1);

                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/updateOutputs/states/' + outputString;
                document.getElementsByTagName('head')[0].appendChild(script);
            }
			
	    function requestUpdatePulsingOutputs() {
                var outputString = "";

                var roomControlItemElems = $("div.roomControls div.item div.controls input").toArray();

                for(var i = 0; i < roomControlItemElems.length; i++) {
                    var input = $(roomControlItemElems[i]);
                    if($(input).attr("type") == "radio") {
                        var inputs = $(input).parent().parent().find("div.radioContainerSlowMotion").toArray();
                        if(inputs.length == 3) {
                            var out1 = $(inputs[0]).attr("data-output");
                            var out2 = $(inputs[2]).attr("data-output");
                            outputString += "-" + out1 + "-" + out2;
                        }
                    }
                }
				
                if(outputString == "")
                    return;
				
                outputString = outputString.substr(1);

                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/updatePulsingOutputs/getpulsing/' + outputString;
                document.getElementsByTagName('head')[0].appendChild(script);
            }
			
	    function requestUpdatePulsingOutputsLastUsed() {
                var outputString = "";

                var roomControlItemElems = $("div.lastUsedControls div.item div.controls input").toArray();

                for(var i = 0; i < roomControlItemElems.length; i++) {
                    var input = $(roomControlItemElems[i]);
                    if($(input).attr("type") == "radio") {
                        var inputs = $(input).parent().parent().find("div.radioContainerSlowMotion").toArray();
                        if(inputs.length == 3) {
                            var out1 = $(inputs[0]).attr("data-output");
                            var out2 = $(inputs[2]).attr("data-output");
                            outputString += "-" + out1 + "-" + out2;
                        }
                    }
                }
				
                if(outputString == "")
                    return;
				
                outputString = outputString.substr(1);

                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/updatePulsingOutputs/getpulsing/' + outputString;
                document.getElementsByTagName('head')[0].appendChild(script);
            }

            function requestUpdateActiveTherms() {
                var outputString = "";

                var roomControlItemElems = $("div.roomControls div.item div.controls input").toArray();

                for(var i = 0; i < roomControlItemElems.length; i++) {
                    var input = $(roomControlItemElems[i]);

                    if($(input).parent().parent().attr("class") == "tempactive") {
                        if($(input).attr("type") == "checkbox") {
                            var output = $(input).attr("data-output");
                            outputString += "-" + output;
                        }
                    }
                }

                outputString = outputString.substr(1);

                if(outputString == "")
                    return;

                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/updateActiveTherms/thermsactiveget/' + outputString;
                document.getElementsByTagName('head')[0].appendChild(script);
            }

            function requestUpdateActiveThermsLastUsed() {
                var outputString = "";

                var roomControlItemElems = $("div.lastUsedControls div.item div.controls input").toArray();

                for(var i = 0; i < roomControlItemElems.length; i++) {
                    var input = $(roomControlItemElems[i]);

                    if($(input).parent().parent().attr("class") == "tempactive") {
                        if($(input).attr("type") == "checkbox") {
                            var output = $(input).attr("data-output");
                            outputString += "-" + output;
                        }
                    }
                }

                outputString = outputString.substr(1);

                if(outputString == "")
                    return;

                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/updateActiveTherms/thermsactiveget/' + outputString;
                document.getElementsByTagName('head')[0].appendChild(script);
            }

            function requestUpdateThermsTarget() {
                var outputString = "";

                var roomControlItemElems = $("div.roomControls div.item div.controls input").toArray();

                for(var i = 0; i < roomControlItemElems.length; i++) {
                    var input = $(roomControlItemElems[i]);

                    if($(input).parent().attr("class") == "tempactive") {
                        if($(input).attr("type") == "number") {
                            var output = $(input).attr("data-output");
                            outputString += "-" + output;
                        }
                    }
                }

                outputString = outputString.substr(1);

                if(outputString == "")
			return;

                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/updateThermsTarget/thermsget/' + outputString;
                document.getElementsByTagName('head')[0].appendChild(script);
            }

            function requestUpdateThermsTargetLastUsed() {
                var outputString = "";

                var roomControlItemElems = $("div.lastUsedControls div.item div.controls input").toArray();

                for(var i = 0; i < roomControlItemElems.length; i++) {
                    var input = $(roomControlItemElems[i]);

                    if($(input).parent().attr("class") == "tempactive") {
                        if($(input).attr("type") == "number") {
                            var output = $(input).attr("data-output");
                            outputString += "-" + output;
                        }
                    }
                }

                outputString = outputString.substr(1);

                if(outputString == "")
			return;

                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/updateThermsTarget/thermsget/' + outputString;
                document.getElementsByTagName('head')[0].appendChild(script);
            }

            function requestSecurityEnable(code,sid) {
                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/securityEnableResult/securityenable/' + code + '/' + sid;
                document.getElementsByTagName('head')[0].appendChild(script);
            }

            function requestSecurityDisable(code,sid) {
                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/securityDisableResult/securitydisable/' + code + '/' + sid;
                document.getElementsByTagName('head')[0].appendChild(script);
            }

            function requestSecurityStates() {
		if(currentMenu != 4) return;
                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/securityStatesResult/securitystatesget';
                document.getElementsByTagName('head')[0].appendChild(script);
            }

            function requestOutputChange(output, value) {
                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/voidCatch/output/' + output + "/" + value;
                document.getElementsByTagName('head')[0].appendChild(script);
            }

            function requestPulsingStart(output, frequency, dutyCycle) {
                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/voidCatch/startpulsing/' + output + "/" + frequency + "/" + dutyCycle;
                document.getElementsByTagName('head')[0].appendChild(script);
            }

            function requestPulsingStop(output) {
                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/voidCatch/stoppulsing/' + output;
                document.getElementsByTagName('head')[0].appendChild(script);
            }

            function requestThermActiveChange(output, value) {
                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/voidCatch/thermactive/' + output + "/" + value;
                document.getElementsByTagName('head')[0].appendChild(script);
            }

            function requestThermTargetChange(output, value) {
                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/voidCatch/thermset/' + output + "/" + value;
                document.getElementsByTagName('head')[0].appendChild(script);
            }

            function requestSecurityInputs() {
                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/genSecurityInputs/securityinputsget';
                document.getElementsByTagName('head')[0].appendChild(script);
            }

            function requestActiveSecurityInputs() {
                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/updateActiveSecurityInputs/securityactiveinputsget';
                document.getElementsByTagName('head')[0].appendChild(script);
            }

            function requestUpdateTemperatures() {
                var outputString = "";
                var roomControlItemElems = $("div.roomControls div.item div.controls div.tempval").toArray();

                for(var i = 0; i < roomControlItemElems.length; i++) {
                    var divValue = $(roomControlItemElems[i]);
                    var output = $(divValue).attr("data-output");
                    outputString += "-" + output;
                }

                outputString = outputString.substr(1);

                if(outputString == "")
                    return;

                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/updateTemperatures/temps/' + outputString;
                document.getElementsByTagName('head')[0].appendChild(script);
            }

            function requestUpdateTemperaturesLastUsed() {
                var outputString = "";
                var roomControlItemElems = $("div.lastUsedControls div.item div.controls div.tempval").toArray();

                for(var i = 0; i < roomControlItemElems.length; i++) {
                    var divValue = $(roomControlItemElems[i]);
                    var output = $(divValue).attr("data-output");
                    outputString += "-" + output;
                }

                outputString = outputString.substr(1);

                if(outputString == "")
                    return;

                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/updateTemperatures/temps/' + outputString;
                document.getElementsByTagName('head')[0].appendChild(script);
            }

            function requestSimulatorStop() {
                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/voidCatch/simulationstop';
                document.getElementsByTagName('head')[0].appendChild(script);
            }

            function requestSimulatorStart(startTime,endTime) {
                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/voidCatch/simulationstart/'+startTime+"/"+endTime;
                document.getElementsByTagName('head')[0].appendChild(script);
            }

            function requestSimulatorState() {
                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/updateSimulatorState/simulationstate';
                document.getElementsByTagName('head')[0].appendChild(script);
            }

            function requestSimulatorTimes() {
                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/updateSimulatorTimes/simulationgettimes';
                document.getElementsByTagName('head')[0].appendChild(script);
            }
	    
	    function updateSimulatorState(res) {
		if(res=='1') {
			$("div.simulator input#switchEnableSimulator").prop("checked", true);
			requestSimulatorTimes();
		} else 
			$("div.simulator input#switchEnableSimulator").prop("checked", false);
	    }
	    
	    function updateSimulatorTimes(res) {
		if(res.indexOf(",,") > -1) {
			var times = res.split(",,");
			$("#startdatepicker").val(
				$.datepicker.formatDate(
					'dd.mm.yy', new Date(Number(times[0]))
				)
			);
			$("#enddatepicker").val(
				$.datepicker.formatDate(
					'dd.mm.yy', new Date(Number(times[1]))
				)
			);
		}
	    }

            function updateTemperatures(res) {
                var pairs = res.split("-");
                for(var i = 0; i < pairs.length; i++) {
                    var pair = pairs[i].split(":");
                    var divsValue = $("div.roomControls div.item div.controls div.tempval[data-output='" + pair[0] +"']").toArray();
                    if(divsValue.length == 1) {
                        divsValue[0].innerHTML = (parseFloat(pair[1]).toFixed(1)) + " °C";
                    }
		    var divsValue2 = $("div.lastUsedControls div.item div.controls div.tempval[data-output='" + pair[0] +"']").toArray();
                    if(divsValue2.length == 1) {
                        divsValue2[0].innerHTML = (parseFloat(pair[1]).toFixed(1)) + " °C";
                    }
                }
            }
	    
	    function genSecurityInputs(res) {
		console.log(res);
                var systemStrings = res.split("?#system?");
		for(var i = 0; i < systemStrings.length; i++) {
                    var system = systemStrings[i].split("?#scon?");
                    var sid = system[0];
		    var inputs = system[1].split("?#ins?");
		    var inputGroup = $("div.security div.activesecurityinputs div.securitygroup[data-sid='" + sid +"'] div.inputs");
		    inputGroup.parent().css("display","none");
		    inputGroup.html("");
		    for(var j = 0; j < inputs.length; j++) {
			var input = inputs[j].split("?#inv?");
			var inputId = input[0];
			var inputDesc = wstr_decode(input[1]);
			inputGroup.append("<div class='securityinput' style='display: none;' data-swid='" + inputId +"'>[<b>" + inputId +"</b>] " + inputDesc +"</div>");
		    }
                }
	    }
	    
	    function updateActiveSecurityInputs(res) {
		console.log(res);
                var systemStrings = res.split("&");
		var inputCounter = 0;
		for(var i = 0; i < systemStrings.length; i++) {
			var system = systemStrings[i].split(":");
			var sid = system[0];
			var inputGroup = $("div.security div.activesecurityinputs div.securitygroup[data-sid='" + sid +"'] div.inputs");
			var activeInputs = system[1].split("-");
			if(system[1] != "")
				inputGroup.parent().css("display","block");
			else
				inputGroup.parent().css("display","none");
			inputGroup.children().css("display","none");
			if(system[1] != "")
				inputCounter++;
			for(var j = 0; j < activeInputs.length; j++)
				inputGroup.find("div.securityinput[data-swid='"+activeInputs[j]+"']").css("display","inline-block");
		}
		if(inputCounter > 0) {
			$("div.security div.activesecurityinputs").css("display","block");
			$("div.security div.securityitems").css("margin-bottom","0");
		} else {
			$("div.security div.activesecurityinputs").css("display","none");
			$("div.security div.securityitems").css("margin-bottom","30px");
		}
	    }

            function voidCatch(c) {}
			
		/*var securityEnabledMessage = "Bezpečnostní systém je nyní zapnutý.";
		var securityDisabledMessage = "Bezpečnostní systém je nyní vypnutý.";
		var securityState = 0;*/
		
		function securityStatesResult(res) {
			var pairs = res.split("-");
			for(var i = 0; i < pairs.length; i++) {
				var pair = pairs[i].split(":");
				if(parseInt(pair[1])==1) {
					var item = $("div.security div.securityitems").find("[data-sid='" + pair[0] + "']");
					if(!item.hasClass("sitemred"))
						item.addClass("sitemred");
				} else {
					var item = $("div.security div.securityitems").find("[data-sid='" + pair[0] + "']");
					if(item.hasClass("sitemred"))
						item.removeClass("sitemred");
				}
			}
		}
		
		function securityEnableResult(res) {
			if(res=="$OK")
				notification("Bezpečnostní systém byl aktivován.");
			else
				notification("Aktivace selhala, špatné heslo!");
		}
		
		function securityDisableResult(res) {
			if(res=="$OK")
				notification("Bezpečnostní systém byl deaktivován.");
			else
				notification("Deaktivace selhala, špatné heslo!");
		}

            function updateOutputs(res) {
                var pairs = res.split("-");
                for(var i = 0; i < pairs.length; i++) {
                    var pair = pairs[i].split(":");
                    var checkboxes = $("div.roomControls div.item div.controls label "
                            + "input[data-output='" + pair[0] +"']").toArray();
                    var checkboxes2 = $("div.lastUsedControls div.item div.controls label "
                            + "input[data-output='" + pair[0] +"']").toArray();
                    var ranges = $("div.roomControls div.item div.controls "
                            + "input[data-output='" + pair[0] +"']").toArray();
                    var ranges2 = $("div.lastUsedControls div.item div.controls "
                            + "input[data-output='" + pair[0] +"']").toArray();
                    var radioContainers = $("div.roomControls div.item div.controls "
                            + "div.radioContainer[data-output='" + pair[0] +"']").toArray();
                    var radioContainers2 = $("div.lastUsedControls div.item div.controls "
                            + "div.radioContainer[data-output='" + pair[0] +"']").toArray();
                    if(checkboxes.length == 1)
                        checkboxes[0].checked = pair[1] == "1";
		    if(checkboxes2.length == 1)
                        checkboxes2[0].checked = pair[1] == "1";
                    if(ranges.length == 1)
                        $(ranges[0]).val(parseInt(pair[1]));
		    if(ranges2.length == 1)
                        $(ranges2[0]).val(parseInt(pair[1]));
                    if(radioContainers.length == 1) {
                        /*var radioBtnss = $(radioContainers[0]).parent().find("input[type='radio']").toArray();
                         if(radioBtnss.length == 3) {
                         for(var j = 0; j < radioBtnss.length; j++) {
                         radioBtnss[j].checked = false;
                         }
                         }*/
                        var radioButton = $(radioContainers[0]).find("input[type='radio']").toArray()[0];
                        radioButton.checked = pair[1] == "1";
                    }
		    if(radioContainers2.length == 1) {
                        var radioButton = $(radioContainers2[0]).find("input[type='radio']").toArray()[0];
                        radioButton.checked = pair[1] == "1";
                    }
                }
                var radioButtonsStop = $("div.roomControls div.item div.controls div.radioContainer input.btnStop").toArray();
                for(var i = 0; i < radioButtonsStop.length; i++) {
                    var radioBtns = $(radioButtonsStop[i]).parent().parent().find("input[type='radio']");
                    if(radioBtns.length == 3) {
                        var out1 = radioBtns[0].checked;
                        var out2 = radioBtns[2].checked;
                        radioButtonsStop[i].checked = !(out1 || out2);
                    }
                }
                var radioButtonsStop2 = $("div.lastUsedControls div.item div.controls div.radioContainer input.btnStop").toArray();
		for(var i = 0; i < radioButtonsStop2.length; i++) {
                    var radioBtns = $(radioButtonsStop2[i]).parent().parent().find("input[type='radio']");
                    if(radioBtns.length == 3) {
                        var out1 = radioBtns[0].checked;
                        var out2 = radioBtns[2].checked;
                        radioButtonsStop2[i].checked = !(out1 || out2);
                    }
                }
            }

            function updatePulsingOutputs(res) {
                var pairs = res.split("-");
                for(var i = 0; i < pairs.length; i++) {
                    var pair = pairs[i].split(":");
                    var radioContainers = $("div.roomControls div.item div.controls "
                            + "div.radioContainerSlowMotion[data-output='" + pair[0] +"']").toArray();
                    if(radioContainers.length == 1) {
                        /*var radioBtnss = $(radioContainers[0]).parent().find("input[type='radio']").toArray();
                         if(radioBtnss.length == 3) {
                         for(var j = 0; j < radioBtnss.length; j++) {
                         radioBtnss[j].checked = false;
                         }
                         }*/
                        var radioButton = $(radioContainers[0]).find("input[type='radio']").toArray()[0];
                        radioButton.checked = pair[1] == "1";
                    }
                    var radioContainers2= $("div.lastUsedControls div.item div.controls "
                            + "div.radioContainerSlowMotion[data-output='" + pair[0] +"']").toArray();
		    if(radioContainers2.length == 1) {
                        var radioButton = $(radioContainers2[0]).find("input[type='radio']").toArray()[0];
                        radioButton.checked = pair[1] == "1";
                    }
                }
                var radioButtonsStop = $("div.roomControls div.item div.controls div.radioContainerSlowMotion input.btnStop").toArray();
                for(var i = 0; i < radioButtonsStop.length; i++) {
                    var radioBtns = $(radioButtonsStop[i]).parent().parent().find("input[type='radio']");
                    if(radioBtns.length == 3) {
                        var out1 = radioBtns[0].checked;
                        var out2 = radioBtns[2].checked;
                        radioButtonsStop[i].checked = !(out1 || out2);
                    }
                }
                var radioButtonsStop2 = $("div.lastUsedControls div.item div.controls div.radioContainerSlowMotion input.btnStop").toArray();
		for(var i = 0; i < radioButtonsStop2.length; i++) {
                    var radioBtns = $(radioButtonsStop2[i]).parent().parent().find("input[type='radio']");
                    if(radioBtns.length == 3) {
                        var out1 = radioBtns[0].checked;
                        var out2 = radioBtns[2].checked;
                        radioButtonsStop2[i].checked = !(out1 || out2);
                    }
                }
            }

            function updateActiveTherms(res) {
                var pairs = res.split("-");
                for(var i = 0; i < pairs.length; i++) {
                    var pair = pairs[i].split(":");
                    var checkboxes = $("div.roomControls div.item div.controls div.tempactive label "
                            + "input[data-output='" + pair[0] +"']").toArray();
                    if(checkboxes.length == 1)
                        checkboxes[0].checked = pair[1] == "1";
                    var checkboxes2 = $("div.lastUsedControls div.item div.controls div.tempactive label "
                            + "input[data-output='" + pair[0] +"']").toArray();
                    if(checkboxes2.length == 1)
                        checkboxes2[0].checked = pair[1] == "1";
                }
            }

            function updateThermsTarget(res) {
                var pairs = res.split("-");
                for(var i = 0; i < pairs.length; i++) {
                    var pair = pairs[i].split(":");
                    var numberElems = $("div.roomControls div.item div.controls div.tempactive "
                            + "input[type='number'][data-output='" + pair[0] +"']").toArray();
                    if(numberElems.length == 1)
                        $(numberElems[0]).val(parseInt(pair[1]));
                    var numberElems2 = $("div.lastUsedControls div.item div.controls div.tempactive "
                            + "input[type='number'][data-output='" + pair[0] +"']").toArray();
                    if(numberElems2.length == 1)
                        $(numberElems2[0]).val(parseInt(pair[1]));
                }
            }

            function executeMacro(controls) {
                var cmdString = "";
                for(var i = 0; i < controls.length; i++) {
                    var state = controls[i] < 0 ? "0" : "1";
                    var value = controls[i] < 0 ? (controls[i]*-1)+"" : controls[i]+"";
                    cmdString += "-" + value + ":" + state;
                }
                cmdString = cmdString.substr(1);
                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/voidCatch/outputs/' + cmdString;
                document.getElementsByTagName('head')[0].appendChild(script);
            }

            $(document).ready(function() {
                $("div.roomControls").fadeOut(0);
                $("div.rooms").fadeOut(0);
                $("div.mainMenu").fadeOut(0);
                $("div.security").fadeOut(0);
		$("div.securityCameras").fadeOut(0);
                loadMainMenu();

                var counter = 0;
                setInterval(function() {
			if(currentMenu == 2) {
				requestUpdateOutputs();
				requestUpdatePulsingOutputs();
				requestUpdateTemperatures();
				requestUpdateActiveTherms();
				if(counter % 6 == 0)
					requestUpdateThermsTarget();
			}
			if(currentMenu == 0) {
				requestUpdateOutputsLastUsed();
				requestUpdatePulsingOutputsLastUsed();
				requestUpdateTemperaturesLastUsed();
				requestUpdateActiveThermsLastUsed();
				if(counter % 6 == 0)
					requestUpdateThermsTargetLastUsed();
			}
			if(currentMenu == 4) {
				requestSecurityStates();
				if(counter % 2 == 0)
					requestActiveSecurityInputs();
			}
			if(currentMenu == 6)
				requestSimulatorState();
			counter++;
                }, 500);
				
		$("div.numkeyboard button").click(function() {
			var val = $(this).html();
			if(val == "←") {
				$("div.numkeyboard div.value span").html("&nbsp;");
				$("div.numkeyboard div.value span").css("-webkit-text-security","none");
				$("div.numkeyboard div.value span").css("-moz-text-security","none");
				$("div.numkeyboard div.value span").css("text-security","none");
			} /*else if(val == "AKTIVOVAT" || val=="DEAKTIVOVAT") {
				if(securityState == 1) {
					requestSecurityDisable($("div.numkeyboard div.value span").html());
				} else {
					requestSecurityEnable($("div.numkeyboard div.value span").html());
				}
				$("div.numkeyboard div.value span").html("&nbsp;");
			}*/ else {
				$("div.numkeyboard div.value span").css("-webkit-text-security","disc");
				$("div.numkeyboard div.value span").css("-moz-text-security","disc");
				$("div.numkeyboard div.value span").css("text-security","disc");
				if($("div.numkeyboard div.value span").html() == "&nbsp;")
					$("div.numkeyboard div.value span").html("");
				$("div.numkeyboard div.value span").html($("div.numkeyboard div.value span").html()+val);
			}
		});
		
		$("div.security div.securityitems div.sitem").click(function() {
			if($(this).data("sid") != '') {
				if($(this).hasClass("sitemred"))
					requestSecurityDisable($("div.numkeyboard div.value span").html(),$(this).data("sid"));
				else
					requestSecurityEnable($("div.numkeyboard div.value span").html(),$(this).data("sid"));
			}
		});
            });

            function loadMainMenu() {
                $("div#navigationBar div.location").html("<span>Hlavní menu</span>");

		//when leaving security system section, clear typed password
		if(currentMenu == 4) {
			$("div.numkeyboard div.value span").html("&nbsp;");
			$("div.numkeyboard div.value span").css("-webkit-text-security","none");
			$("div.numkeyboard div.value span").css("-moz-text-security","none");
			$("div.numkeyboard div.value span").css("text-security","none");
		}
			
                currentMenu = 0;
                $("div.roomControls").fadeOut(100);
                $("div.rooms").fadeOut(100);
                $("div.macros").fadeOut(100);
                $("div.security").fadeOut(100);
		$("div.securityCameras").fadeOut(100);
		$("div.simulator").fadeOut(100);

		loadLastUsedControls();

                setTimeout(function() {
                    $("div.mainMenu").fadeIn(100);
                    $("div.lastUsedControls").fadeIn(100);
                }, 100);
            }

            function loadSecuritySystem() {
                $("div#navigationBar div.location").html("<span>Bezpečnostní systém</span>");
		requestSecurityInputs();

                currentMenu = 4;
                $("div.roomControls").fadeOut(100);
                $("div.rooms").fadeOut(100);
                $("div.macros").fadeOut(100);
                $("div.mainMenu").fadeOut(100);
		$("div.securityCameras").fadeOut(100);
		$("div.simulator").fadeOut(100);
		$("div.lastUsedControls").fadeOut(100);

                setTimeout(function() {
                    $("div.security").fadeIn(100);
                }, 100);
            }

            function loadMacros() {
                $("div#navigationBar div.location").html("<span>Makra</span>");

                currentMenu = 3;
                $("div.mainMenu").fadeOut(100);
                $("div.roomControls").fadeOut(100);
                $("div.rooms").fadeOut(100);
                $("div.security").fadeOut(100);
		$("div.securityCameras").fadeOut(100);
		$("div.simulator").fadeOut(100);
		$("div.lastUsedControls").fadeOut(100);
                var macros = $("div.macros");
                macros.toArray()[0].innerHTML = "";

                var rc = DataStore.macros;

                for(var i = 0; i < rc.length; i++) {
                    macros.toArray()[0].appendChild(DataStore.generateMacrosItemElement(i, rc[i]));
                }

                setTimeout(function() {
                    macros.fadeIn(100);
                }, 100);
            }

            function loadRooms() {
                $("div#navigationBar div.location").html("<span>Místnosti</span>");

                currentMenu = 1;
                $("div.mainMenu").fadeOut(100);
                $("div.roomControls").fadeOut(100);
                $("div.macros").fadeOut(100);
                $("div.security").fadeOut(100);
		$("div.securityCameras").fadeOut(100);
		$("div.simulator").fadeOut(100);
		$("div.lastUsedControls").fadeOut(100);
                var rooms = $("div.rooms");
                rooms.toArray()[0].innerHTML = "";

                var rc = DataStore.rooms;

                for(var i = 0; i < rc.length; i++) {
                    rooms.toArray()[0].appendChild(DataStore.generateRoomsItemElement(rc[i]));
                }

                setTimeout(function() {
                    rooms.fadeIn(100);
                }, 100);
            }

            function loadSecurityCameras() {
                $("div#navigationBar div.location").html("<span>Kamery</span>");

                currentMenu = 5;
                $("div.roomControls").fadeOut(100);
                $("div.rooms").fadeOut(100);
                $("div.macros").fadeOut(100);
                $("div.mainMenu").fadeOut(100);
		$("div.security").fadeOut(100);
		$("div.simulator").fadeOut(100);
		$("div.lastUsedControls").fadeOut(100);

                setTimeout(function() {
			if($("div.securityCameras iframe").attr("src") == "")
				$("div.securityCameras iframe").attr("src", "<?=($_CONFIG['cameras_source']) ?>");
			$("div.securityCameras").fadeIn(100);
                }, 100);
		
		var winh= $(window).height()-65;
		$("div.securityCameras iframe").css("height",winh+'px');
            }

            function loadSimulator() {
                $("div#navigationBar div.location").html("<span>Dovolená</span>");

                currentMenu = 6;
                $("div.roomControls").fadeOut(100);
                $("div.rooms").fadeOut(100);
                $("div.macros").fadeOut(100);
                $("div.mainMenu").fadeOut(100);
		$("div.security").fadeOut(100);
		$("div.securityCameras").fadeOut(100);
		$("div.lastUsedControls").fadeOut(100);

                setTimeout(function() {
                    $("div.simulator").fadeIn(100);
                }, 100);
		
		setTimeout(function() {
			requestSimulatorTimes();
		}, 1000);
            }

            function loadControlsByRoomId(id) {
                $("div#navigationBar div.location").html('<span>'+DataStore.getRoomById(id).name+'</span>');

                currentMenu = 2;
                $("div.mainMenu").fadeOut(100);
                $("div.rooms").fadeOut(100);
                $("div.macros").fadeOut(100);
                $("div.security").fadeOut(100);
		$("div.securityCameras").fadeOut(100);
		$("div.lastUsedControls").fadeOut(100);
                var roomControls = $("div.roomControls");
                roomControls.toArray()[0].innerHTML = "";

                var rc = DataStore.getControlsByRoom(id);

                for(var i = 0; i < rc.length; i++) {
                    roomControls.toArray()[0].appendChild(DataStore.generateRoomControlsItemElement(rc[i]));
                }
                var switches = document.querySelectorAll('div.roomControls input[type="checkbox"].ios-switch');

                for (var i=0, sw; sw = switches[i++]; ) {
                    var div = document.createElement('div');
                    div.className = 'switch';
                    sw.parentNode.insertBefore(div, sw.nextSibling);
                }

                setTimeout(function() {
                    roomControls.fadeIn(100);
                }, 100);

                $("input[type='checkbox'].ios-switch").click(function(event) {
                    event.preventDefault();
                    var check = this;
                    requestOutputChange($(check).attr("data-output"), check.checked ? 1 : 0);
		    updateOutputLastUsage($(check).attr("data-dbid"));
                });

                $("div.tempactive input[type='checkbox'].ios-switch").click(function(event) {
                    event.preventDefault();
                    var check = this;
                    requestThermActiveChange($(check).attr("data-output"), check.checked ? 1 : 0);
		    updateOutputLastUsage($(check).attr("data-dbid"));
                });

                $("input[type='range']").change(function(event) {
                    event.preventDefault();
                    var check = this;
                    requestOutputChange($(check).attr("data-output"), $(check).val());
		    updateOutputLastUsage($(check).attr("data-dbid"));
                });

                $("input[type='number']").change(function(event) {
                    event.preventDefault();
                    var check = this;
                    requestThermTargetChange($(check).attr("data-output"), $(check).val());
		    updateOutputLastUsage($(check).attr("data-dbid"));
                });

                $("div.radioContainer input[type='radio']").click(function(event) {
                    event.preventDefault();
                    var parent = $(this).parent();
                    var cssClass = $(this).attr("class");
                    if(cssClass == "btnDown" || cssClass == "btnUp") {
                        requestOutputChange(parent.attr("data-output"), 1);
			updateOutputLastUsage(parent.attr("data-dbid"));
                    } else if(cssClass == "btnStop") {
                        var outputs = parent.attr("data-output").split(",");
			updateOutputLastUsage(parent.attr("data-dbid"));
                        requestOutputChange(outputs[0], 0);
                        setTimeout(function() {
                            requestOutputChange(outputs[1], 0);
                        }, 200);
                    }
                });
	   }
		
	   function loadLastUsedControls() {
                var roomControls = $("div.lastUsedControls");
                roomControls.toArray()[0].innerHTML = "<div class='separator'>Poslední použité</div>";

                var rc = DataStore.getLastUsedControls();
		var usedRooms = [];

                for(var i = 0; i < rc.length; i++) {
                    if(!(usedRooms.indexOf(rc[i].room) > -1))
			usedRooms.push(rc[i].room);
                }
		
		for(var roomindex = 0; roomindex < usedRooms.length; roomindex++) {
			var currentRoom = usedRooms[roomindex];
			var roomName = DataStore.getRoomById(currentRoom).name;
			roomControls.toArray()[0].innerHTML+= "<div class='roomName'>"+roomName+"</div>";

			for(var i = 0; i < rc.length; i++)
				if(rc[i].room == currentRoom)
					roomControls.toArray()[0].appendChild(DataStore.generateRoomControlsItemElement(rc[i]));
		}
		
                var switches = document.querySelectorAll('div.lastUsedControls input[type="checkbox"].ios-switch');

                for (var i=0, sw; sw = switches[i++]; ) {
                    var div = document.createElement('div');
                    div.className = 'switch';
                    sw.parentNode.insertBefore(div, sw.nextSibling);
                }

                $("input[type='checkbox'].ios-switch").click(function(event) {
                    event.preventDefault();
                    var check = this;
                    requestOutputChange($(check).attr("data-output"), check.checked ? 1 : 0);
		    updateOutputLastUsage($(check).attr("data-dbid"));
                });

                $("div.tempactive input[type='checkbox'].ios-switch").click(function(event) {
                    event.preventDefault();
                    var check = this;
                    requestThermActiveChange($(check).attr("data-output"), check.checked ? 1 : 0);
		    updateOutputLastUsage($(check).attr("data-dbid"));
                });

                $("input[type='range']").change(function(event) {
                    event.preventDefault();
                    var check = this;
                    requestOutputChange($(check).attr("data-output"), $(check).val());
		    updateOutputLastUsage($(check).attr("data-dbid"));
                });

                $("input[type='number']").change(function(event) {
                    event.preventDefault();
                    var check = this;
                    requestThermTargetChange($(check).attr("data-output"), $(check).val());
		    updateOutputLastUsage($(check).attr("data-dbid"));
                });

                $("div.radioContainer input[type='radio']").click(function(event) {
                    event.preventDefault();
                    var parent = $(this).parent();
                    var cssClass = $(this).attr("class");
                    if(cssClass == "btnDown" || cssClass == "btnUp") {
                        requestOutputChange(parent.attr("data-output"), 1);
			updateOutputLastUsage(parent.attr("data-dbid"));
                    } else if(cssClass == "btnStop") {
                        var outputs = parent.attr("data-output").split(",");
			updateOutputLastUsage(parent.attr("data-dbid"));
                        requestOutputChange(outputs[0], 0);
                        setTimeout(function() {
                            requestOutputChange(outputs[1], 0);
                        }, 200);
                    }
                });
				
		$("div.sunblindpulse button.controlUp").click(function(event) {
			var out = $(this).attr("data-output");
			requestPulsingStart(out, 1, 100);
			setTimeout(function(){ 
				requestPulsingStop(out);
			}, 500);
			updateOutputLastUsage($(this).attr("data-dbid"));
                });
				
		$("div.sunblindpulse button.controlDown").click(function(event) {
			var out = $(this).attr("data-output");
			requestPulsingStart(out, 1, 100);
			setTimeout(function(){ 
				requestPulsingStop(out);
			}, 500);
			updateOutputLastUsage($(this).attr("data-dbid"));
                });
				
		$("button.controlUp").click(function() {
			var parent = $(this).parent();
			var num = parent.find("input[type=number]");
			var newVal = parseInt(num.val())+1;
			requestThermTargetChange(num.attr("data-output"), newVal);
			num.val(newVal);
			updateOutputLastUsage(num.attr("data-dbid"));
		});
		
		$("button.controlDown").click(function() {
			var parent = $(this).parent();
			var num = parent.find("input[type=number]");
			var newVal = parseInt(num.val())-1;
			requestThermTargetChange(num.attr("data-output"), newVal);
			num.val(newVal);
			updateOutputLastUsage(num.attr("data-dbid"));
		});
            }

            function goBack() {
		//when leaving security system section, clear typed password
		if(currentMenu == 4) {
			$("div.numkeyboard div.value span").html("&nbsp;");
			$("div.numkeyboard div.value span").css("-webkit-text-security","none");
			$("div.numkeyboard div.value span").css("-moz-text-security","none");
			$("div.numkeyboard div.value span").css("text-security","none");
		}
		
                switch(currentMenu) {
                    case 1:
                    case 3:
                    case 4:
		    case 5:
		    case 6:
                        loadMainMenu();
                        break;
                    case 2:
                        loadRooms();
                        break;
                    default:
                        break;
                }
            }
	    
		function updateOutputLastUsage(itemId) {
			var timeNow = (new Date()).getTime();
			console.log(timeNow);
			$.ajax({
				method: "GET",
				url: "phplib/logLastUsage.php",
				data: { id: itemId, time: timeNow }
			});
		}

            function notification(text) {
                var notifications = $("body").toArray()[0];
                var notification = document.createElement("div");
                notification.setAttribute("class", "notification");
                notification.innerHTML = "<div class='notifcenter'>" + text + "</div>";
                notifications.appendChild(notification);
                $(notification).fadeIn(100);
                setTimeout(function() {
                    $(notification).fadeOut(100);
                    setTimeout(function() {
                        notifications.removeChild(notification);
                    }, 100);
                }, 1100);
            }
        </script>
    </head>
    <body onload="">
		<?php if(!isset($_SESSION["authorized"]) || !$_SESSION["authorized"]) :?>
			<script>window.location.replace("./index.php");</script>
		<?php endif; ?>
        <div id="navigationBar">
            <div class="goBack" onclick="goBack()"><img src="res/icons/Reply%20All%20Arrow%20Filled-50.png" /></div>
            <div class="location"><span>Lokace</span></div>
            <div class="goHome" onclick="loadMainMenu()"><img src="res/icons/Home%20Filled-50.png" /></div>
        </div>
        <div class="mainMenu">
            <div class="item" onclick="loadRooms()">
                <div class="img"><img src="res/icons/Room%20Filled-100.png" /></div>
                <div class="label">Místnosti</div>
            </div>
            <div class="item" onclick="loadMacros()">
                <div class="img"><img src="res/icons/TV%20Filled-100.png" /></div>
                <div class="label">Makra</div>
            </div>
            <div class="item" onclick="loadSecuritySystem()">
                <div class="img"><img src="res/icons/Web%20Shield%20Filled-100.png" /></div>
                <div class="label">Zabezpečení</div>
            </div>
            <div class="item" onclick="loadSecurityCameras()">
                <div class="img"><img src="res/icons/Bullet%20Camera%20Filled-100.png" /></div>
                <div class="label">Kamery</div>
            </div>
            <div class="item" onclick="loadSimulator()">
                <div class="img"><img src="res/icons/Beach%20Filled-100.png" /></div>
                <div class="label">Dovolená</div>
            </div>
        </div>
	<div class="lastUsedControls">
	</div>
        <div class="rooms">
        </div>
        <div class="macros">
        </div>
        <div class="roomControls">
        </div>
	<div class="securityCameras">
		<iframe src=""></iframe>
	</div>
	<div class="simulator">
	<table>
		<tr>
			<td>Počáteční datum:</td>
			<td style="text-align: right;"><input type="text" id="startdatepicker" style="text-align: right;" class="date" readonly /></td>
		</tr>
		<tr>
			<td>Konečné datum:</td>
			<td style="text-align: right;"><input type="text" id="enddatepicker" style="text-align: right;" class="date" readonly /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="text-align: right;"><button id="btnlastweek" class="macrobtn" style="margin-top: 10px; margin-bottom: 20px; margin-right: 5px;">Poslední týden</button></td>
		</tr>
	</table>
	<!---->
		<table>
			<tr>
				<td style="width: 60%;">Simulace přítomnosti</td>
				<td style="text-align: right; width: 40%;">
					<div style="text-align: right; transform: translate(9px,0);">
						<label>
						<input type="checkbox" class="ios-switch" value="0" id="switchEnableSimulator" />
						<div class="switch"></div>
						</label>
					</div>
				</td>
			</tr>
		</table>
	<!---->
	<script>
		$(function() {
			$("#startdatepicker").datepicker({
				firstDay: 1,
				monthNames: [ "Leden", "Únor", "Březen", "Duben", "Květen", "Červen", "Červenec", "Srpen", "Září", "Říjen", "Listopad", "Prosinec" ],
				dateFormat:"dd.mm.yy",
				dayNamesMin: [ "Ne", "Po", "Út", "St", "Čt", "Pá", "So" ],
				duration: "slow"
			});
			$("#startdatepicker").val("Nastavit datum...");
			$("#enddatepicker").datepicker({
				firstDay: 1,
				monthNames: [ "Leden", "Únor", "Březen", "Duben", "Květen", "Červen", "Červenec", "Srpen", "Září", "Říjen", "Listopad", "Prosinec" ],
				dateFormat:"dd.mm.yy",
				dayNamesMin: [ "Ne", "Po", "Út", "St", "Čt", "Pá", "So" ],
				duration: "slow"
			});
			$("#enddatepicker").val("Nastavit datum...");
			$("#btnlastweek").click(function() {
				var dateBefore = new Date();
				dateBefore.setDate(dateBefore.getDate() - 7);
				var dateNow = new Date();
				$("#startdatepicker").val($.datepicker.formatDate('dd.mm.yy', dateBefore));
				$("#enddatepicker").val($.datepicker.formatDate('dd.mm.yy', dateNow));
			});
			$("#switchEnableSimulator").click(function(event) {
				event.preventDefault();
				if(this.checked) {
					//turn on simulator
					var startDate=$("#startdatepicker").val();
					startDate=startDate.split(".");
					var newStartDate=startDate[1]+"/"+startDate[0]+"/"+startDate[2];
					var startDayInt = new Date(newStartDate).getTime();
					var endDate=$("#enddatepicker").val();
					endDate=endDate.split(".");
					var newEndDate=endDate[1]+"/"+endDate[0]+"/"+endDate[2];
					var endDayInt = new Date(newEndDate).getTime();
					if(isNaN(startDayInt)) {
						notification('Počáteční datum není platné!');
						return;
					}
					if(isNaN(endDayInt)) {
						notification('Konečné datum není platné!');
						return;
					}
					requestSimulatorStart(startDayInt, endDayInt);
				} else {
					//turn off simulator
					requestSimulatorStop();
				}
			});
		});
	</script>
	</div>
        <div class="security">
		<div class="state"></div>
		<div class="numkeyboard"><!--
			--><div class="value"><span>&nbsp;</span><div><!--
				--><button>7</button><!--
				--><button>8</button><!--
				--><button>9</button><!--
				--><button>4</button><!--
				--><button>5</button><!--
				--><button>6</button><!--
				--><button>1</button><!--
				--><button>2</button><!--
				--><button>3</button><!--
				--><button class="activate" style="visibility: hidden;">&nbsp;</button><!--
				--><button>0</button><!--
				--><button style="background-color: #a3a3a3;">&#8592;</button><!--
				--></div>
			</div>
		</div>
		<!--<div class="scontrol">
			<div class="turnallon">Zapnout vše</div><div class="turnalloff">Vypnout vše</div><div style="clear: both;"></div>
		</div>-->
		<div class="securityitems">
			<?php $rsSecItems = $database->executeQuery("SELECT * FROM `security_systems`;"); ?>
			<?php while ($row = $rsSecItems->fetch_row()) :?>
			<div class="sitem sitemred" data-sid="<?=($row[0]) ?>"><?=($row[1]) ?></div>
			<script>securitySystems.push(<?=($row[0]) ?>);</script>
			<?php endwhile; ?>
		</div>
		<div class="activesecurityinputs">
			<div class="title"><img src="res/icons/Warning%20Shield%20Filled-50.png" /></div>
			<?php $rsSecItems = $database->executeQuery("SELECT * FROM `security_systems`;"); ?>
			<?php while ($row = $rsSecItems->fetch_row()) :?>
			<div class="securitygroup" data-sid="<?=($row[0]) ?>">
				<div class="securitytitle"><?=($row[1]) ?></div>
				<div class="inputs">
				</div>
			</div>
			<?php endwhile; ?>
		</div>
	</div>
    </body>
</html>
