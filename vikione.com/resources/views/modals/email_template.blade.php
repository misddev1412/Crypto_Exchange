<div class="modal fade" id="edit-email-template" tabindex="-1">
    <div class="modal-dialog modal-dialog-lg modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-md">
                <form action="{{ route('admin.ajax.settings.email.template.update') }}" class="validate-modern" method="POST" id="update_et">
                    @csrf
                    <input type="hidden" name="id" value="{{ $template->id }}">
                    <input type="hidden" name="slug" value="{{ $template->slug }}">
                    <h3 class="popup-title">Edit "{{ $template->name }}" Template</h3>
                    <div class="msg-box"></div>
                    <div class="input-item input-with-label">
                        <label for="name" class="input-item-label"> Name</label>
                        <div class="input-wrap">
                            <input name="name" id="name" class="input-bordered" value="{{ $template->name }}" type="text" readonly="readonly">
                        </div>
                        
                    </div>
                    <div class="input-item input-with-label">
                        <label for="subject" class="input-item-label">Template Subject</label>
                        <div class="input-wrap">
                            <input name="subject" id="subject" class="input-bordered" value="{{ $template->subject }}" type="text" required="">
                        </div>
                    </div>
                    <div class="input-item input-with-label">
                        <label for="greeting" class="input-item-label">Template Greeting</label>
                        <div class="input-wrap">
                            <input name="greeting" id="greeting" class="input-bordered" value="{{ $template->greeting }}" type="text" required="">
                        </div>
                    </div>
                    <div class="input-item  input-with-label">
                        <label for="message" class="input-item-label">Template Content</label>
                        <div class="input-wrap">
                            <textarea id="message" name="message" class="input-bordered input-textarea editor" >{{ $template->message }}</textarea>
                        </div>
                        @if($template->slug == 'users-reset-password-email')
                        <span class="input-note">
                            This line will automatically added: <strong>Your New Password is : ******* </strong>
                        </span>
                        @endif
                    </div>
                    @if(str_contains($template->slug, 'admin'))
                    <div class="input-item input-with-label">
                        <span class="input-item-label">Send Notification to Admin</span>
                        <div class="input-wrap">
                            <input type="checkbox" class="input-switch" name="notify" value="1" {{ $template->notify == 1 ? 'checked' : '' }} id="notify">
                            <label for="notify">Notify</label>
                        </div>
                    </div>
                    @endif
                    <div class="input-item input-with-label">
                        <span class="input-item-label">Email Footer</span>
                        <div class="input-wrap">
                            <input type="checkbox" class="input-switch" name="regards" value="1" {{ $template->regards == 'true' ? 'checked' : '' }} id="regards">
                        </div>
                        <label for="regards">Global</label>
                        <span class="text-info">You can use these shortcut: [[site_name]], [[site_email]], [[user_name]] 
                            @if($template->slug == 'send-user-email')
                            , [[message]]
                            @endif
                            @if(starts_with($template->slug, 'order-'))
                            , [[order_id]], [[order_details]], [[token_symbol]], [[token_name]], [[payment_from]], [[payment_gateway]], [[payment_amount]], [[total_tokens]]
                            @endif
                        </span> <br>
                        <span class="text-danger">Don't use Markdown character,It may broke email style.</span>
                    </div>
                    <div class="gaps-1x"></div>
                    <button type="submit" class="btn btn-md btn-primary ucap">Update </button>
                </form>
            </div>
        </div>{{-- .modal-content --}}
    </div>{{-- .modal-dialog --}}
</div>{{-- Modal End --}}

<script type="text/javascript">
    (function($) {
        var $_form = $('form#update_et');
        if ($_form.length > 0) {
            ajax_form_submit($_form, true);
        }
    })(jQuery);
</script>