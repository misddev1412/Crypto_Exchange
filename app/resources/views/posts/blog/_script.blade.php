<script src="{{ asset('plugins/cvalidator/cvalidator-language-en.js') }}"></script>
<script src="{{ asset('plugins/cvalidator/cvalidator.js') }}"></script>
<script type="text/javascript">
    "use strict";

    $(document).ready(function () {
        var replyForm = $(".comment-reply-form");

        $(document).on('submit', '.comment-reply-form > .reply-form', function (event) {
            event.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var content = form.find("textarea[name='content']").val();
            axios.post(url, {
                content: content,
            })
            .then(function (response) {
                if (response.data.jsonResponse.status === "{{ RESPONSE_TYPE_SUCCESS }}") {
                    location.reload();
                } else {
                    form.find(".invalid-feedback").text(response.data.jsonResponse.message);
                }
                form[0].reset();
            })
            .catch(function (error) {
                jQuery.each(error.response.data.errors, function (i, val) {
                    var selector = form.find(".invalid-feedback");
                    selector.text(val[0])
                });
                // form[0].reset();
            });
        });

        var form = $('#commentForm').cValidate({
            rules : {
                'content' : 'required'
            }
        });
        var mForm = $('#replyForm').cValidate({
            rules : {
                'content' : 'required'
            }
        });
    });
</script>

<script>
    var replyLink = $(".comment-reply-btn");
    replyLink.on("click", function (e) {
        e.preventDefault();
        var commentContent = $(this).parents(".comment-content");
        var url = $(this).data('url');
        // remove all comment-form
        $(".comment-form").remove();
        // add new comment form
        commentContent.append('<div class="comment-form comment-reply-form my-3">\n' +
            '    <form action="'+url+'" method="post" class="reply-form" id="replyForm">\n' +
            '        <div class="form-group">\n' +
            '            <textarea name="content"\n' +
            '                      id=""\n' +
            '                      rows="4" placeholder="{{ __('Write a comment') }}" class="form-control">{{ old('content') }}</textarea>\n' +
            '            <span class="invalid-feedback" data-name="content">{{ $errors->first('content') }}</span>\n' +
            '        </div>\n' +
            '\n' +
            '        {{--submit buttn--}}\n' +
            '        <div class="form-group">\n' +
            '            <button class="btn btn-info form-submission-button" type="submit">{{ __('Reply') }}</button>\n' +
            '        </div>\n' +
            '    </form>\n' +
            '</div>');
    });
</script>
