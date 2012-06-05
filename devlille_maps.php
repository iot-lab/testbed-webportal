<style type="text/css">
body {
  margin: 0;
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 13px;
  line-height: 18px;
  color: #333333;
  background-color: #ffffff;
}
</style>

    <div class="row" id="maps">
        <div class="span8 offset2">
            <div id='selectionbox' style="text-align:center;padding:2px">
            Selected Nodes <input type="text" id='nodebox'/> <i>(example : 1-10,24,25)</i> <button id="btnSave" onClick="save()" value="Save"/>Save</button>
            </div>

            <div ID='div3d' style=" height:400px;background-color:#202020;z-index:-1" oncontextmenu="return false;"></div>

            <div ID='infobox' style="text-align:center">senslab3djs</div>
        </div>

    <script type="text/javascript" src="viewer3Djs/Three.js"></script>
    <script type="text/javascript" src="viewer3Djs/devlille.js"></script>
    <script type="text/javascript" src="viewer3Djs/viewer3D.js"></script>

<script type="text/javascript">

function loadData() {
    var list = document.getElementById("nodebox");
    list.value = window.opener.document.getElementById("devlille_list").value;
    parseNodebox();
}; 

function save() {
    window.opener.document.getElementById("devlille_list").value=document.getElementById("nodebox").value;
    window.close();
}

window.onload = loadData;

</script>


  </body>
</html>
