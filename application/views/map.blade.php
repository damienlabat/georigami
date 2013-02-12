@layout('layout')

@section('content')

<?php $lastCountry=""; $adminName1=""; ?>

    <div id="map-canvas"></div>  

    <div class='locations'>
    @foreach ($data['locations'] as $location)


       <?php
       if ($lastCountry!=$location['countryname']) {
        echo '<h2><img src="./img/flags/'. strtolower($location['countrycode']) .'.png" title="'. $location['countryname'] .'"/> '. $location['countryname'] .'</h2>';
        $lastCountry=$location['countryname']; $adminName1="";
       }

       if ($adminName1!=$location['adminname1']) {
        echo '<h3>'. $location['adminname1'] .'</h3>';
        $adminName1=$location['adminname1']; 
       }

       ?>
      
          <div>
            <a href="./location{{$location['id']}}">
              <h3 title="{{ $location['feature'] }}">{{ $location['name'] }}</h3>
              <!--img src="./img/flags/{{ strtolower($location['countrycode']) }}.png" title="{{ $location['countryname'] }}"/> {{ $location['countryname'] }}<br/-->
              {{ $location['updated_at'] }}
            @if (count($location['blocs']) > 1) 
              X{{ count($location['blocs']) }} 
            @endif
            </a>
          </siv> 

     
       
    @endforeach
  </div>
   
@endsection




          

@section('script')    

        <script src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer.js"></script>       
        <script>
            var Georigami={};
            Georigami.location_list={{$data['locations_json']}};
            $(function() {  Georigami.initMap();    });
        </script>
@endsection      
