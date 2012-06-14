<?php 
session_start();

include("header.php");

?>


    <div class="container">

    <div class="row" id="login_div">
    <div class="span12" heigth="800px">
          <iframe onload="resizeFrame(document.getElementById('childframe'))" src="/monika" width="100%" frameborder="0" id="childframe"></iframe>
    </div>

    </div>
    


<?php include('footer.php') ?>

    <script type="text/javascript">

        function resizeFrame(f) {
              f.style.height = (f.contentWindow.document.body.scrollHeight + 50) + "px";
        }


        function showSignup() {
            window.location.href=".";
        }

        function showLogin() {
            window.location.href=".";
        }
    </script>

  </body>
</html>
