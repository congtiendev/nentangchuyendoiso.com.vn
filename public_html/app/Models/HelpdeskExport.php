<?php
namespace App\Models;

    use Maatwebsite\Excel\Concerns\FromCollection;
    use Maatwebsite\Excel\Concerns\FromQuery;
    use Maatwebsite\Excel\Concerns\WithMapping;
    use Maatwebsite\Excel\Concerns\WithHeadings;
    use Illuminate\Support\Str;
    use Illuminate\Support\HtmlString;
    use App\Models\HelpdeskTicket;


class HelpdeskExport implements FromCollection,WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Lấy  dữ liệu từ session export_data xuống
        $data = HelpdeskTicket::select(
            [
                'helpdesk_tickets.*',
                'helpdesk_ticket_categories.name as category_name',
                'helpdesk_ticket_categories.color',
            ]
        )->join('helpdesk_ticket_categories', 'helpdesk_ticket_categories.id', '=', 'helpdesk_tickets.category');

        if (auth()->user()->type == 'super admin') {
            $data = $data->orderBy('id', 'desc')->get();
        } else {
            $data = $data->where('workspace', getActiveWorkSpace())->orderBy('id', 'desc')->get();
        }

        return $data;
    }
    /**
     * Returns headers for report
     * @return array
     */
    public function headings(): array {
        return [
            'Mã',
            'Được giao đến',
            'Email',    
            "Được tạo bởi",
            "Mô tả",
            "Chủ thể",
            "Loại văn bản",
            "Trạng thái",
            "Ngày tạo"
        ];
    }
 
    public function map($data): array {
        return [
            $data->ticket_id,
            $data->name,
            $data->email,
            $data->createdBy->name,
            strip_tags(new HtmlString($data->description)),
            $data->subject,
            $data->category_name,
            __($data->status),
            date('d-m-Y', strtotime($data->created_at))
        ];
    }
}
