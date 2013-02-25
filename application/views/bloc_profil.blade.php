@layout('bloc_layout')


@section('bodyclass')
profil@endsection


@section('title')
Georigami - {{ $bloc->location->name }} (bloc n° {{ $bloc->id }})
@endsection





@section('bloc_content')


  <!------------>
<?php

  $svg_hscale= 100;
  $svg_vscale= $vscale * $svg_hscale;

?>

    <!--img src="{{URL::to_route('svg', array($bloc->id,$face)) }}" title="North face"-->

   <svg viewBox="{{ -0.01*$svg_hscale }} {{ -0.01*$svg_vscale }} {{ 1.02*$svg_hscale }} {{ ($max+0.12)*$svg_vscale }}" >

   <defs>
      <linearGradient id="glow" x1="0%" x2="0%" y1="0" y2="100%">
        <stop offset="0%" stop-color="#AAA" stop-opacity="1" />
        <stop offset="100%" stop-color="#fff" stop-opacity="1" />
      </linearGradient>
    </defs>


<?php



foreach ($coords as $slice) {
  $coord='';
  foreach ($slice->c as $c) $coord.=($c[0]+(0.5-$dim/2))*$svg_hscale.','.($max-$c[1])*$svg_vscale.',';
  echo "<polygon  points='".$coord . (0.5+$dim/2)*$svg_hscale.",0,".(0.5+$dim/2)*$svg_hscale.",".($max+0.1)*$svg_vscale.",".(0.5-$dim/2)*$svg_hscale.",".($max+0.1)*$svg_vscale.",".(0.5-$dim/2)*$svg_hscale.",0' style='fill:url(#glow);stroke:none' />
        <polyline points='".$coord."' style='fill:none; stroke:black;stroke-width:".$strokewidth."' />
        ";
}
?>

 <!--rect x='-100' y='-100' width='100' height='300' fill='white' />
 <rect x='1' y='-100' width='100' height='300' fill='white' />
 <rect x='-100' y='<?php echo $max+0.1; ?>' width='300' height='300' fill='white' /-->


</svg>


    <!------------>
 <div class='row'>   


  <div class="span6">
      <form method='get' class='form-inline'>
        <input type='hidden' name='face' value='{{$face}}'/>
        vertical scale <input class="vs-input span1" name='vscale' value="{{$vscale}}" type="number" step="0.1" min="0.1">
        <input type='submit' value='update' class='btn'/>
      </form>
  </div>



  <div class="btn-group span6">
    <a href='?vscale={{$vscale}}&face=N' class="btn@if ($face=='N') active@endif">North face</a>
    <a href='?vscale={{$vscale}}&face=W' class="btn@if ($face=='W') active@endif">West face</a>
    <a href='?vscale={{$vscale}}&face=S' class="btn@if ($face=='S') active@endif">South face</a>
    <a href='?vscale={{$vscale}}&face=E' class="btn@if ($face=='E') active@endif">East face</a>
  </div>

</div>



@endsection



@section('bloc_menu')          
          <li class="active"><a href="">profil</a></li>
          <li class=""><a href="{{ $bloc->get_url('3d') }}?vscale={{$vscale}}&face={{$face}}">preview 3D</a></li>
          <li class=""><a href="{{ $bloc->get_url('print') }}?vscale={{$vscale}}&face={{$face}}">print</a></li>
@endsection   
