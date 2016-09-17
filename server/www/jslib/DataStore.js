/**
 * Created by Samuel on 14.08.2015.
 */

var DataStore = {};

DataStore.rooms = [];
DataStore.controls = [];
DataStore.macros = [];
DataStore.lastUsedControls = [];

DataStore.addRoom = function(room) {
    DataStore.rooms.push(room);
};

DataStore.addControl = function(control) {
    DataStore.controls.push(control);
};

DataStore.addMacro = function(macro) {
    DataStore.macros.push(macro);
};

DataStore.addLastUsedControl = function(control) {
    DataStore.lastUsedControls.push(control);
};

DataStore.getRoomById = function (id) {
    for(var i = 0; i < DataStore.rooms.length; i++)
        if(DataStore.rooms[i].id == id)
            return DataStore.rooms[i];

    return null;
};

DataStore.getControlsByRoom = function(roomid) {
var conts = [];
for(var i = 0; i < DataStore.controls.length; i++) {
    if(DataStore.controls[i].room == roomid)
        conts.push(DataStore.controls[i]);
}
return conts;
};

DataStore.getLastUsedControls = function() {
return DataStore.lastUsedControls;
};

DataStore.generateMacrosItemElement = function(id, macro) {
    var elem = document.createElement("div");
    elem.setAttribute("class", "item");
    elem.setAttribute("onclick", "executeMacro(DataStore.macros[" + id + "].controls); notification('aplikováno');");

    var labelElem = document.createElement("div");
    labelElem.setAttribute("class", "label");
    labelElem.innerHTML = macro.name;
    elem.appendChild(labelElem);

    var controlsElem = document.createElement("div");
    controlsElem.setAttribute("class", "controls");
    controlsElem.innerHTML = "<img src='res/icons/Forward-100.png' />";
    elem.appendChild(controlsElem);

    return elem;
};

DataStore.generateRoomsItemElement = function(room) {
    var elem = document.createElement("div");
    elem.setAttribute("class", "item");
    elem.setAttribute("onclick", "loadControlsByRoomId(" + room.id + ")");

    var labelElem = document.createElement("div");
    labelElem.setAttribute("class", "label");
    labelElem.innerHTML = room.name;
    elem.appendChild(labelElem);

    var controlsElem = document.createElement("div");
    controlsElem.setAttribute("class", "controls");
    controlsElem.innerHTML = "<img src='res/icons/Forward-100.png' />";
    elem.appendChild(controlsElem);

    return elem;
};

DataStore.GLOBAL_RADIOBUTTON_ID_COUNTER = 0;

