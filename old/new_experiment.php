<?php 
session_start();

if(!$_SESSION['is_auth']) {
    header("location: .");
    exit();
}
?>

<?php include("header.php") ?>

        <div class="container">
            <h2>New experiment</h2>
            <form class="well form-horizontal" id="form_new_exp">
                <h3>1. Configure your experiment</h3>
                <div class="control-group">
                    <label class="control-label" for="txt_name">Name:</label>
                    <div class="controls">
                        <input id="txt_name" type="text" class="input-large" required="required">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="txt_duration">Duration (minutes):</label>
                    <div class="controls">
                        <input id="txt_duration" type="number" class="input-large" required="required">
                    </div>
                </div>
               <!-- <div class="control-group">
                    <label class="control-label">Execution</label>
                    <div class="controls">
                        <label class="radio">
                            <input type="radio" name="ExecutionType" id="optionsRadiosAsap" value="asap"
                            checked="">as soon as possible</label>
                        <label class="radio">
                            <input type="radio" name="ExecutionType" id="optionsRadiosScheduled" value="scheduled">scheduled</label>
                    </div>
                </div>
                -->
                <h3>2. Select your nodes</h3>
                <div class="control-group">
                    <label class="control-label">Resources</label>
                    <div class="controls">
                        <label class="radio">
                            <input type="radio" name="ResourcesType" id="optionsRadiosType" value="type"
                            checked="">by type</label>
                        <!-- by type -->
                        <div class="" id="divResourcesType">
                            <label class="control-label" for="txt_fixed">Fixed:</label>
                            <div class="controls">
                                <input id="txt_fixed" type="text" class="input-large">
                            </div>
                        </div>
                        <label class="radio">
                            <input type="radio" name="ResourcesType" id="optionsRadiosMaps" value="physical">physical</label>
                        <div class="" id="divResourcesMap">
                           <!-- <p>
                                <a href="#" id="str_maps">Strasbourg Maps</a>
                                <input id="str_list" value="" />
                            </p> 
                            <p>
                                <a href="#" id="gre_maps">Grenoble Maps</a>
                                <input id="gre_list" value="" />
                            </p>
                            -->
                            <p>
                                <a href="#" id="devlille_maps">Devlille Maps</a>
                                <input id="devlille_list" value="" />
                            </p>

                        </div>
                    </div>
                </div>
                <button id="btn_submit" class="btn btn-primary" type="submit">Next</button>
            </form>
            <?php include( 'footer.php') ?>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {

                //restore value from localStorage
                $("#txt_name").val(localStorage.getItem("txt_name"));
                $("#txt_duration").val(localStorage.getItem("txt_duration"));
                $("#devlille_list").val(localStorage.getItem("devlille_list"));

                //ressources type
                $("#divResourcesMap").hide();
                $("input[name=ResourcesType]").change(function () {
                    if ($(this).val() == "physical") {
                        $("#divResourcesType").hide();
                        $("#divResourcesMap").show();
                    } else {
                        $("#divResourcesType").show();
                        $("#divResourcesMap").hide();
                    }

                });

                //open popup
                $("#devlille_maps").click(function () {
                    window.open('devlille_maps.php', '', 'resizable=yes, location=no, width=500, height=500, menubar=no, status=no, scrollbars=no, menubar=no');
                });

            });

            //on submit
            $("#form_new_exp").bind("submit", function () {

                var exp_json = {
                    "type": $("input[name=ResourcesType]:checked").val(),
                    "name": $("#txt_name").val(),
                    "duration": parseInt($("#txt_duration").val())
                };

                var my_nodes = [];

                //build nodes list
                if ($("#devlille_list").val() != "") {
                    var devlille_all = parseNodebox($("#devlille_list").val());
                    for (i = 0; i < devlille_all.length; i++) {
                        my_nodes.push("node"+devlille_all[i]+".devlille.senslab.info");
                    }
                }
                
                exp_json.nodes = my_nodes;
                
                //save value in localStorage
                localStorage.setItem("txt_name",$("#txt_name").val());
                localStorage.setItem("txt_duration",$("#txt_duration").val());
                localStorage.setItem("devlille_list",$("#devlille_list").val());

                if (typeof localStorage != 'undefined') {
                    localStorage.setItem("exp_json", JSON.stringify(exp_json));
                } else { }

                window.location.href = "new_experiment2.php";

                return false;
            })

            // expand a list of nodes containing dash intervals
            // 1-3,5,9 -> 1,2,3,5,9
            function expand(factExp) {
                exp = [];
                for (i = 0; i < factExp.length; i++) {
                    dashExpression = factExp[i].split("-");
                    if (dashExpression.length == 2) {
                        for (j = parseInt(dashExpression[0]); j < (parseInt(dashExpression[1]) + 1); j++)
                        exp.push(j);
                    } else exp.push(parseInt(factExp[i]));
                }
                exp.sort(sortfunction);
                for (var i = 1; i < exp.length; i++) {
                    if (exp[i] == exp[i - 1]) {
                        exp.splice(i--, 1);
                    }
                }
                return exp;
            }

            function parseNodebox(input) {
                return expand(input.split(","));
            }

            function sortfunction(a, b) {
                return (a - b) //causes an array to be sorted numerically and ascending
            }
        </script>
        </body>
        
        </html>
