{{ Form::open(['route' => ['profile.avatar.update'],  'method' => 'post', 'class'=>'form-horizontal validator', 'enctype'=>'multipart/form-data']) }}
<div class="avatar-box lf-toggle-bg-card lf-toggle-border-color border text-center border-bottom-0">
    <div class="position-relative" id="profileAvatar" style="background-image: url({{ get_avatar($user->avatar) }})">
        {{--        <img src="" alt="{{ __('Profile Image') }}" class="img-rounded img-fluid" id="profileAvatar">--}}
        <div class="ajax-loader">
            <div class="sk-cube-grid m-auto">
                <div class="sk-cube sk-cube1"></div>
                <div class="sk-cube sk-cube2"></div>
                <div class="sk-cube sk-cube3"></div>
                <div class="sk-cube sk-cube4"></div>
                <div class="sk-cube sk-cube5"></div>
                <div class="sk-cube sk-cube6"></div>
                <div class="sk-cube sk-cube7"></div>
                <div class="sk-cube sk-cube8"></div>
                <div class="sk-cube sk-cube9"></div>
            </div>
        </div>
        <input type="file"
               name="avatar"
               v-on:change="changeAvatar($event)"
               class="d-none"
               id="avatarInput">
        <button id="changeAvatar" type="button" class="lf-toggle-border-color">
            <i class="fa fa-camera"></i>
        </button>
    </div>
</div>
<p class="lf-toggle-bg-card text-center p-3 m-0 lf-toggle-border-color border">{{ $user->profile->full_name }}</p>
{{ Form::close() }}

@section('extra-script')
    <script>
        "use strict";

        $(document).ready(function () {
            var changeAvatarBtn = $("#changeAvatar");
            var avatarInput = $("#avatarInput");
            var profileAvatar = $("#profileAvatar");
            // var avatarSrc = profileAvatar.attr("src");
            var loader = $(".ajax-loader");

            changeAvatarBtn.on("click", function (e) {
                e.preventDefault();
                avatarInput.trigger('click');
            });

            // upload avatar instantly using ajax
            avatarInput.on("change", function () {
                loader.css("display", "flex");

                var form = $(this).parents("form");
                var url = form.attr("action");

                let avatar = document.getElementById("avatarInput").files[0];
                let formData = new FormData();
                formData.append("avatar", avatar);

                // send request
                axios.post(url, formData)
                    .then(function (response) {
                        if (response.data.{{ RESPONSE_STATUS_KEY }} == "{{ RESPONSE_TYPE_SUCCESS }}") {
                            if (response.data.avatar) {
                                var date = new Date();
                                profileAvatar.css({
                                    "background-image": 'url(' + response.data.avatar + "?" + date.getTime() + ')'
                                });
                            }
                            flashBox('success', '{{ __('Avatar has been updated successfully.') }}');
                        } else {
                            flashBox('error', '{{ __('Failed to update avatar.') }}');
                        }
                    })
                    .catch(function (error) {
                        if (error.response.data.errors.avatar[0]) {
                            flashBox('error', error.response.data.errors.avatar[0])
                        }
                    }).finally(function () {
                    loader.css("display", "none");
                });
            });
        });
    </script>
@endsection