DataStore.generateRoomControlsItemElement = function(control) {
    var elem = document.createElement("div");
    elem.setAttribute("class", "item");

    var labelElem = document.createElement("div");
    labelElem.setAttribute("class", "label");

    var labelIconElem = document.createElement("img");
    if(control.applianceType == 0) { //light
        labelIconElem.src = "res/icons/Idea%20Filled-100.png";
        labelElem.appendChild(labelIconElem);
    } else if(control.applianceType == 1) { //el. socket
        labelIconElem.src = "res/icons/Electrical%20Filled-100.png";
        labelElem.appendChild(labelIconElem);
    } else if(control.applianceType == 2) { //heat pump
        labelIconElem.src = "res/icons/Geothermal-100.png";
        labelElem.appendChild(labelIconElem);
    } else if(control.applianceType == 3) {//TV
        labelIconElem.src = "res/icons/TV%20Filled-100.png";
        labelElem.appendChild(labelIconElem);
    } else if(control.applianceType == 4) {//garage doors
        labelIconElem.src = "res/icons/Garage-100.png";
        labelElem.appendChild(labelIconElem);
    } else if(control.applianceType == 5) {//thermostat
        labelIconElem.src = "res/icons/Temperature-100.png";
        labelElem.appendChild(labelIconElem);
    } else if(control.applianceType == 6) {//sun blind
		labelIconElem.src = "res/icons/SunBlind-104.png";
        labelElem.appendChild(labelIconElem);
	} else if(control.applianceType == 7) {//lock
		labelIconElem.src = "res/icons/Lock-104.png";
        labelElem.appendChild(labelIconElem);
	}

    var labelTextElem = document.createElement("div");
    labelTextElem.setAttribute("class", "text");
    labelTextElem.innerHTML = control.name;
    labelElem.appendChild(labelTextElem);

    elem.appendChild(labelElem);

    var controlsElem = document.createElement("div");
    controlsElem.setAttribute("class", "controls");
    if(control.type == 0) {
		if(control.applianceType == 6) {
			controlsElem.innerHTML =
                "<div><div class='radioContainer' data-dbid='" + control.id + "' data-output='" + control.outputs[0] + "'>" +
                "<input type='radio' id='rad" + DataStore.GLOBAL_RADIOBUTTON_ID_COUNTER + "_1' name='rad" + DataStore.GLOBAL_RADIOBUTTON_ID_COUNTER + "' class='btnDown' />" +
                "<label for='rad" + DataStore.GLOBAL_RADIOBUTTON_ID_COUNTER + "_1'></label>" +
                "</div>"+
                "<div class='radioContainer' data-dbid='" + control.id + "' data-output='" + control.outputs[0] + "," + control.outputs[1] + "'>" +
                "<input type='radio' id='rad" + DataStore.GLOBAL_RADIOBUTTON_ID_COUNTER + "_2' name='rad" + DataStore.GLOBAL_RADIOBUTTON_ID_COUNTER + "' class='btnStop' />" +
                "<label for='rad" + DataStore.GLOBAL_RADIOBUTTON_ID_COUNTER + "_2'></label>" +
                "</div>"+
                "<div class='radioContainer' data-dbid='" + control.id + "' data-output='" + control.outputs[1] + "'>" +
                "<input type='radio' id='rad" + DataStore.GLOBAL_RADIOBUTTON_ID_COUNTER + "_3' name='rad" + DataStore.GLOBAL_RADIOBUTTON_ID_COUNTER + "' class='btnUp' />" +
                "<label for='rad" + DataStore.GLOBAL_RADIOBUTTON_ID_COUNTER + "_3'></label>" +
                "</div></div>";
            DataStore.GLOBAL_RADIOBUTTON_ID_COUNTER++;
			controlsElem.innerHTML +=
				"<div class='sunblindpulse'>"+
				"<button class='controlUp' data-dbid='" + control.id + "' data-output='" + control.outputs[1] + "'>&uarr;</button> <button class='controlDown' data-dbid='" + control.id + "' data-output='" + control.outputs[0] + "'>&darr;</button>"+
				"</div>";
            DataStore.GLOBAL_RADIOBUTTON_ID_COUNTER++;
		} else {
		    controlsElem.innerHTML = "<label><input data-dbid='" + control.id + "' data-output='" + control.outputs[0] + "' type='checkbox' class='ios-switch' value='0' /></label>";
        }
    } else if(control.type == 1) {
        controlsElem.innerHTML = "<div data-dbid='" + control.id + "' data-output='" + control.outputs[0] + "' class='tempval'></div>"+
            "<div class='tempactive'>"+
            "(°C)<input data-dbid='" + control.id + "' data-output='" + control.outputs[0] + "' type='number' value='' disabled /> "+
			//"<div class='controlUpDownContainer'>"+
			"<button class='controlUp'>&uarr;</button> <button class='controlDown'>&darr;</button>"+
			//"<div>"+
            "<label><input data-dbid='" + control.id + "' data-output='" + control.outputs[0] + "' type='checkbox' class='ios-switch' value='0' /></label>"+
            "</div>";
    } else if(control.type == 2)
        controlsElem.innerHTML = "<input data-dbid='" + control.id + "' data-output='" + control.outputs[0] + "' type='range' min='0' max='100' step='1' value='0' />";

    elem.appendChild(controlsElem);
    return elem;
};

