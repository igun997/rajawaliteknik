@extends('adminlte::page')

@section('title', $title)

@section('content_header')

@stop

@section('content')
    <div class="row">

        <div class="col-8">
            <div class="card" style="max-height: 30em">
                <div class="card-header">
                    <div class="card-title">Data Produk <button class="btn btn-primary m-2" id="reload_penjualan" title="Refresh Data Produk"><li class="fa fa-spinner"></li></button></div>
                </div>
                <div class="card-body">

                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Form Penjualan <button class="btn btn-primary m-2" id="reload_penjualan" title="Refresh Form Penjualan"><li class="fa fa-spinner"></li></button></div>
                </div>
                <div class="card-body">
                    <form action="">
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
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Jenis Pembayaran</label>
                            <select name="additional_info[]" class="form-control">
                                <option value="Cash">Cash</option>
                                <option value="Giro">Giro</option>
                                <option value="Cashbon">Cashbon</option>
                            </select>
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
                        "<td>"+d.quantity+"</td>",
                        "<td><button class='btn btn-danger delete' type='button' data-link='{{route("pos.api.cart.delete")}}?product_id="+i+"'><li class='fa fa-trash'></li></button> </td>",
                        "</tr>",
                    ]
                    items.push(item.join(""));
                });
                $("#itemList").html(items.join(""));
                $("#priceTotal").html("Rp. "+res.total);
                $("#itemList").on("click",".delete",function () {
                    let path = $(this).data("link");
                    $.get(path,function (res) {
                        list_cart();
                    }).fail(function () {
                        toastr.error("Koneksi Terputus");
                    })
                })
            }).fail(function () {
                toastr.error("Koneksi Anda Terputus !!");
            });

        }
    </script>
@stop

