@layout('layout')





@section('title')
Georigami - {{$data['location']->name}}
@endsection





@section('content')


         <ul class="pager">            
              @if ($data['prev']!=null)
              <li class="previous">   
                  <a href="{{URL::to_route('location', array($data['prev']->id)) }}" title="{{$data['prev']->name}}">&larr; Older</a>               
              @else
              <li class="previous disabled">
                  <a href="#">&larr; Older</a>
              @endif
              </li>
              @if ($data['next']!=null)
              <li class="next">
                  <a href="{{URL::to_route('location', array($data['next']->id)) }}" title="{{$data['next']->name}}">Newer &rarr;</a>                  
              @else
              <li class="next disabled">
                  <a href="#">Newer &rarr;</a>
              @endif
              </li>
          </ul>


      <div class="row">
      
        <div class="span4">
          
          <h4>{{$data['location']->name}}</h4>
                  <img src="{{URL::base()}}/img/flags/{{ strtolower($data['location']->countrycode) }}.png" title="{{ $data['location']->countrycode }}" alt=""/> <a href="{{ URL::to('map') }}#{{ strtolower($data['location']->countrycode) }}">{{ Geoname::getISO3166( $data['location']->countrycode)}}</a><br/>
                  <h4 title="{{Geoname::getFCode($data['location']->fcode)[1]}}">{{Geoname::getFCode($data['location']->fcode)[0]}}</h4>
                  {{Geoname::getFcl($data['location']->fcl)}}<br/>
                  {{$data['location']->adminname1}}<br/>
                  {{$data['location']->adminname2}}<br/>
                  {{$data['location']->adminname3}}<br/>
                  {{$data['location']->adminname4}}<br/>
                  <a href='http://toolserver.org/~geohack/geohack.php?params={{$data['location']->lat}}___N_{{$data['location']->lng}}___E'/>{{$data['location']->lat}}, {{$data['location']->lng}}</a><br/>

                  <br/>
                  <h4>Nearest locations:</h4>
                  TODO

        </div>

        <div class="span8">
          <div id="map-canvas2"></div>
        </div>

      </div>



  @foreach ($data['location']->blocs as $bloc) 
        <a href='{{URL::to_route('get', array($bloc->id)) }}'> 
            <div class=' bloc2 clearfix'>
              <div class='span2'><img src="{{URL::to_route('svg', array($bloc->id,'N')) }}" title="North face" class="hidden-phone"></div>
              <div class='span2'><img src="{{URL::to_route('svg', array($bloc->id,'W')) }}" title="West face" class="hidden-phone"></div>
              <div class='span2'><img src="{{URL::to_route('svg', array($bloc->id,'S')) }}" title="South face" class="hidden-phone"></div>
              <div class='span2'><img src="{{URL::to_route('svg', array($bloc->id,'E')) }}" title="East face" class="hidden-phone"></div>




              <div class='span3'>
                <br/>
                altitude: {{round($bloc->min)}}m to {{round($bloc->max)}}m<br/>
                {{$bloc->width}}m x {{$bloc->height}}m<br/>
                slices: {{$bloc->vslices}} x {{$bloc->hslices}}<br/>
                {{$bloc->vslices*$bloc->vsamples + $bloc->hslices*$bloc->hsamples}} samples<br/>
                <br/>
                {{$bloc->created_at}}<br/> 
              </div>

              </a>

            </div>
               



      
    @endforeach 

 <a href='{{ URL::to('new') }}?lat={{$data['location']->lat}}&lng={{$data['location']->lng}}' class='btn btn-primary'>Build a new one</a>


 


@endsection





          

@section('script')         

        <script>
            
             Georigami.location={{$data['location_json']}}; 
            
            $(function() {  Georigami.initLocation();    });
           

        </script>

@endsection      
