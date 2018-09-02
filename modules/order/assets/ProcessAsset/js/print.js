// window.onload = function (ev) {
//     window.open("/order/process/print/", "print");
// }

$('body').on('click', "._print_button", function () {
    window.open("/order/process/print/", "print");
})