                </div>
            </div>

            <!-- Footer -->
            <footer class="footer">
                <div class="container-fluid" style="text-align: center; text-transform: capitalize;">
                    <div class="row">
                        <div class="col-md-6">
                         
                        <div class="col-md-6">
                            Copyright &copy;<?php echo date("Y"); ?><a href="<?php echo $config['web']['url'];?>" alt="<?php echo $data['short_title']; ?>" title="<?php echo $data['short_title']; ?>"> <?php echo $data['short_title']; ?></a>.
                        </div>
                    </div>
                </div>
            </footer>
            <!-- End Footer -->

        </div>
        <!-- end wrapper -->

        <!-- App js -->
        <script src="https://www.google.com/recaptcha/api.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="/assets/default/vendor.min.js"></script>
        <script src="/assets/default/jquery.dataTables.min.js"></script>
        <script src="/assets/default/dataTables.bootstrap4.js"></script>
        <script src="/assets/default/dataTables.responsive.min.js"></script>
        <script src="/assets/default/responsive.bootstrap4.min.js"></script>
        <script src="/assets/default/dataTables.buttons.min.js"></script>
        <script src="/assets/default/buttons.bootstrap4.min.js"></script>
        <script src="/assets/default/buttons.html5.min.js"></script>
        <script src="/assets/default/buttons.flash.min.js"></script>
        <script src="/assets/default/buttons.print.min.js"></script>
        <script src="/assets/default/dataTables.keyTable.min.js"></script>
        <script src="/assets/default/dataTables.select.min.js"></script>
        <script src="/assets/default/app.min.js"></script>

        <?php
        include_once "SEOSecretIDN-schema-all.php";
        include_once "SEOSecretIDN-javascript-all.php";
        ?>

    </body>
</html>