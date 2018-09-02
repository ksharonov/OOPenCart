function ProfileFormClass() {

}

ProfileFormClass.prototype = {
    constructor: ProfileFormClass
};

ProfileFormClass.prototype.phoneChanged = false;

ProfileFormClass.prototype.init = function () {
    this.events();
};

ProfileFormClass.prototype.events = function () {
    var self = this;

    $("#user-edit-pjax").on("pjax:end", function () {
        $.pjax.reload({container: "#user-view-pjax"});
    });

    $('body').on('click', '._document_tab', function () {
        var documentTitle = $("._document_tab");
        var documentNotification = $("._document_tab span")
        setTimeout(function () {
            $.pjax.reload({container: "#p2"});
            if (documentTitle.is(':has(span)')) {
                documentNotification.remove();
            }
        }, 1000)
    });

    $('body').on('change', '#user-phone', function () {
        self.phoneChanged = true;
    });

    $('#userEditForm').submit(function (event) {
        if (self.phoneChanged) {
            console.log('error');
            event.preventDefault();
            self.sendSms();
            return false;
        }
        self.phoneChanged = true;
    });

    $('body').on('click', '._submit_sms_message', function () {
        console.log('ok');
        self.submitSms();
    });
};

ProfileFormClass.prototype.sendSms = function () {
    var self = this;
    modal.show('#smsSubmitBonus');

    $.ajax({
        url: '/api/profile/send-phone-change-sms',
        type: "POST",
        data: {},
        success: function (success) {
            if (!success) {
                self.phoneChanged = false;
                console.log('oooooooooooook');
                modal.hide('#smsSubmitBonus');
                $('#userEditForm').submit();
            }
            console.log(success);
        }
    });
};

ProfileFormClass.prototype.submitSms = function () {
    var self = this;
    var code = $('.apply__input[name="code"]').val();
    $.ajax({
        url: '/api/profile/submit-phone-change-sms',
        type: "POST",
        data: {
            code: code
        },
        success: function (success) {
            modal.hide('#smsSubmitBonus');
            self.phoneChanged = false;
            $('#userEditForm').submit();
        }
    });
};


var profileForm = new ProfileFormClass();
profileForm.init();