DataStore.generateLastUsedControlsItemElement = function(control) {
    var elem = document.createElement("div");
    elem.setAttribute("class", "item");

    var labelElem = document.createElement("div");
    labelElem.setAttribute("class", "label");

    var labelIconElem = document.createElement("img");
    if(control.applianceType == 0) { //light
        labelIconElem.src = "res/icons/Idea%20Filled-100.png";
        labelElem.appendChild(labelIconElem);
    } else if(control.applianceType == 1) { //el. socket
        labelIconElem.src = "res/icons/Electrical%20Filled-100.png";
        labelElem.appendChild(labelIconElem);
    } else if(control.applianceType == 2) { //heat pump
        labelIconElem.src = "res/icons/Geothermal-100.png";
        labelElem.appendChild(labelIconElem);
    } else if(control.applianceType == 3) {//TV
        labelIconElem.src = "res/icons/TV%20Filled-100.png";
        labelElem.appendChild(labelIconElem);
    } else if(control.applianceType == 4) {//garage doors
        labelIconElem.src = "res/icons/Garage-100.png";
        labelElem.appendChild(labelIconElem);
    } else if(control.applianceType == 5) {//thermostat
        labelIconElem.src = "res/icons/Temperature-100.png";
        labelElem.appendChild(labelIconElem);
    } else if(control.applianceType == 6) {//sun blind
		labelIconElem.src = "res/icons/SunBlind-104.png";
        labelElem.appendChild(labelIconElem);
	} else if(control.applianceType == 7) {//lock
		labelIconElem.src = "res/icons/Lock-104.png";
	labelElem.appendChild(labelIconElem);
	}

    var labelTextElem = document.createElement("div");
    labelTextElem.setAttribute("class", "text");
    labelTextElem.innerHTML = DataStore.getRoomById(control.room).name + ' -> ' + control.name;
    labelElem.appendChild(labelTextElem);

    elem.appendChild(labelElem);

    var controlsElem = document.createElement("div");
    controlsElem.setAttribute("class", "controls");
    if(control.type == 0) {
		if(control.applianceType == 6) {
			controlsElem.innerHTML =
                "<div><div class='radioContainer' data-dbid='" + control.id + "' data-output='" + control.outputs[0] + "'>" +
                "<input type='radio' id='rad" + DataStore.GLOBAL_RADIOBUTTON_ID_COUNTER + "_1' name='rad" + DataStore.GLOBAL_RADIOBUTTON_ID_COUNTER + "' class='btnDown' />" +
                "<label for='rad" + DataStore.GLOBAL_RADIOBUTTON_ID_COUNTER + "_1'></label>" +
                "</div>"+
                "<div class='radioContainer' data-dbid='" + control.id + "' data-output='" + control.outputs[0] + "," + control.outputs[1] + "'>" +
                "<input type='radio' id='rad" + DataStore.GLOBAL_RADIOBUTTON_ID_COUNTER + "_2' name='rad" + DataStore.GLOBAL_RADIOBUTTON_ID_COUNTER + "' class='btnStop' />" +
                "<label for='rad" + DataStore.GLOBAL_RADIOBUTTON_ID_COUNTER + "_2'></label>" +
                "</div>"+
                "<div class='radioContainer' data-dbid='" + control.id + "' data-output='" + control.outputs[1] + "'>" +
                "<input type='radio' id='rad" + DataStore.GLOBAL_RADIOBUTTON_ID_COUNTER + "_3' name='rad" + DataStore.GLOBAL_RADIOBUTTON_ID_COUNTER + "' class='btnUp' />" +
                "<label for='rad" + DataStore.GLOBAL_RADIOBUTTON_ID_COUNTER + "_3'></label>" +
                "</div></div>";
            DataStore.GLOBAL_RADIOBUTTON_ID_COUNTER++;
			controlsElem.innerHTML +=
				"<div class='sunblindpulse'>"+
				"<button class='controlUp' data-dbid='" + control.id + "' data-output='" + control.outputs[1] + "'>&uarr;</button> <button class='controlDown' data-dbid='" + control.id + "' data-output='" + control.outputs[0] + "'>&darr;</button>"+
				"</div>";
            DataStore.GLOBAL_RADIOBUTTON_ID_COUNTER++;
		} else {
		    controlsElem.innerHTML = "<label><input data-dbid='" + control.id + "' data-output='" + control.outputs[0] + "' type='checkbox' class='ios-switch' value='0' /></label>";
        }
    } else if(control.type == 1) {
        controlsElem.innerHTML = "<div data-dbid='" + control.id + "' data-output='" + control.outputs[0] + "' class='tempval'></div>"+
            "<div class='tempactive'>"+
            "(°C)<input data-dbid='" + control.id + "' data-output='" + control.outputs[0] + "' type='number' value='' disabled /> "+
			//"<div class='controlUpDownContainer'>"+
			"<button class='controlUp'>&uarr;</button> <button class='controlDown'>&darr;</button>"+
			//"<div>"+
            "<label><input data-dbid='" + control.id + "' data-output='" + control.outputs[0] + "' type='checkbox' class='ios-switch' value='0' /></label>"+
            "</div>";
    } else if(control.type == 2)
        controlsElem.innerHTML = "<input data-dbid='" + control.id + "' data-output='" + control.outputs[0] + "' type='range' min='0' max='100' step='1' value='0' />";

    elem.appendChild(controlsElem);
    return elem;
};