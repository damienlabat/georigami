@layout('layout')

@section('bodyclass')
location@endsection

@section('title')
Georigami - {{$location->name}}
@endsection

@section('content')

         <ul class="pager">
              @if ($prev!=null)
              <li class="previous">
                  <a href="{{ $prev->get_url() }}" title="{{$prev->name}}">&larr; Older</a>
              @else
              <li class="previous disabled">
                  <a href="#">&larr; Older</a>
              @endif
              </li>
              @if ($next!=null)
              <li class="next">
                  <a href="{{ $next->get_url() }}" title="{{$next->name}}">Newer &rarr;</a>
              @else
              <li class="next disabled">
                  <a href="#">Newer &rarr;</a>
              @endif
              </li>
          </ul>

      <div class="row">

        <div class="span4">

          <h4>{{$location->name}}</h4>
                  <img src="{{URL::base()}}/img/flags/{{ strtolower($location->countrycode) }}.png" title="{{ $location->countrycode }}" alt=""/> <a href="{{ URL::to('map') }}#{{ strtolower($location->countrycode) }}">{{ $location->countryname}}</a><br/>
                  <h4 title="{{$location->fcodedetail()}}">{{$location->fcodename()}}</h4>
                  {{$location->fclassname()}}<br/>
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
            <div class='title'><a href='{{$bloc->get_url() }}'>altitude: {{round($bloc->min)}}m to {{round($bloc->max)}}m |
                {{$bloc->width}}m x {{$bloc->height}}m |
                slices: {{$bloc->vslices}} x {{$bloc->hslices}} |
                {{$bloc->vslices*$bloc->vsamples + $bloc->hslices*$bloc->hsamples}} samples<br/>
                {{$bloc->created_at}}</a></div>
              <div class='row'>
                <div class='span3'><a href='{{$bloc->get_url('profil') }}?face=N'><img src="{{URL::base()}}/svg/{{ $bloc->getDirectoryNum() }}/bloc{{ $bloc->id }}N.svg" title="North face"></a></div>
                <div class='span3'><a href='{{$bloc->get_url('profil') }}?face=W'><img src="{{URL::base()}}/svg/{{ $bloc->getDirectoryNum() }}/bloc{{ $bloc->id }}W.svg" title="West face"></a></div>
                <div class='span3'><a href='{{$bloc->get_url('profil') }}?face=S'><img src="{{URL::base()}}/svg/{{ $bloc->getDirectoryNum() }}/bloc{{ $bloc->id }}S.svg" title="South face"></a></div>
                <div class='span3'><a href='{{$bloc->get_url('profil') }}?face=E'><img src="{{URL::base()}}/svg/{{ $bloc->getDirectoryNum() }}/bloc{{ $bloc->id }}E.svg" title="Est face"></a></div>
              </div>
            </div>

    @endforeach
  </div>

 <a href='{{ URL::to('new') }}?lat={{$location->lat}}&lng={{$location->lng}}' class='btn btn-primary'>Build a new one</a>

@endsection

@section('script')
        <script>
             Georigami.location={{$location_json}};
        </script>
@endsection
