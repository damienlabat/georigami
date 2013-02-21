@layout('layout')





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
                  <img src="{{URL::base()}}/img/flags/{{ strtolower($location->countrycode) }}.png" title="{{ $location->countrycode }}" alt=""/> <a href="{{ URL::to('map') }}#{{ strtolower($location->countrycode) }}">{{ Geoname::getISO3166( $location->countrycode)}}</a><br/>
                  <h4 title="{{Geoname::getFCode($location->fcode)[1]}}">{{Geoname::getFCode($location->fcode)[0]}}</h4>
                  {{Geoname::getFcl($location->fcl)}}<br/>
                  {{$location->adminname1}}<br/>
                  {{$location->adminname2}}<br/>
                  {{$location->adminname3}}<br/>
                  {{$location->adminname4}}<br/>
                  <a href='http://toolserver.org/~geohack/geohack.php?params={{$location->lat}}___N_{{$location->lng}}___E'/>{{$location->lat}}, {{$location->lng}}</a><br/>

                  <br/>
                  <h4>Nearest locations:</h4>
                  TODO

        </div>

        <div class="span8">
          <div id="map-canvas2"></div>
        </div>

      </div>

      <div class='row'>

  @foreach ($location->blocs as $bloc) 
        <a href='{{$bloc->get_url() }}' class='span6'> 
            <div class=' bloc2 clearfix'>
              <div class='span2'><img src="{{URL::to_route('svg', array($bloc->id,$face)) }}" title="{{ $face }} face"></div>





              <div class='span3'>
                <br/>
                altitude: {{round($bloc->min)}}m to {{round($bloc->max)}}m<br/>
                {{$bloc->width}}m x {{$bloc->height}}m<br/>
                slices: {{$bloc->vslices}} x {{$bloc->hslices}}<br/>
                {{$bloc->vslices*$bloc->vsamples + $bloc->hslices*$bloc->hsamples}} samples<br/>
                <br/>
                {{$bloc->created_at}}<br/> 
              </div>
            </div>
              </a>

            
               



      
    @endforeach 
  </div>
  

 <a href='{{ URL::to('new') }}?lat={{$location->lat}}&lng={{$location->lng}}' class='btn btn-primary'>Build a new one</a>


 


@endsection





          

@section('script')         

        <script>
            
             Georigami.location={{$location_json}}; 
            
            $(function() {  Georigami.initLocation();    });
           

        </script>

@endsection      
