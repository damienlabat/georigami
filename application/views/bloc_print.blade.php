@layout('bloc_layout')


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
  <div class='blocmenu'>
      <a id='profildownload' class='btn btn-primary pull-right span2' href='#' onclick="window.print();return false;">{{__('georigami.print')}}</a>
      <form method='get' class='form-inline'>
        <fieldset>
          {{__('georigami.verticalscale')}} <input class="vs-input" name='vscale' value="{{ $vscale }}" type="number" step="0.1" min="0.1">
          <label class="checkbox">
            <input type='checkbox' name='hidecut' <?php if ($hidecut=='on') echo "checked='checked'" ?>/> {{__('georigami.hidecutlines')}}
          </label>
          <label class="checkbox">
            <input type='checkbox' name='hidetext' <?php if ($hidetext=='on') echo "checked='checked'" ?>/> {{__('georigami.hidetext')}}
          </label>
          &nbsp;<input type='submit' value='{{__('georigami.update')}}' class='vs-update btn'/>
        </fieldset>
      </form>
  </div>


  <div class='pull-left span12<?php
    if ($hidecut!='on') echo ' showcut';
    if ($hidetext!='on') echo ' showtext';
  ?>'>
  <h4>{{__('georigami.horizontal')}}</h4>

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
  <h4>{{__('georigami.vertical')}}</h4>

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
          <li class=""><a data-action="profil"  href="{{ $bloc->get_url('profil') }}?vscale={{$vscale}}&face={{$face}}">{{__('georigami.profil')}}</a></li>
          <li class=""><a data-action="3d"  href="{{ $bloc->get_url('3d') }}?vscale={{$vscale}}&face={{$face}}">{{__('georigami.3dview')}}</a></li>
          <li class="active"><a href="#">{{__('georigami.printmodel')}}</a></li>
@endsection
