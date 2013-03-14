@layout('bloc_layout')


@section('bodyclass')
profil@endsection


@section('title')
Georigami - {{ $bloc->location->name }} (bloc n° {{ $bloc->id }})
@endsection


<?php

$style_list=array(

  ''=>            'default',
  'white'=>       'white',
  'fillstyle2'=>  'fillstyle2',
  'fillstyle3'=>  'fillstyle3',
  'fillstyle4'=>  'fillstyle4',
  'lines'=>       'lines',
  'joydiv'=>      'joy division'
  );

?>



@section('bloc_content')


<?php



 // if ($max<1) $max=1;

?>


<div class='row'>

  <div class='span8'>

   @render('svg.style')

    {{ $svg }}

  </div>


    <div class="span4">

     <div id='facepicker' class="btn-group">
        <a href='?vscale={{ $vscale }}&face=N' class="btn@if ($face=='N') active@endif">North face</a>
        <a href='?vscale={{ $vscale }}&face=W' class="btn@if ($face=='W') active@endif">West face</a>
        <a href='?vscale={{ $vscale }}&face=S' class="btn@if ($face=='S') active@endif">South face</a>
        <a href='?vscale={{ $vscale }}&face=E' class="btn@if ($face=='E') active@endif">East face</a>
    </div>

  <div class=''>
      <form method='get' class='form-inline'>
        <fieldset>
        <input type='hidden' name='face' value='{{ $face }}'/>

        <div>
          <label  for="vscale">vertical scale</label>
          <input class="vs-input" name='vscale' value="{{ $vscale }}" type="number" step="0.1" min="0" max="5">
        </div><br/>





        <div>
          <label  for="dx">translate X</label>
          <input type="range" step="0.01" min="-5" max="5" id="input-translateX" name='dx' value='{{ $dx }}'/>
        </div><br/>

        <div>
          <label  for="dy">translate Y</label>
          <input type="range" step="0.01" min="-2.5" max="5" id="input-translateY" name='dy' value='{{ $dy }}'/>
        </div><br/>

        <div>
          <label  for="dscale">perspective</label>
          <input type="range" step="0.001" min="0" max="1" id="input-scale" name='dscale' value='{{ $dscale }}'/>
        </div><br/>


        <div>
          <label for="style">style</label>
          <select id='styleswicther' name='style'>
<?php

foreach ($style_list as $key => $value) {
  echo "<option value='".$key."'";
  if ($style==$key) echo " selected";
  echo ">".$value."</option>".PHP_EOL;
}

?>
          </select>
        </div><br/>

        <input type='submit' value='update' class='vs-update btn'/>
      </fieldset>
      </form>

    </div>






  </div>



</div>



@endsection



@section('bloc_menu')
          <li class="active"><a href="">profil</a></li>
          <li class=""><a data-action="3d"  href="{{ $bloc->get_url('3d') }}?vscale={{$vscale}}&face={{$face}}">preview 3D</a></li>
          <li class=""><a data-action="print"  href="{{ $bloc->get_url('print') }}?vscale={{$vscale}}&face={{$face}}">print</a></li>
@endsection


@section('script')
        <script>
            Georigami.profil={{ json_encode( $profil_data ) }};
            Georigami.svg_hscale={{ $svg_hscale }};
        </script>
@endsection