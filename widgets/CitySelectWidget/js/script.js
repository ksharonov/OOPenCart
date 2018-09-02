$("#w2").submit(function (event) {
    event.preventDefault();
    return false;
});
$(document).on("change", "#citycookie-cityselected", function () {
    $.ajax({
        type: 'POST',
        url: '/api/city/select-city',
        data: {citySelected: $("#citycookie-cityselected").val()},
        success: function (data) {
            setTimeout(function () {
                location.reload();
            }, 500);
            // location.reload();
        }
    });
})

$("body").on("change", "#citycookie-cityselected", function () {
    $("#w2").submit();
});

$("body").on("click", "#citysession-citysubmit", function () {
    // $("#w2").submit();
    $.ajax({
        type: 'POST',
        url: '/api/city/select-city',
        data: {citySelected: "null"},
        success: function (data) {
            setTimeout(function () {
                location.reload();
            }, 500);
            // location.reload();
        }
    });
});

$("body").on("submit", "#w2", function (event) {
    event.preventDefault();
    $.ajax({
        type: 'POST',
        url: '/api/city/select-city',
        data: {citySelected: $("#citycookie-cityselected").val()},
        success: function (data) {
            setTimeout(function () {
                location.reload();
            }, 500);
            // location.reload();
        }
    });
    return false;
});