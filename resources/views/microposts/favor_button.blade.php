
    @if(Auth::user()->is_favoring($micropost->id))
        {{-- お気に入り登録解除のフォーム --}}
        {!! Form::open(["route"=>["favorites.unfavor", $micropost->id], "method"=>"delete"]) !!}
            {!! Form::submit("Unfavor", ["class"=>"btn btn-danger btn-sm"]) !!}
        {!! Form::close() !!}
    @else
        {{-- お気に入り登録のフォーム --}}
        {!! Form::open(["route"=>["favorites.favor", $micropost->id], "method"=>"post"]) !!}
            {!! Form::submit("favor", ["class"=>"btn btn-primary btn-sm"]) !!}
        {!! Form::close() !!}
    @endif
