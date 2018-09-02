var tabs = new Tabs();
tabs.init();

/** Класс для работы с табами */
function Tabs() {
    this.activeTab = {};
    this.prevActiveTab = {};
    this.tabs = {};

    /*
     * Инициализация виджета
     */
    this.init = function () {

        var self = this;

        var tabsBlocks = $('[data-tab-name]');

        $.each(tabsBlocks, function (key, tabs) {
            var tabName = $(tabs).data('tab-name');

            $(tabs).find('[data-tab-element="list-item"]')
                .removeClass('tabs__item_active');

            $(tabs).find('[data-tab-element="trigger-item"]')
                .removeClass('tabs__trigger_active');

            $(tabs).find('[data-tab-element="content-item"]')
                .hide();

            self.tabs[tabName] = [];

            var firstItemId = $($(tabs).find('[data-tab-item]')[0]).data('tab-item');
            self.activeTab[tabName] = firstItemId;

            self.setTab(tabName, firstItemId, false);

            self.events(tabName);

            $(tabs).find('a[data-tab-element="list-item"][data-tab-item][data-tab-main="' + tabName + '"]').each(function (itemKey, itemData) {
                var id = $(itemData).data('tab-item');
                self.tabs[tabName].push(id)
            });
        });

        self.autoTab();

    };

    /*
     * Сделать активным
     */
    this.setTab = function (tabName, item, reload) {
        var self = this;

        // console.log(self.activeTab);

        if (reload === undefined) {
            reload = true;
        }

        $('.tabs__panel_active').removeClass('.tabs__panel_active');

        $('[data-tab-name="' + tabName + '"] [data-tab-main="' + tabName + '"][data-tab-element="list-item"]')
            .removeClass('tabs__item_active');

        $('[data-tab-name="' + tabName + '"] [data-tab-main="' + tabName + '"][data-tab-element="trigger-item"]')
            .removeClass('tabs__trigger_active');

        $('[data-tab-name="' + tabName + '"] [data-tab-main="' + tabName + '"][data-tab-element="content-item"]')
            .hide()
            .removeClass('tabs__panel_active');

        $('[data-tab-name="' + tabName + '"] [data-tab-main="' + tabName + '"][data-tab-element="list-item"][data-tab-item="' + item + '"]')
            .addClass('tabs__item_active');

        $('[data-tab-name="' + tabName + '"] [data-tab-main="' + tabName + '"][data-tab-element="trigger-item"][data-tab-item="' + item + '"]')
            .addClass('tabs__trigger_active');

        $('[data-tab-name="' + tabName + '"] [data-tab-main="' + tabName + '"][data-tab-element="content-item"][data-tab-item="' + item + '"]')
            .show()
            .addClass('tabs__panel_active');

        if (reload) {
            self.pjaxReload(tabName, self.prevActiveTab[tabName]);
            self.pjaxReload(tabName, self.activeTab[tabName]);
        }

    };

    /*
     * События
     */
    this.events = function (tabName) {
        var self = this;

        $('[data-tab-name="' + tabName + '"] [data-tab-main="' + tabName + '"][data-tab-element="list-item"]').on('click', function (event) {
            if (this.hasAttribute('data-step')) {
                return;
            }

            var id = $(this).data('tab-item');
            self.prevActiveTab = $.extend({}, self.activeTab);
            self.activeTab[tabName] = id;
            self.setTab(tabName, id);
        });

        $('[data-tab-name="' + tabName + '"] [data-tab-main="' + tabName + '"][data-tab-element="trigger-item"]').on('click', function (event) {
            if (this.hasAttribute('data-step')) {
                return;
            }

            var id = $(this).data('tab-item');
            self.prevActiveTab = $.extend({}, self.activeTab);
            self.activeTab[tabName] = id;
            self.setTab(tabName, id);
        });

        $('._step_tab[data-tab-name-step="' + tabName + '"]').on('click', function (event) {
            self.tabStep(tabName, event)
        });
    };

    /**
     * Шаговая смена активного таба
     * @param tabName
     * @param event
     */
    this.tabStep = function (tabName, event) {
        var self = this,
            target = $(event.target),
            active = self.activeTab[tabName],
            nextActiveKey = null,
            prevActiveKey = null,
            tabsLength = self.tabs[tabName].length;

        var activeTab = $('.tabs__panel_active'),
            tabs = $(activeTab).find('.tabs__panel_active'),
            form = null;

        if (tabs) {
            form = $(tabs).find('form');
        }

        var dataSubmiter = $(activeTab).find('form[data-submit="1"]');

        if (dataSubmiter.length !== 0) {
            form = $(activeTab).find('form[data-submit="1"]');
            $(form).submit();
        }

        $(form).submit();

        for (var i = 0; i < self.tabs[tabName].length; i++) {
            var item = self.tabs[tabName][i];
            if (item === active) {
                nextActiveKey = i + 1;
            }
            if (item === active) {
                prevActiveKey = i - 1;
            }
        }

        if (nextActiveKey === tabsLength) {
            nextActiveKey = tabsLength - 1;
            // nextActiveKey = 0;
        }

        if (prevActiveKey === -1) {
            prevActiveKey = 0;
        }

        if (target.data('step') === 'back') {
            active = self.tabs[tabName][prevActiveKey];
        } else if (target.data('step') === 'next') {
            active = self.tabs[tabName][nextActiveKey];
        }

        self.activeTab[tabName] = active;
        self.setTab(tabName, active, false);

        history.pushState(null, null, "#" + active);
    };

    /**
     * Обновление содержимого табов
     * @param tabName
     * @param objSource
     */
    this.pjaxReload = function (tabName, objSource) {
        if (objSource === undefined) {
            return;
        }

        var self = this,
            item = objSource,
            pjaxData = $('[data-tab-name="' + tabName + '"] [data-tab-main="' + tabName + '"][data-tab-element="content-item"][data-tab-item="' + item + '"]')
                .find("[data-pjax-container]"),
            reloadIds = [];

        $.each(pjaxData, function (key, data) {
            var id = $(data).attr('id');
            reloadIds.push("#" + id);
        });

        setTimeout(function () {
            pjax.reload(reloadIds);
        }, 300);

    };

    /* Автоматическое включение таба при обновлении страницы */
    this.autoTab = function () {
        var hash = window.location.hash;

        if(hash) {
            $('.tabs__item[href="' + hash + '"]').trigger('click');
        }
    };
}