@extends('layouts.master',['activeSideNav' => active_side_nav()])
@section('title', $title)
@section('content')
    <?php $title_name = ucwords(preg_replace('/[-_]+/', ' ', $type)); ?>
    <div class="container">
        <div class="row mt-5 mb-4">
            <div class="col-md-12">
                <div class="card-tools float-right">
                    <a href="{{ route('application-settings.edit',['type'=>$type]) }}"
                       class="btn btn-info btn-sm back-button">{{__('Edit :settingName Setting',['settingName' =>$title_name])}}</a>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-sm-4 col-md-3">
                <ul class="nav nav-pills flex-column">
                    <?php $default = true; ?>
                    @foreach($settings['settingSections'] as $settingSection)
                        <?php
                        $current_route = is_current_route('application-settings.index', 'active bg-info', ['type' => $settingSection]);
                        if ($default) {
                            $current_route = is_current_route('application-settings.index', 'active bg-info', null, ['type' => $settingSection]);
                        }
                        ?>
                        <li class="nav-item">
                            <a class="nav-link {{$current_route}}" href="{{route('application-settings.index',['type'=>$settingSection])}}">{{ucwords(preg_replace('/[-_]+/',' ',$settingSection))}}</a>
                        </li>
                        <?php $default = false; ?>
                    @endforeach
                </ul>
            </div>
            <div class="col-sm-8 col-md-9">
                <table class="table table-bordered lf-setting-table">
                    {{ $settings['html'] }}
                </table>
            </div>
        </div>
    </div>
@endsection
