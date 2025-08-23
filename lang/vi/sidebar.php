<?php
return [
    'function' => [
        [
            'name' => 'Quản lý nhân viên',
            'icon' => '<svg class="pc-icon"><use xlink:href="#custom-user-add"></use></svg>',
            'route' => ['staff'],
            'module' => [
                [
                    'name' => 'Danh sách nhân viên',
                    'path' => route('staff.index')
                ],
                [
                    'name' => 'Thêm mới nhân viên',
                    'path' => route('staff.create')
                ],
            ]
        ],
        [
            'name' => 'Quản lý khách hàng',
            'icon' => '<svg class="pc-icon"><use xlink:href="#custom-user"></use></svg>',
            'route' => ['user'],
            'module' => [
                [
                    'name' => 'Danh sách khách hàng',
                    'path' => route('user.index')
                ],
                [
                    'name' => 'Thêm mới khách hàng',
                    'path' => route('user.create')
                ],
            ]
        ],
        [
            'name' => 'Quản lý bài viết',
            'icon' => '<svg class="pc-icon"><use xlink:href="#custom-user"></use></svg>',
            'route' => ['post'],
            'module' => [
                [
                    'name' => 'Quản lý bài viết',
                    'path' => route('post.index')
                ],
                [
                    'name' => 'Thêm mới bài viết',
                    'path' => route('post.create')
                ],
                [
                    'name' => 'Bài viết đã xóa',
                    'path' => route('post.trash')
                ]
            ]
        ],
        [
            'name' => 'Quản lý sản phẩm',
            'icon' => '<svg class="pc-icon"><use xlink:href="#custom-shopping-bag"></use></svg>',
            'route' => ['product'],
            'module' => [
                [
                    'name' => 'Quản lý sản phẩm',
                    'path' => route('product.index')
                ],
                [
                    'name' => 'Thêm mới sản phẩm',
                    'path' => route('product.create')
                ],
                [
                    'name' => 'Sản phẩm đã xóa',
                    'path' => route('product.trash')
                ]
            ]
        ],
        [
            'name' => 'Quản lý thuộc tính SP',
            'icon' => '<svg class="pc-icon"><use xlink:href="#custom-data"></use></svg>',
            'route' => ['attributeCategory'],
            'module' => [
                [
                    'name' => 'Quản lý thuộc tính SP',
                    'path' => route('attributeCategory.index')
                ],
                [
                    'name' => 'Thêm mới thuộc tính SP',
                    'path' => route('attributeCategory.create')
                ],
                [
                    'name' => 'Thuộc tính SP đã xóa',
                    'path' => route('attributeCategory.trash')
                ]
            ]
        ],
        [
            'name' => 'Quản lý danh mục',
            'icon' => '<svg class="pc-icon"> <use xlink:href="#custom-element-plus"></use> </svg>',
            'route' => ['category'],
            'module' => [
                [
                    'name' => 'Danh sách',
                    'path' => route('category.index')
                ],
                [
                    'name' => 'Danh mục đã xóa',
                    'path' => route('category.trash')
                ]

            ]
        ],
        [
            'name' => 'Quản lý thương hiệu',
            'icon' => '<svg class="pc-icon"> <use xlink:href="#custom-element-plus"></use> </svag>',
            'route' => ['brand'],
            'module' => [
                [
                    'name' => 'Danh sách',
                    'path' => route('brand.index')
                ],
                [
                    'name' => 'Thương hiệu đã xóa',
                    'path' => route('brand.trash')
                ]

            ]
        ],
        [
            'name' => 'Quản lý bộ sưu tập',
            'icon' => '<svg class="pc-icon"> <use xlink:href="#custom-layer"></use> </svg>',
            'route' => ['collection'],
            'module' => [
                [
                    'name' => 'Danh sách bộ sưu tập',
                    'path' => route('collection.index')
                ],
                [
                    'name' => 'Thêm mới bộ sưu tập',
                    'path' => route('collection.create')
                ],
                [
                    'name' => 'Bộ sưu tập đã xóa',
                    'path' => route('collection.trash')
                ]
            ]
        ],
        [
            'name' => 'Quản lý đơn hàng',
            'icon' => '<svg class="pc-icon"> <use xlink:href="#custom-box-1"></use> </svg>',
            'route' => ['order'],
            'module' => [
                [
                    'name' => 'Danh sách đơn hàng',
                    'path' => route('order.index')
                ],
                [
                    'name' => 'Thêm mới đơn hàng',
                    'path' => route('order.create')
                ],
                [
                    'name' => 'Đơn hàng đã xóa',
                    'path' => route('order.trash')
                ]
            ]
        ],
        [
            'name' => 'Quản lý mã giảm giá',
            'icon' => '<svg class="pc-icon"> <use xlink:href="#custom-dollar-square"></use> </svg>',
            'route' => ['discount'],
            'module' => [
                [
                    'name' => 'Danh sách mã giảm giá',
                    'path' => route('discountCode.index')
                ],
                [
                    'name' => 'Thêm mới mã giảm giá',
                    'path' => route('discountCode.create')
                ],
                [
                    'name' => 'Mã giảm giá đã xóa',
                    'path' => route('discountCode.trash')
                ]
            ]
        ],
        [
            'name' => 'Quản lý phản hồi',
            'icon' => '<svg class="pc-icon"> <use xlink:href="#custom-message-2"></use> </svg>',
            'route' => ['comment'],
            'module' => [
                [
                    'name' => 'Quản lý bình luận',
                    'path' => route('comment.index')
                ],
                [
                    'name' => 'Quản lý đánh giá',
                    'path' => route('review.index')
                ],
                [
                    'name' => 'Quản lý nội dung bình luận',
                    'path' => route('CommentForbiddenWord.index')
                ]
            ]
        ],
        [
            'name' => 'Quản lý phân quyền',
            'icon' => '<svg class="pc-icon"><use xlink:href="#custom-shield"></use></svg>',
            'route' => ['permission'],
            'module' => [
                [
                    'name' => 'Danh sách quyền',
                    'path' => route('permission.index')
                ],
                [
                    'name' => 'Thêm mới vai trò',
                    'path' => route('role.create')
                ]
            ]
        ],
        [
            'name' => 'Quản lý hệ thống',
            'icon' => '<svg class="pc-icon"> <use xlink:href="#custom-setting-2"></use> </svg>',
            'route' => route('setting.index'),
            'module' => []
        ]
    ]
];
