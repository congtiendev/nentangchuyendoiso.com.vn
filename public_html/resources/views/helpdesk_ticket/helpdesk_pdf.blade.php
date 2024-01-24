<style media="all">
            body{
		line-height: 1.5;
		font-family: 'DejaVuSans', 'sans-serif';
		color: #333542;
	}
        .container{
            margin: 0 auto;
        }
        .text-center{
            text-align: center;
        }
        .mt-5{
            margin-top: 5rem;
        }
        .mt-2{
            margin-top: 2rem;
        }   
        .fw-bold{
            font-weight: bold;
        }
        .row{
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }
        .col-md-6{
            flex: 0 0 50%;
            max-width: 50%;
            position: relative;
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
        }   
        .col-12{
            flex: 0 0 100%;
            max-width: 100%;
            position: relative;
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
        }
        .mt-2{
            margin-top: 2rem;
        }
        .gap-2{
            gap: 2rem;
        }
        .table-bordered{
            border: 1px solid #dee2e6;
        }
        .table{
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }
        .table-bordered thead th, .table-bordered thead td{
            border-bottom-width: 2px;
        }
        .table-bordered thead th, .table-bordered thead td{
            border-bottom-width: 2px;
        }
        .table-bordered td, .table-bordered th{
            border: 1px solid #dee2e6;
        }
        .mt-5{
            margin-top: 5rem;
        }
        .mt-3{
            margin-top: 3rem;
        }
        h5{
            margin-block-start: 0.5em;
            margin-block-end: 0.5em;
            font-weight: bold;
        }
        </style>
<body>
    <div class="container" style="width: 700px;">
        <div class="text-center mt-5">
            <h1>Văn Bản</h1>
        </div>
        <div class="row mt-5">
            <h5 class="fw-bold" >Mã: {{' '.$ticket->ticket_id}}</h5>
        </div>
        <div class="row mt-2">
            <div class="col-md-6">
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
            <div class="col-md-6">
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
