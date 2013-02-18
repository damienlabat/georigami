@layout('layout')





@section('title')
Georigami - Map
@endsection





@section('content')

<?php 
$continentCode=null; $countryCode=null; $adminName1=null; $adminName2=null; $adminName3=null; $adminName4=null; $ulopen=false; 
?>

    <div id="map-canvas"></div>  

    <div class='locations'>
    @foreach ($data['locations'] as $location)


       <?php
       if ($continentCode!==$location->continentcode) {

        if ($ulopen) { echo '</ul>'; $ulopen=false; }
        if ($adminName1!==null) echo "</div>";
        if ($countryCode!==null) echo "</div>";

        echo '<h1>'. Geoname::continentCode($location->continentcode) .'</h1>';
        $continentCode=$location->continentcode;
        $countryCode=null; $adminName1=null; $adminName2plus=null;
       }


       if ($countryCode!==$location->countrycode) {

         if ($ulopen) { echo '</ul>'; $ulopen=false; }
        if ($adminName1!==null) echo "</div>";
        if ($countryCode!==null) echo "</div>";

        echo '<div class="row"><a name="'.strtolower($location->countrycode).'"></a><h2><img src="./img/flags/'.strtolower($location->countrycode) .'.png" style="width:32px"><br/>'. (Geoname::getISO3166($location->countrycode)!=''?Geoname::getISO3166($location->countrycode):'ocean') . HTML::showcount( Bloc::count_withlocation('countrycode',$location->countrycode) ). '</h2>';
        $countryCode=$location->countrycode;
        $adminName1=null; $adminName2plus=null;
       }


       if ($adminName1!==$location->adminname1) {
        if ($ulopen) { echo '</ul>'; $ulopen=false; }
        if ($adminName1!==null) echo "</div>";
        echo '<div class="span3"><h4>'. $location->adminname1 . HTML::showcount( Bloc::count_withlocation('adminname1',$location->adminname1) ).'</h4>';
        $adminName1=$location->adminname1; 
       }

       if ($adminName2!==$location->adminname2) {   
        if ($ulopen) { echo '</ul>'; $ulopen=false; }
        echo '<span>'. $location->adminname2 .'</span>';
        $adminName2=$location->adminname2; 
       }

    /*   if ($adminName3!==$location->adminname3) {        
        if ($ulopen) { echo '</ul>'; $ulopen=false; }
        echo '<span>'. $location->adminname3 .'</span>';
        $adminName3=$location->adminname3; 
       }

       if ($adminName4!==$location->adminname4) {        
        if ($ulopen) { echo '</ul>'; $ulopen=false; }
        echo '<span>'. $location->adminname4 .'</span>';
        $adminName4=$location->adminname4; 
       }
    */

       if (!$ulopen) { echo '<ul>'; $ulopen=true; }
       ?>
      
          
            <li><a href="./location{{$location->id}}">{{ $location->name }}{{ HTML::showcount( $location->blocs()->count() ) }}</a></li>
          
     
       
    @endforeach
   <?php 
   if ($adminName1!="") echo "</ul></div>";
   if ($countryCode!=null) echo "</div>";
    ?>
  </div>
   
@endsection




          

@section('script')    

     
        <script>
            Georigami.location_list={{$data['locations_json']}};
            $(function() {  Georigami.initMap();    });
        </script>
@endsection      
