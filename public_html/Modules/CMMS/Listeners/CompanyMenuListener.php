<?php

namespace Modules\CMMS\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'CMMS';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Hồ sơ giải quyết'),
            'icon' => '',
            'name' => 'cmms-dashboard',
            'parent' => 'dashboard',
            'order' => 110,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'cmms.dashboard',
            'module' => $module,
            'permission' => 'cmms dashboard manage'
        ]);
        $menu->add([
            'title' => __('Hồ sơ giải quyết'),
            'icon' => 'circles',
            'name' => 'cmms',
            'parent' => null,
            'order' => 650,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'cmms manage'
        ]);
        $menu->add([
            'title' => __('Vị trí'),
            'icon' => '',
            'name' => 'location',
            'parent' => 'cmms',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'location.index',
            'module' => $module,
            'permission' => 'location manage'
        ]);
        $menu->add([
            'title' => __('Yêu cầu'),
            'icon' => '',
            'name' => 'work-order',
            'parent' => 'cmms',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'workorder.index',
            'module' => $module,
            'permission' => 'workorder manage'
        ]);
        $menu->add([
            'title' => __('Loại yêu cầu'),
            'icon' => '',
            'name' => 'component',
            'parent' => 'cmms',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'component.index',
            'module' => $module,
            'permission' => 'components manage'
        ]);
        $menu->add([
            'title' => __('Các bộ phận'),
            'icon' => '',
            'name' => 'parts',
            'parent' => 'cmms',
            'order' => 25,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'parts.index',
            'module' => $module,
            'permission' => 'parts manage'
        ]);
        $menu->add([
            'title' => __('Nhóm'),
            'icon' => '',
            'name' => 'pms',
            'parent' => 'cmms',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'pms.index',
            'module' => $module,
            'permission' => 'pms manage'
        ]);
        $menu->add([
            'title' => __('Đối tượng liên kết'),
            'icon' => '',
            'name' => 'suppliers',
            'parent' => 'cmms',
            'order' => 35,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'supplier.index',
            'module' => $module,
            'permission' => 'suppliers manage'
        ]);
        $menu->add([
            'title' => __('POs'),
            'icon' => '',
            'name' => 'cmms_pos',
            'parent' => 'cmms',
            'order' => 40,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'cmms_pos.index',
            'module' => $module,
            'permission' => 'POs purchase order manage'
        ]);
    }
}
