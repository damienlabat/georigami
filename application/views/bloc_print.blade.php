@layout('bloc_layout')

@section('title')
Georigami - {{ $bloc->location->name }} (bloc n° {{$bloc->id }})@endsection

@section('bodyclass')
print@endsection

@section('bloc_content')

<?php
  $svgobj_width=650;

  $svg_hscale= 100;
  $svg_vscale= $vscale * $svg_hscale;

  $w= $bloc->width  / max( $bloc->width, $bloc->height );
  $h= $bloc->height / max( $bloc->width, $bloc->height );
?>
      <a id='profildownload' class='btn pull-right' href='#' onclick="window.print();return false;">print</a>
      <form method='get' class='form-inline'>
        <fieldset>
          vertical scale <input class="vs-input" name='vscale' value="{{ $vscale }}" type="number" step="0.1" min="0.1">
          <label class="checkbox">
            <input type='checkbox' name='hidecut' <?php if ($hidecut=='on') echo "checked='checked'" ?>/> hide cut lines
          </label>
          <label class="checkbox">
            <input type='checkbox' name='hidetext' <?php if ($hidetext=='on') echo "checked='checked'" ?>/> hide text
          </label>
          <input type='submit' value='update' class='vs-update btn'/>
        </fieldset>
      </form>


  <div class='pull-left span12<?php
    if ($hidecut!='on') echo ' showcut';
    if ($hidetext!='on') echo ' showtext';
  ?>'>
  <h4>horizontal</h4>

  @foreach ($bloc->coords->h as $slice)
    <svg  width='{{$svgobj_width}}px' height='{{$svgobj_width*((($slice->m+0.12)*$svg_vscale)/( 1.02*$svg_hscale ))}}px'  class='svgprint pull-left' viewBox="{{ -0.01*$svg_hscale }} {{ -0.01*$svg_vscale }} {{ 1.02*$svg_hscale }} {{ ($slice->m+0.12)*$svg_vscale }}">
    <?php

      $coord='';
      foreach ($slice->c as $c) $coord.=($w-$c[0])*$svg_hscale.','.($slice->m-$c[1])*$svg_vscale.',';
      echo "<polygon class='svgslice'  points='" . $coord . " ".(0*$svg_hscale).",".($slice->m+0.1)*$svg_vscale.",".($w*$svg_hscale).",".($slice->m+0.1)*$svg_vscale."'/>";

      $di=round( count($slice->c)/($bloc->vslices+1 ));
      for ($i=$di; $i <= $bloc->vslices*$di; $i=$i+$di) {
         echo "<line class='svgcut'  x1='".(($w-$slice->c[$i][0])*$svg_hscale-0.1)."' x2='".(($w-$slice->c[$i][0])*$svg_hscale-0.1)."' y1='".($slice->m-($slice->c[$i][1]-0.1)/2)*$svg_vscale."' y2='".($slice->m+0.1)*$svg_vscale."'  />";
         echo "<line class='svgcut'  x1='".(($w-$slice->c[$i][0])*$svg_hscale+0.1)."' x2='".(($w-$slice->c[$i][0])*$svg_hscale+0.1)."' y1='".($slice->m-($slice->c[$i][1]-0.1)/2)*$svg_vscale."' y2='".($slice->m+0.1)*$svg_vscale."'  />";
      }

    ?>
      <text x="0" y="0" transform="translate( {{0.003*$svg_hscale}}, {{($slice->m+0.04)*$svg_vscale}}), rotate(90)">{{$slice->t}}</text>
    </svg>
  @endforeach
  </div>

  <div class='pull-left span12<?php
    if ($hidecut!='on') echo ' showcut';
    if ($hidetext!='on') echo ' showtext';
  ?>'>
  <h4>vertical</h4>

    <?php $reverse_vslices=array_reverse($bloc->coords->v)  ?>
  @foreach ($reverse_vslices as $slice)
    <svg width='{{$svgobj_width}}px' height='{{$svgobj_width*((($slice->m+0.12)*$svg_vscale)/( 1.02*$svg_hscale ))}}px'  class='svgprint' viewBox="{{ -0.01*$svg_hscale }} {{ -0.01*$svg_vscale }} {{ 1.02*$svg_hscale }} {{ ($slice->m+0.12)*$svg_vscale }}">
    <?php

      $coord='';
      foreach ($slice->c as $c) $coord.=($c[0])*$svg_hscale.','.($slice->m-$c[1])*$svg_vscale.',';
      echo "<polygon class='svgslice'  points='" . $coord . " ".($h*$svg_hscale).",".($slice->m+0.1)*$svg_vscale.",".(0*$svg_hscale).",".($slice->m+0.1)*$svg_vscale."'/>";

      $di=round( count($slice->c)/($bloc->hslices+1 ));
      for ($i=$di; $i <= $bloc->hslices*$di; $i=$i+$di) {
         echo "<line class='svgcut'  x1='".(($slice->c[$i][0])*$svg_hscale-0.1)."' x2='".(($slice->c[$i][0])*$svg_hscale-0.1)."' y1='".($slice->m-($slice->c[$i][1]-0.1)/2)*$svg_vscale."' y2='".($slice->m-($slice->c[$i][1]))*$svg_vscale."'  />";
         echo "<line class='svgcut'  x1='".(($slice->c[$i][0])*$svg_hscale+0.1)."' x2='".(($slice->c[$i][0])*$svg_hscale+0.1)."' y1='".($slice->m-($slice->c[$i][1]-0.1)/2)*$svg_vscale."' y2='".($slice->m-($slice->c[$i][1]))*$svg_vscale."'  />";
      }

    ?>
       <text x="0" y="0" transform="translate( {{0.003*$svg_hscale}}, {{($slice->m+0.04)*$svg_vscale}}), rotate(90)">{{$slice->t}}</text>
    </svg>
  @endforeach
  </div>

@endsection

@section('bloc_menu')
          <li class=""><a data-action="profil"  href="{{ $bloc->get_url('profil') }}?vscale={{$vscale}}&face={{$face}}">profil</a></li>
          <li class=""><a data-action="3d"  href="{{ $bloc->get_url('3d') }}?vscale={{$vscale}}&face={{$face}}">preview 3D</a></li>
          <li class="active"><a href="#">print</a></li>
@endsection
