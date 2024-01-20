<?php

namespace Modules\Lead\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Lead';
        $menu = $event->menu;
        $menu->add([
            'title' => __('CRM'),
            'icon' => '',
            'name' => 'crm-dashboard',
            'parent' => 'dashboard',
            'order' => 50,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'lead.dashboard',
            'module' => $module,
            'permission' => 'crm dashboard manage'
        ]);
        $menu->add([
            'title' => __('CRM'),
            'icon' => 'layers-difference',
            'name' => 'crm',
            'parent' => null,
            'order' => 500,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'crm manage'
        ]);
        $menu->add([
            'title' => __('Lead'),
            'icon' => '',
            'name' => 'lead',
            'parent' => 'crm',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'leads.index',
            'module' => $module,
            'permission' => 'lead manage'
        ]);
        $menu->add([
            'title' => __('Deal'),
            'icon' => '',
            'name' => 'deal',
            'parent' => 'crm',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'deals.index',
            'module' => $module,
            'permission' => 'deal manage'
        ]);
        $menu->add([
            'title' => __('System Setup'),
            'icon' => '',
            'name' => 'system-setup',
            'parent' => 'crm',
            'order' => 25,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'pipelines.index',
            'module' => $module,
            'permission' => 'crm setup manage'
        ]);
        $menu->add([
            'title' => __('Report'),
            'icon' => '',
            'name' => 'crm-report',
            'parent' => 'crm',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' =>'crm report manage'
        ]);
        $menu->add([
            'title' => __('Lead'),
            'icon' => '',
            'name' => 'lead-report',
            'parent' => 'crm-report',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'report.lead',
            'module' => $module,
            'permission' => 'lead report'
        ]);
        $menu->add([
            'title' => __('Deal'),
            'icon' => '',
            'name' => 'deal-report',
            'parent' => 'crm-report',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'report.deal',
            'module' => $module,
            'permission' => 'deal report'
        ]);
    }
}
