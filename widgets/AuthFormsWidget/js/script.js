function AuthFormClass() {

}

AuthFormClass.prototype = {
    constructor: AuthFormClass
};

AuthFormClass.prototype.init = function () {
    console.log('events');
    this.events();
};

AuthFormClass.prototype.events = function () {
    $('#login-form').submit(function (event) {
        event.preventDefault();

    });

    $('._login_submit').on('click', function (event) {
        var formElement = $('#login-form'),
            form = $(formElement).serialize(),
            path = $(formElement).prop('action'),
            buttonSubmit = $(formElement).find('[type="submit"]');
        buttonSubmit.prop('disabled', true);

        $.get(
            path,
            form,
            function (success) {
                if (success === 'Error') {
                    $('._error_login').show();
                }
                setTimeout(function () {
                    buttonSubmit.prop('disabled', false);
                }, 1500);

            }
        );
    });

    $('#restore-form').submit(function (event) {
        event.preventDefault();
    });

    $('._restore_submit').on('click', function (event) {
        var formElement = $('#restore-form'),
            form = $(formElement).serialize(),
            path = $(formElement).prop('action'),
            buttonSubmit = $(formElement).find('[type="submit"]');
        buttonSubmit.prop('disabled', true);

        $.post(
            path,
            form,
            function (success) {
                $('._success_restore').show();
                setTimeout(function () {
                    modal.hide('#authRestore');
                    buttonSubmit.prop('disabled', false);
                }, 1500);
            }
        );
    });


    $('#register-form').submit(function (event) {
        event.preventDefault();
    });

    $('._register_submit').on('click', function () {
        console.log('ok');
        var formElement = $('#register-form'),
            form = $(formElement).serialize(),
            path = $(formElement).prop('action'),
            buttonSubmit = $(formElement).find('[type="submit"]');
        buttonSubmit.prop('disabled', true);

        $.post(
            path,
            form,
            function (success) {
                $('._success_register').show();
                setTimeout(function () {
                    modal.hide('#authRegister');
                    buttonSubmit.prop('disabled', false);
                    window.location.reload(true);
                }, 1500);
            }
        );
    });
};

var authForm = new AuthFormClass();
authForm.init();