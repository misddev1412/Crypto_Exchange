<script src="{{ asset('plugins/moment.js/moment.min.js') }}"></script>
<script src="{{ asset('plugins/bs4-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{asset('plugins/datatables/datatables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables/table-datatables-responsive.min.js')}}"></script>
<script type="text/javascript">
    "use strict";

    //Init jquery Date Picker
    $('.datepicker').datetimepicker({
        format: 'YYYY-MM-DD',
    });

    $(document).ready(function () {


        $('.lf-filter-toggler').on('click', function () {
            $('.lf-filter-container').slideToggle();
        });

        $('.download').on('click', function (e) {
            e.preventDefault();
            let url = $(this).attr('href');
            let type = $(this).attr('data-type');
            let params = $(this).closest('form.lf-filter-form').serializeArray().reduce(function (obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});

            params['type'] = type;
            axios.get(url, {
                params: params
            }).then((response) => {
                if (response.data.file && response.data.name) {
                    var a = document.createElement("a");
                    a.href = response.data.file;
                    a.download = response.data.name;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                }
            }).catch((error) => {
                    console.log(error)
                }
            );

        });
    });
</script>
