@layout('layout')

@section('title')
{{__('georigami.title')}} - {{$bloc->location->name}} (bloc n° {{$bloc->id}})
@endsection

@section('content')

         <ul class="pager">
              @if ($prev!=null)
              <li class="previous">
                  <a href="{{ $prev->get_url($show) }}?face={{$face}}" title="{{$prev->location->name}}">&larr; {{__('georigami.older')}}</a>
              @else
              <li class="previous disabled">
                  <a href="#">&larr; {{__('georigami.older')}}</a>
              @endif
              </li>
              @if ($next!=null)
              <li class="next">
                  <a href="{{ $next->get_url($show) }}?face={{$face}}" title="{{$next->location->name}}">{{__('georigami.newer')}} &rarr;</a>
              @else
              <li class="next disabled">
                  <a href="#">{{__('georigami.newer')}} &rarr;</a>
              @endif
              </li>
          </ul>

        <div id='blocinfo' class='row' data-id='{{$bloc->id}}' data-vscale='{{$vscale}}' data-face='{{$face}}' data-view='{{$show}}'>

            <div class="span2">
               <img src="{{URL::base()}}svg/{{ $bloc->getDirectoryNum() }}/bloc{{ $bloc->id }}{{ $face }}.svg" title="{{Str::slug($bloc->location->name)}} {{$face}} view"  class="hidden-phone bloc-face">
               <img src="{{URL::base()}}png/{{ $bloc->getDirectoryNum() }}/bloc{{ $bloc->id }}{{ $face }}_{{ Str::slug($bloc->location->name) }}.png" title="{{Str::slug($bloc->location->name)}} {{$face}} view"  class="ifnotsvg bloc-face">
            </div>
            <div class="span2">
              <a href='{{ $bloc->location->get_url() }}'>
               <h4><?php
            if ($bloc->location->name!='unknown place')  echo $bloc->location->name;
            elseif  ($bloc->location->adminname4!='')  echo $bloc->location->adminname4;
            elseif  ($bloc->location->adminname3!='')  echo $bloc->location->adminname3;
            else echo __('georigami.unnamed');
               ?></h4></a>
               @if ($bloc->location->countrycode!=='')
                <img class="flag" src="{{URL::base()}}img/flags/{{ strtolower($bloc->location->countrycode) }}.png" title="{{ $bloc->location->countrycode }}" alt="" />  <a href='{{URL::to_route('map')}}#{{strtolower($bloc->location->countrycode)}}' class="countryname">{{ $bloc->location->countryname }}</a><br/>
              @endif
                <span>{{ $bloc->location->fclname }}</span><br/>

                {{$bloc->location->adminname1}}<br/>

            </div>
            <div class="span6">
                {{__('georigami.altitude',array('from'=>round($bloc->min), 'to'=>round($bloc->max)))}}<br/>
                {{$bloc->width}}m x {{$bloc->height}}m<br/>
                {{__('georigami.rotation')}} {{$bloc->rotate}}°<br/>
                {{__('georigami.slices')}} {{$bloc->hslices}} x {{$bloc->vslices}}<br/>
                {{$bloc->vslices*$bloc->vsamples + $bloc->hslices*$bloc->hsamples}} samples<br/>
                {{$bloc->created_at_localized}}
            </div>
      </div>

<br/>

<div class='navbar'>
  <div class="navbar-inner">
    <a class="brand" href="#">Bloc N°{{$bloc->id}}</a>
    <ul class="nav" id="bloc-menu">@yield('bloc_menu')</ul>
  </div>
</div>

      @yield('bloc_content')

@endsection

@section('script')

@endsection
