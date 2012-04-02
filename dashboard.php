<?php 
session_start();
if(!$_SESSION['is_auth']) {
    header("location: http://devgrenoble.senslab.info/portal/");
    exit();
}

?>

<?php include("header.php") ?>

    <div class="container">
        
    <div class="row">
        <div class="span8">
          <h2>New Experiment</h2>
           <p>
           
           </p>
        </div>
        <div class="span4">
          <h2>Personal dashboard</h2>
          <p><i class="icon-cog"></i> Experiments:</p>
            <ul>
                <li><span class="badge badge-success">1</span> running</li>
                <li><span class="badge badge-info">2</span> upcoming</li>
                <li><span class="badge">851</span> done</li>
            </ul>
          <p><i class="icon-th"></i> Profiles: 2 <p>
          <p><i class="icon-home"></i> Home's quota: 60% (600/1000Mo)
            <div class="progress" style="width:200px">
              <div class="bar"
                   style="width: 60%;"></div>
            </div>
            </p>
          <p><i class="icon-user"></i> VM's Status: <button class="btn btn-success">ON</button></p>

           <p><a href="https://strasbourg.senslab.info/monika">View nodes status</a></p>
           <p><a href="https://strasbourg.senslab.info/drawgantt">View Gantt chart</a></p>
           

           <p><a href="#"><button class="btn btn-danger" onClick="new_password()">Get new password</button></a></p>

          </div>
      </div>
        
        <script type="text/javascript">
        
        
        function new_password() {
            var user = {
                "login": "<?php echo $_SESSION["login"] ?>",
            };
            
            console.log(user);
            
            $.ajax({
            url: "http://devgrenoble.senslab.info/rest/admin/user?modpassword",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify(user),
                dataType: "text",
            
                success:function(data){
                    alert("ok");
                },
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    alert("error: " + errorThrows);
                }
            });
        }
        
        </script>
        
        <?php include('footer.php') ?>

  </body>
</html>
