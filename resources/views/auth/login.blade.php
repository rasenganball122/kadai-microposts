@extends("layouts.app")

@section("content")
    <div class="text-center">
        <hi>Log in</hi>
    </div>
    
    <div class="row">
        <div class="col-sm-6 offset-sm-3">
            {!! Form::open(["route"=>"login.post"]) !!}
                <div class="form-group">
                    {!! Form::label("email", "Email") !!}
                    {!! Form::text("email", null, ["class"=>"form-control"]) !!}
                </div>
                
                <div class="form-group">
                    {!! Form::label("password", "Password") !!}
                    {!! Form::password("password", null, ["class"=>"form-control"]) !!}
                </div>
            
                {!! Form::submit("Log in", ["class"=>"btn btn-primary btn-block"]) !!}
            {!! Form::close() !!}
            
            {{--ユーザー登録ページへのリンク--}}
            <p class="mt-2">New user? {!! link_to_route("signup.get", "Sign up now!") !!}</p>
        </div> 
    </div>
@endsection

