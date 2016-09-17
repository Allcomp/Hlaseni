/**
 * Created by Samuel on 17.08.2015.
 */

var Macro = function(id, name, controls) {
    this.id = id;
    this.name = name;
    this.controls = [];
    var cnt = controls.split(",");
    for(var i = 0; i < cnt.length; i++) {
        this.controls.push(parseInt(cnt[i]));
    }
};