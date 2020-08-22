@extends('adminlte::page')

@section('title', $title)

@section('content_header')

@stop

@section('content')
    <div class="row">

        <div class="col-6">
            <div class="card" style="max-height: 30em;overflow-y: scroll">
                <div class="card-header">
                    <div class="card-title">Data Produk <button class="btn btn-primary m-2" id="reload_produk" title="Refresh Data Produk"><li class="fa fa-spinner"></li></button></div>
                </div>
                <div class="card-body">
                    <div class="row" id="listProduk">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Form Penjualan <button class="btn btn-primary m-2" id="reload_penjualan" title="Refresh Form Penjualan"><li class="fa fa-spinner"></li></button></div>
                </div>
                <div class="card-body">
                    <form action="" method="post" onsubmit="return false">
                        <div class="form-group">
                            <label>Nama Pelanggan</label>
                            <select name="customer_id" class="form-control">
                                <option value="">-</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Data Produk</label>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Produk</th>
                                            <th>Harga</th>
                                            <th>Diskon</th>
                                            <th>Jumlah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemList">

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3">Total</th>
                                            <th colspan="3" id="priceTotal">Rp. 0</th>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Diskon</th>
                                            <th colspan="4" id="diskon">-</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Jenis Pembayaran</label>
                            <select name="additional_info[]" class="form-control">
                                <option value="Cash">Cash</option>
                                <option value="Giro">Giro</option>
                                <option value="Cashbon">Credit</option>
                            </select>
                        </div>
                        </div>
                        <div class="form-group" style="text-align: center">
                            <button class="btn btn-success" type="submit">Simpan & Cetak Faktur</button>
                        </div>
                    </form>
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
        list_cart();
        list_pelanggan();
        list_produk();
        $("#reload_penjualan").on("click",function () {
            console.log("Reload");
            $.get("{{route("pos.api.cart.clear")}}",function () {
                list_cart();
                list_pelanggan();
                $("form")[0].reset();
                toastr.info("Reload Form Penjualan ...");
            }).fail(function () {
                toastr.error("Form Gagal Di Reload")
            })

        })
        $("#reload_produk").on("click",function () {
            console.log("Reload");
            toastr.info("Reload Produk ...");
            list_produk();
        })
        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }
        function list_cart() {
            $("#itemList").html("");
            $.get("{{route("pos.api.cart.list")}}",function (res) {
                let num = 1;
                let items = [];
                $.each(res.data,function (i,d) {
                    console.log(i)
                    let item = [
                        "<tr>",
                        "<td>"+(num++)+"</td>",
                        "<td>"+d.name+"</td>",
                        "<td>"+formatNumber(d.price)+"</td>",
                            "<td>"+((d.conditions.parsedRawValue)?formatNumber((d.conditions.parsedRawValue*d.quantity)):"-")+"</td>",
                        "<td>"+d.quantity+"</td>",
                        "<td><button class='btn btn-danger delete' type='button' data-link='{{route("pos.api.cart.delete")}}?product_id="+i+"'><li class='fa fa-trash'></li></button> </td>",
                        "</tr>",
                    ]
                    items.push(item.join(""));
                });
                $("#itemList").html(items.join(""));
                $("#priceTotal").html("Rp. "+res.total);
                $("#diskon").html("");
                $.each(res.discount,function (i,d) {
                    $("#diskon").append("<p>"+d+"</p>");
                })
                $("#itemList").on("click",".delete",function (e) {
                    e.preventDefault();
                    $("#itemList").off("click");
                    let path = $(this).data("link");
                    $.post(path,function (res) {
                        list_cart();
                    }).fail(function () {
                        toastr.error("Koneksi Terputus");
                    })
                })
            }).fail(function () {
                toastr.error("Koneksi Anda Terputus !!");
            });

        }
        function list_pelanggan() {
            let instance = $("select[name=customer_id]");
            instance.html("");
            $.get("{{route("pos.api.pelanggan")}}",function (data) {
                let items = [];
                $.each(data.data,function(k,d){
                    items.push("<option value='"+d.id+"'>"+d.name+"</option>");
                });
                instance.html(items.join(""));
            })
        }
        function list_produk() {
            $("#listProduk").html("");
            const produk = function (data) {
                let build = [
                    '<div class="col-12 col-sm-8 col-md-6 col-lg-6">',
                    '<div class="card">',
                    '<div class="card-img-overlay d-flex justify-content-end">',
                    '<a type="button" data-id="'+data.id+'"  class="card-link text-success add_to_cart">',
                    '<i class="fas fa-shopping-cart"></i>',
                    '</a>',
                    '</div>',
                    '<div class="card-body">',
                    '<h4 class="card-title">'+data.name+'</h4>',
                    '<div class="card-text ">',
                    '<table class="table-bordered table mt-5 ">',
                    '<tr>',
                    '<th>Harga</th>',
                    '<th>Stock</th>',
                    '<th>Ukuran</th>',
                    '</tr>',
                    '<tr>',
                    '<td class="text-green">'+formatNumber(data.price)+'</td>',
                    '<td>'+((data.stock)?data.stock:'<p class="text-danger">Stock Habis<p>')+'</td>',
                    '<td>'+data.size_data.name+'</td>',
                    '</tr>',
                    '</table>',
                    '</div>',
                    '</div>',
                    '</div>',
                    '</div>',
                ];
                return build.join("");
            };
            $.get("{{route("pos.api.produk")}}",function (res) {
                let items = [];
                $.each(res.data,function (i,d) {
                    items.push(produk(d));
                })
                $("#listProduk").html(items.join(""));
                $("#listProduk").on("click",".add_to_cart",function (e) {
                    e.preventDefault();
                    id = $(this).data("id");
                    cust_id = $("select[name=customer_id]").val();
                    qty = prompt("Jumlah Beli");
                    qty = parseFloat(qty);
                    if(qty > 0){
                        $.post("{{route("pos.api.cart.add")}}",{product_id:id,qty:qty,customer_id:cust_id},function (res) {
                            if(res.code === 200){
                                toastr.success(res.msg);
                                location.reload();
                            }else{
                                toastr.warning(res.msg);
                            }
                        }).fail(function () {
                            toastr.error("Terputus Dari Server");
                        })
                    }

                })
            })
        }
        $("form").on("submit",function () {
            let data = $(this).serializeArray();
            console.log(data)
            $.post("{{route("pos.api.cart.checkout")}}",data,function (r) {
                toastr.success(r.msg);
                setTimeout(function () {
                    if(r.url !== ""){
                        list_cart();
                        location.href = r.url;
                    }else{
                        location.reload();
                    }
                },500)

            }).fail(function () {
                toastr.error("Terputus Dengan Server");
            })
        })
    </script>
@stop

