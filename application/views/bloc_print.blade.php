@layout('bloc_layout')





@section('title')
Georigami - {{ $bloc->location->name }} (bloc n° {{$bloc->id }})@endsection


@section('bodyclass')
print@endsection



@section('bloc_content')







<?php 

  $svg_hscale= 100;
  $svg_vscale= $vscale * $svg_hscale;

  $w= $bloc->width  / max( $bloc->width, $bloc->height );
  $h= $bloc->height / max( $bloc->width, $bloc->height );
?>



      <form method='get' class='form-inline'>
        vertical scale <input class="vs-input span1" name='vscale' value="{{ $vscale }}" type="number" step="0.1" min="0.1">
        <input type='submit' value='update' class='vs-update btn'/>
      </form>

  <h4>horizontal</h4>
  <div class='row'>

  @foreach ($bloc->coords->h as $slice)
  <div class='span12'>
    <svg  class='svgprint' viewBox="{{ -0.01*$svg_hscale }} {{ -0.01*$svg_vscale }} {{ 1.02*$svg_hscale }} {{ ($slice->m+0.12)*$svg_vscale }}">
    <?php


      $coord='';
      foreach ($slice->c as $c) $coord.=($w-$c[0])*$svg_hscale.','.($slice->m-$c[1])*$svg_vscale.',';
      echo "<polygon class='svgslice'  points='" . $coord . " ".(0*$svg_hscale).",".($slice->m+0.1)*$svg_vscale.",".($w*$svg_hscale).",".($slice->m+0.1)*$svg_vscale."'/>";

      $di=round( count($slice->c)/($bloc->vslices+2 ));
      for ($i=$di; $i <= $bloc->vslices*$di; $i=$i+$di) { 
         echo "<line class='svgcut'  x1='".($w-$slice->c[$i][0])*$svg_hscale."' x2='".($w-$slice->c[$i][0])*$svg_hscale."' y1='".($slice->m-($slice->c[$i][1]-0.1)/2)*$svg_vscale."' y2='".($slice->m+0.1)*$svg_vscale."'  />";
      }

    ?>
      <text x="0" y="0" transform="translate( {{0.003*$svg_hscale}}, {{($slice->m+0.04)*$svg_vscale}}), rotate(90)">{{$slice->t}}</text>
    </svg>
  </div>

  @endforeach

</div>



  <h4>vertical</h4>
  <div class='row'>

    <?php $reverse_vslices=array_reverse($bloc->coords->v)  ?>
  @foreach ($reverse_vslices as $slice)
  <div class='span12'>
    <svg  class='svgprint' viewBox="{{ -0.01*$svg_hscale }} {{ -0.01*$svg_vscale }} {{ 1.02*$svg_hscale }} {{ ($slice->m+0.12)*$svg_vscale }}">
    <?php


      $coord='';
      foreach ($slice->c as $c) $coord.=($c[0])*$svg_hscale.','.($slice->m-$c[1])*$svg_vscale.',';
      echo "<polygon class='svgslice'  points='" . $coord . " ".($h*$svg_hscale).",".($slice->m+0.1)*$svg_vscale.",".(0*$svg_hscale).",".($slice->m+0.1)*$svg_vscale."'/>";

      $di=round( count($slice->c)/($bloc->hslices+2 ));
      for ($i=$di; $i <= $bloc->hslices*$di; $i=$i+$di) { 
         echo "<line class='svgcut'  x1='".($slice->c[$i][0])*$svg_hscale."' x2='".($slice->c[$i][0])*$svg_hscale."' y1='".($slice->m-($slice->c[$i][1]-0.1)/2)*$svg_vscale."' y2='".($slice->m-($slice->c[$i][1]))*$svg_vscale."'  />";
      }

    ?>
       <text x="0" y="0" transform="translate( {{0.003*$svg_hscale}}, {{($slice->m+0.04)*$svg_vscale}}), rotate(90)">{{$slice->t}}</text>
    </svg>
  </div>

  @endforeach





</div>







@endsection



@section('bloc_menu')
          <li class=""><a data-action="profil"  href="{{ $bloc->get_url('profil') }}?vscale={{$vscale}}&face={{$face}}">profil</a></li>
          <li class=""><a data-action="3d"  href="{{ $bloc->get_url('3d') }}?vscale={{$vscale}}&face={{$face}}">preview 3D</a></li>
          <li class="active"><a href="#">print</a></li>
@endsection
