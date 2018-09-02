<?php
$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;
$roles = array_keys(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->identity->id));
$isManager = in_array('manager', $roles);
?>

<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/images/user.png" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->username ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Поиск..."/>
                <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => [
                    ['label' => 'Панель управления', 'options' => ['class' => 'header']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    [
                        'label' => 'Сайт',
                        'icon' => 'keyboard-o',
                        'url' => '#',
                        'options' => ['class' => (in_array($controller, [
                            'pages',
                            'blocks',
                            'settings',
                            'extensions',
                            'templates'
                        ])) ? 'active' : ''],
                        'items' => [
                            [
                                'label' => 'Страницы',
                                'icon' => 'file',
                                'options' => [
                                    'class' => $controller == 'pages' ? 'active' : ''
                                ],
                                'url' => '/admin/pages',
                            ],
                            [
                                'label' => 'Слайдеры',
                                'icon' => 'file',
                                'options' => [
                                    'class' => $controller == 'slider' ? 'active' : ''
                                ],
                                'url' => '/admin/slider',
                            ],
                            [
                                'label' => 'Блоки',
                                'icon' => 'square',
                                'options' => [
                                    'class' => $controller == 'blocks' ? 'active' : ''
                                ],
                                'url' => '/admin/blocks',
                            ],
                            [
                                'label' => 'Параметры',
                                'icon' => 'delicious',
                                'options' => [
                                    'class' => $controller == 'settings' ? 'active' : '',
                                    'hidden' => $isManager
                                ],
                                'url' => '/admin/settings',
                            ],
                            [
                                'label' => 'Расширения',
                                'icon' => 'arrows-alt',
                                'options' => [
                                    'class' => $controller == 'extensions' ? 'active' : '',
                                    'hidden' => $isManager
                                ],
                                'url' => '/admin/extensions',
                            ],
                            [
                                'label' => 'Шаблоны',
                                'icon' => 'cubes',
                                'options' => [
                                    'class' => $controller == 'templates' ? 'active' : '',
                                    'hidden' => $isManager
                                ],
                                'url' => '/admin/templates',
                            ],
                            [
                                'label' => 'Города на сайте',
                                'icon' => 'map-marker',
                                'options' => [
                                    'class' => $controller == 'cities' ? 'active' : '',
                                ],
                                'url' => '/admin/cities',
                            ],
                        ]
                    ],
                    [
                        'label' => 'Магазин',
                        'icon' => 'shopping-bag',
                        'url' => '#',
                        'options' => ['class' => (in_array($controller, [
                            'storages',
                            'manufacturers',
                            'cheques'
                        ])) ? 'active' : ''],
                        'items' => [
                            [
                                'label' => 'Склады',
                                'icon' => 'map-marker',
                                'options' => [
                                    'class' => $controller == 'storages' ? 'active' : ''
                                ],
                                'url' => '/admin/storages',
                            ],
                            [
                                'label' => 'Производители',
                                'icon' => 'id-badge',
                                'options' => [
                                    'class' => $controller == 'manufacturers' ? 'active' : ''
                                ],
                                'url' => '/admin/manufacturers',
                            ],
                            [
                                'label' => 'Чеки',
                                'icon' => 'id-badge',
                                'options' => [
                                    'class' => $controller == 'cheques' ? 'active' : ''
                                ],
                                'url' => '/admin/cheques',
                            ]
                        ]
                    ],
                    [
                        'label' => 'Посты',
                        'icon' => 'keyboard-o',
                        'url' => '#',
                        'options' => ['class' => (in_array($controller, [
                            'posts',
                            'post-categories'
                        ])) ? 'active' : ''],
                        'items' => [
                            [
                                'label' => 'Редактор постов',
                                'icon' => 'keyboard-o',
                                'options' => ['class' => $controller == 'posts' ? 'active' : ''],
                                'url' => '/admin/posts',
                            ],
                            [
                                'label' => 'Категории',
                                'icon' => 'filter',
                                'options' => ['class' => $controller == 'post-categories' ? 'active' : ''],
                                'url' => '/admin/post-categories'
                            ]
                        ]
                    ],
                    [
                        'label' => 'Заказы',
                        'icon' => 'shopping-cart',
                        'url' => '#',
                        'options' => [
                            'class' => (in_array($controller, ['orders', 'order-statuses'])) ? 'active' : '',
                            'hidden' => $isManager],
                        'items' => [
                            [
                                'label' => 'Заказы',
                                'icon' => 'shopping-cart',
                                'options' => ['class' => $controller == 'orders' ? 'active' : ''],
                                'url' => '/admin/orders'
                            ],
                            [
                                'label' => 'Статусы',
                                'icon' => 'shopping-cart',
                                'options' => ['class' => $controller == 'order-statuses' ? 'active' : ''],
                                'url' => '/admin/order-statuses'
                            ]
                        ]
                    ],
                    [
                        'label' => 'Доставка',
                        'icon' => 'shopping-bag',
                        'url' => '#',
                        'options' => ['class' => (in_array($controller, [
                            'delivery-cost'
                        ])) ? 'active' : ''],
                        'items' => [
                            [
                                'label' => 'Цена доставки по городам',
                                'icon' => 'map-marker',
                                'options' => [
                                    'class' => $controller == 'delivery-cost' ? 'active' : ''
                                ],
                                'url' => '/admin/delivery-cost/',
                            ]
                        ]
                    ],
                    [
                        'label' => 'Товары',
                        'icon' => 'shopping-bag',
                        'url' => '#',
                        'options' => [
                            'class' => (in_array($controller, [
                                'product-options',
                                'products',
                                'product-categories',
                                'product-price-groups',
                                'product-attribute-groups',
                                'product-attributes',
                                'product-analogues',
                                'units'
                            ])) ? 'active' : ''],
                        'items' => [
                            [
                                'label' => 'Товары',
                                'icon' => 'shopping-bag',
                                'options' => ['class' => $controller == 'products' ? 'active' : ''],
                                'url' => '/admin/products'
                            ],
                            [
                                'label' => 'Категории товаров',
                                'icon' => 'filter',
                                'options' => ['class' => $controller == 'product-categories' ? 'active' : ''],
                                'url' => '/admin/product-categories'
                            ],
                            [
                                'label' => 'Опции товаров',
                                'icon' => 'sitemap',
                                'options' => [
                                    'class' => $controller == 'product-options' ? 'active' : '',
                                    'hidden' => true
                                ],
                                'url' => '/admin/product-options'
                            ],
                            [
                                'label' => 'Группы атрибутов',
                                'icon' => 'list-ol',
                                'options' => ['class' => $controller == 'product-attribute-groups' ? 'active' : ''],
                                'url' => '/admin/product-attribute-groups'
                            ],
                            [
                                'label' => 'Атрибуты товаров',
                                'icon' => 'list-ol',
                                'options' => ['class' => $controller == 'product-attributes' ? 'active' : ''],
                                'url' => '/admin/product-attributes'
                            ],
                            [
                                'label' => 'Прайсы',
                                'icon' => 'rub',
                                'options' => ['class' => $controller == 'product-price-groups' ? 'active' : ''],
                                'url' => '/admin/product-price-groups'
                            ],
//                            [
//                                'label' => 'Аналоги товаров',
//                                'icon' => 'arrows-h',
//                                'options' => ['class' => $controller == 'product-analogues' ? 'active' : ''],
//                                'url' => '/admin/product-analogues'
//                            ],
                            [
                                'label' => 'Единицы измерения',
                                'icon' => 'arrows-h',
                                'options' => ['class' => $controller == 'units' ? 'active' : ''],
                                'url' => '/admin/units'
                            ]
                        ]
                    ],
                    [
                        'label' => 'Фильтры',
                        'icon' => 'filter',
                        'url' => '#',
                        'options' => [
                            'class' => (in_array($controller, [
                                'product-filters',
                                'product-filter-fasts'
                            ])) ? 'active' : ''],
                        'items' => [
                            [
                                'label' => 'Фильтры товаров',
                                'icon' => 'filter',
                                'options' => ['class' => $controller == 'product-filters' ? 'active' : ''],
                                'url' => '/admin/product-filters'
                            ],
                            [
                                'label' => 'Быстрые фильтры',
                                'icon' => 'filter',
                                'options' => ['class' => $controller == 'product-filter-fasts' ? 'active' : ''],
                                'url' => '/admin/product-filter-fasts'
                            ]
                        ]
                    ],
                    [
                        'label' => 'Пользователи',
                        'icon' => 'user-o',
                        'url' => '#',
                        'options' => ['class' => (in_array($controller, [
                            'assignment',
                            'role',
                            'permission',
                            'rule',
                            'users',
                            'clients'
                        ])) ? 'active' : ''],
                        'items' => [
                            [
                                'label' => 'Пользователи',
                                'icon' => 'user-o',
                                'options' => ['class' => $controller == 'users' ? 'active' : ''],
                                'url' => '/admin/users'
                            ],
                            [
                                'label' => 'Клиенты',
                                'icon' => 'user-o',
                                'options' => ['class' => $controller == 'clients' ? 'active' : ''],
                                'url' => '/admin/clients'
                            ],
                            [
                                'label' => 'Права доступа',
                                'icon' => 'window-close',
                                'url' => "$controller",
                                'options' => [
                                    'hidden' => $isManager,
                                    'class' => (in_array($controller, [
                                        'assignment',
                                        'role',
                                        'permission',
                                        'rule'
                                    ])) ? 'active' : ''],
                                'items' => [
                                    [
                                        'label' => 'Назначения',
                                        'icon' => 'id-badge',
                                        'options' => ['class' => $controller == 'assignment' ? 'active' : ''],
                                        'url' => '/admin/rbac/assignment',
                                    ],
                                    [
                                        'label' => 'Роли',
                                        'icon' => 'id-card-o',
                                        'options' => ['class' => $controller == 'role' ? 'active' : ''],
                                        'url' => '/admin/rbac/role'
                                    ],
                                    [
                                        'label' => 'Разрешения',
                                        'icon' => 'address-card',
                                        'options' => ['class' => $controller == 'permission' ? 'active' : ''],
                                        'url' => '/admin/rbac/permission'
                                    ],
                                    [
                                        'label' => 'Правила',
                                        'icon' => 'address-book',
                                        'options' => ['class' => $controller == 'rule' ? 'active' : ''],
                                        'url' => '/admin/rbac/rule'
                                    ]
                                ]
                            ],
                        ],
                    ],
                    [
                        'label' => 'Инструменты',
                        'icon' => 'filter',
                        'url' => '#',
                        'options' => [
                            'class' => (in_array($controller, [
                                'product-filters'
                            ])) ? 'active' : ''],
                        'items' => [
                            [
                                'label' => 'SEO',
                                'icon' => 'filter',
                                'options' => ['class' => $controller == 'seo' ? 'active' : ''],
                                'url' => '/admin/seo'
                            ]
                        ]
                    ],
                    YII_ENV_DEV ? ['label' => 'Инструменты разработчика', 'options' => ['class' => 'header']] : [],
                    YII_ENV_DEV ? ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],] : [],
                    YII_ENV_DEV ? ['label' => 'Отладка', 'icon' => 'dashboard', 'url' => ['/debug'],] : []
                ],
            ]
        ) ?>

    </section>

</aside>
