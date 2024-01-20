<?php

namespace Modules\CMMS\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\LandingPage\Entities\MarketplacePageSetting;

class MarketPlaceSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $data['product_main_banner'] = '';
        $data['product_main_status'] = 'on';
        $data['product_main_heading'] = 'CMMS';
        $data['product_main_description'] = '<p>CmmsGo is the most convenient tool to manage your purchases, sales, and maintenance of the components, and parts along with outsourcing of the same following the location you are working. It makes the whole management of such a thing very transparent and usable.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = '<h2>Complete Maintenance Management System</h2>';
        $data['dedicated_theme_description'] = '<p>CMMS gives users deep insight into their maintenance needs with detailed work order schedules, reliable reports, dashboards, log times.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_status":"on","dedicated_theme_section_heading":"CMMS makes it easy to manage work orders","dedicated_theme_section_description":"<p>Workorder Management Solution lets you improve the team&rsquo;s time while completing the task and reduces the time you use to manage it. It helps you keep an eye on activities and keep you up to date with all the required information.&nbsp;<\/p>","dedicated_theme_section_cards":{"1":{"title":"Easy to manage work orders","description":"This tool gives you a central communication platform where teammates can share updates and documents with ease."},"2":{"title":"Easy to manage work request","description":"This tool gives can manage user work request which is help to communicate with company and easily send to thier work requirement."}}},{"dedicated_theme_section_image":"","dedicated_theme_section_status":"on","dedicated_theme_section_heading":"Most effective way to manage your preventive maintenance","dedicated_theme_section_description":"<p>A preventive maintenance solution makes sure that on an ongoing basis, maintenance can be scheduled automatically for assets, and equipment and allocated to a chosen contractor in a seamless and automated manner.<\/p>","dedicated_theme_section_cards":{"1":{"title":"Manage preventive maintenance","description":"A preventive Maintenance solution is the easiest way to keep your parts running smoothly and prevent downtime with a proactive schedule of machine"},"2":{"title":"Well-managed purchase orders","description":"It helps you track how many orders you have placed for an item in a specific time period and with a specific supplier"}}},{"dedicated_theme_section_image":"","dedicated_theme_section_status":"on","dedicated_theme_section_heading":"Manage several locations","dedicated_theme_section_description":"<p>CMMS with multiple locations greatly benefit from location management software as it provides centralized control and oversight. It allows businesses to effectively monitor and manage operations across all locations from a single platform<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_status":"on","dedicated_theme_section_heading":"Component maintenance operations","dedicated_theme_section_description":"<p>This report is for a single component and shows key data around who has worked on the Component , Associated documentation and images such as repair manuals and warranties.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"CMMS"},{"screenshots":"","screenshots_heading":"CMMS"},{"screenshots":"","screenshots_heading":"CMMS"},{"screenshots":"","screenshots_heading":"CMMS"},{"screenshots":"","screenshots_heading":"CMMS"}]';
        $data['addon_heading'] = '<h2>Why choose dedicated modules<b> for Your Business?</b></h2>';
        $data['addon_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['addon_section_status'] = 'on';
        $data['whychoose_heading'] = 'Why choose dedicated modulesfor Your Business?';
        $data['whychoose_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['pricing_plan_heading'] = 'Empower Your Workforce with DASH';
        $data['pricing_plan_description'] = '<p>Access over Premium Add-ons for Accounting, HR, Payments, Leads, Communication, Management, and more, all in one place!</p>';
        $data['pricing_plan_demo_link'] = '#';
        $data['pricing_plan_demo_button_text'] = 'View Live Demo';
        $data['pricing_plan_text'] = '{"1":{"title":"Pay-as-you-go"},"2":{"title":"Unlimited installation"},"3":{"title":"Secure cloud storage"}}';
        $data['whychoose_sections_status'] = 'on';
        $data['dedicated_theme_section_status'] = 'on';

        foreach($data as $key => $value){
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', 'CMMS')->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => 'CMMS'

                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
