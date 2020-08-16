@extends('adminlte::page')

@section('title', $title)

@section('content_header')

@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Data Akun</div>
                </div>
                <div class="card-body">
                    <a href="{{route("akun.add")}}" class="btn btn-success mb-2">Tambah Akun</a>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th>Level</th>
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
                                    <td>{{$row->email}}</td>
                                    <td>{{$row->username}}</td>
                                    <td>{{$row->level_lang}}</td>
                                    <td>{{$row->status_lang}}</td>
                                    <td>{{date("d-m-Y",strtotime($row->created_at))}}</td>
                                    <td>{{($row->updated_at)?date("d-m-Y",strtotime($row->updated_at)):null}}</td>
                                    <td>
                                        <a href="{{route("akun.update",$row->id)}}" class="btn btn-warning mb-2">
                                            <li class="fa fa-edit"></li>
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

