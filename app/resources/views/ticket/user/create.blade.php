@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                @component('components.form_box')
                    @slot('title', __('Create Ticket'))
                    @slot('indexUrl', route('tickets.index'))
                    {{ Form::open(['route'=>'tickets.store', 'method' => 'post', 'files'=> true, 'id' => 'ticketForm']) }}
                    @include('ticket.user._form',['buttonText' => __('Create')])
                    {{ Form::close() }}
                @endcomponent
            </div>
        </div>
    </div>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/jasny-bootstrap/css/jasny-bootstrap.min.css') }}">
@endsection

@section('script')
    <script src="{{ asset('plugins/jasny-bootstrap/js/jasny-bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/cvalidator/cvalidator-language-en.js') }}"></script>
    <script src="{{ asset('plugins/cvalidator/cvalidator.js') }}"></script>
    <script>
        "use strict";

        $(document).ready(function () {
            $('#ticketForm').cValidate({
                rules : {
                    'title' : 'required|max:255',
                    'content' : 'required|max:500',
                    'attachment' : 'mimetypes:jpg,jpeg,png,doc,docx,pdf,txt|max:1024',
                }
            });
        });
    </script>
@endsection

