<?php

namespace Modules\CMMS\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\EmailTemplate;
use App\Models\EmailTemplateLang;

class EmailTemplateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $emailTemplate = [
            'Work Order Request',
            'Work Order Assigned',
            'New Supplier',
            'New POs',
        ];

        $defaultTemplate = [
            'Work Order Request' => [
                'subject' => 'Work Order Request',
                'variables' => '{
                    "App Url": "app_url",
                    "App Name": "app_name",
                    "Company Name": "company_name",
                    "Work Request Name": "work_request_name",
                    "Email": "email",
                    "Problem": "problem",
                    "Instructions": "instruction"
                  }',
                'lang' => [
                    'ar' => '<p><strong>عزيزي </strong>{app_name}<strong>,</strong></p>
                    <p><strong>طلب العمل </strong>: { work_request_name }</p>
                    <p><strong>البريد الالكتروني </strong>: {email}</p>
                    <p><strong>المشكلة</strong> : {problem}</p>
                    <p><strong>Instruction</strong>: {instruction}</p>
                    <p><strong>برجاء الاتصال بنا للحصول على مزيد من المعلومات.</strong></p>
                    <p><strong>Regards نوع ،</strong></p>
                    <p>{app_name}</p>',
                    'da' => '<p><strong>K&aelig;re </strong>{ app_name },</p>
                    <p><strong>Arbejdsanmodning </strong>: { work_request_name }</p>
                    <p><strong>E-mail </strong>: { email }</p>
                    <p><strong>Problem </strong>: { problem }</p>
                    <p><strong>Instruction</strong>: {instruction}</p>
                    <p>Kontakt os for at f&aring; flere oplysninger.</p>
                    <p>Kind Hilds,</p>
                    <p>{ app_name }</p>',
                    'de' => '<p><strong>K&aelig;re </strong>{ app_name },</p>
                    <p><strong>Arbejdsanmodning </strong>: { work_request_name }</p>
                    <p><strong>E-mail </strong>: { email }</p>
                    <p><strong>Problem </strong>: { problem }</p>
                    <p><strong>Instruction</strong>: {instruction}</p>
                    <p>Kontakt os for at f&aring; flere oplysninger.</p>
                    <p>Kind Hilds,</p>
                    <p>{ app_name }</p>',
                    'en' => '<p><strong>Dear</strong> {app_name},</p>
                    <p><strong> Work Request Name</strong>: {work_request_name}</p>
                    <p><strong> Email</strong>&nbsp;: {email}</p>
                    <p><strong>Problem</strong>&nbsp;: {problem}</p>
                    <p><strong>Instruction</strong>: {instruction}</p>
                    <p>Please Contact us for more information.</p>
                    <p>Kind Regards,</p>
                    <p>{app_name}</p>',
                    'es' => '<p><strong>Estimado </strong>{app_name},</p>
                    <p><strong>Solicitud de trabajo </strong>: {work_request_name}</p>
                    <p><strong>Correo electr&oacute;nico </strong>: {email}</p>
                    <p><strong>Problema </strong>: {problem}</p>
                    <p><strong>Instruction</strong>: {instruction}</p>
                    <p>P&oacute;ngase en contacto con nosotros para obtener m&aacute;s informaci&oacute;n.</p>
                    <p>Bondadoso,</p>
                    <p>{app_name}</p>',
                    'fr' => '<p><strong>Cher</strong> { app_name },</p>
                    <p><strong>Demande de travail </strong>: { work_request_name }</p>
                    <p><strong>Adresse &eacute;lectronique </strong>: { email }</p>
                    <p><strong>Incident </strong>: { problem }</p>
                    <p><strong>Instruction</strong>: {instruction}</p>
                    <p>Veuillez nous contacter pour plus dinformations.</p>
                    <p>Kind Regards,</p>
                    <p>{ nom_app }</p>',
                    'it' => '<p><strong>Caro </strong>{app_name},</p>
                    <p><strong>Richiesta di lavoro </strong>: {work_request_name}</p>
                    <p><strong>mail </strong>: {email}</p>
                    <p><strong>Problema </strong>: {problema}</p>
                    <p><strong>Instruction</strong>: {instruction}</p>
                    <p>Vi preghiamo di contattarci per maggiori informazioni.</p>
                    <p>Kind Regards,</p>
                    <p>{app_name}</p>',
                    'ja' => '<p><strong>{app_name}の定義</strong></p>
                    <p><strong>作業要求 </strong>: {work_request_name}</p>
                    <p><strong>E メール </strong>: {email}</p>
                    <p><strong>問題 </strong>: {problem}</p>
                    <p><strong>Instruction</strong>: {instruction}</p>
                    <p>詳しくは、弊社にお問い合わせください。</p>
                    <p>カンド・リーカード</p>
                    <p>{app_name}</p>',
                    'nl' => '<p><strong>Geachte </strong>{ app_name },</p>
                    <p><strong>Werkopdracht </strong>: { work_request_name }</p>
                    <p><strong>E-mail </strong>: { email }</p>
                    <p><strong>Probleem </strong>: { problem }</p>
                    <p><strong>Instruction</strong>: {instruction}</p>
                    <p>Neem contact met ons op voor meer informatie.</p>
                    <p>Vriendelijke groeten,</p>
                    <p>{ app_name }</p>',
                    'pl' => '<p><strong>Drogi </strong>{app_name },</p>
                    <p><strong>Zlecenie pracy </strong>: {work_request_name }</p>
                    <p><strong>E-mail </strong>: {email }</p>
                    <p><strong>Problem </strong>: {problem }</p>
                    <p><strong>Instruction</strong>: {instruction}</p>
                    <p>Skontaktuj się z nami, aby uzyskać więcej informacji.</p>
                    <p>W Odniesieniu Do Rodzaju,</p>
                    <p>{app_name }</p>',
                    'ru' => '<p><strong>Уважаемый </strong>{ app_name },</p>
                    <p><strong>Рабочий запрос </strong>: { work_request_name }</p>
                    <p><strong>лектронная почта </strong>: { email }</p>
                    <p><strong>Неполадка </strong>: { неполадка }</p>
                    <p><strong>Instruction</strong>: {instruction}</p>
                    <p>Пожалуйста, свяжитесь с нами для получения дополнительной информации.</p>
                    <p>Привет.</p>
                    <p>{ имя_программы }</p>',
                    'pt' => '<p><strong>Querido </strong>{app_name},</p>
                    <p><strong>Solicita&ccedil;&atilde;o de Trabalho </strong>: {work_request_name}</p>
                    <p><strong>E-mail </strong>: {email}</p>
                    <p><strong>Problema </strong>: {problema}</p>
                    <p><strong>Instruction</strong>: {instruction}</p>
                    <p>Por favor, entre em contato conosco para mais informa&ccedil;&otilde;es.</p>
                    <p>Esp&eacute;cie Considera,</p>
                    <p>{app_name}</p>',
                    'tr' => '<p><strong>Sevgili</strong> {app_name},</p>
                    <p><strong> İş İsteği</strong> <strong>Ad</strong>: {work_request_name}</p>
                    <p><strong> Eposta</strong>&nbsp;: {email}</p>
                    <p><strong>Sorun</strong>&nbsp;: {problem}</p>
                    <p><strong>Instruction</strong>: {instruction}</p>
                    <p>Daha fazla bilgi için lütfen bizimle iletişim kurun.</p>
                    <p>Saygılarımla,</p>
                    <p>{app_name}</p>',
                    'zh' => '<p><strong>亲</strong> {app_name},</p>
                    <p><strong> 工作请求</strong> <strong>  名称</strong>: {work_request_name}</p>
                    <p><strong> 名称</strong>&nbsp;: {email}</p>
                    <p><strong>问题</strong>&nbsp;: {problem}</p>
                    <p><strong>Instruction</strong>: {instruction}</p>
                    <p>请联系我们了解更多信息。</p>
                    <p>敬敬,</p>
                    <p>{app_name}</p>',
                    'iw' => '<p><strong>יקר</strong> {app_name},</p>
                    <p><strong> בקשת עבודה</strong> <strong>Name</strong>: {work_request_name}</p>
                    <p><strong> דוא " ל</strong>&nbsp;: {email}</p>
                    <p><strong>בעיה</strong>&nbsp;: {problem}</p>
                    <p><strong>Instruction</strong>: {instruction}</p>
                    <p>פנו אלינו לקבלת מידע נוסף..</p>
                    <p>סוג ד " ש,</p>
                    <p>{app_name}</p>',
                    'pt-br' => '<p><strong>Querido</strong> {app_name},</p>
                    <p><strong> Solicitação de Trabalho</strong> <strong>Name</strong>: {work_request_name}</p>
                    <p><strong> Email</strong>&nbsp;: {email}</p>
                    <p><strong>Problema</strong>&nbsp;: {problem}</p>
                    <p><strong>Instruction</strong>: {instruction}</p>
                    <p>Entre em contato conosco para mais informações.</p>
                    <p>Saudações,</p>
                    <p>{app_name}</p>',

                ],
            ],

            'Work Order Assigned' => [
                'subject' => 'Work Order Assigned',
                'variables' => '{
                    "App Url": "app_url",
                    "App Name": "app_name",
                    "Company Name": "company_name",
                    "Work Order Id": "work_order_id",
                    "Email": "email",
                    "Work Order Due Date": "work_order_due_date",
                    "Components" : "components",
                    "URL": "url"
                  }',
                'lang' => [
                    'ar' => '<p><strong>مرحبا</strong></p>
                    <p>تم تخصيص أمر تشغيل جديد لك.</p>
                    <p><strong>كود أمر التشغيل</strong> : { work_order_id }</p>
                    <p>الأصول :<strong> { components }</strong></p>
                    <p><strong>الأولوية </strong>: { priority }</p>
                    <p><strong>تاريخ استحقاق أمر التشغيل</strong> : { work_order_due_date }</p>
                    <p>نحن نتطلع لجلسة استماع منك</p>
                    <p>Regards نوع ،</p>
                    <p>{ app_name }</p>',
                    'da' => '<p><strong>Hallo?</strong></p>
                    <p>Ny arbejdsordre er tildelt til dig.</p>
                    <p><strong>Arbejdsordre-id </strong>: { work_order_id }</p>
                    <p><strong>Aktiver </strong>: { components }</p>
                    <p><strong>Prioritet</strong>: { priority }</p>
                    <p><strong>Forfaldsdato for arbejdsordren </strong>: { work_order_due_date }</p>
                    <p>Vi ser frem til at h&oslash;re fra dig.</p>
                    <p>Kind Hilds,</p>
                    <p>{ app_name }</p>',
                    'de' => '<p><strong>Hallo,</strong></p>
                    <p>Der neue Auftrag wird Ihnen zugewiesen.</p>
                    <p><strong>Auftrags-ID </strong>: {work_order_id}</p>
                    <p><strong>Components </strong>: {components}</p>
                    <p><strong>Priorit&auml;t</strong> : {priority}</p>
                    <p><strong>F&auml;lligkeitsdatum f&uuml;r Arbeitsauftrag </strong>: {work_order_due_date}</p>
                    <p>Wir freuen uns, von Ihnen zu h&ouml;ren.</p>
                    <p>G&uuml;tige Gr&uuml;&szlig;e,</p>
                    <p>{app_name}</p>',
                    'en' => '<p><strong>Hi</strong>.</p>
                    <p>A new work order has been assigned to you.</p>
                    <p><strong>Work Order ID</strong>: {work_order_id}</p>
                    <p><strong>Components</strong>: {components}</p>
                    <p><strong>Priority</strong>: {priority}</p>
                    <p><strong>Work Order Due Date</strong>: {work_order_due_date}</p>
                    <p>We look forward to hearing from you.</p>
                    <p>Regards type,</p>
                    <p>{app_name}</p>',
                    'es' => '<p><strong>Hola,</strong></p>
                    <p>Se le asigna una nueva orden de trabajo.</p>
                    <p><strong>Id De Orden De Trabajo </strong>: {work_order_id}</p>
                    <p><strong>Activos </strong>: {components}</p>
                    <p><strong>prioridad </strong>: {priority}</p>
                    <p><strong>Fecha de vencimiento del pedido de trabajo </strong>: {work_order_due_date}</p>
                    <p>Estamos mirando hacia adelante escuchando de usted.</p>
                    <p>Bondadoso,</p>
                    <p>{app_name}</p>',
                    'fr' => '<p><strong>Bonjour,</strong></p>
                    <p>Une nouvelle intervention vous est affect&eacute;e.</p>
                    <p><strong>ID de lintervention </strong>: { work_order_id }</p>
                    <p><strong>Actifs </strong>: { components }</p>
                    <p><strong>Priority </strong>: { priority }</p>
                    <p><strong>Date d&eacute;ch&eacute;ance de lintervention </strong>: { work_order_due_date }</p>
                    <p>Nous attendons avec impatience de vous entendre.</p>
                    <p>Kind Regards,</p>
                    <p>{ app_name }</p>',
                    'it' => '<p><strong>Ciao,</strong></p>
                    <p>Nuovo ordine di lavoro &egrave; assegnato a te.</p>
                    <p><strong>Id ordine di lavoro </strong>: {work_order_id}</p>
                    <p><strong>Attivit&agrave; </strong>: {components}</p>
                    <p><strong>priorit&agrave; </strong>: {priority}</p>
                    <p><strong>Data ordine di lavoro Data </strong>: {work_order_due_date}</p>
                    <p>Siamo in attesa di udienza da parte sua.</p>
                    <p>Kind Regards,</p>
                    <p>{app_name}</p>',
                    'ja' => '<p><strong>こんにちは。</strong></p>
                    <p>新規作業指示書がユーザーに割り当てられます。</p>
                    <p><strong>作業指示書 ID </strong>: {work_order_id}</p>
                    <p><strong>アセット</strong> : {components}</p>
                    <p><strong>優先順位</strong> : {priority}</p>
                    <p><strong>作業指示書期限</strong> : {work_order_due_date}</p>
                    <p>私たちはあなたからの意見聴取をしています</p>
                    <p>カンド・リーカード</p>
                    <p>{app_name}</p>',
                    'nl' => '<p><strong>Hallo,</strong></p>
                    <p>Nieuwe werkorder is toegewezen aan u.</p>
                    <p><strong>Werkorder-ID </strong>: { work_order_id }</p>
                    <p><strong>Activa </strong>: { components }</p>
                    <p><strong>prioriteit </strong>: { priority }</p>
                    <p><strong>Vervaldatum werkorder </strong>: { work_order_due_date }</p>
                    <p>We zijn op zoek naar een hoorzitting van je.</p>
                    <p>Vriendelijke groeten,</p>
                    <p>{ app_name }</p>',
                    'pl' => '<p><strong>Witaj,</strong></p>
                    <p>Nowe zlecenie pracy jest przypisane do użytkownika.</p>
                    <p><strong>Identyfikator zlecenia pracy </strong>: {work_order_id }</p>
                    <p><strong>Zasoby aplikacyjne </strong>: {components }</p>
                    <p><strong>priorytet </strong>: {priority }</p>
                    <p><strong>Data zakończenia zlecenia pracy </strong>: {work_order_due_date }</p>
                    <p>Czekamy na przesłuchanie od Ciebie.</p>
                    <p>W Odniesieniu Do Rodzaju,</p>
                    <p>{app_name }</p>',
                    'ru' => '<p><strong>Привет.</strong></p>
                    <p>Вам назначено новое рабочее задание.</p>
                    <p><strong>Id Рабочего Задания</strong>: { work_order_id }</p>
                    <p><strong>Активы </strong>: { components }</p>
                    <p><strong>приоритет </strong>: { priority }</p>
                    <p><strong>Дата выполнения рабочего задания </strong>: { priority }</p>
                    <p>Мы с вами смотрим вперед.</p>
                    <p>Привет.</p>
                    <p>{ priority }</p>',
                    'pt' => '<p><strong>Ol&aacute;,</strong></p>
                    <p>Nova Ordem de Servi&ccedil;o &eacute; designada a voc&ecirc;.</p>
                    <p><strong>Id do Pedido de Servi&ccedil;o </strong>: {work_order_id}</p>
                    <p><strong>Ativos </strong>: {components}</p>
                    <p><strong>prioridade </strong>: {priority}</p>
                    <p><strong>Ordem de Servi&ccedil;o Devido Data </strong>: {work_order_due_date}</p>
                    <p>Estamos a olhar para a audi&ccedil;&atilde;o a partir de v&oacute;s.</p>
                    <p>Esp&eacute;cie Considera,</p>
                    <p>{app_name}</p>',
                    'tr' => '<p><strong>Merhaba.</strong>.</p>
                    <p>Size yeni bir iş emri atanmıştır.</p>
                    <p><strong>İş Emri Tanıtıcısı</strong>: {work_order_id}</p>
                    <p><strong>Varlıklar</strong>: {components}</p>
                    <p><strong>Öncelik</strong>: {priority}</p>
                    <p><strong>İş Emri Son Tarihi</strong>: {work_order_due_date}</p>
                    <p>Senden duymayı dört gözle bekliyoruz.</p>
                    <p>Saygılarımla,</p>
                    <p>{app_name}</p>',
                    'zh' => '<p><strong>你好我是</strong>.</p>
                    <p>已向您分配新的工单.</p>
                    <p><strong>工单标识</strong>: {work_order_id}</p>
                    <p><strong>资产</strong>: {components}</p>
                    <p><strong>优先权</strong>: {priority}</p>
                    <p><strong>工单到期日期</strong>: {work_order_due_date}</p>
                    <p>我们期待听到你的意见。</p>
                    <p>典型问题</p>
                    <p>{app_name}</p>',
                    'iw' => '<p><strong>היי</strong>.</p>
                    <p>הזמנת עבודה חדשה הוקצתה לכם.</p>
                    <p><strong>זיהוי הזמנת עבודה</strong>: {work_order_id}</p>
                    <p><strong>נכסים</strong>: {components}</p>
                    <p><strong>קדימות</strong>: {priority}</p>
                    <p><strong>תאריך יעד של הזמנת עבודה</strong>: {work_order_due_date}</p>
                    <p>אנחנו מצפים בקוצר רוח לשמוע ממך..</p>
                    <p>סוג דרישת שלום,</p>
                    <p>{app_name}</p>',
                    'pt-br' => '<p><strong>Oi</strong>.</p>
                    <p>Uma nova ordem de serviço foi atribuída a você.</p>
                    <p><strong>ID da ordem de serviço</strong>: {work_order_id}</p>
                    <p><strong>Ativo</strong>: {components}</p>
                    <p><strong>Prioridade</strong>: {priority}</p>
                    <p><strong>Data de Vencimento da Ordem de Serviço</strong>: {work_order_due_date}</p>
                    <p>Estamos ansiosos para ouvir de você.</p>
                    <p>Respeito ao tipo,</p>
                    <p>{app_name}</p>',
                ],
            ],

            'New Supplier' => [
                'subject' => 'New Supplier',
                'variables' => '{
                    "App Url": "app_url",
                    "App Name": "app_name",
                    "Company Name": "company_name",
                    "Name": "name",
                    "Email": "email",
                    "Contact": "contact",
                    "URL": "url"
                  }',
                'lang' => [
                    'ar' => '<p><strong>مرحبا&nbsp;</strong></p>
                    <p>مرحبا بك في { app_name }.</p>
                    <p><strong>الاسم : </strong>{ name }</p>
                    <p><strong>البريد الالكتروني :</strong> { mail }&nbsp; &nbsp;<strong> &nbsp; &nbsp; &nbsp;</strong></p>
                    <p><strong>جهة الاتصال : </strong>{ contact }</p>
                    <p>شكرا</p>
                    <p>{ app_name }</p>',
                    'da' => '<p><strong>Hallo?&nbsp;</strong></p>
                    <p>Velkommen til { app_name }.</p>
                    <p><strong>Navn: </strong>{ name }</p>
                    <p><strong>E-mail: </strong>{ email }&nbsp; &nbsp; &nbsp; <strong>&nbsp; &nbsp;</strong></p>
                    <p><strong>Kontaktperson:</strong> { contact }</p>
                    <p>Tak.</p>
                    <p>{ app_name }</p>',
                    'de' => '<p><strong>Hallo,&nbsp;</strong></p>
                    <p>Willkommen bei {app_name}.</p>
                    <p><strong>Name: </strong>{name}</p>
                    <p><strong>E-Mail: </strong>{email}&nbsp; &nbsp; <strong>&nbsp; &nbsp; &nbsp;</strong></p>
                    <p><strong>Kontakt: </strong>{contact}</p>
                    <p>Danke,</p>
                    <p>{Anwendungsname}</p>',
                    'en' => '<p><strong>Hello</strong>,&nbsp;</p>
                    <p>Welcome to {app_name}.</p>
                    <p><strong>Name</strong> : {name}</p>
                    <p><strong>Email</strong> : {email}&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>
                    <p><strong>Contact</strong> : {contact}</p>
                    <p><strong>Thanks</strong>,</p>
                    <p>{app_name}</p>',
                    'es' => '<p><strong>Hola,&nbsp;</strong></p>
                    <p>Bienvenido a {app_name} .</p>
                    <p><strong>Nombre: </strong> {name} </p>
                    <p><strong>Correo electr&oacute;nico:</strong> {email} &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>
                    <p><strong>Contacto: </strong>{contact}</p>
                    <p>Gracias,</p>
                    <p> {app_name} </p>',
                    'fr' => '<p><strong>Bonjour,&nbsp;</strong></p>
                    <p>Bienvenue dans { app_name }.</p>
                    <p><strong>Nom: </strong>{ name }</p>
                    <p><strong>Adresse &eacute;lectronique: </strong>{ email }&nbsp; &nbsp; <strong>&nbsp; &nbsp; &nbsp;</strong></p>
                    <p><strong>Contact: </strong>{ contact }</p>
                    <p>Merci,</p>
                    <p><strong>{ nom_app }</strong></p>',
                    'it' => '<p><strong>Ciao,&nbsp;</strong></p>
                    <p>Benvenuti in {app_name}.</p>
                    <p><strong>Nome: </strong>{name}</p>
                    <p><strong>Email: </strong>{email}&nbsp; &nbsp; &nbsp; &nbsp; <strong>&nbsp;</strong></p>
                    <p><strong>Contatto: </strong>{contact}</p>
                    <p>Grazie,</p>
                    <p>{app_name}</p>',
                    'ja' => '<p><strong>こんにちは。&nbsp;</strong></p>
                    <p>{app_name}へようこそ。</p>
                    <p><strong>名前 : </strong>{name}</p>
                    <p><strong>E メール : </strong>{email}&nbsp; &nbsp; &nbsp; <strong>&nbsp; &nbsp;</strong></p>
                    <p><strong>連絡先 : </strong>{contact}</p>
                    <p>ありがとう。</p>
                    <p>{app_name}</p>',
                    'nl' => '<p><strong>Hallo,&nbsp;</strong></p>
                    <p>Welkom bij { app_name }.</p>
                    <p><strong>Naam: </strong>{ name }</p>
                    <p><strong>E-mail: </strong>{ email }&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>
                    <p><strong>Contactpersoon: </strong>{ contact }</p>
                    <p>Bedankt.</p>
                    <p>{ app_name }</p>',
                    'pl' => '<p><strong>Witaj,&nbsp;</strong></p>
                    <p>Witamy w aplikacji {app_name }.</p>
                    <p><strong>Nazwa: </strong>{name }</p>
                    <p><strong>E-mail:</strong> {email }&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>
                    <p><strong>Kontakt: </strong>{contact }</p>
                    <p>Dziękuję,</p>
                    <p>{app_name }</p>',
                    'ru' => '<p><strong>Привет.&nbsp;</strong></p>
                    <p>Вас приветствует { app_name }.</p>
                    <p><strong>Имя: </strong>{ name }</p>
                    <p><strong>Электронная почта:</strong> { email }&nbsp; <strong>&nbsp; &nbsp; &nbsp; &nbsp;</strong></p>
                    <p><strong>Контакт: </strong>{ contact }</p>
                    <p>Спасибо.</p>
                    <p>{ имя_программы }</p>',
                    'pt' => '<p><strong>Ol&aacute;,&nbsp;</strong></p>
                    <p>Bem-vindo a {app_name}.</p>
                    <p><strong>Nome:</strong> {nome}</p>
                    <p><strong>E-mail: </strong>{email}&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>
                    <p><strong>Contato: </strong>{contact}</p>
                    <p>Obrigado,</p>
                    <p>{app_name}</p>',
                    'tr' => '<p><strong>Merhaba.</strong>,&nbsp;</p>
                    <p>Hoş Geldiniz: {app_name}.</p>
                    <p><strong>Name</strong> : {name}</p>
                    <p><strong>Eposta</strong> : {email}&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>
                    <p><strong>İlgili Kişi</strong> : {contact}</p>
                    <p><strong>Teşekkürler.</strong>,</p>
                    <p>{app_name}</p>',
                    'zh' => '<p><strong>你好啊</strong>,&nbsp;</p>
                    <p>欢迎来到 {app_name}.</p>
                    <p><strong>名称</strong> : {name}</p>
                    <p><strong>名称</strong> : {email}&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>
                    <p><strong>联系</strong> : {contact}</p>
                    <p><strong>谢谢</strong>,</p>
                    <p>{app_name}</p>',
                    'iw' => '<p><strong>הלו</strong>,&nbsp;</p>
                    <p>ברוכים הבאים ל - {app_name}.</p>
                    <p><strong>שם</strong> : {name}</p>
                    <p><strong>דוא " ל</strong> : {email}&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>
                    <p><strong>איש קשר</strong> : {contact}</p>
                    <p><strong>תודה</strong>,</p>
                    <p>{app_name}</p>',
                    'pt-br' => '<p><strong>Olá</strong>,&nbsp;</p>
                    <p>Bem-vindo ao {app_name}.</p>
                    <p><strong>Nome</strong> : {name}</p>
                    <p><strong>Email</strong> : {email}&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>
                    <p><strong>Contato</strong> : {contact}</p>
                    <p><strong>Obrigado</strong>,</p>
                    <p>{app_name}</p>',
                ],
            ],

            'New POs' => [
                'subject' => 'New POs',
                'variables' => '{
                    "App Url": "app_url",
                    "App Name": "app_name",
                    "Company Name": "company_name",
                    "Supplier": "supplier",
                    "Items": "items",
                    "Description": "pos_description",
                    "Quantity": "quantity",
                    "Price": "price",
                    "Purchase Order Date" :"purchase_order_date",
                    "Expected Delivery Date":"expected_delivery_date",
                    "URL": "url"
                  }',
                'lang' => [
                    'ar' => '<p>مرحبا ، مرحبا بك في { app_name }.</p>
                    <p>مورد : <strong>{ supplier }</strong></p>
                    <p>البنود : <strong>{ items }</strong></p>
                    <p><strong>الوصف</strong> : { pos_description }</p>
                    <p>الكمية : <strong>{ quantity }</strong></p>
                    <p>السعر : <strong>{ price }</strong></p>
                    <p><strong>تاريخ أمر التوريد</strong> : { purchase_order_date }</p>
                    <p><strong>تاريخ التسليم المتوقع</strong> : { expected_delivery_date }</p>
                    <p>{ app_url }</p>
                    <p>شكرا</p>
                    <p>{ app_name }</p>',
                    'da' => '<p><strong>Hej, </strong></p>
                    <p>velkommen til { app_name }.</p>
                    <p><strong>Leverand&oslash;r</strong>: { supplier }</p>
                    <p><strong>Elementer</strong>: { items }</p>
                    <p><strong>Beskrivelse</strong>: { pos_description }</p>
                    <p><strong>M&aelig;ngde</strong>: { quantity }</p>
                    <p><strong>Pris</strong>: { price }</p>
                    <p><strong>Indk&oslash;bsordredato</strong>: { purchase_order_date }</p>
                    <p><strong>Forventet leverdato</strong>: { expected_delivery_date }</p>
                    <p>{ app_url }</p>
                    <p>Tak.</p>
                    <p>{ app_name }</p>',
                    'de' => '<p><strong>Hallo, </strong></p>
                    <p>Willkommen bei {app_name}.</p>
                    <p><strong>Anbieter</strong>: {supplier}</p>
                    <p><strong>Elemente</strong>: {items}</p>
                    <p><strong>Beschreibung</strong>: {pos_description}</p>
                    <p><strong>Menge</strong>: {quantity}</p>
                    <p><strong>Preis</strong>: {price}</p>
                    <p><strong>Bestelldatum</strong>: {purchase_order_date}</p>
                    <p><strong>Erwartetes &Uuml;bergabedatum</strong>: {expected_delivery_date}</p>
                    <p>{app_url}</p>
                    <p>Danke,</p>
                    <p>{Anwendungsname}</p>',
                    'en' => '<p><strong>Hello</strong>,&nbsp;</p>
                    <p>Welcome to {app_name}.</p>
                    <p><strong>Supplier&nbsp;</strong>: {supplier}</p>
                    <p><strong>Items</strong>&nbsp;: {items}</p>
                    <p><strong>Description</strong>&nbsp;: {pos_description}</p>
                    <p><strong>Quantity&nbsp;</strong>: {quantity}</p>
                    <p><strong>Price&nbsp;</strong>: {price}</p>
                    <p><strong>Purchase Order date</strong>&nbsp;: {purchase_order_date}</p>
                    <p><strong>Expected Deliver date</strong>&nbsp;: {expected_delivery_date}</p>
                    <p>{app_url}</p>
                    <p>Thanks,<br />{app_name}</p>',
                    'es' => '<p><strong>Hola, </strong></p>
                    <p>Bienvenido a {app_name}.</p>
                    <p><strong>Proveedor</strong>: {supplier}</p>
                    <p><strong>Elementos</strong>: {items}</p>
                    <p><strong>Descripci&oacute;n</strong>: {pos_description}</p>
                    <p><strong>Cantidad</strong>: {quantity}</p>
                    <p><strong>Precio</strong>: {price}</p>
                    <p><strong>Fecha de pedido de compra</strong>: {purchase_order_date}</p>
                    <p><strong>Fecha de entrega esperada</strong>: {expected_delivery_date}</p>
                    <p>{app_url}</p>
                    <p>Gracias,</p>
                    <p>{app_name}</p>',
                    'fr' => '<p><strong>Bonjour</strong>,</p>
                    <p>Bienvenue dans { app_name }.</p>
                    <p><strong>Fournisseur</strong>: { supplier }</p>
                    <p><strong>El&eacute;ments</strong>: { items }</p>
                    <p><strong>Description</strong>: { pos_description }</p>
                    <p><strong>Quantit&eacute;</strong>: { quantity }</p>
                    <p><strong>Prix</strong>: { price }</p>
                    <p><strong>Date du bon de commande</strong>: { purchase_order_date }</p>
                    <p><strong>Date de livraison attendue</strong>: { expected_delivery_date }</p>
                    <p>{ adresse_url }</p>
                    <p>Merci,</p>
                    <p>{ nom_app }</p>',
                    'it' => '<p><strong>Ciao</strong>,</p>
                    <p>Benvenuti in {app_name}.</p>
                    <p><strong>Fornitore</strong>: {supplier}</p>
                    <p><strong>Articoli</strong>: {items}</p>
                    <p><strong>Descrizione</strong>: {pos_description}</p>
                    <p><strong>Quantit&agrave;</strong>: {quantity}</p>
                    <p><strong>Prezzo</strong>: {price}</p>
                    <p><strong>Data ordine di acquisto</strong>: {purchase_order_date}</p>
                    <p><strong>Data Prevista Consegna</strong>: {expected_delivery_date}</p>
                    <p>{app_url}</p>
                    <p>Grazie,</p>
                    <p>{app_name}</p>',
                    'ja' => '<p><strong>こんにちは</strong>、</p>
                    <p>{app_name}へようこそ。</p>
                    <p><strong>ベンダー</strong> : {supplier}</p>
                    <p><strong>アイテム</strong> : {items}</p>
                    <p><strong>説明</strong> : {pos_description}</p>
                    <p><strong>数量</strong> : {quantity}</p>
                    <p><strong>価格</strong> : {price}</p>
                    <p><strong>発注書の日付</strong> : {purchase_order_date}</p>
                    <p><strong>提出予定日</strong> : { expected_delivery_date }</p>
                    <p>{app_url}</p>
                    <p>ありがとう。</p>
                    <p>{app_name}</p>',
                    'nl' => '<p><strong>Hallo</strong>,</p>
                    <p>Welkom bij { app_name }.</p>
                    <p><strong>Leverancier</strong>: { supplier }</p>
                    <p><strong>Items</strong>: { items }</p>
                    <p><strong>Beschrijving</strong>: { pos_description }</p>
                    <p><strong>Hoeveelheid</strong>: { quantity }</p>
                    <p><strong>Prijs</strong>: { price }</p>
                    <p><strong>Inkooporderdatum</strong>: { purchase_order_date }</p>
                    <p><strong>Verwachte leverdatum</strong>: { expected_delivery_date }</p>
                    <p>{ app_url }</p>
                    <p>Bedankt.</p>
                    <p>{ app_name }</p>',
                    'pl' => '<p><strong>Witaj</strong>,</p>
                    <p>&nbsp;Witamy w aplikacji {app_name }.</p>
                    <p><strong>Dostawca</strong>: {supplier }</p>
                    <p><strong>Elementy</strong>: {items }</p>
                    <p><strong>Opis</strong>: {pos_description }</p>
                    <p><strong>Ilość</strong>: {quantity }</p>
                    <p><strong>Cena</strong>: {price }</p>
                    <p><strong>Data zam&oacute;wienia zakupu</strong>: {purchase_order_date }</p>
                    <p><strong>Oczekiwana data dostarczenia</strong>: {expected_delivery_date }</p>
                    <p>{app_url }</p>
                    <p>Dziękuję,</p>
                    <p>{app_name }</p>',
                    'ru' => '<p><strong>Привет</strong>.</p>
                    <p>Вас приветствует { app_name }.</p>
                    <p><strong>Поставщик</strong>: { supplier }</p>
                    <p><strong>Элементы</strong>: { items }</p>
                    <p><strong>Описание</strong>: { pos_description }</p>
                    <p><strong>Количество</strong>: { quantity }</p>
                    <p><strong>Цена</strong>: { price }</p>
                    <p><strong>Дата заказа на закупку </strong>: { purchase_order_date }</p>
                    <p><strong>Ожидаемая дата доставки </strong>: { expected_delivery_date }</p>
                    <p>{ app_url }</p>
                    <p>Спасибо.</p>
                    <p>{ имя_программы }</p>',
                    'pt' => '<p><strong>Ol&aacute;</strong>,</p>
                    <p>&nbsp;Bem-vindo a {app_name}.</p>
                    <p><strong>Fornecedor</strong>: {supplier}</p>
                    <p><strong>Itens</strong>: {itens}</p>
                    <p><strong>Descri&ccedil;&atilde;o</strong>: {pos_description}</p>
                    <p><strong>Quantidade</strong>: {quantity}</p>
                    <p><strong>Pre&ccedil;o</strong>: {price}</p>
                    <p><strong>Data da Ordem de Compra</strong>: {comprase_order_date}</p>
                    <p><strong>Expectativa Entregar data </strong>: {expected_delivery_date}</p>
                    <p>{app_url}</p>
                    <p>Obrigado,</p>
                    <p>{app_name}</p>',
                    'tr' => '<p><strong>Merhaba.</strong>,&nbsp;</p>
                    <p>Hoş Geldiniz: {app_name}.</p>
                    <p><strong>Satıcı&nbsp;</strong>: {supplier}</p>
                    <p><strong>Öğeler</strong>&nbsp;: {items}</p>
                    <p><strong>Açıklama</strong>&nbsp;: {pos_description}</p>
                    <p><strong>Miktar&nbsp;</strong>: {quantity}</p>
                    <p><strong>Fiyat&nbsp;</strong>: {price}</p>
                    <p><strong>Satınalma Siparişi tarihi</strong>&nbsp;: {purchase_order_date}</p>
                    <p><strong>Beklenen Teslim tarihi</strong>&nbsp;: {expected_delivery_date}</p>
                    <p>{app_url}</p>
                    <p>Teşekkürler.,<br />{app_name}</p>',
                    'zh' => '<p><strong>你好啊</strong>,&nbsp;</p>
                    <p>欢迎来到 {app_name}.</p>
                    <p><strong>供应商&nbsp;</strong>: {supplier}</p>
                    <p><strong>项目</strong>&nbsp;: {items}</p>
                    <p><strong>描述</strong>&nbsp;: {pos_description}</p>
                    <p><strong>数量&nbsp;</strong>: {quantity}</p>
                    <p><strong>价格&nbsp;</strong>: {price}</p>
                    <p><strong>采购订单日期</strong>&nbsp;: {purchase_order_date}</p>
                    <p><strong>预期交付日期</strong>&nbsp;: {expected_delivery_date}</p>
                    <p>{app_url}</p>
                    <p>谢谢,<br />{app_name}</p>',
                    'iw' => '<p><strong>הלו</strong>,&nbsp;</p>
                    <p>ברוכים הבאים ל {app_name}.</p>
                    <p><strong>משווק&nbsp;</strong>: {supplier}</p>
                    <p><strong>פריטים</strong>&nbsp;: {items}</p>
                    <p><strong>תיאור</strong>&nbsp;: {pos_description}</p>
                    <p><strong>כמות&nbsp;</strong>: {quantity}</p>
                    <p><strong>מחיר&nbsp;</strong>: {price}</p>
                    <p><strong>תאריך הזמנת רכש</strong>&nbsp;: {purchase_order_date}</p>
                    <p><strong>תאריך Deלכבד צפוי</strong>&nbsp;: {expected_delivery_date}</p>
                    <p>{app_url}</p>
                    <p>תודה,<br />{app_name}</p>',
                    'pt-br' => '<p><strong>Olá</strong>,&nbsp;</p>
                    <p>Bem-vindo ao {app_name}.</p>
                    <p><strong>Fornecedor&nbsp;</strong>: {vendsupplieror}</p>
                    <p><strong>Itens</strong>&nbsp;: {items}</p>
                    <p><strong>Descrição</strong>&nbsp;: {pos_description}</p>
                    <p><strong>Quantidade&nbsp;</strong>: {quantity}</p>
                    <p><strong>Preço&nbsp;</strong>: {price}</p>
                    <p><strong>Data da ordem de compra</strong>&nbsp;: {purchase_order_date}</p>
                    <p><strong>Data prevista de entrega</strong>&nbsp;: {expected_delivery_date}</p>
                    <p>{app_url}</p>
                    <p>Obrigado,<br />{app_name}</p>',
                ],
            ],
        ];

        foreach($emailTemplate as $eTemp)
        {
            $table = EmailTemplate::where('name',$eTemp)->where('module_name','CMMS')->exists();
            if(!$table)
            {
                $emailtemplate=  EmailTemplate::create(
                    [
                        'name' => $eTemp,
                        'from' => 'CMMS',
                        'module_name' => 'CMMS',
                        'created_by' => 1,
                        'workspace_id' => 0
                        ]
                    );
                    foreach($defaultTemplate[$eTemp]['lang'] as $lang => $content)
                    {
                        EmailTemplateLang::create(
                            [
                                'parent_id' => $emailtemplate->id,
                                'lang' => $lang,
                                'subject' => $defaultTemplate[$eTemp]['subject'],
                                'variables' => $defaultTemplate[$eTemp]['variables'],
                                'content' => $content,
                            ]
                        );
                    }
            }
        }
    }
}
