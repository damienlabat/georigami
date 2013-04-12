@layout('layout')

@section('bodyclass')
location@endsection

@section('title')
{{__('georigami.title')}} - {{$location->name}}
@endsection

@section('content')

         <ul class="pager">
              @if ($prev!=null)
              <li class="previous">
                  <a href="{{ $prev->get_url() }}" title="{{$prev->name}}">&larr; {{__('georigami.older')}}</a>
              @else
              <li class="previous disabled">
                  <a href="#">&larr; {{__('georigami.older')}}</a>
              @endif
              </li>
              @if ($next!=null)
              <li class="next">
                  <a href="{{ $next->get_url() }}" title="{{$next->name}}">{{__('georigami.newer')}} &rarr;</a>
              @else
              <li class="next disabled">
                  <a href="#">{{__('georigami.newer')}} &rarr;</a>
              @endif
              </li>
          </ul>

      <div class="row">

        <div class="span4 blocmenu">

          <h4>{{$location->name}}</h4>
                  <img src="{{URL::base()}}img/flags/{{ strtolower($location->countrycode) }}.png" title="{{ $location->countrycode }}" alt=""/> <a href="{{ URL::to('map') }}#{{ strtolower($location->countrycode) }}" class="countryname">{{ $location->countryname}}</a><br/>
                  {{$location->fclname}}<br/>
                  {{$location->adminname1}}<br/>
                  {{$location->adminname2}}<br/>
                  {{$location->adminname3}}<br/>
                  {{$location->adminname4}}<br/>
                  <a href='http://toolserver.org/~geohack/geohack.php?params={{$location->lat}}___N_{{$location->lng}}___E'/>{{$location->lat}}, {{$location->lng}}</a><br/>

        </div>

        <div class="span8">
          <div id="map-canvas2"></div>
        </div>

      </div>

      <div class='row'>

  @foreach ($location->blocs as $bloc)

            <div class='bloc2 clearfix'>
            <div class='title'><a href='{{$bloc->get_url() }}'>{{__('georigami.altitude',array('from'=>round($bloc->min), 'to'=>round($bloc->max)))}}&nbsp;|&nbsp;
                {{$bloc->width}}m x {{$bloc->height}}m&nbsp;|&nbsp;
                {{__('georigami.rotation')}}: {{$bloc->rotate}}°&nbsp;|&nbsp;
                {{__('georigami.slices')}}: {{$bloc->hslices}} x {{$bloc->vslices}}&nbsp;|&nbsp;
                {{__('georigami.samples')}}: {{$bloc->vslices*$bloc->vsamples + $bloc->hslices*$bloc->hsamples}}&nbsp;|&nbsp;
                {{$bloc->created_at_localized}}</a></div>
              <div class='row'>
                <div class='span3'><a href='{{$bloc->get_url('profil') }}?face=N'><img src="{{URL::base()}}svg/{{ $bloc->getDirectoryNum() }}/bloc{{ $bloc->id }}N.svg" title="{{__('georigami.nface')}}"></a></div>
                <div class='span3'><a href='{{$bloc->get_url('profil') }}?face=W'><img src="{{URL::base()}}svg/{{ $bloc->getDirectoryNum() }}/bloc{{ $bloc->id }}W.svg" title="{{__('georigami.wface')}}"></a></div>
                <div class='span3'><a href='{{$bloc->get_url('profil') }}?face=S'><img src="{{URL::base()}}svg/{{ $bloc->getDirectoryNum() }}/bloc{{ $bloc->id }}S.svg" title="{{__('georigami.sface')}}"></a></div>
                <div class='span3'><a href='{{$bloc->get_url('profil') }}?face=E'><img src="{{URL::base()}}svg/{{ $bloc->getDirectoryNum() }}/bloc{{ $bloc->id }}E.svg" title="{{__('georigami.eface')}}"></a></div>
              </div>
            </div>

    @endforeach
  </div>

 <a href='{{ URL::to('new') }}?lat={{$location->lat}}&amp;lng={{$location->lng}}' class='btn btn-primary'>{{__('georigami.buildnew')}}</a>

@endsection

@section('script')
        <script>
             Georigami.location={{$location_json}};
        </script>
@endsection
