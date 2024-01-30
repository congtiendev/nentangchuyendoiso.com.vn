<?php

namespace App\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Base';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Tổng quan'),
            'icon' => 'home',
            'name' => 'dashboard',
            'parent' => null,
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('User Management'),
            'icon' => 'users',
            'name' => 'user-management',
            'parent' => null,
            'order' => 50,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'user manage'
        ]);
        $menu->add([
            'title' => __('User'),
            'icon' => '',
            'name' => 'user',
            'parent' => 'user-management',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'users.index',
            'module' => $module,
            'permission' => 'user manage'
        ]);
        $menu->add([
            'title' => __('Role'),
            'icon' => '',
            'name' => 'role',
            'parent' => 'user-management',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'roles.index',
            'module' => $module,
            'permission' => 'roles manage'
        ]);
        $menu->add([
            'title' => __('Văn bản trình ký'),
            'icon' => 'replace',
            'name' => 'proposal',
            'parent' => '',
            'order' => 150,
            'ignore_if' => [],
            'depend_on' => ['Account','Taskly'],
            'route' => 'proposal.index',
            'module' => $module,
            'permission' => 'proposal manage'
        ]);
        $menu->add([
            'title' => __('Văn bản ký duyệt'),
            'icon' => 'file-invoice',
            'name' => 'invoice',
            'parent' => '',
            'order' => 200,
            'ignore_if' => [],
            'depend_on' => ['Account','Taskly'],
            'route' => 'invoice.index',
            'module' => $module,
        'permission' => 'invoice manage'
        ]);
        $menu->add([
            'title' => __('Messenger'),
            'icon' => 'brand-hipchat',
            'name' => 'messenger',
            'parent' => '',
            'order' => 1500,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'chatify',
            'module' => $module,
            'permission' => 'user chat manage'
        ]);
        $menu->add([
            'title' => __('Liên kết văn bản'),
            'icon' => 'headphones',
            'name' => 'helpdesk',
            'parent' => null,
            'order' => 1900,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'helpdesk.index',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
        $menu->add([
            'title' => __('Settings'),
            'icon' => 'settings',
            'name' => 'settings',
            'parent' => null,
            'order' => 2000,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'setting manage'
        ]);
        $menu->add([
            'title' => __('System Settings'),
            'icon' => '',
            'name' => 'system-settings',
            'parent' => 'settings',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'settings.index',
            'module' => $module,
            'permission' => 'setting manage'
        ]);
        // $menu->add([
        //     'title' => __('Setup Subscription Plan'),
        //     'icon' => '',
        //     'name' => 'setup-subscription-plan',
        //     'parent' => 'settings',
        //     'order' => 20,
        //     'ignore_if' => [],
        //     'depend_on' => [],
        //     'route' => 'plans.index',
        //     'module' => $module,
        //     'permission' => 'plan manage'
        // ]);
        // $menu->add([
        //     'title' => __('Order'),
        //     'icon' => '',
        //     'name' => 'order',
        //     'parent' => 'settings',
        //     'order' => 30,
        //     'ignore_if' => [],
        //     'depend_on' => [],
        //     'route' => 'plan.order.index',
        //     'module' => $module,
        //     'permission' => 'plan orders'
        // ]);

         $menu->add([
            'title' => __('Hỗ trợ kỹ thuật'),
            'icon' => 'users',
            'name' => 'support-tech',
            'parent' => null,
            'order' => 700,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
        $menu->add([
            'title' => __('Tài liệu hướng dẫn sử dụng'),
            'icon' => '',
            'name' => 'pdf',
            'parent' => 'support-tech',
            'order' => 600,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'technical.show-pdf',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
        $menu->add([
            'title' => __('Video HDSD'),
            'icon' => '',
            'name' => 'video',
            'parent' => 'support-tech',
            'order' => 500,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'technical.show-video',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);

        $menu->add([
            'title' => __('Kho văn bản'),
            'icon' => 'ti ti-file-text',
            'name' => 'text warehouse',
            'parent' => null,
            'order' => 600,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
        $menu->add([
            'title' => __('Sổ văn bản đến'),
            'icon' => '',
            'name' => 'document book arrives',
            'parent' => 'text warehouse',
            'order' => 601,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'helpdesk.index',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
        $menu->add([
            'title' => __('Sổ văn bản đi'),
            'icon' => '',
            'name' => 'text book',
            'parent' => 'text warehouse',
            'order' => 602,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'helpdesk.index',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
        $menu->add([
            'title' => __('Văn bản liên quan'),
            'icon' => '',
            'name' => 'related documents',
            'parent' => 'text warehouse',
            'order' => 603,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'helpdesk.index',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
       
        $menu->add([
            'title' => __('Thư viện văn bản'),
            'icon' => '',
            'name' => 'text library',
            'parent' => 'text warehouse',
            'order' => 604,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'helpdesk.index',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);

        $menu->add([
            'title' => __('Quản lí hồ sơ'),
            'icon' => 'ti ti-ti ti-file-text',
            'name' => 'record management',
            'parent' => '',
            'order' => 605,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
        $menu->add([
            'title' => __('Hồ sơ duyệt mượn LĐ'),
            'icon' => '',
            'name' => 'borrow-employee-records',
            'parent' => 'record management',
            'order' => 606,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'borrow-employee-records.index',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);

        $menu->add([
            'title' => __('Hồ sơ duyệt mượn DC'),
            'icon' => '',
            'name' => 'borrow-asset-records',
            'parent' => 'record management',
            'order' => 607,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'borrow-asset-records.index',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
        //
         $menu->add([
            'title' => __('Quản lí tài liệu'),
            'icon' => 'ti ti-file-invoice',
            'name' => 'document management',
            'parent' => null,
            'order' => 650,
            'ignore_if' => [],
            'depend_on' => [],
//            'route' => 'helpdesk.index',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
        $menu->add([
            'title' => __('Danh mục kho'),
            'icon' => '',
            'name' => 'document book arrives',
            'parent' => 'document management',
            'order' => 601,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'helpdesk.index',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
        $menu->add([
            'title' => __('Danh sách lưu trữ hồ sơ'),
            'icon' => '',
            'name' => 'list contract',
            'parent' => 'document management',
            'order' => 690,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'recordkeeping.list',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
//        danh sách mượn
        $menu->add([
            'title' => __('Danh sách mượn'),
            'icon' => '',
            'name' => 'document book arrives',
            'parent' => 'document management',
            'order' => 601,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'manager-file.index',
            'module' => $module,
//            'permission' => 'helpdesk ticket manage'
        ]);
        $menu->add([
            'title' => __('Danh mục kệ'),
            'icon' => '',
            'name' => 'text book',
            'parent' => 'document management',
            'order' => 602,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'helpdesk.index',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
        $menu->add([
            'title' => __('Danh mục hộp'),
            'icon' => '',
            'name' => 'related documents',
            'parent' => 'document management',
            'order' => 603,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'helpdesk.index',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
        $menu->add([
            'title' => __('Danh mục tầng'),
            'icon' => '',
            'name' => 'text library',
            'parent' => 'document management',
            'order' => 604,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'helpdesk.index',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
        // Công việc đa giao
        $menu->add([
            'title' => __('Công việc đã giao'),
            'icon' => 'ti ti-file-invoice',
            'name' => 'work assigned',
            'parent' => null,
            'order' => 660,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
        $menu->add([
            'title' => __('Văn bản xét duyệt'),
            'icon' => '',
            'name' => 'document review',
            'parent' => 'work assigned',
            'order' => 661,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'invoice.index',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
        $menu->add([
            'title' => __('Văn bản ban hành'),
            'icon' => '',
            'name' => 'issued documents',
            'parent' => 'work assigned',
            'order' => 662,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'invoice.index',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);

        $menu->add([
            'title' => 'Trình ký mẫu',
            'icon' => '',
            'name' => 'signature-sample',
            'parent' => 'work assigned',
            'order' => 662,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'signature-sample.index',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);

        $menu->add([
            'title' => 'Loại trình ký mẫu',
            'icon' => '',
            'name' => 'signature-sample-type',
            'parent' => 'work assigned',
            'order' => 663,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'signature-sample-type.index',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);

        //Quản lí công việc
        $menu->add([
            'title' => __('Quản lí công việc'),
            'icon' => 'icon ti ti-calendar-time',
            'name' => 'work management',
            'parent' => null,
            'order' => 670,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
        $menu->add([
            'title' => __('Nhóm'),
            'icon' => '',
            'name' => 'group',
            'parent' => 'work management',
            'order' => 670,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'pms.index',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
        //
          $menu->add([
            'title' => __('Lưu trữ công việc'),
            'icon' => 'ti ti-brand-codesandbox',
            'name' => 'archive work',
            'parent' => null,
            'order' => 680,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
        $menu->add([
            'title' => __('Danh sách mượn'),
            'icon' => '',
            'name' => 'list-ld',
            'parent' => 'archive work',
            'order' => 684,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'list.ld',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
        $menu->add([
            'title' => __('Dự án'),
            'icon' => '',
            'name' => 'prj',
            'parent' => 'archive work',
            'order' => 681,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'projects.index',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
        $menu->add([
            'title' => __('Hồ sơ'),
            'icon' => '',
            'name' => 'file',
            'parent' => 'archive work',
            'order' => 682,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'document.index',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
        $menu->add([
            'title' => __('Tiếp nhận lưu trữ'),
            'icon' => '',
            'name' => 'receiving storage',
            'parent' => 'archive work',
            'order' => 683,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'employee.index',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
    }
}
