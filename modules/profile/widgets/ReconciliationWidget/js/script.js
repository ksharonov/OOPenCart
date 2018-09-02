$(function () {
    checkAwait();
    reloadDatePicker();
    checkAct();
});

$(document).on('submit', "#reconciliation-form", function (event) {
    event.preventDefault();
    return false;
});

$("#user-edit-pjax").on("pjax:end", function () {
    setTimeout(function () {
        reloadDatePicker();
    }, 500);
});