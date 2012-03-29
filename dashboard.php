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
           

          </div>
      </div>
        
        <?php include('footer.php') ?>


  </body>
</html>
