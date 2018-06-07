<!-- ------------------------------------- -->
<!--            BEGIN FOOTER               -->
<!-- ------------------------------------- -->

<footer class="site-footer" id="colophon" role="contentinfo">

    <div class="container">
        <div class="row">
            <div class="footer-text col-sm-9">
                <a href="/what-is-iot-lab/">WHAT IS IOT-LAB?</a> 
                <a href="/about-us/">ABOUT US</a>
                <a href="https://github.com/iot-lab/iot-lab/issues">SUPPORT</a>
                <a href="mailto:admin@iot-lab.info">CONTACT US</a> &nbsp;
                Copyright &copy; FIT IoT-LAB
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
