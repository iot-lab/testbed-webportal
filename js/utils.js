// Array Remove - By John Resig (MIT Licensed)
Array.prototype.remove = function(from, to) {
    var rest = this.slice((to || from) + 1 || this.length);
    this.length = from < 0 ? this.length + from : from;
    return this.push.apply(this, rest);
};

// Array Clean
Array.prototype.clean = function(deleteValue) { 
    for (var i = 0; i < this.length; i++) { 
        if (this[i] == deleteValue) { 
            this.splice(i, 1); 
            i--; 
        } 
    } 
    return this; 
};  

// Redirect to dashboard
function redirectDashboard() {
    window.location.href = ".";
}

// Rebuild profile and firmware json
function rebuildJson(exp_json) {
    
    json_tmp = [];
    
    //build a more simple json for parsing
    if(exp_json.profileassociations != null)
    {
        for(i = 0; i < exp_json.profileassociations.length; i++) {
            for(j = 0; j < exp_json.profileassociations[i].nodes.length;j++){
                json_tmp.push({
                    "node": exp_json.profileassociations[i].nodes[j],
                    "profilename":exp_json.profileassociations[i].profilename});
            }
        }
    }

    if(exp_json.firmwareassociations != null) {
        for(i = 0; i < exp_json.firmwareassociations.length; i++) {
            for(j = 0; j < exp_json.firmwareassociations[i].nodes.length;j++){
                
                var find = false;
                for(k = 0; k < json_tmp.length && !find; k++) {
                    if(json_tmp[k].node == exp_json.firmwareassociations[i].nodes[j])
                    {
                        json_tmp[k].firmwarename = exp_json.firmwareassociations[i].firmwarename;
                        find = true;
                    }
                }
                
                if(!find)
                {
                    json_tmp.push({
                        "node": exp_json.firmwareassociations[i].nodes[j],
                        "firmwarename":exp_json.firmwareassociations[i].firmwarename});
                }
            }
        }
    }
    
    return json_tmp;
}

// Display variable
function displayVar(variable) {
    if(variable != null) {
        return variable;
    }
    else {
        return "";
    }
}
