<?php

namespace Modules\Training\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Training';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Đào tạo'),
            'icon' => '',
            'name' => 'training',
            'parent' => 'hrm',
            'order' => 35,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'trainings manage'
        ]);
        $menu->add([
            'title' => __('Danh sách đào tạo'),
            'icon' => '',
            'name' => 'training-list',
            'parent' => 'training',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'training.index',
            'module' => $module,
            'permission' => 'training manage'
        ]);
        $menu->add([
            'title' => __('Người đào tạo'),
            'icon' => '',
            'name' => 'trainer',
            'parent' => 'training',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'trainer.index',
            'module' => $module,
            'permission' => 'trainer manage'
        ]);
    }
}
