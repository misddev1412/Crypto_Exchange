<form action="{{$route}}" class="lf-filter-container{{!empty(request()->get($pageName.'-fltr')) ? '' : ' lf-d-none'}} lf-toggle-border-color" method="get" id="{{'lf-'.$pageName.'-fltr'}}">
    <div class="lf-filter-wrapper row">
        @foreach($filterFields as $filterKey => $filterValue)
            <div class="my-3 col-md-3">
                <h6>{{$filterValue[1]}}</h6>
                @if(is_array($filterValue[2]))
                    @foreach($filterValue[2] as $optionKey => $optionValue)
                        <div>
                            <input id="{{$pageName}}-fltr-{{$filterKey}}-{{$optionKey}}"
                                   name="{{$pageName}}-fltr[{{$filterKey}}][]" type="checkbox"
                                   value="{{$optionKey}}"
                                   {{is_array(request()->input($pageName.'-fltr.'.$filterKey)) && in_array($optionKey,request()->input($pageName.'-fltr.'.$filterKey)) ? ' checked' : ''}} class="lf-filter-checkbox">
                            <label class="lf-filter-checkbox-label"
                                   for="{{$pageName}}-fltr-{{$filterKey}}-{{$optionKey}}">{{$optionValue}}</label>
                        </div>
                    @endforeach
                @elseif($filterValue[2]==='range')
                    <div>
                        <div class="row">
                            <div class="col mr-1 pr-0">
                                <input name="{{$pageName}}-fltr[{{$filterKey}}][0]" type="text" value="{{request()->input($pageName.'-fltr.'.$filterKey.'.0')}}" class="form-control" placeholder="{{__('Min')}}">
                            </div>
                            <div class="col ml-1 pl-0">
                                <input name="{{$pageName}}-fltr[{{$filterKey}}][1]" type="text" value="{{request()->input($pageName.'-fltr.'.$filterKey.'.1')}}" class="form-control" placeholder="{{__('Max')}}">
                            </div>
                        </div>
                    </div>
                @elseif($filterValue[2]==='preset')
                    @foreach($filterValue[4] as $presetKey => $presetValue)
                        <div>
                            <input id="{{$pageName}}-fltr-{{$filterKey}}-{{$presetKey}}"
                                   name="{{$pageName}}-fltr[{{$filterKey}}][{{$presetKey}}]" type="checkbox"
                                   value="1"
                                   {{request()->input($pageName.'-fltr.'.$filterKey.'.'.$presetKey) ? ' checked' : ''}} class="lf-filter-checkbox">
                            <label class="lf-filter-checkbox-label"
                                   for="{{$pageName}}-fltr-{{$filterKey}}-{{$presetKey}}">{{$filterValue[4][$presetKey][0]}}</label>
                        </div>
                    @endforeach
                @endif
            </div>
        @endforeach
    </div>
    <button type="submit" class="btn btn-info"><i class="fa fa-filter"></i> Filter</button>

    @if(!empty(return_get($pageName.'_srch')) && !is_array(return_get($pageName.'_srch')))
        <input type="hidden" name="{{$pageName}}_srch" value="{{request()->input($pageName.'_srch')}}">
    @endif
    @if(!empty(return_get($pageName.'_comp')) && !is_array(return_get($pageName.'_comp')))
        <input type="hidden" name="{{$pageName}}_comp" value="{{request()->input($pageName.'_comp')}}">
    @endif
    @if(!empty(return_get($pageName.'_ssf')) && !is_array(return_get($pageName.'_ssf')))
        <input type="hidden" name="{{$pageName}}_ssf" value="{{request()->input($pageName.'_ssf')}}">
    @endif
    @if(!empty(return_get($pageName.'_frm')) && !is_array(return_get($pageName.'_frm')))
        <input type="hidden" name="{{$pageName}}_frm" value="{{request()->input($pageName.'_frm')}}">
    @endif
    @if(!empty(return_get($pageName.'_to')) && !is_array(return_get($pageName.'_to')))
        <input type="hidden" name="{{$pageName}}_to" value="{{request()->input($pageName.'_to')}}">
    @endif
    @if(!empty(return_get($pageName.'_sort')) && !is_array(return_get($pageName.'_sort')))
        <input type="hidden" name="{{$pageName}}_sort" value="{{request()->input($pageName.'_sort')}}">
    @endif
    @if(!empty(return_get($pageName.'_ord')) && !is_array(return_get($pageName.'_ord')))
        <input type="hidden" name="{{$pageName}}_ord" value="{{request()->input($pageName.'_ord')}}">
    @endif
</form>
