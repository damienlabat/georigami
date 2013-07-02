@layout('layout')

@section('bodyclass')
savedview@endsection

@section('title')
{{__('georigami.title')}} - {{$saved->bloc->location->name}} (bloc n° {{$saved->bloc->id}})
@endsection

@section('content')

<a href='{{$url}}' title='{{$saved->bloc->location->name}}'><h1>{{$saved->bloc->location->name}}</h1></a>

<div class="row">

        <div class="span4 blocmenu">
            <a href='{{$url}}' title='{{$saved->bloc->location->name}}'>
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
          <div id="map-canvas2"></div>
        </div>
</div>        

<a href='{{$url}}' title='{{$saved->bloc->location->name}}' class='ifnotsvg'>
  <img src='{{URL::base()}}png/view/{{ $saved->getDirectoryNum() }}/view{{$saved->id}}_{{Str::slug($saved->bloc->location->name)}}.png' alt='{{$saved->bloc->location->name}}'/>
</a>

<a href='{{$url}}' title='{{$saved->bloc->location->name}}' class='ifsvg'>
  {{ $svg }}
</a>

<a href='{{$url}}' title='modifier' class='btn'>{{__('georigami.edit')}}</a>


@endsection

@section('script')
        <script>
             Georigami.location={{$location_json}};
        </script>
@endsection

