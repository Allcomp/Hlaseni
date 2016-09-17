/**
 * Created by Samuel on 13.08.2015.
 */

var Control = function(id, room, name, outputs, type, appliance_type) {
    this.id = id;
    this.room = room;
    this.name = name;
    this.outputs = outputs.split(",");
    this.type = type;
    this.applianceType = appliance_type;
};