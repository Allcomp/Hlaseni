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
        <script src="jslib/Room.js"></script>
        <script src="jslib/Control.js"></script>
        <script src="jslib/Macro.js"></script>
        <script src="jslib/DataStore.js"></script>
        <link rel="stylesheet" type="text/css" href="css/main.css" />
        <link rel="stylesheet" type="text/css" href="css/switches.css" />
        <link rel="stylesheet" type="text/css" href="css/inputsRange.css" />
        <link rel="stylesheet" type="text/css" href="css/radioButtons.css" />
        <meta content='width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0' name='viewport' />
        <?php include("./phplib/loadJavascriptData.php"); ?>
        <script>

            var currentMenu = 0;

            var rangeUpdateCounter = -1;

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

            function requestSecurityEnable(code) {
                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/securityEnableResult/securityenable/' + code;
                document.getElementsByTagName('head')[0].appendChild(script);
            }

            function requestSecurityDisable(code) {
                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/securityDisableResult/securitydisable/' + code;
                document.getElementsByTagName('head')[0].appendChild(script);
            }

            function requestSecurityState() {
				if(currentMenu != 4) return;
                var script = document.createElement('script');
                script.src = 'http://<?=$_CONFIG['ip']?>:<?=$_CONFIG['port']?>/securityStateResult/securitystateget';
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

            function updateTemperatures(res) {
                var pairs = res.split("-");
                for(var i = 0; i < pairs.length; i++) {
                    var pair = pairs[i].split(":");
                    var divsValue = $("div.roomControls div.item div.controls div.tempval[data-output='" + pair[0] +"']").toArray();
                    if(divsValue.length == 1) {
                        divsValue[0].innerHTML = (parseFloat(pair[1]).toFixed(1)) + " °C";
                    }
                }
            }

            function voidCatch(c) {}
			
			var securityEnabledMessage = "Bezpečnostní systém je nyní zapnutý.";
			var securityDisabledMessage = "Bezpečnostní systém je nyní vypnutý.";
			var securityState = 0;
			
			function securityStateResult(res) {
				if(res=="1") {
					$("div.security div.state").html(securityEnabledMessage);
					$("div.security div.numkeyboard button.activate").html("DEAKTIVOVAT");
					securityState = 1;
				} else {
					$("div.security div.state").html(securityDisabledMessage);
					$("div.security div.numkeyboard button.activate").html("AKTIVOVAT");
					securityState = 0;
				}
			}
			
			function securityEnableResult(res) {
				if(res=="ok")
					notification("Bezpečnostní systém byl aktivován.");
				else
					notification("Aktivace selhala, špatné heslo!");
			}
			
			function securityDisableResult(res) {
				if(res=="ok")
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
                    var ranges = $("div.roomControls div.item div.controls "
                            + "input[data-output='" + pair[0] +"']").toArray();
                    var radioContainers = $("div.roomControls div.item div.controls "
                            + "div.radioContainer[data-output='" + pair[0] +"']").toArray();
                    if(checkboxes.length == 1)
                        checkboxes[0].checked = pair[1] == "1";
                    if(ranges.length == 1)
                        $(ranges[0]).val(parseInt(pair[1]));
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
            }

            function updateActiveTherms(res) {
                var pairs = res.split("-");
                for(var i = 0; i < pairs.length; i++) {
                    var pair = pairs[i].split(":");
                    var checkboxes = $("div.roomControls div.item div.controls div.tempactive label "
                            + "input[data-output='" + pair[0] +"']").toArray();
                    if(checkboxes.length == 1)
                        checkboxes[0].checked = pair[1] == "1";
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
					if(currentMenu == 4)
						requestSecurityState();
                    counter++;
                }, 500);
				
				$("div.numkeyboard button").click(function() {
					var val = $(this).html();
					if(val == "←") {
						$("div.numkeyboard div.value span").html("&nbsp;");
					} else if(val == "AKTIVOVAT" || val=="DEAKTIVOVAT") {
						if(securityState == 1) {
							requestSecurityDisable($("div.numkeyboard div.value span").html());
						} else {
							requestSecurityEnable($("div.numkeyboard div.value span").html());
						}
						$("div.numkeyboard div.value span").html("&nbsp;");
					} else {
						if($("div.numkeyboard div.value span").html() == "&nbsp;")
							$("div.numkeyboard div.value span").html("");
						$("div.numkeyboard div.value span").html($("div.numkeyboard div.value span").html()+val);
					}
				});
            });

            function loadMainMenu() {
                $("div#navigationBar div.location").html("Hlavní menu");

                currentMenu = 0;
                $("div.roomControls").fadeOut(100);
                $("div.rooms").fadeOut(100);
                $("div.macros").fadeOut(100);
                $("div.security").fadeOut(100);

                setTimeout(function() {
                    $("div.mainMenu").fadeIn(100);
                }, 100);
            }

            function loadSecuritySystem() {
                $("div#navigationBar div.location").html("Bezpečnostní systém");

                currentMenu = 4;
                $("div.roomControls").fadeOut(100);
                $("div.rooms").fadeOut(100);
                $("div.macros").fadeOut(100);
                $("div.mainMenu").fadeOut(100);

                setTimeout(function() {
                    $("div.security").fadeIn(100);
                }, 100);
            }

            function loadMacros() {
                $("div#navigationBar div.location").html("Makra");

                currentMenu = 3;
                $("div.mainMenu").fadeOut(100);
                $("div.roomControls").fadeOut(100);
                $("div.rooms").fadeOut(100);
                $("div.security").fadeOut(100);
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
                $("div#navigationBar div.location").html("Místnosti");

                currentMenu = 1;
                $("div.mainMenu").fadeOut(100);
                $("div.roomControls").fadeOut(100);
                $("div.macros").fadeOut(100);
                $("div.security").fadeOut(100);
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

            function loadControlsByRoomId(id) {
                $("div#navigationBar div.location").html(DataStore.getRoomById(id).name);

                currentMenu = 2;
                $("div.mainMenu").fadeOut(100);
                $("div.rooms").fadeOut(100);
                $("div.macros").fadeOut(100);
                $("div.security").fadeOut(100);
                var roomControls = $("div.roomControls");
                roomControls.toArray()[0].innerHTML = "";

                var rc = DataStore.getControlsByRoom(id);

                for(var i = 0; i < rc.length; i++) {
                    roomControls.toArray()[0].appendChild(DataStore.generateRoomControlsItemElement(rc[i]));
                }
                var switches = document.querySelectorAll('input[type="checkbox"].ios-switch');

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
                });

                $("div.tempactive input[type='checkbox'].ios-switch").click(function(event) {
                    event.preventDefault();
                    var check = this;
                    requestThermActiveChange($(check).attr("data-output"), check.checked ? 1 : 0);
                });

                $("input[type='range']").change(function(event) {
                    event.preventDefault();
                    var check = this;
                    requestOutputChange($(check).attr("data-output"), $(check).val());
                });

                $("input[type='number']").change(function(event) {
                    event.preventDefault();
                    var check = this;
                    requestThermTargetChange($(check).attr("data-output"), $(check).val());
                });

                $("div.radioContainer input[type='radio']").click(function(event) {
                    event.preventDefault();
                    var parent = $(this).parent();
                    var cssClass = $(this).attr("class");
                    if(cssClass == "btnDown" || cssClass == "btnUp") {
                        requestOutputChange(parent.attr("data-output"), 1);
                    } else if(cssClass == "btnStop") {
                        var outputs = parent.attr("data-output").split(",");
                        requestOutputChange(outputs[0], 0);
                        setTimeout(function() {
                            requestOutputChange(outputs[1], 0);
                        }, 200);
                    }
                });

                $("div.radioContainerSlowMotion input[type='radio']").click(function(event) {
                    event.preventDefault();
                    var parent = $(this).parent();
                    var cssClass = $(this).attr("class");
					var radioBtns = parent.parent().find("div.radioContainerSlowMotion");
					var out1 = 0;
					var out2 = 0;
					if(radioBtns.length == 3) {
                        out1 = $(radioBtns[0]).attr("data-output");
                        out2 = $(radioBtns[2]).attr("data-output");
                    }
                    if(cssClass == "btnDown") {
						requestPulsingStop(out2);
                        requestPulsingStart(out1, 10, 10);
                    } else if(cssClass == "btnUp") {
						requestPulsingStop(out1);
                        requestPulsingStart(out2, 10, 10);
					} else if(cssClass == "btnStop") {
						requestPulsingStop(out1);
						requestPulsingStop(out2);
                    }
                });
				
				$("button.controlUp").click(function() {
					var parent = $(this).parent();
					var num = parent.find("input[type=number]");
					var newVal = parseInt(num.val())+1;
                    requestThermTargetChange(num.attr("data-output"), newVal);
					num.val(newVal);
				});
				
				$("button.controlDown").click(function() {
					var parent = $(this).parent();
					var num = parent.find("input[type=number]");
					var newVal = parseInt(num.val())-1;
                    requestThermTargetChange(num.attr("data-output"), newVal);
					num.val(newVal);
				});
            }

            function goBack() {
                switch(currentMenu) {
                    case 1:
                    case 3:
                    case 4:
                        loadMainMenu();
                        break;
                    case 2:
                        loadRooms();
                        break;
                    default:
                        break;
                }
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
            <div class="location">Lokace</div>
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
            <div class="item" onclick="">
                <div class="img"><img src="res/icons/Settings%203%20Filled-100.png" /></div>
                <div class="label">Nastavení</div>
            </div>
        </div>
        <div class="rooms">
        </div>
        <div class="macros">
        </div>
        <div class="roomControls">
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
				--><button>&#8592;</button><!--
				--><button>0</button><!--
				--><button class="activate">AKTIVOVAT</button><!--
			--></div>
        </div>
    </body>
</html>