$("[data-tab-name='order'] *").on("pjax:end", function () {
    $.pjax.reload({container: "#orderData"});
});
