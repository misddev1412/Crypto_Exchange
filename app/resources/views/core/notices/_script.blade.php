<!-- for datatable and date picker -->
<script src="{{ asset('plugins/cvalidator/cvalidator-language-en.js') }}"></script>
<script src="{{ asset('plugins/cvalidator/cvalidator.js') }}"></script>
<script src="{{ asset('plugins/moment.js/moment.min.js') }}"></script>
<script src="{{ asset('plugins/bs4-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
<script type="text/javascript">
    "use strict";

    $(document).ready(function () {
        //Init jquery Date Picker
        $('#start_time').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false
        });

        $('#end_time').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false
        });

        $("#start_time").on("dp.change", function (e) {
            $('#end_time').data("DateTimePicker").minDate(e.date);
        });
        $("#end_time").on("dp.change", function (e) {
            $('#start_time').data("DateTimePicker").maxDate(e.date);
        });
        var form =$('#noticeForm').cValidate({
            rules : {
                'title' : 'required|max:255',
                'description' : 'required',
                'type' : 'required|in:' + '{{ array_to_string(notices_types()) }}',
                'visible_type' : 'required|in:' + '{{ array_to_string(notices_visible_types()) }}',
                'start_at' : 'required|dateFormat:Y-m-d H:i:s',
                'end_time' : 'required|dateFormat:Y-m-d H:i:s',
                'is_active' : 'required'
            }
        });
    });
</script>
