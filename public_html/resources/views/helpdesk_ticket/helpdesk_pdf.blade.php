<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/fonts/material.css')}}">
    <title>Văn bản</title>
</head>

<body>
    <div class="container" style="width: 700px;">
        <div class="text-center mt-5">
            <h1>Văn Bản</h1>
        </div>
        <div class="row mt-5">
            <h5 class="fw-bold">Mã: {{' '.$ticket->ticket_id}}</h5>
        </div>
        <div class="row mt-2">
            <div class="col-6">
                <div class="row">
                    <div class="col-12">
                        <span class="fw-bold mt-2">Thông tin người gửi</span>
                    </div>
                    <div class="col-12 mt-2">
                        <span>Người gửi:  
                            @php
                                $user = DB::table('users')->where('id', $ticket->user_id)->first();
                                if($user){
                                    echo $user->name;
                                }
                            @endphp
                        </span>
                    </div>
                    <div class="col-12 mt-2">
                        <span>Chủ thể: {{ $ticket->subject }}</span>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="col-12">
                    <span class="fw-bold mt-2">Thông tin người nhận</span>
                </div>
                <div class="col-12 mt-2">
                    <span>Người nhận: {{' '.$ticket->name}}</span>
                </div>
                <div class="col-12 mt-2">
                    <span>Email: {{' '.$ticket->email}}</span>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12 d-flex gap-2">
                <h5 class="fw-bold">Loại văn bản: </h5><span style="font-size: 18px;">
                    @php
                        $type = DB::table('helpdesk_ticket_categories')->where('id', $ticket->category)->first();
                        if($type){
                            echo $type->name;
                        }
                    @endphp
                </span>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Mô tả</th>
                    <th scope="col">Ngày</th>
                </tr>
            </thead>
            <tbody>
               @foreach($data as $row)
                <tr>
                    <td>{!!  $row->description !!}</td>
                    <td>{{ date('d-m-Y', strtotime($row->created_at)) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
</body>

</html>