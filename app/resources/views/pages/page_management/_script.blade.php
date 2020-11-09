<script src="{{ asset('plugins/cvalidator/cvalidator-language-en.js') }}"></script>
<script src="{{ asset('plugins/cvalidator/cvalidator.js') }}"></script>
<script src="{{ asset('plugins/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('plugins/select2/js/select2.js') }}"></script>
<script type="text/javascript">
    "use strict";

    $(document).ready(function () {


        $('.meta_keywords').select2({
            placeholder: "Insert Meta Keywords",
            allowClear: true,
            tags: true,
        });

        let pageForm = $('#pageForm').cValidate({
            rules : {
                'title' : 'required|max:255',
                'meta_description' : 'escapeInput|max:160',
                'meta_keywords.*' : 'required',
                'editor_content' : 'required',
                'is_published' : 'required|in:{{array_to_string(active_status())}}'
            },
            attributes : {
                'meta_keywords.*' : 'meta keywords'
            }
        });

        tinymce.init({
            selector: '#editor_content',
            menubar: false,
            plugins: 'link image code lists textcolor colorpicker table hr',
            toolbar: 'bold italic link image alignleft aligncenter alignright  forecolor backcolor code table',
            relative_urls: false,
            mobile: {
                menubar: false,
                toolbar: 'bold italic link image alignleft aligncenter alignright code',
            },
            valid_child_elements : "h1/h2/h3/h4/h5/h6/a[%itrans_na],table[thead|tbody|tfoot|tr|td],strong/b/p/div/em/i/td[%itrans|#text],body[%btrans|#text]",
            setup: function(editor) {
                editor.on('change keyup focus', function(e) {
                    $('#editor_content').val(editor.getContent());
                    pageForm.reFormat('editor_content');
                });
            }
        });
    });
</script>
