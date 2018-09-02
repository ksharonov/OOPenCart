function reloadDatePicker() {
    var inputFrom = $("#reconciliationsession-from");
    var inputTo = $("#reconciliationsession-to");
    var button = $(".entity__act");
    var from, to;

    button.on("click", (function () {
        button.attr('disabled', true);
        $.ajax({
            url: '/api/profile/request-act',
            type: 'POST',
            data: {"from": inputFrom.val(), "to": inputTo.val()},
            success: function (data) {
                if (data === true) {
                    button.attr('disabled', true);
                    modal.show('#reconcilationModal');
                    $('._reconsilation_text').html('Акт будет сформирован в течение 1-2 минут');
                } else if (data === 1) {
                    button.attr('disabled', true);
                    modal.show('#reconcilationModal');
                    $('._reconsilation_text').html('Акт, за запрошенный вами период уже имеется.');
                } else if (data === 2) {
                    button.attr('disabled', true);
                    modal.show('#reconcilationModal');
                    $('._reconsilation_text').html('Акт за данный период уже был запрошен, пожалуйста подождите.');
                } else if (data === false) {
                    console.log(123);
                    //alert('error');
                    //modal.show('#reconcilationModal');
                    //$('._reconsilation_text').html('Произошла ошибка. Повторите запрос позднее.');
                }
            },
            error: function () {
                modal.show('#reconcilationModal');
                $('._reconsilation_text').html('Произошла ошибка. Повторите запрос позднее.');
            }
        });
        return;
    }));

    inputFrom.removeClass("hasDatepicker");
    inputFrom.datepicker({
        language: 'ru',
        dateFormat: 'dd.mm.yy',
        onSelect: function (dateText) {
            from = dateText;
            if (from && to) {
                $.ajax({
                    url: '/api/profile/validate',
                    type: "POST",
                    data: {"from": from, "to": to},
                    success: function (data) {
                        if (data == true) {
                            button.attr("disabled", false);
                        } else {
                            button.attr("disabled", true);
                        }
                    }
                });
            }
        }
    });

    inputTo.removeClass('hasDatepicker');
    inputTo.datepicker({
        language: 'ru',
        dateFormat: 'dd.mm.yy',
        onSelect: function (dateText) {
            to = dateText;
            if (from && to) {
                $.ajax({
                    url: '/api/profile/validate',
                    type: "POST",
                    data: {"from": from, "to": to},
                    success: function (data) {
                        if (data == true) {
                            button.attr("disabled", false);
                        } else {
                            button.attr("disabled", true);
                        }
                    }
                });
            }
        }
    });
}