<?php

namespace Modules\Hrm\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Hrm';
        $menu = $event->menu;
        $menu->add([
            'title' => __('HRM'),
            'icon' => '',
            'name' => 'hrm-dashboard',
            'parent' => 'dashboard',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'hrm.dashboard',
            'module' => $module,
            'permission' => 'hrm dashboard manage'
        ]);
        $menu->add([
            'title' => __('HRM'),
            'icon' => 'ti ti-3d-cube-sphere',
            'name' => 'hrm',
            'parent' => null,
            'order' => 450,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'hrm manage'
        ]);
        $menu->add([
            'title' => __('Employee'),
            'icon' => '',
            'name' => 'employee',
            'parent' => 'hrm',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'employee.index',
            'module' => $module,
            'permission' => 'employee manage'
        ]);
        $menu->add([
        'title' => __('Bổ nhiệm'),
        'icon' => '',
        'name' => 'appoint',
        'parent' => 'hrm',
        'order' => 12,
        'ignore_if' => [],
        'depend_on' => [],
        'route' => 'appoint.appoint',
        'module' => $module,
        'permission' => 'employee manage'
        ]);
        $menu->add([
            'title' => __('Payroll'),
            'icon' => '',
            'name' => 'payroll',
            'parent' => 'hrm',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'sidebar payroll manage'
        ]);
        $menu->add([
            'title' => __('Set salary'),
            'icon' => '',
            'name' => 'set-salary',
            'parent' => 'payroll',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'setsalary.index',
            'module' => $module,
            'permission' => 'setsalary manage'
        ]);
        $menu->add([
            'title' => __('Payslip'),
            'icon' => '',
            'name' => 'payslip',
            'parent' => 'payroll',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'payslip.index',
            'module' => $module,
            'permission' => 'setsalary pay slip manage'
        ]);
        $menu->add([
            'title' => __('Attendance'),
            'icon' => '',
            'name' => 'attendance',
            'parent' => 'hrm',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'attendance manage'
        ]);
        $menu->add([
            'title' => __('Mark Attendance'),
            'icon' => '',
            'name' => 'mark-attendance',
            'parent' => 'attendance',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'attendance.index',
            'module' => $module,
            'permission' => 'attendance manage'
        ]);
        $menu->add([
            'title' => __('Bulk Attendance'),
            'icon' => '',
            'name' => 'bulk-attendance',
            'parent' => 'attendance',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'attendance.bulkattendance',
            'module' => $module,
            'permission' => 'bulk attendance manage'
        ]);
        $menu->add([
            'title' => __('Manage Leave'),
            'icon' => '',
            'name' => 'manage-leave',
            'parent' => 'hrm',
            'order' => 25,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'leave.index',
            'module' => $module,
            'permission' => 'leave manage'
        ]);
        $menu->add([
            'title' => __('HR Admin'),
            'icon' => '',
            'name' => 'hr-admin',
            'parent' => 'hrm',
            'order' => 45,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'sidebar hr admin manage'
        ]);
        $menu->add([
            'title' => __('Award'),
            'icon' => '',
            'name' => 'award',
            'parent' => 'hr-admin',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'award.index',
            'module' => $module,
            'permission' => 'award manage'
        ]);
        $menu->add([
            'title' => __('Điều chuyển nhân sự'),
            'icon' => '',
            'name' => 'transfer',
            'parent' => 'hr-admin',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'transfer.index',
            'module' => $module,
            'permission' => 'transfer manage'
        ]);
        $menu->add([
            'title' => __('Nghỉ việc'),
            'icon' => '',
            'name' => 'resignation',
            'parent' => 'hr-admin',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'resignation.index',
            'module' => $module,
            'permission' => 'resignation manage'
        ]);
        $menu->add([
            'title' => __('Trip'),
            'icon' => '',
            'name' => 'trip',
            'parent' => 'hr-admin',
            'order' => 25,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'trip.index',
            'module' => $module,
            'permission' => 'travel manage'
        ]);
        $menu->add([
            'title' => __('Promotion'),
            'icon' => '',
            'name' => 'promotion',
            'parent' => 'hr-admin',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'promotion.index',
            'module' => $module,
            'permission' => 'promotion manage'
        ]);
        $menu->add([
            'title' => __('Complaints'),
            'icon' => '',
            'name' => 'complaints',
            'parent' => 'hr-admin',
            'order' => 35,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'complaint.index',
            'module' => $module,
            'permission' => 'complaint manage'
        ]);
        $menu->add([
            'title' => __('Warning'),
            'icon' => '',
            'name' => 'warning',
            'parent' => 'hr-admin',
            'order' => 40,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'warning.index',
            'module' => $module,
            'permission' => 'warning manage'
        ]);
        $menu->add([
            'title' => __('Miễn nhiệm'),
            'icon' => '',
            'name' => 'termination',
            'parent' => 'hr-admin',
            'order' => 45,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'termination.index',
            'module' => $module,
            'permission' => 'termination manage'
        ]);
        $menu->add([
            'title' => __('Announcement'),
            'icon' => '',
            'name' => 'announcement',
            'parent' => 'hr-admin',
            'order' => 50,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'announcement.index',
            'module' => $module,
            'permission' => 'announcement manage'
        ]);
        $menu->add([
            'title' => __('Holidays'),
            'icon' => '',
            'name' => 'holidays',
            'parent' => 'hr-admin',
            'order' => 55,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'holiday.index',
            'module' => $module,
            'permission' => 'holiday manage'
        ]);
        $menu->add([
            'title' => "Quản lí ca làm việc",
            'icon' => '',
            'name' => 'workshift',
            'parent' => 'hrm',
            'order' => 49,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'workshift.index',
            'module' => $module,
        ]);
        $menu->add([
            'title' => "Bảo hiểm",
            'icon' => '',
            'name' => 'insurance',
            'parent' => 'hrm',
            'order' => 50,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'insurance.index',
            'module' => $module,
        ]);
        $menu->add([
            'title' => __('Event'),
            'icon' => '',
            'name' => 'event',
            'parent' => 'hrm',
            'order' => 50,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'event.index',
            'module' => $module,
            'permission' => 'event manage'
        ]);
        $menu->add([
            'title' => __('Document'),
            'icon' => '',
            'name' => 'document',
            'parent' => 'hrm',
            'order' => 55,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'document.index',
            'module' => $module,
            'permission' => 'document manage'
        ]);
        $menu->add([
            'title' => __('Kỷ luật'),
            'icon' => '',
            'name' => 'company-policy',
            'parent' => 'hrm',
            'order' => 60,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'company-policy.index',
            'module' => $module,
            'permission' => 'companypolicy manage'
        ]);
        $menu->add([
            'title' => __('System Setup'),
            'icon' => '',
            'name' => 'system-setup',
            'parent' => 'hrm',
            'order' => 65,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'branch.index',
            'module' => $module,
            'permission' => 'branch manage'
        ]);
        $menu->add([
            'title' => __('Report'),
            'icon' => '',
            'name' => 'hrm-report',
            'parent' => 'hrm',
            'order' => 70,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'sidebar hrm report manage'
        ]);
        $menu->add([
            'title' => __('Monthly Attendance'),
            'icon' => '',
            'name' => 'monthly-attendance',
            'parent' => 'hrm-report',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'report.monthly.attendance',
            'module' => $module,
            'permission' => 'attendance report manage'
        ]);
        $menu->add([
            'title' => __('Leave'),
            'icon' => '',
            'name' => 'leave',
            'parent' => 'hrm-report',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'report.leave',
            'module' => $module,
            'permission' => 'leave report manage'
        ]);
        $menu->add([
            'title' => __('Payroll'),
            'icon' => '',
            'name' => 'report-payroll',
            'parent' => 'hrm-report',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'report.payroll',
            'module' => $module,
            'permission' => 'payroll report manage'
        ]);
        $menu->add([
            'title' => __('Quản lí quy trình'),
            'icon' => '',
            'name' => 'procedures',
            'parent' => 'hrm',
            'order' => 100,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'procedures.index',  
            'module' => $module,
            'permission' => 'employee manage'
        ]);
    }
}
