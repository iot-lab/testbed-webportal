<?php
session_start();

include("header.php");

?>

<div class="container" style="width:1250px" id="container_drawgantt"><br/>
    <iframe onload="resizeFrame(document.getElementById('childframe'))" src="/drawgantt" width="100%" frameborder="0"
            id="childframe"></iframe>
</div>



<?php include('footer.php') ?>

<script type="text/javascript">
    $("#drawgantt").addClass("active");

    function resizeFrame(f) {
        var height = document.documentElement.clientHeight - document.getElementById("container_drawgantt").offsetTop - 50;
        f.style.height = height + "px";
    }

    function getPos(el) {
        // yay readability
        for (var lx = 0, ly = 0; el != null; lx += el.offsetLeft, ly += el.offsetTop, el = el.offsetParent);
        return {x: lx, y: ly};
    }

    function showSignup() {
        window.location.href = ".";
    }

    function showLogin() {
        window.location.href = ".";
    }

</script>

</body>
</html>
