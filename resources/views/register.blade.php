@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>Register Page</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6 offset-3">

            <div class="card card-default">
                @include("message")
                <form role="form" action="{{route("register")}}" method="post">
                    <div class="card-body">
                        @csrf
                        <div class="form-group">
                            <label >Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama" placeholder="Nama Lengkap">
                        </div>
                        <div class="form-group">
                            <label >Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label >No HP</label>
                            <input type="text" class="form-control" name="no_hp" placeholder="No HP">
                        </div>
                        <div class="form-group">
                            <label >Username</label>
                            <input type="text" class="form-control" name="username" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <label >Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <label >Alamat</label>
                            <textarea name="alamat" class="form-control" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@stop

@section('css')

@stop

@section('js')

@stop
