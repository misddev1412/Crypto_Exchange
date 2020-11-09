@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        {{ Form::open(['route'=>['roles.update',$role->slug], 'method'=>'PUT','class'=> 'roles-form clearfix', 'id' => 'roleForm']) }}
        @include('core.roles._form',['buttonText'=>__('Update')])
        {{ Form::close() }}
    </div>
@endsection

@section('script')
    @include('core.roles._script')
@endsection
