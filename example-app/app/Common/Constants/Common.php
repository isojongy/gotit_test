<?php

namespace App\Common\Constants;

class Common
{
    const CREATE = 'create';
    const UPDATE = 'update';
    const DELETE = 'delete';
    const FORMAT_SQL_DATE_YMD = 'Y-m-d';
    const ROLE_ADMIN_ID = 1;
    const ROLE_MANAGER_ID = 2;
    const ROLE_USER_ID = 3;
    const FILE_EXT = '.txt,.csv,.doc,.xlsx,.xls,.docx,.pdf,.zip';
    const PHOTO_EXT = '.jpeg,.png,.jpg,.gif,.svg,.zip';
    const PHONE_SEPARATED = '-';
    const MODEL_USER = 'App\Models\User';
    const FORMAT_DATE_DMY_COMMON = 'Y/m/d';
    const FORMAT_DATE_DMYHI_COMMON = 'Y/m/d H:i';
    const ICON_USER_ROLES = [
        '0' => [
            'icon' => '<i class="icon text-dark-50 flaticon-menu-1"></i>',
            'color' => 'label-light-dark-50',
        ],
        '11' => [
            'icon' => '<i class="icon text-danger flaticon-avatar"></i>',
            'color' => 'label-light-danger',
        ],
        '12' => [
            'icon' => '<i class="icon text-warning fas fa-user-secret"></i>',
            'color' => 'label-light-warning',
        ],
        '13' => [
            'icon' => '<i class="icon text-success flaticon-customer"></i>',
            'color' => 'label-light-success',
        ],
        '14' => [
            'icon' => '<i class="icon text-info fas fa-user-tie"></i>',
            'color' => 'label-light-info',
        ],
    ];
    const GIFT_IPHONE_ID = 1;
    const GIFT_MONEY_ID = 2;
    const GIFT_BAG_ID = 3;
    const POINT_LIMIT = 10;
}
