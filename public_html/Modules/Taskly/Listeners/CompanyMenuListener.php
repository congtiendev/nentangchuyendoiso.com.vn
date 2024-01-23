<?php

namespace Modules\Taskly\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Taskly';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Công Việc'),
            'icon' => '',
            'name' => 'taskly-dashboards',
            'parent' => 'dashboard',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'taskly.dashboard',
            'module' => $module,
            'permission' => 'taskly dashboard manage'
        ]);
        $menu->add([
            'title' => __('Quy trình công việc'),
            'icon' => 'square-check',
            'name' => 'projects',
            'parent' => null,
            'order' => 300,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'project manage'
        ]);
        $menu->add([
            'title' => __('Công việc'),
            'icon' => '',
            'name' => 'project',
            'parent' => 'projects',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'projects.index',
            'module' => $module,
            'permission' => 'project manage'
        ]);
        $menu->add([
            'title' => __('Báo cáo công việc'),
            'icon' => '',
            'name' => 'project-report',
            'parent' => 'projects',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'project_report.index',
            'module' => $module,
            'permission' => 'project report manage'
        ]);
        $menu->add([
            'title' => __('System Setup'),
            'icon' => '',
            'name' => 'system-setup',
            'parent' => 'projects',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'stages.index',
            'module' => $module,
            'permission' => 'taskly setup manage'
        ]);
    }
}
