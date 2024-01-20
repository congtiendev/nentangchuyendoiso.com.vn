<?php

namespace Modules\CMMS\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\AIAssistant\Entities\AssistantTemplate;

class AIAssistantTemplateListTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $defaultTemplate = [
            [
                'template_name'=>'wo_name',
                'prompt'=>"give me the  name for a : \\n\\n##description## \\n\\n of : \\n ##title##\\n\\n",
                'template_module'=>'workorder',
                'field_json'=>'{"field":[{"label":"title","placeholder":"e.g. car store,bike","field_type":"text_box","field_name":"title"},{"label":"Description","placeholder":"e.g. engilne oil checking , filter checking","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=>'0',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'template_name'=>'instructions',
                'prompt'=>'suggest strict one line instruction for ##description## for worker',
                'template_module'=>'workorder',
                'field_json'=>'{"field":[{"label":"What type of your work ?","placeholder":"e.g.engine oil level , break checking","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=>'1',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
             [
                'template_name'=>'description',
                'prompt'=>'suggest strict one line log time description for ##description##',
                'template_module'=>'wos_logtime',
                'field_json'=>'{"field":[{"label":"name","placeholder":"e.g.","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=>'1',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'template_name'=>'description',
                'prompt'=>'generate strict one line invoice description for ##description##',
                'template_module'=>'wos_invoice',
                'field_json'=>'{"field":[{"label":"name","placeholder":"e.g.check engine oil,fuel testing","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=>'1',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'template_name'=>'description',
                'prompt'=>'generate tiny comment to customer for this work order  : ##title##',
                'template_module'=>'wos_comment',
                'field_json'=>'{"field":[{"label":"name","placeholder":"e.g.","field_type":"textarea","field_name":"title"}]}',
                'is_tone'=>'1',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'template_name'=>'name',
                'prompt'=>'list out only just name of component that can used in :nn ##description## nn the use of component must be  for :n ##title## nnrnrn',
                'template_module'=>'components',
                'field_json'=>'{"field":[{"label":"title","placeholder":"e.g. car store,it company","field_type":"text_box","field_name":"title"},{"label":"Description","placeholder":"e.g. maintain cars","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=>'0',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'template_name'=>'sku',
                'prompt'=>'Generate random 8 letter of string that string can contain : ##keywords##',
                'template_module'=>'components',
                'field_json'=>'{"field":[{"label":"keyword","placeholder":"e.g.A-Z,0-9","field_type":"text_box","field_name":"keyword"}]}',
                'is_tone'=>'0',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'template_name'=>'Category',
                'prompt'=>'generate name of the category of ##title##',
                'template_module'=>'components',
                'field_json'=>'{"field":[{"label":"title","placeholder":"e.g.","field_type":"textarea","field_name":"title"}]}',
                'is_tone'=>'0',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'template_name'=>'Component_Tag',
                'prompt'=>'generate the component tag of ##title##',
                'template_module'=>'components',
                'field_json'=>'{"field":[{"label":"title","placeholder":"e.g.","field_type":"textarea","field_name":"title"}]}',
                'is_tone'=>'0',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'template_name'=>'Description',
                'prompt'=>'generate description of ##description##',
                'template_module'=>'components',
                'field_json'=>'{"field":[{"label":"what is your components detail ?","placeholder":"e.g.","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=>'1',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'template_name'=>'description',
                'prompt'=>'suggest log time description for a ##title##',
                'template_module'=>'components_logtime',
                'field_json'=>'{"field":[{"label":"name","placeholder":"e.g.","field_type":"textarea","field_name":"title"}]}',
                'is_tone'=>'1',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'template_name'=>'name',
                'prompt'=>'give me the part name that is in ##title##',
                'template_module'=>'parts',
                'field_json'=>'{"field":[{"label":"title","placeholder":"e.g.car,bike","field_type":"textarea","field_name":"title"}]}',
                'is_tone'=>'0',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'template_name'=>'category',
                'prompt'=>'give name of category for a ##title##',
                'template_module'=>'parts',
                'field_json'=>'{"field":[{"label":"Part Name","placeholder":"e.g.","field_type":"text_box","field_name":"title"}]}',
                'is_tone'=>'0',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'template_name'=>'description',
                'prompt'=>'suggest log time description for a ##title##',
                'template_module'=>'parts_logtime',
                'field_json'=>'{"field":[{"label":"name","placeholder":"e.g.","field_type":"textarea","field_name":"title"}]}',
                'is_tone'=>'1',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'template_name'=>'description',
                'prompt'=>'Generate me the description for a preventive maintenance of ##description##',
                'template_module'=>'pms',
                'field_json'=>'{"field":[{"label":"what is your maintenance task ?","placeholder":"e.g.daily opertaing checklist","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=>'1',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'template_name'=>'description',
                'prompt'=>'generate invoice description for ##description##',
                'template_module'=>'pms_invoice',
                'field_json'=>'{"field":[{"label":"name","placeholder":"e.g.","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=>'1',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                'template_name'=>'description',
                'prompt'=>'suggest strict one line log time description for ##description##',
                'template_module'=>'pms_logtime',
                'field_json'=>'{"field":[{"label":"name","placeholder":"e.g.engine oil level checking","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=>'1',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
        ];
        foreach($defaultTemplate as $temp)
        {
            $check = AssistantTemplate::where('template_module',$temp['template_module'])->where('module','CMMS')->where('template_name',$temp['template_name'])->exists();
            if(!$check)
            {
                AssistantTemplate::create(
                    [
                        'template_name' => $temp['template_name'],
                        'template_module' => $temp['template_module'],
                        'module' => 'CMMS',
                        'prompt' => $temp['prompt'],
                        'field_json' => $temp['field_json'],
                        'is_tone' => $temp['is_tone'],
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
            }
        }
    }
}
