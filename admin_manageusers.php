<?php

session_start();
if(!$_SESSION['is_auth'] || !$_SESSION['is_admin'] ) {
    header("location: http://devgrenoble.senslab.info/portal/");
    exit();
}

?>

<?php include("header.php") ?>

    <div class="container">

      <!-- Example row of columns -->
      <div class="row">
        <div class="span12">
          <h2>Users</h2>
            <p></p>
                <table id="tbl_users" class="table table-bordered table-striped table-condensed">
                <thead>
                    <tr>
                        <th>Login</th>
                        <th>FirstName</th>
                        <th>LastName</th>
                        <th>Email</th>
                        <th>Validate</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                </table>

        </div>

      </div>

      <hr>

<?php include('footer.php') ?>


    <script type="text/javascript">

    var userjson = {};


    $(document).ready(function(){

        $.ajax({
            url: "http://devgrenoble.senslab.info/rest/admin/users",
            type: "GET",
            dataType: "json",
            success:function(data){
                userjson = data;
                var i = 0;
                $.each(data, function(key,val) {
                    var btnState = "";
                    var btnClass = "";
                    if(val.validate) { btnState = 'disabled="true"' }
                    else { btnClass="btn-primary" };

                    $("#tbl_users tbody").append(
                    '<tr data=' + i + '>'+
                    '<td>' + val.login + '</td>'+
                    '<td>' + val.firstName + '</td>'+
                    '<td>'+ val.lastName +'</td>'+
                    '<td><a href="mailto:' + val.email + '">' + val.email + '</a></td>'+
                    '<td><a href="#"><button class="btn ' + btnClass + ' validate "' + btnState + 'onClick="validateUser('+i+')">Validate</button></a> ' +
                    '<a href="#"><button class="btn btn-danger" onClick="deleteUser('+i+')">Delete</button></a></td>'
                    +'</tr>');
                    i++;
                });
            },
            error:function(XMLHttpRequest, textStatus, errorThrows){
                alert("error: " + errorThrows)
            }
        });
    });
    
    function deleteUser(id) {
        
        if(confirm("Delete user?"))
        {
            var userdelete = userjson[id];
            $.ajax({
            url: "http://devgrenoble.senslab.info/rest/admin/user",
                type: "DELETE",
                contentType: "application/json",
                data: JSON.stringify(userdelete),
                dataType: "text",
            
                success:function(data){
                    $("tr[data="+id+"]").remove()    
                },
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    alert("error: " + errorThrows)
                }
            });
        }
    };
    
    
    function validateUser(id) {
        
        if(confirm("Validate user?"))
        {
            var uservalidate = userjson[id];
            $.ajax({
                url: "http://devgrenoble.senslab.info/rest/admin/user?validate",
                type: "POST",
                dataType: "text",
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify(uservalidate),
                success:function(data){
                    $("tr[data="+id+"] .validate").attr("disabled","disabled")
                    $("tr[data="+id+"] .validate").removeClass("btn-primary");
                },
                error:function(XMLHttpRequest, textStatus, errorThrows){
                    alert("error:" + errorThrows)
                }
            })
        };
    };
    
    </script>

  </body>
</html>
