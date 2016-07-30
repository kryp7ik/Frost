<script type="text/javascript">
    $(document).ready(function() {
        $('.editable').editable({
            validate: function(value) {
                if($.trim(value) == '')
                    return 'Value is required.';
            },
            placement: 'top',
            send:'always',
            ajaxOptions: {
                dataType: 'json',
                type: 'post'
            }
        });
    });
</script>