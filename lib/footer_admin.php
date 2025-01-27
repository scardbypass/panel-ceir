                </div>
            </div>
            
            <!-- Footer -->
            <footer class="footer">
                <div class="container-fluid" style="text-align: center; text-transform: capitalize;">
                        </div>
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
        <script src="/assets/js/jquery.core.js"></script>
        <script src="/assets/js/jquery.app.js"></script>

        <?php
        include_once "SEOSecretIDN-schema-all.php";
        include_once "SEOSecretIDN-javascript-admin.php";
        ?>

    </body>
</html>