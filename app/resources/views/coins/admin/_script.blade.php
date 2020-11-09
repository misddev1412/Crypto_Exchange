<script src="{{ asset('plugins/cvalidator/cvalidator-language-en.js') }}"></script>
<script src="{{ asset('plugins/cvalidator/cvalidator.js') }}"></script>
<script>
    "use strict";

    $(document).ready(function () {
        $('#coinForm').cValidate({
            rules : {
                'type' : 'required',
                'symbol' : 'required|max:10',
                'name' : 'required|max:255',
                'icon' : 'image',
                'exchange_status' : 'required',
                'is_active' : 'required',
            }
        });
    });
</script>
