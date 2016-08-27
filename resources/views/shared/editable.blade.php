<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $.fn.editable.defaults.params = function (params) {
            params._token = $("meta[name=csrf_token]").attr("content");
            return params;
        };
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