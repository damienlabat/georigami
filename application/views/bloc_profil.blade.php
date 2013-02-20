@layout('bloc_layout')





@section('title')
Georigami - {{$data['bloc']['location']['name']}} (bloc n° {{$data['bloc']['id']}})
@endsection





@section('bloc_content')


  <!------------>
<?php

  $hscale= 100;
  $vscale= $data['vscale'] * $hscale;

?>

    <!--img src="{{URL::to_route('svg', array($data['bloc']['id'],$data['face'])) }}" title="North face"-->

   <svg viewBox="{{ -0.01*$hscale }} {{ -0.01*$vscale }} {{ 1.02*$hscale }} {{ ($data['max']+0.12)*$vscale }}" >

   <defs>
      <linearGradient id="glow" x1="0%" x2="0%" y1="0" y2="100%">
        <stop offset="0%" stop-color="#AAA" stop-opacity="1" />
        <stop offset="100%" stop-color="#fff" stop-opacity="1" />
      </linearGradient>
    </defs>


<?php



foreach ($data['coords'] as $slice) {
  $coord='';
  foreach ($slice->c as $c) $coord.=($c[0]+(0.5-$data['dim']/2))*$hscale.','.($data['max']-$c[1])*$vscale.',';
  echo "<polygon  points='".$coord . (0.5+$data['dim']/2)*$hscale.",0,".(0.5+$data['dim']/2)*$hscale.",".($data['max']+0.1)*$vscale.",".(0.5-$data['dim']/2)*$hscale.",".($data['max']+0.1)*$vscale.",".(0.5-$data['dim']/2)*$hscale.",0' style='fill:url(#glow);stroke:none' />
        <polyline points='".$coord."' style='fill:none; stroke:black;stroke-width:".$data['strokewidth']."' />
        ";
}
?>

 <!--rect x='-100' y='-100' width='100' height='300' fill='white' />
 <rect x='1' y='-100' width='100' height='300' fill='white' />
 <rect x='-100' y='<?php echo $data['max']+0.1; ?>' width='300' height='300' fill='white' /-->


</svg>


    <!------------>
 <div class='row'>   


  <div class="span6">
      <form method='get' class='form-inline'>
        <input type='hidden' name='face' value='{{$data['face']}}'/>
        vertical scale <input class="vs-input span1" name='vscale' value="{{$data['vscale']}}" type="number" step="0.1" min="0.1">
        <input type='submit' value='update' class='btn'/>
      </form>
  </div>



  <div class="btn-group span6">
    <a href='?vscale={{$data['vscale']}}&face=N' class="btn@if ($data['face']=='N') active@endif">North face</a>
    <a href='?vscale={{$data['vscale']}}&face=W' class="btn@if ($data['face']=='W') active@endif">West face</a>
    <a href='?vscale={{$data['vscale']}}&face=S' class="btn@if ($data['face']=='S') active@endif">South face</a>
    <a href='?vscale={{$data['vscale']}}&face=E' class="btn@if ($data['face']=='E') active@endif">East face</a>
  </div>

</div>



@endsection



@section('bloc_menu')          
          <li class="active"><a href="">profil</a></li>
          <li class=""><a href="{{URL::to_route('getplus', array($data['bloc']['id'], '3d')) }}?vscale={{$data['vscale']}}&face={{$data['face']}}">preview 3D</a></li>
          <li class=""><a href="{{URL::to_route('getplus', array($data['bloc']['id'], 'print')) }}?vscale={{$data['vscale']}}&face={{$data['face']}}">print</a></li>
@endsection





          

@section('script')         

        <script>
        </script>

@endsection      
