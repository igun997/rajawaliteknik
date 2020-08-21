@extends('adminlte::page')

@section('title', $title)

@section('content_header')

@stop

@section('content')
    <div class="row">
        <div class="col-md-6 offset-3">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{$title}}</div>
                </div>
                <div class="card-body">
                    <form action="" onsubmit="return false" method="get">
                        @csrf
                        <div class="form-group">
                            <label>Dari Tanggal</label>
                            <input type="text" class="form-control date" autocomplete="off" name="from" required>
                        </div>
                        <div class="form-group">
                            <label>Sampai Tanggal</label>
                            <input type="text" class="form-control date"  autocomplete="off" name="to" required>
                        </div>
                        <div class="form-group">
                            <label>Jenis Laporan</label>
                            <select id="jenis" class="form-control">
                                <option value="{{route("laporan.penjualan.pdf")}}">PDF</option>
                                <option value="{{route("laporan.penjualan.excel")}}">Excel</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status Laporan</label>
                            <select id="status" class="form-control">
                                @foreach($data as $row)
                                    <option value="{{$row["value"]}}">{{$row["name"]}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success block">Hasilkan</button>
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
        $(".date").datepicker({
            format:"yyyy-mm-dd"
        });
        $("form").on("submit",function () {
            path = $("#jenis").val();
            status = $("#status").val();
            location.href = path+"?from="+$("input[name=from]").val()+"&status="+status+"&to="+$("input[name=to]").val();
        });
    </script>
@stop

