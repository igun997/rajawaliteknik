@extends('adminlte::page')

@section('title', $title)

@section('content_header')

@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{$title}}</div>
                </div>
                <div class="card-body">
                    <a href="{{route("pos.menus")}}" class="btn btn-success mb-2">Tambah Penjualan</a>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>No Faktur</th>
                                <th>Nama Pelanggan</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Diskon</th>
                                <th>Keterangan</th>
                                <th>Dibuat</th>
                                <th>Diubah</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $key => $row)
                                <tr>
                                    <td>{{($key+1)}}</td>
                                    <td>{{$row->invoice_number}}</td>
                                    <td>{{$row->customer->name}}</td>
                                    <td>{{$row->status_lang}}</td>
                                    <td>Rp.{{number_format($row->total)}}</td>
                                    <td>Rp.{{number_format(round($row->discount))}}</td>
                                    <td>{{$row->additional_info}}</td>
                                    <td>{{date("d-m-Y",strtotime($row->created_at))}}</td>
                                    <td>{{($row->updated_at)?date("d-m-Y",strtotime($row->updated_at)):null}}</td>
                                    <td>
                                        <button type="button" data-id="{{$row->id}}" class="btn btn-primary mb-2 detail">
                                            <li class="fa fa-eye"></li>
                                        </button>
                                        <button type="button" data-id="{{$row->id}}"  class="btn btn-primary mb-2 print">
                                            <li class="fa fa-print"></li>
                                        </button>
                                        @if($row->status === \App\Casts\StatusOrder::CASHBON)
                                        <button type="button" data-id="{{$row->id}}" class="btn btn-warning mb-2 cashbon">
                                            <li class="fa fa-money-bill-wave"></li>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')

@stop

