/* -------------------------------------------
::::::: Start Flash Box :::::::
------------------------------------------- */
"use strict";

function elementAction(id, formSubmit) {
    var elem = document.getElementById(id);
    if (elem) {
        if (formSubmit == 'y') {
            document.getElementById(id).submit();
        } else {
            return elem.parentNode.removeChild(elem);
        }
    }
}

function closeMethod() {
    elementAction($('.flash-message').find('.flash-confirm').attr('data-form-auto-id'));
    $('.flash-message').removeClass('flash-message-active').remove('flash-message-window');
    $('.flash-message').find('.flash-confirm').attr('href', 'javascript:;').removeAttr('data-form-id').removeAttr('data-form-auto-id');
    $('.flash-message')
        .find('.centralize-content')
        .removeClass('flash-success')
        .removeClass('flash-error')
        .removeClass('flash-warning')
        .removeClass('flash-confirmation')
        .find('p')
        .text('');
}

$(document).on('click', '.flash-close', function (e) {
    e.preventDefault();
    closeMethod();
});

$(document).on('click', '.flash-message-window', function (e) {
    e.preventDefault();
    closeMethod();
});

$(document).on('click', '.flash-confirm', function (e) {
    var $this = $(this);
    var dataInfo = $this.attr('data-form-id');
    var autoForm = $this.attr('data-form-auto-id');
    if (autoForm) {
        e.preventDefault();
        elementAction(autoForm, 'y');
        closeMethod();
    } else if (dataInfo) {
        e.preventDefault();
        $('#' + dataInfo).submit();
        closeMethod();
    }
});

$(document).on('click', '.confirmation', function (e) {
    e.preventDefault();
    var $this = $(this);
    var dataAlert = $this.attr('data-alert');
    dataInfo = $this.attr('data-form-id');
    if (!dataInfo) {
        var dataInfo = $this.attr('href');
        $('.flash-message').find('.flash-confirm').attr('href', dataInfo);
    } else {
        var autoForm = $this.attr('data-form-method');
        if (autoForm) {
            var link = $this.attr('href');
            var dataToken = $('meta[name="csrf-token"]').attr('content');
            autoForm = autoForm.toUpperCase();
            if (autoForm == 'POST' || autoForm == 'PUT' || autoForm == 'DELETE') {
                var newForm = '<form id="#auto-form-generation-' + dataInfo + '" method="POST" action= "' + link + '" style="height: 0; width: 0; overflow: hidden;">'; //
                newForm = newForm + '<input type = "hidden" name ="_token" value = "' + dataToken + '">';
                newForm = newForm + '<input type = "hidden" name ="_method" value = "' + autoForm + '">';
                $('body').prepend(newForm);
            }
            $('.flash-confirm').attr('data-form-auto-id', '#auto-form-generation-' + dataInfo);
        } else {
            $('.flash-message').find('.flash-confirm').attr('data-form-id', dataInfo);
        }
    }
    $('.flash-message').find('.centralize-content').addClass('flash-confirmation').find('p').text(dataAlert);
    $('.flash-message').addClass('flash-message-active');
});

function flashBox(warnType, message) {
    $('.flash-message').find('.centralize-content').addClass('flash-' + warnType).find('p').html(message);
    $('.flash-message').addClass('flash-message-active flash-message-window');
}

/* -------------------------------------------
::::::: End Flash Box :::::::
------------------------------------------- */
