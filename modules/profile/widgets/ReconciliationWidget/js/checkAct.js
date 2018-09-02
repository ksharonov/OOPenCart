// ежеминутная проверка на наличие готовности акта
function checkAct() {
    setInterval(function () {
        $.ajax({
            url: '/api/profile/check-act',
            success: function (data) {
                var documentTitle = $('._document_tab');
                if (data === true) {
                    if (!documentTitle.hasClass("tabs__item_active")) {
                        if (!documentTitle.is(':has(span)')) {
                            documentTitle.append('<span>1</span>');
                        }
                    }
                    $.pjax.reload({container: "#p2"});
                }
            }
        });
    }, 60000)
}

// проверка на наличие новых док-ов при перезагрузке страницы
function checkAwait() {
    $.ajax({
        type: 'GET',
        url: '/api/profile/check-await',
        success: function (data) {
            var documentTitle = $('a[data-tab-item="profile-tabs-tab3"]');
            if (data === true) {
                if (!documentTitle.is(':has(span)')) {
                    documentTitle.append('<span>1</span>');
                }
            } else {
                if (documentTitle.is(':has(span)')) {
                    documentTitle.empty();
                }
            }
        }
    });
}