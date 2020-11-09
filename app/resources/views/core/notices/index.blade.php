@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-12">
                {{ $dataTable['filters'] }}
                {{ $dataTable['advanceFilters'] }}
                <div class="my-4">
                    @component('components.table',['class' => 'lf-data-table'])
                        @slot('thead')
                            <tr class="bg-primary text-white">
                                <th class="all">{{ __('Title') }}</th>
                                <th class="min-phone-l">{{ __('Start Time') }}</th>
                                <th class="min-phone-l">{{ __('End Time') }}</th>
                                <th class="min-phone-l text-center">{{ __('Type') }}</th>
                                <th class="min-phone-l text-center">{{ __('Visibility') }}</th>
                                <th class="min-phone-l text-center">{{ __('Status') }}</th>
                                <th class="none">{{ __('Description') }}</th>
                                <th class="all no-sort text-right">{{ __('Action') }}</th>
                            </tr>
                        @endslot

                        @foreach($dataTable['items'] as $notice)
                            <tr>
                                <td>{{$notice->title}}</td>
                                <td>{{$notice->start_at}}</td>
                                <td>{{$notice->end_at}}</td>
                                <td class="text-center">
                                    <span
                                        class="font-size-12 py-1 px-2 badge badge-{{ $notice->type }}">{{ notices_types($notice->type) }}</span>
                                </td>
                                <td>{{ notices_visible_types($notice->visible_type) }}</td>
                                <td class="text-center">{{ display_active_status($notice->is_active) }}</td>
                                <td>{{ $notice->description }}</td>
                                <td class="lf-action text-right">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-info dropdown-toggle"
                                                data-toggle="dropdown"
                                                aria-expanded="false">
                                            <i class="fa fa-gear"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" role="menu">
                                            <a class="dropdown-item"
                                               href="{{ route('notices.edit',['notice' => $notice->id, 'return-url' => request()->getUri()]) }}"><i
                                                    class="fa fa-pencil"></i> {{ __('Edit') }}</a>
                                            <a class="dropdown-item confirmation" data-alert="{{__('Are you sure?')}}"
                                               data-form-id="urm-{{$notice->id}}" data-form-method='delete'
                                               href="{{ route('notices.destroy',$notice->id) }}"><i
                                                    class="fa fa-trash-o"></i> {{ __('Delete') }}</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endcomponent
                </div>
                {{ $dataTable['pagination'] }}
            </div>
        </div>
    </div>
@endsection

@section('style')
    @include('layouts.includes.list-css')
@endsection

@section('script')
    @include('layouts.includes.list-js')
@endsection
