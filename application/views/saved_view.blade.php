@layout('layout')

@section('bodyclass')
savedview@endsection

@section('title')
{{__('georigami.title')}} - {{$saved->bloc->location->name}} (bloc n° {{$saved->bloc->id}})
@endsection

@section('content')

 <ul class="pager">
              @if ($prev!=null)
              <li class="previous">
                  <a href="{{ $prev->get_url() }}" title="{{$prev->bloc->location->name}}">&larr; {{__('georigami.older')}}</a>
              @else
              <li class="previous disabled">
                  <a href="#">&larr; {{__('georigami.older')}}</a>
              @endif
              </li>
              @if ($next!=null)
              <li class="next">
                  <a href="{{ $next->get_url() }}" title="{{$next->bloc->location->name}}">{{__('georigami.newer')}} &rarr;</a>
              @else
              <li class="next disabled">
                  <a href="#">{{__('georigami.newer')}} &rarr;</a>
              @endif
              </li>
          </ul>

<a href='{{$url}}' title="{{$saved->bloc->location->name}}"><h1>{{$saved->bloc->location->name}}</h1></a>

<div class="row">

        <div class="span4 blocmenu">
            <a href='{{$url}}' title="{{$saved->bloc->location->name}}">
              <h4>{{$saved->bloc->location->name}}</h1>
            </a>
            <img src="{{URL::base()}}img/flags/{{ strtolower($saved->bloc->location->countrycode) }}.png" title="{{ $saved->bloc->location->countrycode }}" alt=""/> <a href="{{ URL::to('map') }}#{{ strtolower($saved->bloc->location->countrycode) }}" class="countryname">{{ $saved->bloc->location->countryname}}</a><br/>
            {{$saved->bloc->location->fclname}}<br/>
            {{$saved->bloc->location->adminname1}}<br/>
            {{$saved->bloc->location->adminname2}}<br/>
            {{$saved->bloc->location->adminname3}}<br/>
            {{$saved->bloc->location->adminname4}}<br/>
            <a href='http://toolserver.org/~geohack/geohack.php?params={{$saved->bloc->location->lat}}___N_{{$saved->bloc->location->lng}}___E'/>{{$saved->bloc->location->lat}}, {{$saved->bloc->location->lng}}</a><br/>
        </div>


        <div class="span8">
          <div id="map-canvas2" class='blocsmap'></div>
        </div>
</div>        

@if (TRUE==$png_exist)
<a href='{{$url}}' title="{{$saved->bloc->location->name}}">
  <img class='imgprofil' src='{{URL::base()}}png/view/{{ $saved->getDirectoryNum() }}/view{{$saved->id}}_{{Str::slug($saved->bloc->location->name)}}.png' alt="{{$saved->bloc->location->name}}" title="{{$saved->bloc->location->name}}"/>
</a>
<a href='{{URL::base()}}png/view/{{ $saved->getDirectoryNum() }}/view{{$saved->id}}_{{Str::slug($saved->bloc->location->name)}}.png' title="{{$saved->bloc->location->name}}">view{{$saved->id}}_{{Str::slug($saved->bloc->location->name)}}.png</a><br/>
@else
<a href='{{$url}}'   title="{{$saved->bloc->location->name}}">
  {{ $svg }}
</a>
 @endif

<a href='{{$url}}' title='modifier' class='btn'>{{__('georigami.edit')}}</a>


@endsection

@section('script')
        <script>
             Georigami.location={{$location_json}};
        </script>
@endsection

