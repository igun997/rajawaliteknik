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
                    <form action="{{$route}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="name" value="{{@$data->name}}" required>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea type="text" class="form-control" name="address" required>{{@$data->address}}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Diskon</label>
                            <input type="number" class="form-control" name="percentage_discount" value="{{@$data->discount}}" min="0"  max="100"  placeholder="Kosongkan Jika Tidak Punya Diskon">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success block">Simpan</button>
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

        let val = parseFloat($("input[name=discount]").val());
        if(val > 0){

        }else{
            $("input[name=discount]").val("0");
        }
    </script>
@stop

