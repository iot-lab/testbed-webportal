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
function rebuildJson(exp_json,log_json=[],showEmptyAssociations=false) {
	
	/*if(!log_json) log_json=[];
	if(!showEmptyAssociations) showEmptyAssociations= false;*/

	// log treatment
    var json_log = [];

    if(log_json["0"])
            for(var k = 0; k < log_json["0"].length; k++) {
                json_log[log_json["0"][k]]= [];
                json_log[log_json["0"][k]]['state']="Success";
                json_log[log_json["0"][k]]['style']="background-color:#DFF0D8;color:#468847";
            }
    if(log_json["1"])
            for(var k = 0; k < log_json["1"].length; k++) {
                json_log[log_json["1"][k]]= [];
                json_log[log_json["1"][k]]['state']="Failure";
                json_log[log_json["1"][k]]['style']="background-color:#F2DEDE;color:#B94A48";
            }            
                                                                    
                                                                    
    json_tmp = [];
    
    //build a more simple json for parsing
    if(exp_json.profileassociations != null)
    {
        for(var i = 0; i < exp_json.profileassociations.length; i++) {
            for(var j = 0; j < exp_json.profileassociations[i].nodes.length;j++){
            	var state = (json_log[exp_json.profileassociations[i].nodes[j]]?json_log[exp_json.profileassociations[i].nodes[j]]['state']:"Not available");
            	var style = (json_log[exp_json.profileassociations[i].nodes[j]]?json_log[exp_json.profileassociations[i].nodes[j]]['style']:"");
                json_tmp.push({
                    "node": exp_json.profileassociations[i].nodes[j],
                    "profilename":exp_json.profileassociations[i].profilename,
                    "state":state,
                    "style":style});
            }
        }
    }

    if(exp_json.firmwareassociations != null) {
        for(var i = 0; i < exp_json.firmwareassociations.length; i++) {
            for(var j = 0; j < exp_json.firmwareassociations[i].nodes.length;j++){
                
                var find = false;
                for(var k = 0; k < json_tmp.length && !find; k++) {
                    if(json_tmp[k].node == exp_json.firmwareassociations[i].nodes[j])
                    {
                        json_tmp[k].firmwarename = exp_json.firmwareassociations[i].firmwarename;
                        find = true;
                    }
                }
                
                if(!find)
                {
                	var state = (json_log[exp_json.firmwareassociations[i].nodes[j]]?json_log[exp_json.firmwareassociations[i].nodes[j]]['state']:"Not available");
                    var style = (json_log[exp_json.firmwareassociations[i].nodes[j]]?json_log[exp_json.firmwareassociations[i].nodes[j]]['style']:"");
                    json_tmp.push({
                        "node": exp_json.firmwareassociations[i].nodes[j],
                        "firmwarename":exp_json.firmwareassociations[i].firmwarename,
                        "state":state,
                        "style":style});
                }
            }
        }
    }
    
    if(showEmptyAssociations) {
	    //then nodes without association
	    for(var l=0; l<exp_json.nodes.length; l++) {
	        find = false;
	
	        if(exp_json.type == "physical") {
	
	            for(var z=0; z<json_tmp.length && !find; z++){
	                if(exp_json.nodes[l] == json_tmp[z].node) {
	                    find = true;
	                }
	            }
	
	            if(!find) {
	            	var state = (json_log[exp_json.nodes[l]]?json_log[exp_json.nodes[l]]['state']:"Not available");
	                var style = (json_log[exp_json.nodes[l]]?json_log[exp_json.nodes[l]]['style']:"");
	                json_tmp.push({"node": exp_json.nodes[l],"profilename":"","firmwarename":"","state":state,"style":style});
	            }
	        }
	        else {
	            for(var z=0; z<json_tmp.length && !find; z++){
	                if(exp_json.nodes[l].alias == json_tmp[z].node) {
	                    find = true;
	                }
	            }
	
	            if(!find) {
	                json_tmp.push({"node": exp_json.nodes[l].alias,"profilename":"","firmwarename":""});
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