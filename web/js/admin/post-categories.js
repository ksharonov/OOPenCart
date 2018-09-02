$(document).ready(function () {
    setEventSlug('#post-title', '#post-slug');
    setEventSlug('#postcategory-title', '#postcategory-slug');
    setEventSlug('#product-title', '#product-slug');
});


function generateSlug(text) {
    return text.toString().toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^\u0100-\uFFFF\w\-]/g, '-')
        .replace(/\-\-+/g, '-')
        .replace(/^-+/, '')
        .replace(/-+$/, '');
}

function setEventSlug(input, setInput) {
    $(input).change(function () {
        var $this = $(this);
        if ($(setInput).val().length < 1) {
            var title = $this.val();

            var slug = generateSlug(title);

            $(setInput).val(slug);
        }
    });
}