@if (Auth::user()->is_favorite($micropost->id))
    <span class="badge badge-light badge-pill"># My favorite</span>
    {!! Form::open(['route' => ['favorites.unfavor', $micropost->id], 'method' => 'delete']) !!}
        {!! Form::submit('Un-favorite this', ['class' => "btn btn-outline-secondary btn-sm"]) !!}
    {!! Form::close() !!}
@else
    <span class="badge badge-light badge-pill"># Not my favorite</span>
    {!! Form::open(['route' => ['favorites.favor', $micropost->id]]) !!}
        {!! Form::submit('Favorite this', ['class' => "btn btn-outline-success btn-sm"]) !!}
    {!! Form::close() !!}
@endif

