$('._manufacturer_image_path').on('change', function () {
    $('.manufacturer-form__image').prop('src', $(this).val());
});