@layout('layout')

@section('bodyclass')
map@endsection

@section('title')
{{__('georigami.title')}} - {{__('georigami.map')}}
@endsection

@section('content')
<h1>{{__('georigami.map')}}</h1>

<?php
/*$continentCode=null;*/ $countryCode=null; $adminName1=null; $adminName2=null; $adminName3=null; $adminName4=null; $ulopen=false;
?>

    <div id="map-canvas"></div>

    <div class='locations'>
    @foreach ($locations as $location)

       <?php

       if ($countryCode!==$location->countrycode) {

         if ($ulopen) { echo '</ul>'; $ulopen=false; }
        if ($adminName1!==null) echo "</div>";
        if ($countryCode!==null) echo "</div>";

        echo '<div class="row"><a name="'.strtolower($location->countrycode).'" class="anchor"></a>'
        .'<h2 class="countryname">';
        if ($location->countrycode!=='')
          echo '<img class="flag" src="'.URL::base().'img/flags/'.strtolower($location->countrycode) .'.png" style="width:32px"><br/>';

        echo ($location->countrycode!=''?$location->countryname:__('georigami.ocean'))
        .HTML::showcount( Bloc::count_with( array('countrycode'=>$location->countrycode) ) ). '</h2>';

        $countryCode=$location->countrycode;
        $adminName1=null; $adminName2plus=null;
       }

       if ($adminName1!==$location->adminname1) {
        if ($ulopen) { echo '</ul>'; $ulopen=false; }
        if ($adminName1!==null) echo "</div>";

        echo '<div class="span3"><h4>'. $location->adminname1
        .HTML::showcount( Bloc::count_with( array('adminname1'=>$location->adminname1, 'countrycode'=>$location->countrycode) ) ).'</h4>';

        $adminName1=$location->adminname1;
       }

       if ($adminName2!==$location->adminname2) {
        if ($ulopen) { echo '</ul>'; $ulopen=false; }
        echo '<span>'. $location->adminname2 .'</span>';
        $adminName2=$location->adminname2;
       }


       if (!$ulopen) { echo '<ul>'; $ulopen=true; }
       ?>

            <li><a href="{{  $location->get_url() }}" title="{{$location->name}}"><?php
            if ($location->name!='unknown place')  echo $location->name;
            elseif  ($location->adminname4!='')  echo $location->adminname4;
            elseif  ($location->adminname3!='')  echo $location->adminname3;
            else echo __('georigami.unnamed');
            ?> {{ HTML::showcount( $location->blocs()->count() ) }}</a></li>

    @endforeach
   <?php
   if ($adminName1!="") echo "</ul></div>";
   if ($countryCode!=null) echo "</div>";
    ?>
  </div>

@endsection

@section('script')
        <script>
            Georigami.location_list={{ $locations_json }};
        </script>
@endsection
