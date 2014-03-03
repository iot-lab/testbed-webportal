<!-- ------------------------------------- -->
<!--            BEGIN FOOTER               -->
<!-- ------------------------------------- -->

<footer class="site-footer" id="colophon" role="contentinfo">

    <div class="container">
        <div class="row">

            <div class="bottom-navigation col-sm-6 col-sm-push-6">
            </div>
            <!-- .bottom-navigation -->
            <div class="footer-text col-sm-6 col-sm-pull-6">
                Copyright &copy; 2014 FIT/IoT-LAB - All Rights Reserved
                - <a href="/about-us/">About us</a>
                - <a href="mailto:admin@iot-lab.info">Contact us</a>
            </div>
            <!-- .footer-text -->
        </div>
        <!-- .row -->
    </div>
    <!-- .container -->

</footer><!-- #colophon -->


<?php if (isset($_SESSION['basic_value'])) { ?>
    <script type='text/javascript'>
        $.ajaxSetup({
            cache: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('Authorization', 'Basic <?php echo $_SESSION['basic_value']; ?>')
            }
        });
    </script>
<?php } ?>

</body>
</html>
