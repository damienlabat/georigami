@layout('layout')

@section('content')

    <div id="map-canvas"></div>  

    <ul class='locations'>
    @foreach ($data['locations'] as $location)


       
      
          <li>
            <a href="./location{{$location['id']}}">
              <h3 title="{{ $location['feature'] }}">{{ $location['name'] }}</h3>
              <img src="./img/flags/{{ strtolower($location['countrycode']) }}.png" title="{{ $location['countryname'] }}"/> {{ $location['countryname'] }}<br/>
              {{ $location['updated_at'] }}
            @if (count($location['blocs']) > 1) 
              X{{ count($location['blocs']) }} 
            @endif
            </a>
          </li> 

     
       
    @endforeach
  </ul>
   
@endsection




          

@section('script')    

        <script src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer.js"></script>       
        <script>
            var Georigami={};
            Georigami.location_list={{$data['locations_json']}};
            $(function() {  Georigami.initMap();    });
        </script>
@endsection      
