
<?php include("header.php") ?>

    <div class="container">
        
    <button id="btn_maps" class="btn">Maps</button>

    <div class="row" id="maps">
        <div class="span8 offset2">
            <div ID='infobox' style="text-align:center">senslab3djs</div>   
            <div ID='div3d' style=" height:300px;background-color:#202020;z-index:-1" oncontextmenu="return false;"></div>
            <div ID='selectionbox' style="text-align:center">
            Selected Nodes <i>(example : 1-10,24,25)</i><br/><input type="text" ID='nodebox'/>          
            </div> 
        </div>
    </div>
        
        
<?php include('footer.php') ?>

    <script type="text/javascript">
        
        $(document).ready(function(){
            
            $("#btn_maps").click(function(){
                $("#maps").toggle();
            });
        });
    
        
    </script>


    <script type="text/javascript" src="viewer3Djs/Three.js"></script>
    <script type="text/javascript" src="viewer3Djs/strasbourg.js"></script>
    <script type="text/javascript" src="viewer3Djs/viewer3D.js"></script>

  </body>
</html>
