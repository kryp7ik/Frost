<!-- <script
        src="https://code.jquery.com/jquery-1.12.4.min.js"
        integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
        crossorigin="anonymous"></script> -->
<script src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="/js/ripples.min.js"></script>
<script src="/js/material.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/v/bs/dt-1.10.12/datatables.min.js"></script>
<script src="/js/moment.min.js"></script>
<script src="/js/bootstrap-datetimepicker.min.js"></script>

<script>
    $(document).ready(function() {
        $.material.init();
        $.material.ripples();
        $('select').select2();


        $(table).DataTable( {
            "paging": false,
            "info" : false,
        });



    });

</script>