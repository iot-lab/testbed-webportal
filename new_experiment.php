
<?php include("header.php") ?>

    <div class="container">


<h2>New experiment</h2>

	<form class="well form-horizontal" id="form_add">

              <div class="control-group">
                <label class="control-label" for="txt_name">Name:</label>
                <div class="controls">
                    <input id="txt_name" type="text" class="input-large" required="required">
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="txt_duration">Duration (minutes):</label>
                <div class="controls">
                    <input id="txt_duration" type="text" class="input-large" required="required">
                </div>
              </div>


	<div class="control-group">
            <label class="control-label">Execution</label>
            <div class="controls">
              <label class="radio">
                <input type="radio" name="ExecutionType" id="optionsRadiosAsap" value="asap" checked="">
                as soon as possible
              </label>
              <label class="radio">
                <input type="radio" name="ExecutionType" id="optionsRadiosScheduled" value="scheduled">
                scheduled
              </label>
            </div>
          </div>


        <div class="control-group">
            <label class="control-label">Resources</label>
            <div class="controls">
              <label class="radio">
                <input type="radio" name="ResourcesType" id="optionsRadiosType" value="type" checked="">
                type
              </label>


	<!-- by type -->
              <div class="control-group" id="divResourcesType">
                <label class="control-label" for="txt_fixed">Fixed:</label>
                <div class="controls">
                    <input id="txt_fixed" type="text" class="input-large">
                </div>
              </div>



              <label class="radio">
                <input type="radio" name="ResourcesType" id="optionsRadiosMaps" value="maps">
                maps
              </label>
            </div>
          </div>


    <div class="row" id="maps">
        <div class="span8 offset2">
            <div ID='selectionbox' style="text-align:center;padding:2px">
            Selected Nodes <input type="text" ID='nodebox'/> <i>(example : 1-10,24,25)</i>
            </div>

            <div ID='div3d' style=" height:300px;background-color:#202020;z-index:-1" oncontextmenu="return false;"></div>
            
		<div ID='infobox' style="text-align:center">senslab3djs</div>   
        </div>
    </div>
        
		<button id="btn_submit" class="btn btn-primary" type="submit">Submit</button>
              
              
    </form>


        
<?php include('footer.php') ?>

    <script type="text/javascript">
        
        $(document).ready(function(){
		

		$("#maps").hide();
//set3dsize();
		$("input[name=ResourcesType]").change(function () {
			if($(this).val() == "maps") {
				$("#maps").show();
				$("#divResourcesType").hide();
			}
			else {
				$("#maps").hide();
				$("#divResourcesType").show();
			}
			console.log($(this).val());

		});


        });
    
        
    </script>


    <script type="text/javascript" src="viewer3Djs/Three.js"></script>
    <script type="text/javascript" src="viewer3Djs/strasbourg.js"></script>
    <script type="text/javascript" src="viewer3Djs/viewer3D.js"></script>

  </body>
</html>
