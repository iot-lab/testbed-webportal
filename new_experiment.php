
<?php include("header.php") ?>

    <div class="container">


<h2>New experiment</h2>

	<form class="well form-horizontal" id="form_new_exp">

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

	        <div class="" id="divResourcesMap">
                	<ul>
                        <li><a href="#" id="str_maps">Strasbourg Maps</a> <input id="str_list" value=""/></li>
                        <li><a href="#" id="gre_maps">Grenoble Maps</a> <input id="gre_list" value=""/></li>
                	</ul>
        	</div>

          </div>


	<button id="btn_submit" class="btn btn-primary" type="submit">Submit</button>

    </form>


	<?php include('footer.php') ?>

    </div>


        

    <script type="text/javascript">
        
        $(document).ready(function(){
		

		$("#divResourcesMap").hide();
		$("input[name=ResourcesType]").change(function () {
			if($(this).val() == "maps") {
				$("#divResourcesType").hide();
				$("#divResourcesMap").show();
			}
			else {
				$("#divResourcesType").show();
				$("#divResourcesMap").hide();
			}

		});

		$("#str_maps").click(function(){
			window.open('str_maps.php', '', 'resizable=no, location=no, width=500, height=500, menubar=no, status=no, scrollbars=no, menubar=no');
		});

                $("#gre_maps").click(function(){
                        window.open('gre_maps.php', '', 'resizable=no, location=no, width=500, height=500, menubar=no, status=no, scrollbars=no, menubar=no');
                });

        });
 

	$("#form_new_exp").bind("submit",function(){

		console.log("TODO");
		return false;
	})

   
        
    </script>


  </body>
</html>
