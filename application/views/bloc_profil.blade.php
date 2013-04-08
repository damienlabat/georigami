@layout('bloc_layout')

@section('bodyclass')
profil@endsection


<?php

$styleList=array(
  ''=>            'default',
  'grey'=>       'grey',
  'fillstyle2'=>  'fillstyle2',
  'fillstyle3'=>  'fillstyle3',
  'fillstyle4'=>  'fillstyle4',
  'beetlejuice'=>  'beetlejuice',
  'lines'=>       'lines',
  'joydiv'=>      'joy division',
  'joydiv2'=>      'joy division 2',
  'joydiv3'=>      'joy division 3'
  );

?>

@section('bloc_content')


<div class='row'>

  <div class='span8'>


    {{ $svg }}

  </div>

    <div class="span4">
     <div id='facepicker' class="btn-group">
        <a data-face='N' href='?vscale={{ $vscale }}&face=N&dx={{ $dx }}&dy={{ $dy }}&dscale={{ $dscale }}&style={{ $style }}' class="btn@if ($face=='N')
        active@endif">{{__('georigami.nface')}}</a>
        <a data-face='W' href='?vscale={{ $vscale }}&face=W&dx={{ $dx }}&dy={{ $dy }}&dscale={{ $dscale }}&style={{ $style }}' class="btn@if ($face=='W') active
        @endif">{{__('georigami.wface')}}</a>
        <a data-face='S' href='?vscale={{ $vscale }}&face=S&dx={{ $dx }}&dy={{ $dy }}&dscale={{ $dscale }}&style={{ $style }}' class="btn@if ($face=='S') active
        @endif">{{__('georigami.sface')}}</a>
        <a data-face='E' href='?vscale={{ $vscale }}&face=E&dx={{ $dx }}&dy={{ $dy }}&dscale={{ $dscale }}&style={{ $style }}' class="btn@if ($face=='E') active
        @endif">{{__('georigami.eface')}}</a>
    </div>

  <div class='blocmenu'>
      <form method='get' class='form-inline'>
        <fieldset>
        <input type='hidden' id='input-face' name='face' value='{{ $face }}'/>

        <div>
          <label  for="vscale">{{__('georigami.verticalscale')}}</label>
          <input class="vs-input" name='vscale' value="{{ $vscale }}" type="number" step="0.1" min="0">
        </div><br/>

        <div>
          <label  for="dx">{{__('georigami.xtranslate')}}</label>
          <input type="range" step="0.01" min="-8" max="8" id="input-translateX" name='dx' value='{{ $dx }}'/>
        </div><br/>

        <div>
          <label  for="dy">{{__('georigami.ytranslate')}}</label>
          <input type="range" step="0.01" min="-4" max="8" id="input-translateY" name='dy' value='{{ $dy }}'/>
        </div><br/>

        <div>
          <label  for="dscale">{{__('georigami.perspective')}}</label>
          <input type="range" step="0.001" min="0" max="1" id="input-scale" name='dscale' value='{{ $dscale }}'/>
        </div><br/>

        <div>
          <label for="style">{{__('georigami.style')}}</label>
          <select id='styleswicther' name='style'>
<?php

foreach ($styleList as $key => $value) {
    echo "<option value='".$key."'";
    if ($style==$key)
        echo " selected";
    echo ">".$value."</option>".PHP_EOL;
}

?>
          </select>
        </div><br/>

        <input type='submit' value='{{__('georigami.update')}}' class='vs-update btn'/>
      </fieldset>
      </form>
      </div>
      <a id='profildownload' class='btn btn-primary'' href='{{ $bloc->get_url('download') }}?vscale={{ $vscale }}&face=N&dx={{ $dx }}&dy={{ $dy }}&dscale={{ $dscale }}&style={{ $style }}'>{{__('georigami.savedownload')}}</a>



  </div>

</div>

@endsection

@section('bloc_menu')
          <li class="active">
            <a href="">{{__('georigami.profil')}}</a>
          </li>
          <li class="">
            <a data-action="3d"  href="{{ $bloc->get_url('3d') }}?vscale={{$vscale}}&face={{$face}}">{{__('georigami.3dview')}}</a>
          </li>
          <li class="">
            <a data-action="print"  href="{{ $bloc->get_url('print') }}?vscale={{$vscale}}&face={{$face}}">{{__('georigami.printmodel')}}</a>
          </li>
@endsection

@section('script')
        <script>
            Georigami.profil={{ json_encode( $profil_data ) }};
            Georigami.svg_hscale={{ $svg_hscale }};
        </script>
@endsection
