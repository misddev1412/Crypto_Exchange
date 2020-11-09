<script src="{{ asset('plugins/cvalidator/cvalidator-language-en.js') }}"></script>
<script src="{{ asset('plugins/cvalidator/cvalidator.js') }}"></script>
<script src="{{ asset('plugins/jasny-bootstrap/js/jasny-bootstrap.min.js') }}"></script>
<script src="{{ asset('plugins/tinymce/tinymce.min.js') }}"></script>
<script type="text/javascript">
    "use strict";

    $(document).ready(function () {
        let postForm = $('#postForm').cValidate({
            rules : {
                'title' : 'required|max:255',
                'category_slug' : 'required',
                'is_featured' : 'required',
                'editor_content' : 'required',
                'featured_image' : 'image|max:2048',
                'is_published' : 'required',
            }
        });

        tinymce.init({
            selector: '#editor_content',
            menubar: false,
            plugins: 'link image code lists textcolor colorpicker',
            toolbar: 'bold italic link image alignleft aligncenter alignright  forecolor backcolor code',
            relative_urls: false,
            setup: function(editor) {
                editor.on('change keyup focus', function(e) {
                    $('#editor_content').val(editor.getContent());
                    postForm.reFormat('editor_content');
                });
            }
        });

        $('.fileinput').on('clear.bs.fileinput', function() {
            postForm.reFormat('featured_image');
        });
    });
</script>
