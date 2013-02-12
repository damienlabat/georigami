@layout('layout')

@section('content')

<?php $lastCountry=null; $adminName1=null; ?>

    <div id="map-canvas"></div>  

    <div class='locations'>
    @foreach ($data['locations'] as $location)


       <?php
       if ($lastCountry!==$location->countryname) {

        if ($adminName1!==null) echo "</ul></div>";
        if ($lastCountry!==null) echo "</div>";

        echo '<div class="row"><h2>'. $location->countryname .'</h2>';
        $lastCountry=$location->countryname; $adminName1=null;
       }

       if ($adminName1!==$location->adminname1) {
        if ($adminName1!==null) echo "</ul></div>";
        echo '<div class="span3"><h4>'. $location->adminname1 .'</h4><ul>';
        $adminName1=$location->adminname1; 
       }

       ?>
      
          
            <li><a href="./location{{$location->id}}">{{ $location->name }}</a></li>
          
     
       
    @endforeach
   <?php 
   if ($adminName1!="") echo "</ul></div>";
   if ($lastCountry!="") echo "</div>";
    ?>
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
