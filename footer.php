      <footer>
          <hr/>
        <p style="font-size:11px;text-align:center">Copyright &copy 2012 Senslab - All Rights Reserved - <a href="https://gforge.inria.fr/tracker/?func=add&group_id=1014&atid=9376">Report bug</a></p>
      </footer>
      
      </div>
      
      

<?php if (isset($_SESSION['basic_value'])) { ?>
  <script type='text/javascript'>
        $.ajaxSetup({
            beforeSend: function (xhr) {
                xhr.setRequestHeader ('Authorization', 'Basic <?php echo $_SESSION['basic_value']; ?>');
            }
        });
    </script>
<?php  } ?>
