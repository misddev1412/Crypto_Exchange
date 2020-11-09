<script>
    "use strict";

    $(function(){
        $(document).on('click', '.cancel_order', function (event) {
            event.preventDefault();
            const buttonInstance = $(this);
            buttonInstance.attr('disabled', 'disabled');
            const loadingText = "{{__('Canceling...')}}";

            if (buttonInstance.html() !== loadingText) {
                buttonInstance.data('original-text', buttonInstance.html());
                buttonInstance.html(loadingText);
            }

            const url = buttonInstance.data('url');
            const orderId = buttonInstance.data('id');
            vueInstance.cancelOrder(orderId, url, buttonInstance);
        });
    });

    let validationOptions = {
        messageOnSubmit : true,
        formSubmission : false,
        rules: {
            stop: "required|numeric|decimalScale:11,8|between:0.00000001,99999999999.99999999",
            price : "required|numeric|decimalScale:11,8|between:0.00000001,99999999999.99999999",
            limit : "required|numeric|decimalScale:11,8|between:0.00000001,99999999999.99999999",
            amount : "required|numeric|decimalScale:11,8|between:0.00000001,99999999999.99999999",
            total : "required|numeric|decimalScale:11,8|between:0.00000001,99999999999.99999999",
        }
    };

    let limitBuyForm = $('#limit-buy-form').cValidate(validationOptions);
    let limitSellForm = $('#limit-sell-form').cValidate(validationOptions);
    let stopLimitBuyForm = $('#stop-limit-buy-form').cValidate(validationOptions);
    let stopLimitSellForm = $('#stop-limit-sell-form').cValidate(validationOptions);
    let marketBuyForm = $('#market-buy-form').cValidate(validationOptions);
    let marketSellForm = $('#market-sell-form').cValidate(validationOptions);
</script>
