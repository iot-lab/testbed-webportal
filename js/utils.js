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


function displayVar(variable) {
    if(variable != null) {
        return variable;
    }
    else {
        return "";
    }
}
