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
                    <a href="{{route("pelanggan.add")}}" class="btn btn-success mb-2">Tambah Pelanggan</a>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Diskon</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                                <th>Diubah</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $key => $row)
                                <tr>
                                    <td>{{($key+1)}}</td>
                                    <td>{{$row->name}}</td>
                                    <td>{{$row->address}}</td>
                                    <td align="center">{!! ($row->has_discount)?"<span class='badge badge-success'>".$row->percentage_discount." %</span>":"<span class='badge badge-danger'>Tidak Ada</span>" !!}</td>
                                    <td>{{$row->status_lang}}</td>
                                    <td>{{date("d-m-Y",strtotime($row->created_at))}}</td>
                                    <td>{{($row->updated_at)?date("d-m-Y",strtotime($row->updated_at)):null}}</td>
                                    <td>
                                        <a href="{{route("pelanggan.update",$row->id)}}" class="btn btn-warning mb-2">
                                            <li class="fa fa-edit"></li>
                                        </a>
                                        <a href="{{route("pelanggan.discount.list",["id"=>$row->id])}}" class="btn btn-primary mb-2">
                                            <li class="fa fa-plus"></li>
                                        </a>
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

    </script>
@stop

