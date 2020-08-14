<div class="row">
    <div class="col-12">
            @if(count($errors->all()) > 0)
            <div class="alert alert-danger">
                @foreach($errors->all() as $item)
                    <p>{{$item}}</p>
                @endforeach
            </div>
            @endif

            @if(session()->get("msg") !== null)
                <div class="alert alert-success">
                        <p>{{session()->get("msg")}}</p>
                </div>
            @endif
    </div>
</div>
