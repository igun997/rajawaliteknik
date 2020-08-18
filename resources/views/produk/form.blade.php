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
                            <label>Nama Produk</label>
                            <input type="text" class="form-control" name="name" value="{{@$data->name}}" required>
                        </div>
                        <div class="form-group">
                            <label>Ukuran</label>
                            <select name="size_id" class="form-control" required>
                            @foreach($sizes as $size)
                                @if(isset($data->size_id))
                                        @if($data->size_id == $size->id)
                                            <option value="{{$size->id}}" selected>{{$size->name}}</option>
                                        @else
                                            <option value="{{$size->id}}">{{$size->name}}</option>
                                        @endif
                                @else
                                    <option value="{{$size->id}}">{{$size->name}}</option>
                                @endif
                            @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Harga</label>
                            <input type="number" class="form-control" name="price" value="{{@$data->price}}" min="0" required>
                        </div>
                        <div class="form-group">
                            <label>Stok</label>
                            <input type="number" class="form-control" name="stock" value="{{(isset($data->stock)?$data->stock:0)}}" min="0" >
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
@stop

