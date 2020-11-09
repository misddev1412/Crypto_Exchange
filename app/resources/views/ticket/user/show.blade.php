@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                @component('components.alert',['type' => 'primary', 'class' => 'mb-4 rounded-0 bg-primary text-white lf-toggle-border-color'])
                    <h3 class="card-title">{{$ticket->title}}</h3>
                @endcomponent

            </div>
            <div class="col-lg-4 order-lg-1 mb-lg-0 mb-4">
                @component('components.card',['class' => 'lf-toggle-border-color lf-toggle-bg-card', 'headerClass' => 'bg-primary mb-2'])
                    @slot('header')
                        <h3 class="text-light">{{__('Ticket Information')}}</h3>
                    @endslot
                    <div class="border-bottom lf-toggle-border-color mb-3 pb-3 align-items-center">
                        <div class="mb-2">{{ __('Ticket ID') }} :</div>
                        <div class="text-muted">{{$ticket->id}}</div>
                    </div>
                    @if($ticket->ticket_id)
                        <div class="border-bottom lf-toggle-border-color d-flex mb-3 pb-3 align-items-center">
                            <div class="mr-3">{{ __('Reference ID') }} :</div>
                            <a target="_blank"
                               href="{{ route('trader.tickets.show', $ticket->ticket_id) }}">{{$ticket->ticket_id}}</a>
                        </div>
                    @endif

                    @if(auth()->id() == $ticket->assigned_to || auth()->user()->isSuperAdmin())
                        <div class="border-bottom lf-toggle-border-color d-flex mb-3 pb-3 align-items-center">
                            <div class="mr-3">{{ __('Assigned To') }} :</div>
                            <div
                                class="text-muted">{{ optional(optional($ticket->assignedUser)->profile)->full_name}}</div>
                        </div>
                    @endif
                    <div class="border-bottom lf-toggle-border-color d-flex mb-3 pb-3 align-items-center">
                        <div class="mr-3">{{ __('Date') }} :</div>
                        <div class="text-muted">{{$ticket->created_at->toDateTimeString()}}</div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="mr-3">{{__('Status')}} :</div>
                        <small
                            class="px-2 py-1 badge badge-{{ config('commonconfig.ticket_status.'. $ticket->status.'.color_class') }}">{{ ticket_status($ticket->status) }}</small>
                    </div>
                @endcomponent

                @if(in_array($ticket->status,[STATUS_OPEN]))
                    @component('components.card',['class'=> 'mt-4 lf-toggle-border-color lf-toggle-bg-card','headerClass' => 'bg-primary mb-2'])
                        @slot('header')
                            <h3 class="text-light">{{__('Action')}}</h3>
                        @endslot
                        <a class="btn btn-block btn-danger confirmation" data-form-method="put"
                           data-alert="{{ 'Are you sure?' }}" data-form-id="close-{{ $ticket->id }}"
                           href="{{ route('tickets.close', ['ticket' => $ticket->id]) }}">{{ __('Close Ticket') }}</a>
                    @endcomponent
                @endif
            </div>
            <div class="col-lg-8 order-lg-0">
                @component('components.card',['type' => 'info','class' => 'lf-toggle-border-color lf-toggle-bg-card', 'headerClass' => 'bg-primary','bodyClass'=> 'pt-4'])
                    @slot('header')
                        <h3 class="card-title text-light">{{__('Discussion')}}</h3>
                    @endslot
                    <div class="single-comment mb-4">
                        <div class="ticket-comment-header d-flex align-items-center">
                            <img width="50" height="50" class="rounded-circle"
                                 src=" {{ get_avatar($ticket->user->avatar) }}" alt="">
                            <span class="ml-3">
                                <h4 class="mb-0">{{$ticket->user->profile->full_name}}</h4>
                                <small
                                    class="text-muted">{{$ticket->created_at !== null?$ticket->created_at->diffForHumans():''}}</small>
                            </span>
                        </div>
                        <div class="ticket-comment-body">
                            {{$ticket->content}}
                            @if($ticket->attachment)
                                <div class="attachment mt-4">
                                    {{ view_html(ticket_comment_attachment_link(route('tickets.attachment.download',['ticket' => $ticket->id,'fileName' => $ticket->attachment]), $ticket->attachment)) }}
                                </div>
                            @endif
                        </div>
                    </div>
                    @foreach($ticket->comments as $comment)
                        <div class="single-comment mb-4">
                            <div class="ticket-comment-header d-flex align-items-center">
                                <img width="50" height="50" class="rounded-circle"
                                     src=" {{ get_avatar($comment->user->avatar) }}" alt="">
                                <span class="ml-3">
                                <h4 class="mb-0">{{$comment->user->profile->full_name}}</h4>
                                <small
                                    class="text-muted">{{$comment->created_at !== null?$comment->created_at->diffForHumans():''}}</small>
                            </span>
                            </div>
                            <div class="ticket-comment-body">
                                {{$comment->content}}

                                @if($comment->attachment)
                                    <div class="attachment mt-4">
                                        {{ view_html(ticket_comment_attachment_link(route('tickets.attachment.download',['ticket' => $ticket->id,'fileName' => $comment->attachment]),$comment->attachment)) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endcomponent

                @includeWhen(($ticket->status < STATUS_RESOLVED),'ticket.user._comment_form')
            </div>
        </div>
    </div>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/jasny-bootstrap/css/jasny-bootstrap.min.css') }}">
    <style>
        .ticket-comment-header {
            margin-bottom: 25px;
            margin-left: 9px;
        }

        .ticket-comment-body {
            padding: 15px;
            position: relative;
        }

        .ticket-comment-body:after {
            content: '';
            border-left: 15px solid transparent;
            border-right: 15px solid transparent;
            border-bottom: 15px solid;
            position: absolute;
            top: -15px;
            left: 30px;
            -moz-transform: translateX(-15px);
            -o-transform: translateX(-15px);
            -webkit-transform: translateX(-15px);
            -ms-transform: translateX(-15px);
            transform: translateX(-15px);
        }


    </style>
@endsection

@section('script')
    <script src="{{ asset('plugins/jasny-bootstrap/js/jasny-bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/cvalidator/cvalidator-language-en.js') }}"></script>
    <script src="{{ asset('plugins/cvalidator/cvalidator.js') }}"></script>
    <script>
        "use strict";

        $(document).ready(function () {
            var form =$('#ticketCommentForm').cValidate({
                rules : {
                    'content' : 'required|max:500',
                    'attachment' : 'mimetypes:jpg,jpeg,png,doc,docx,pdf,txt|max:1024',
                }
            });
        });
    </script>
@endsection


