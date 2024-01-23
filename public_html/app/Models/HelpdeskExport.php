<?php
namespace App\Models;

    use Maatwebsite\Excel\Concerns\FromCollection;
    use Maatwebsite\Excel\Concerns\FromQuery;
    use Maatwebsite\Excel\Concerns\WithMapping;
    use Maatwebsite\Excel\Concerns\WithHeadings;
    use Illuminate\Support\Str;
    use Illuminate\Support\HtmlString;


class HelpdeskExport implements FromCollection,WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Lấy  dữ liệu từ session export_tickets xuống
        $data = session()->get('export_tickets');
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
