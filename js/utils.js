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
function rebuildJson(exp_json,showEmptyAssociations) {
	

	if(!showEmptyAssociations) showEmptyAssociations= false;
                                             
    json_tmp = [];
    
    //build a more simple json for parsing
    if(exp_json.profileassociations != null)
    {
        for(var i = 0; i < exp_json.profileassociations.length; i++) {
            for(var j = 0; j < exp_json.profileassociations[i].nodes.length;j++){
            	json_tmp.push({
                    "node": exp_json.profileassociations[i].nodes[j],
                    "profilename":exp_json.profileassociations[i].profilename});
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
                    json_tmp.push({
                        "node": exp_json.firmwareassociations[i].nodes[j],
                        "firmwarename":exp_json.firmwareassociations[i].firmwarename});
                }
            }
        }
    }
    
    if(exp_json.deploymentresults != null) {
        for(var i = 0; i < exp_json.deploymentresults.length; i++) {
            for(var j = 0; j < exp_json.deploymentresults[i].nodes.length;j++){

                var find = false;
                for(var k = 0; k < json_tmp.length && !find; k++) {
                    if(json_tmp[k].node == exp_json.deploymentresults[i].nodes[j])
                    {
                        var state = exp_json.deploymentresults[i].state;
                        json_tmp[k].state = state=="0"?"Success":"Failure";
                        json_tmp[k].style = state=="0"?"background-color:#DFF0D8;color:#468847":"background-color:#F2DEDE;color:#B94A48";
                        find = true;
                    }
                }

                if(!find)
                {
                    var state = exp_json.deploymentresults[i].state=="0"?"Success":"Failure";
                    var style = exp_json.deploymentresults[i].state=="0"?"background-color:#DFF0D8;color:#468847":"background-color:#F2DEDE;color:#B94A48";
                    json_tmp.push({
                        "node": exp_json.deploymentresults[i].nodes[j],
                        "state":state,
                        "style":style});
                }
            }
        }
    } else {
        for(var k = 0; k < json_tmp.length; k++) {
                json_tmp[k].state = "Not available";
                json_tmp[k].style = "";
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
	                json_tmp.push({"node": exp_json.nodes[l],"profilename":"","firmwarename":"","state":"Not available","style":""});
	            }
	        }
	        else {
	            for(var z=0; z<json_tmp.length && !find; z++){
	                if(exp_json.nodes[l].alias == json_tmp[z].node) {
	                    find = true;
	                }
	            }
	
	            if(!find) {
	                json_tmp.push({"node": exp_json.nodes[l].alias,"profilename":"","firmwarename":"","state":"Not available","style":""});
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