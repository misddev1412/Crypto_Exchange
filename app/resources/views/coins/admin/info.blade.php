{{ Form::open(['route' => ['coins.icon.update', $coin->symbol],  'method' => 'post', 'class'=>'form-horizontal validator', 'enctype'=>'multipart/form-data']) }}
<div class="card lf-toggle-bg-card lf-toggle-border-color">
    <div class="card-body text-center position-relative py-5">
        <img src="{{ get_coin_icon($coin->icon) }}"
             alt="{{ $coin->symbol }}"
             class="img-rounded img-fluid lf-w-120px"
             id="coinIcon">
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
               name="icon"
               v-on:change="changeIcon($event)"
               class="d-none"
               id="iconInput">
        <button id="changeIcon"
                type="button" class="lf-toggle-border-color">
            <i class="fa fa-camera"></i>
        </button>
        <p class="request-message"></p>
    </div>
    <div class="card-footer bg-primary">
        <h3 class="text-bold text-lg-center text-light m-0 font-weight-bold">{{ $coin->symbol }}</h3>
        <p class="text-sm-center text-light mb-0">({{ $coin->name }})</p>
    </div>
</div>
{{ Form::close() }}


@section('extra-script')
    <script>
        "use strict";

        $(document).ready(function () {
            var changeIconBtn = $("#changeIcon");
            var iconInput = $("#iconInput");
            var coinIcon = $("#coinIcon");
            // var iconSrc = coinIcon.attr("src");
            var loader = $(".ajax-loader");
            var message = $(".request-message");

            changeIconBtn.on("click", function (e) {
                e.preventDefault();
                iconInput.trigger('click');
            });

            // upload icon instantly using ajax
            iconInput.on("change", function () {
                message.text("");
                if (message.hasClass("text-danger")) {
                    message.removeClass("text-danger");
                }
                if (message.hasClass("text-success")) {
                    message.removeClass("text-success");
                }

                loader.css("display", "flex");

                var form = $(this).parents("form");
                var url = form.attr("action");

                let icon = document.getElementById("iconInput").files[0];
                let formData = new FormData();
                formData.append("icon", icon);

                // send request
                axios.post(url, formData)
                    .then(function (response) {
                        if (response.data.{{ RESPONSE_STATUS_KEY }} == "{{ RESPONSE_TYPE_SUCCESS }}") {
                            if (response.data.icon) {
                                var date = new Date();
                                coinIcon.attr("src", response.data.icon + "?" + date.getTime());
                            }
                            message.addClass("text-success");
                        } else {
                            message.addClass("text-danger");
                        }
                        message.text(response.data.{{RESPONSE_MESSAGE_KEY}});
                        message.show();
                        setTimeout(function () {
                            loader.css("display", "none");
                            message.hide();
                            message.text('');
                        }, 2300);
                    })
                    .catch(function (error) {
                        if (error.response.data.errors.icon[0]) {
                            message.addClass("text-danger");
                            message.text(error.response.data.errors.icon[0]);
                            message.show();

                            setTimeout(function () {
                                loader.css("display", "none");
                                message.hide();
                                message.text('');
                            }, 2300);
                        }
                    });
            });
        });
    </script>
@endsection
