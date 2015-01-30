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
                Copyright &copy; FIT IoT-LAB
                - <a href="/about-us/">ABOUT US</a>
                - <a href="mailto:admin@iot-lab.info">CONTACT US</a>
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
