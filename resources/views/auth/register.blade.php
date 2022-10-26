@layout("layouts.app")

@section
    <div class="text-center">
        <h1>Sign up</h1>
    </div>
    
    <div class="row">
        <div class="col-sm-6 offset-sm-3">
            {!! From::open(["route"=>"Signup.post"]) !!}
                <div class="form-group">
                    {!! Form::label("name", "Name") !!}
                    {!! From::text("name", null, ["class"=>"form-control"]) !!}
                </div>
                
                <div class="form-group">
                    {!! Form::label("email", "Email") !!}
                    {!! From::taxt("email", null, ["class"=>"form-control"]) !!}
                </div>
                
                <div class="form-group">
                    {!! From::label("password", "Password") !!}
                    {!! Form::text("password", null, ["class"=>"form-control"]) !!}
                </div>
                
                <div class="form-group">
                    {!! Form::label("password_confirmation", "Confirmation") !!}
                    {!! Form::taxt("password_confirmation", null, ["class"=>"form-control"]) !!}
                </div>
                
                {!! Form::submit("Sign up", ["class"=>"btn btn-primary btn-block"]) !!}
            {!! Form::close() !!}
        </div>
    </div>
@endsection