@section("js")
    @include("msg")
    <script>
        $("table").DataTable();
        function formatNumber(num) {
            if(parseFloat(num) > 0){
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            }
            return 0;
        }
        $("table").on("click",".print",function () {
            id = $(this).data("id");
            console.log("ID "+id);
            var dialog = bootbox.dialog({
                title: 'Cetak',
                message: '<p align="center"><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
            });
            dialog.init(function() {
                setTimeout(function () {
                    let body = [
                        "<div class='row'>",
                        "<div class='col-md-6'>",
                        "<a href='{{route("orders.print.faktur")}}?order_id="+id+"' class='btn btn-primary btn-lg btn-block'>Nota Faktur</a>",
                        "</div>",
                        "<div class='col-md-6'>",
                        "<a href='{{route("orders.print.shipping")}}?order_id="+id+"' class='btn btn-primary btn-lg btn-block'>Surat Jalan</a>",
                        "</div>",
                        "</div>",
                    ];
                    dialog.find(".bootbox-body").html(body.join(""));
                }, 100);
            });

        })
        $("table").on("click",".cashbon",function () {
            id = $(this).data("id");
            var dialog = bootbox.dialog({
                title: 'Pembayaran Cicilan',
                message: '<p align="center"><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
            });
            let cash = function(d){
                let f = [
                    "<div class='row'>",
                    "<div class='col-12'>",
                    "<form onsubmit='return false' method='post'>",
                    "<div class='form-group'>",
                    "<label>Total Terbayar</label>",
                    "<input class='form-control' disabled value='Rp. "+formatNumber(d.paided_total)+"'>",
                    "</div>",
                    "<div class='form-group'>",
                    "<label>Total Harus Di Bayar</label>",
                    "<input class='form-control'  disabled value='Rp. "+formatNumber((d.total - d.paided_total))+"'>",
                    "</div>",
                    "<div class='form-group'>",
                    "<label>Dibayar</label>",
                    "<input class='form-control'  type='number' required name='total' min='0' max='"+(d.total - d.paided_total)+"' value=''>",
                    "</div>",
                    "<div class='form-group'>",
                    "<button class='btn btn-success' type='submit'>Bayar</button>",
                    "</div>",
                    "</form>",
                    "</div>",
                    "</div>",
                ]
                return f.join("");
            };
            $.get("{{route("orders.api.cashbon")}}?order_id="+id,function (d) {
                dialog.init(function(){
                    setTimeout(function(){
                        dialog.find(".bootbox-body").html(cash(d.data));
                        dialog.find("form").on("submit",function () {
                            let data = $(this).serializeArray();
                            console.log(data);
                            data[data.length] = {name:"order_id",value:id};
                            sisa = (d.data.total - d.data.paided_total);
                            sisa = (sisa-parseFloat(data[0].value));
                            console.log(sisa)
                            if(sisa === 0){
                                data[data.length] = {name:"paided",value:1};
                            }
                            $.post("{{route("orders.api.cashbon.create")}}",data,function (res) {
                                toastr.success(res.msg);
                                setTimeout(function () {
                                    bootbox.hideAll();
                                    if(res.reload){
                                        location.reload();
                                    }
                                },500)
                            })
                        })
                    }, 500);
                });
            })

        })

        $("table").on("click",".detail",function () {
            id = $(this).data("id");
            let item = function (data,is_product=true) {
                if(is_product){
                    let product = [
                        '<div class="row">',
                        '<div class="col-8">',
                        '<h5 class="product-name"><strong>'+data.product.name+' / '+data.size_name+'</strong></h5>',
                        '</div>',
                        '<div class="col-4">',
                        '<div class="row">',
                        '<div class="col-12 text-right">',
                        '<h6><strong>'+formatNumber(data.qty)+' <span class="text-muted">x</span> '+formatNumber(data.price)+'</strong></h6>',
                        '</div>',
                        '<div class="col-12 text-right text-green">',
                        '<h6><strong>Rp. '+formatNumber(data.subtotal)+'</strong></h6>',
                        '</div>',
                        '</div>',
                        '</div>',
                        '</div>',
                        '</div>',
                    ];
                    return product.join("");
                }else{
                    button = "-";
                    button_cancel = "<button class='btn btn-danger status m-2' data-status='7' data-id='"+data.id+"' type='button'>Batalkan</button>"
                    if(data.status == 1){
                        button = "<button class='btn btn-success status m-2' type='button' data-status='2' data-id='"+data.id+"'>Diproses</button>"
                    }else if(data.status == 2){
                        button = "<button class='btn btn-success status m-2' type='button' data-status='3' data-id='"+data.id+"'>Dikirim</button>"
                    }else if(data.status == 3){
                        button = "<button class='btn btn-success status m-2' type='button' data-status='4' data-id='"+data.id+"'>Sukses</button>"
                    }else if(data.status == 4){
                        button = "<button class='btn btn-success status m-2' type='button' data-status='5' data-id='"+data.id+"'>Return</button>"
                    }else if(data.status == 7){
                        button_cancel = " ";
                    }
                    let product = [
                        '<div class="row">',
                        '<div class="col-12">',
                        '<div class="table-responsive">',
                        '<table class="table table-bordered">',
                        '<tr>',
                        '<th>Total Bayar</th>',
                        '<td>Rp. '+formatNumber(data.total)+'</td>',
                        '</tr>',
                        '<tr>',
                        '<th>Diskon</th>',
                        '<td>Rp. '+formatNumber(data.discount)+'</td>',
                        '</tr>',
                        '<tr>',
                        '<th>Status</th>',
                        '<td>'+data.status_lang+'</td>',
                        '</tr>',
                        '</tr>',
                        '<tr style="'+((data.cashbon_status)?'':'display: none')+'">',
                        '<th>Cashbon Progress</th>',
                        '<td>Rp. '+formatNumber(data.cashbon_progress)+' / Rp. '+formatNumber(data.total)+'</td>',
                        '</tr>',
                        '<tr>',
                        '<th>Aksi</th>',
                        '<td>'+button+button_cancel+'</td>',
                        '</tr>',
                        '</table>',
                        '</div>',
                        '</div>',
                        '</div>',
                    ];
                    return product.join("");
                }
            }
            var dialog = bootbox.dialog({
                title: 'Detail Order',
                message: '<p align="center"><i class="fa fa-spin fa-spinner"></i> Loading...</p>'
            });
            $.get("{{route("orders.api.product")}}?order_id="+id,function (res) {
                dialog.init(function(){
                    setTimeout(function(){
                        let items = [];
                        $.each(res.data.items,function (i,d) {
                            items.push(item(d,true));
                        })
                        items.push(item(res.data,false));
                        dialog.find('.bootbox-body').html(items.join("<hr>"));
                        dialog.find(".status").on("click",function () {
                            let id = $(this).data("id")
                            let status = $(this).data("status")

                            $.get("{{route("orders.update_status")}}?order_id="+id+"&status="+status,function (r) {
                                toastr.success(r.msg);
                                setTimeout(function () {
                                    location.reload();
                                },500);
                            })
                        })
                    }, 500);
                });
            })

        })

    </script>
@stop

