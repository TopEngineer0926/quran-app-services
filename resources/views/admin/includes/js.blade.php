        <!-- start: MAIN JAVASCRIPTS -->
        <!--[if lt IE 9]>
		<script src="assets/plugins/respond.min.js"></script>
		<script src="assets/plugins/excanvas.min.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<![endif]-->
        <!--[if gte IE 9]><!-->
        <!-- Custom Settings -->
        <script src="{{assets('js/settings.js')}}"></script>
        <!-- End Custom Settings -->

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdn.rawgit.com/prashantchaudhary/ddslick/master/jquery.ddslick.min.js"></script>
        <!--<![endif]-->
        <script src="{{assets('plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js')}}"></script>
        <script src="{{assets('plugins/bootstrap/js/bootstrap.min.js')}}"></script>
        <script src="{{assets('plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js')}}"></script>
        <script src="{{assets('plugins/blockUI/jquery.blockUI.js')}}"></script>
        <script src="{{assets('plugins/iCheck/jquery.icheck.min.js')}}"></script>
        <script src="{{assets('plugins/perfect-scrollbar/src/jquery.mousewheel.js')}}"></script>
        <script src="{{assets('plugins/perfect-scrollbar/src/perfect-scrollbar.js')}}"></script>
        <script src="{{assets('plugins/less/less-1.5.0.min.js')}}"></script>
        <script src="{{assets('plugins/jquery-cookie/jquery.cookie.js')}}"></script>
        <script src="{{assets('plugins/bootstrap-colorpalette/js/bootstrap-colorpalette.js')}}"></script>
        <script src="{{assets('js/main.js')}}"></script>
        <script src="//js.pusher.com/3.1/pusher.min.js"></script>
        <script src="{{assets('js/notification.js')}}"></script>
        <script src="{{assets('js/toastr.min.js')}}'"></script>
        <script src="{{assets('plugins/ladda-bootstrap/dist/spin.min.js')}}"></script>
		<script src="{{assets('plugins/ladda-bootstrap/dist/ladda.min.js')}}"></script>
		<script src="{{assets('plugins/bootstrap-switch/static/js/bootstrap-switch.min.js')}}"></script>
        <script src="{{assets('js/ui-buttons.js')}}"></script>
        <!-- end: MAIN JAVASCRIPTS -->





        <!-- Custom Scripts -->
        <script src="{{assets('js/scripts.js')}}"></script>
        <script src="{{assets('js/emergency.js')}}"></script>

        <!-- Optional JS -->

        <!-- Argon JS -->
        <!--<script src="{{assets('js/argon.js?v=1.0.0')}}"></script>-->
        <!--Date Time Picker-->
        <script src="{{assets('vendor/datetime-picker/js/jquery.datetimepicker.full.min.js')}}"></script>
        <!--Data Tables-->
        <!--<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>-->

        <script>
            jQuery(document).ready(function() {
                Main.init();
               // UIModals.init();
                UIButtons.init();
            });

            $('#myDropdown').ddslick({
                onSelected: function(selectedData) {
                    //callback function: do something with selectedData;
                }
            });
        </script>

