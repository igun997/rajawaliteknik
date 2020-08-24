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
                            <select name="product_id" class="form-control">
                                @foreach(\App\Models\Product::all() as $row)
                                    @if(isset($data->product_id))
                                        <option value="{{$row->id}}" {{((($data->product_id == $row->id)?"selected":""))}}>{{$row->name}}</option>
                                    @else
                                        <option value="{{$row->id}}">{{$row->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Diskon (%)</label>
                            <input type="number" min="1" max="100" class="form-control" value="{{@$data->percentage_discount}}" name="percentage_discount">
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

    </script>
@stop

