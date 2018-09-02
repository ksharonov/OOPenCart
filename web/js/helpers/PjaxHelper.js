var pjax = new PjaxHelper();

function PjaxHelper() {
    this.inUpdate = false;

    /**
     * Обновление pjax контейнеров
     * @param {Array} pjaxContainers -  массив id-контейнеров pjax.
     */
    this.reload = function (pjaxContainers) {
        var lastContainer = pjaxContainers[pjaxContainers.length - 1],
            self = this;

        if (!this.inUpdate) {
            this.inUpdate = true;
            $.each(pjaxContainers, function (index, container) {
                if ($(container).html() === undefined) {
                    delete pjaxContainers[index];
                    pjaxContainers.splice(index, 1)
                }

                if (index + 1 < pjaxContainers.length) {
                    $(container).one('pjax:end', function (xhr, options) {
                        $.pjax.reload({container: pjaxContainers[index + 1]});
                    });
                }
            });

            if (pjaxContainers.length > 0)
                $.pjax.reload({container: pjaxContainers[0]});
        }


        $(lastContainer).one('pjax:end', function (xhr, options) {
            self.inUpdate = false;
        });
    }
}