@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @if(Auth::user()->isManager())
            @include('components.redeem-point')
        @else
            @include('components.user-menu')
        @endif
    </div>
</div>
@endsection
