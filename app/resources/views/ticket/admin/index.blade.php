@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-12">
                {{ $dataTable['filters'] }}
                {{ $dataTable['advanceFilters'] }}
                <div class="py-4">
                    @component('components.table', ['class' => 'lf-data-table'])
                        @slot('thead')
                            <tr class="bg-primary text-light">
                                <th class="all">{{ __('Ticket ID') }}</th>
                                <th class="min-phone-l">{{ __('Heading') }}</th>
                                <th class="none">{{ __('Reference ID') }}</th>
                                <th class="min-phone-l text-center">{{ __('Assigned To') }}</th>
                                <th class="none">{{ __('Date') }}</th>
                                <th class="all text-center">{{ __('Status') }}</th>
                                <th class="text-center all no-sort">{{ __('Action') }}</th>
                            </tr>
                        @endslot
                        @foreach($dataTable['items'] as $ticket)
                            <tr>
                                <td>{{ $ticket->id }}</td>
                                <td>{{ $ticket->title }}</td>
                                <td>
                                    @if($ticket->ticket_id)
                                        <div class="border-bottom d-flex mb-3 pb-3 align-items-center">
                                            <a target="_blank"
                                               href="{{ route('admin.tickets.show', $ticket->ticket_id) }}">{{$ticket->ticket_id}}</a>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($ticket->assigned_to)
                                        <a target="_blank"
                                           href="{{ route('profile.index', $ticket->assigned_to) }}">{{ $ticket->assigned_user_full_name }}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $ticket->created_at }}</td>
                                <td class="text-center"><small
                                        class="font-size-12 py-1 px-2 badge badge-{{ config('commonconfig.ticket_status.'.$ticket->status.'.color_class') }}">{{ ticket_status($ticket->status) }}</small>
                                </td>
                                <td class="lf-action text-light">
                                    <div class="btn-group">
                                        <button class="btn btn-info dropdown-toggle"
                                                data-toggle="dropdown">
                                            <i class="fa fa-gear"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            @if( has_permission('admin.tickets.show'))
                                                <a class="dropdown-item"
                                                   href="{{ route('admin.tickets.show', $ticket->id) }}">
                                                    <i class="fa fa-eye"></i> {{ __('Show') }}
                                                </a>
                                            @endif
                                            @if( has_permission('admin.tickets.assign') && $ticket->status ==STATUS_OPEN)
                                                <a class="dropdown-item confirmation"
                                                   data-alert="{{ __('Are you sure?') }}"
                                                   data-form-id="up-{{$ticket->id}}" data-form-method="put"
                                                   href="{{ route('admin.tickets.assign',  $ticket->id) }}">
                                                    <i class="fa fa-user-plus"></i> {{ __('Assign Me') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endcomponent
                </div>
            </div>
        </div>
        {{ $dataTable['pagination'] }}
    </div>

@endsection

@section('style')
    @include('layouts.includes.list-css')
@endsection
@section('script')
    @include('layouts.includes.list-js')
@endsection
