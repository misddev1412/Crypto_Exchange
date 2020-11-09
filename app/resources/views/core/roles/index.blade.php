@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-12">
                {{ $dataTable['filters'] }}
                <div class="my-4">
                    <table class="table lf-data-table">
                        <thead>
                        <tr class="bg-primary text-white">
                            <th class="all">{{ __('Role Name') }}</th>
                            <th class="min-phone-l">{{ __('Date') }}</th>
                            <th class="min-phone-l text-center">{{ __('Status') }}</th>
                            <th class="text-right all no-sort">{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($dataTable['items'] as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->created_at }}</td>
                                <td class="text-center">{{ view_html($role->is_active ? '<i class="fa fa-check text-success"></i>' :  '<i class="fa fa-close text-danger"></i>') }}</td>
                                <td class="lf-action text-right">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-info dropdown-toggle"
                                                data-toggle="dropdown"
                                                aria-expanded="false">
                                            <i class="fa fa-gear"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" role="menu">
                                            <a class="dropdown-item"
                                               href="{{ route('roles.edit',$role->slug) }}"><i
                                                    class="fa fa-pencil"></i> {{ __('Edit') }}</a>
                                            @if(!in_array($role->slug, $defaultRoles))
                                                <a class="dropdown-item confirmation"
                                                   data-alert="{{__('Do you want to delete this role?')}}"
                                                   data-form-id="ur-{{ $role->slug }}"
                                                   data-form-method='delete'
                                                   href="{{ route('roles.destroy',$role->slug) }}"><i
                                                        class="fa fa-trash-o"></i> {{ __('Delete') }}</a>
                                                @if($role->is_active == STATUS_ACTIVE)
                                                    <a data-form-id="ur-{{ $role->slug }}"
                                                       data-form-method="PUT"
                                                       href="{{ route('roles.status',$role->slug) }}"
                                                       class="dropdown-item confirmation"
                                                       data-alert="{{__('Do you want to disable this role?')}}"><i
                                                            class="fa  fa-times-circle-o"></i> {{ __('Disable') }}
                                                    </a>
                                                @endif
                                            @endif
                                            @if(!$role->is_active)
                                                <a data-form-id="ur-{{ $role->slug }}"
                                                   data-form-method="PUT"
                                                   href="{{ route('roles.status',$role->slug) }}"
                                                   class="dropdown-item confirmation"
                                                   data-alert="{{__('Do you want to active this role?')}}"><i
                                                        class="fa fa-check-square-o"></i> {{ __('Active') }}</a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
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
