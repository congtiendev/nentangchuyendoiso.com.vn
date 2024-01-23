<?php

namespace Modules\Pos\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Pos';
        $menu = $event->menu;
        // $menu->add([
        //     'title' => __('POS'),
        //     'icon' => '',
        //     'name' => 'pos-dashboard',
        //     'parent' => 'dashboard',
        //     'order' => 40,
        //     'ignore_if' => [],
        //     'depend_on' => [],
        //     'route' => 'pos.dashboard',
        //     'module' => $module,
        //     'permission' => 'pos dashboard manage'
        // ]);
        // $menu->add([
        //     'title' => __('POS'),
        //     'icon' => 'grid-dots',
        //     'name' => 'pos',
        //     'parent' => null,
        //     'order' => 475,
        //     'ignore_if' => [],
        //     'depend_on' => [],
        //     'route' => '',
        //     'module' => $module,
        //     'permission' => 'pos manage'
        // ]);
        $menu->add([
            'title' => __('Warehouse'),
            'icon' => '',
            'name' => 'warehouse',
            'parent' => 'pos',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'warehouse.index',
            'module' => $module,
            'permission' => 'warehouse manage'
        ]);
        $menu->add([
            'title' => __('Purchase'),
            'icon' => '',
            'name' => 'purchase',
            'parent' => 'pos',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'purchase.index',
            'module' => $module,
            'permission' => 'purchase manage'
        ]);
        $menu->add([
            'title' => __('Add POS'),
            'icon' => '',
            'name' => 'add-pos',
            'parent' => 'pos',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'pos.index',
            'module' => $module,
            'permission' => 'pos add manage'
        ]);
        $menu->add([
            'title' => __('POS Order'),
            'icon' => '',
            'name' => 'pos-order',
            'parent' => 'pos',
            'order' => 25,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'pos.report',
            'module' => $module,
            'permission' => 'pos add manage'
        ]);
        $menu->add([
            'title' => __('Transfer'),
            'icon' => '',
            'name' => 'transfer',
            'parent' => 'pos',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'warehouse-transfer.index',
            'module' => $module,
            'permission' => 'pos add manage'
        ]);
        $menu->add([
            'title' => __('Report'),
            'icon' => '',
            'name' => 'pos-reports',
            'parent' => 'pos',
            'order' => 35,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'report manage'
        ]);
        $menu->add([
            'title' => __('Warehouse Report'),
            'icon' => '',
            'name' => 'warehouse-report',
            'parent' => 'pos-reports',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'report.warehouse',
            'module' => $module,
            'permission' => 'report warehouse'
        ]);
        $menu->add([
            'title' => __('Purchase Daily/Monthly Report'),
            'icon' => '',
            'name' => 'purchase-report',
            'parent' => 'pos-reports',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'report.daily.purchase',
            'module' => $module,
            'permission' => 'report purchase'
        ]);
        $menu->add([
            'title' => __('Pos Daily/Monthly Report'),
            'icon' => '',
            'name' => 'pos-report',
            'parent' => 'pos-reports',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'report.daily.pos',
            'module' => $module,
            'permission' => 'report pos'
        ]);
        $menu->add([
            'title' => __('Pos VS Purchase Report'),
            'icon' => '',
            'name' => 'pos-vs-purchase-report',
            'parent' => 'pos-reports',
            'order' => 25,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'report.pos.vs.purchase',
            'module' => $module,
            'permission' => 'report pos vs expense'
        ]);
    }
}
