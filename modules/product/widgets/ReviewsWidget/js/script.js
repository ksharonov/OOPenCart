$('#reviewForm').on('XXsubmit', function (event) {
    event.preventDefault();
    var formData = $('#reviewForm').serialize();
    if (grecaptcha.getResponse() == '') {
        return false;
    }
    $.ajax({
        type: 'POST',
        url: '/api/review/index',
        data: formData,
        complete: function (succes) {
            console.log(succes);
            console.info('Review submitted!');
            window.location.reload(false);
        },
    });
});