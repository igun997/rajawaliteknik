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
                            <label>Status</label>
                            <select id="status" class="form-control">
                                @foreach($status as $row)
                                    <option value="{{$row["value"]}}">{{$row["name"]}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tipe Transaksi</label>
                            <select id="type" class="form-control">
                                @foreach($type as $row)
                                    <option value="{{$row["value"]}}">{{$row["name"]}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Sumber Data</label>
                            <select id="ref" class="form-control">
                                @foreach($ref as $row)
                                    <option value="{{$row["value"]}}">{{$row["name"]}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Jenis Laporan</label>
                            <select id="jenis" class="form-control">
                                <option value="{{route("laporan.keuangan.pdf")}}">PDF</option>
                                <option value="{{route("laporan.keuangan.excel")}}">Excel</option>
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
            ref = $("#ref").val();
            status = $("#status").val();
            type = $("#type").val();
            location.href = path+"?from="+$("input[name=from]").val()+"&to="+$("input[name=to]").val()+"&ref="+ref+"&status="+status+"&type="+type;
        });
    </script>
@stop

