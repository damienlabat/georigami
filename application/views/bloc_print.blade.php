@layout('bloc_layout')





@section('title')
Georigami - {{$data['bloc']['location']['name']}} (bloc n° {{$data['bloc']['id']}})@endsection


@section('bodyclass')
print@endsection



@section('bloc_content')







<?php 
  $hscale= 100;
  $vscale= $data['vscale'] * $hscale;

  $w= $data['bloc']['width']  / max( $data['bloc']['width'], $data['bloc']['height'] );
  $h= $data['bloc']['height'] / max( $data['bloc']['width'], $data['bloc']['height'] );
?>





  <h4>horizontal</h4>
  <div class='row'>

  @foreach ($data['bloc']['coords']->h as $slice)
  <div class='span12'>
    <svg  class='svgprint' viewBox="{{ -0.01*$hscale }} {{ -0.01*$vscale }} {{ 1.02*$hscale }} {{ ($slice->m+0.12)*$vscale }}">
    <?php


      $coord='';
      foreach ($slice->c as $c) $coord.=($w-$c[0])*$hscale.','.($slice->m-$c[1])*$vscale.',';
      echo "<polygon class='svgslice'  points='" . $coord . " ".(0*$hscale).",".($slice->m+0.1)*$vscale.",".($w*$hscale).",".($slice->m+0.1)*$vscale."'/>";

      $di=round( count($slice->c)/($data['bloc']['vslices']+2 ));
      for ($i=$di; $i <= count($slice->c)-$di; $i=$i+$di) { 
         echo "<line class='svgcut'  x1='".($w-$slice->c[$i][0])*$hscale."' x2='".($w-$slice->c[$i][0])*$hscale."' y1='".($slice->m-($slice->c[$i][1]-0.1)/2)*$vscale."' y2='".($slice->m+0.1)*$vscale."'  />";
      }

    ?>
      <text x="{{ 0.01*$hscale }}" y="{{ ($slice->m+0.01)*$vscale }}" transform="rotate(90,{{0.01*$hscale}},{{ ($slice->m+0.01)*$vscale }})">{{$slice->t}}</text>
    </svg>
  </div>

  @endforeach

</div>



  <h4>vertical</h4>
  <div class='row'>

    <?php $reverse_vslices=array_reverse($data['bloc']['coords']->v)  ?>
  @foreach ($reverse_vslices as $slice)
  <div class='span12'>
    <svg  class='svgprint' viewBox="{{ -0.01*$hscale }} {{ -0.01*$vscale }} {{ 1.02*$hscale }} {{ ($slice->m+0.12)*$vscale }}">
    <?php


      $coord='';
      foreach ($slice->c as $c) $coord.=($c[0])*$hscale.','.($slice->m-$c[1])*$vscale.',';
      echo "<polygon class='svgslice'  points='" . $coord . " ".($h*$hscale).",".($slice->m+0.1)*$vscale.",".(0*$hscale).",".($slice->m+0.1)*$vscale."'/>";

      $di=round( count($slice->c)/($data['bloc']['hslices']+2 ));
      for ($i=$di; $i <= count($slice->c)-$di; $i=$i+$di) { 
         echo "<line class='svgcut'  x1='".($slice->c[$i][0])*$hscale."' x2='".($slice->c[$i][0])*$hscale."' y1='".($slice->m-($slice->c[$i][1]-0.1)/2)*$vscale."' y2='".($slice->m-($slice->c[$i][1]))*$vscale."'  />";
      }

    ?>
       <text x="{{ 0.01*$hscale }}" y="{{ ($slice->m+0.01)*$vscale }}" transform="rotate(90,{{0.01*$hscale}},{{ ($slice->m+0.01)*$vscale }})">{{$slice->t}}</text>
    </svg>
  </div>

  @endforeach





</div>





<br/>
      <form method='get' class='form-inline'>
        vertical scale <input class="vs-input span1" name='vscale' value="{{$data['vscale']}}" type="number" step="0.1" min="0.1">
        <input type='submit' value='update' class='btn'/>
      </form>


@endsection



@section('bloc_menu')
          <li class=""><a href="{{URL::to_route('getplus', array($data['bloc']['id'], 'profil')) }}?vscale={{$data['vscale']}}&face={{$data['face']}}">profil</a></li>
          <li class=""><a href="{{URL::to_route('getplus', array($data['bloc']['id'], '3d')) }}?vscale={{$data['vscale']}}&face={{$data['face']}}">preview 3D</a></li>
          <li class="active"><a href="#">print</a></li>
@endsection
