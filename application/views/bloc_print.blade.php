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

      <form method='get' class='form form-horizontal'>

        <div class="control-group">
          <label class="control-label" for="vscale">{{__('georigami.verticalscale')}}</label>
          <div class="controls">
            <input class="vs-input" name='vscale' value="{{ $vscale }}" type="number" step="0.1" min="0.1">
          </div>
        </div>

        <div class="control-group">

          <div class="controls">
            <label class="checkbox">
              <input type='checkbox' name='hidecut' <?php if ($hidecut=='on') echo "checked='checked'" ?>/> {{__('georigami.hidecutlines')}}
            </label>
          </div>

          <div class="controls">
            <label class="checkbox">
              <input type='checkbox' name='hidetext' <?php if ($hidetext=='on') echo "checked='checked'" ?>/> {{__('georigami.hidetext')}}
            </label>
          </div>
        </div>

        <div class='control-group'>
            <label class="control-label" for="showhslice">{{__('georigami.horizontalslicesshow')}}</label>
            <div class="controls">
              <select name='showhslice'>
                <option value='none'<?php if ($showhslice=='none') echo ' selected'; ?>>{{__('georigami.none')}}</option>
                <option value='north'<?php if ($showhslice=='north') echo ' selected'; ?>>{{__('georigami.northside')}}</option>
                <option value='south'<?php if ($showhslice=='south') echo ' selected'; ?>>{{__('georigami.southside')}}</option>
              </select>
          </div>
        </div>

        <div class='control-group'>
            <label class="control-label" for="showvslice">{{__('georigami.verticalslicesshow')}}</label>
            <div class="controls">
              <select name='showvslice'>
                <option value='none'<?php if ($showvslice=='none') echo ' selected'; ?>>{{__('georigami.none')}}</option>
                <option value='west'<?php if ($showvslice=='west') echo ' selected'; ?>>{{__('georigami.westside')}}</option>
                <option value='east'<?php if ($showvslice=='east') echo ' selected'; ?>>{{__('georigami.eastside')}}</option>
              </select>
          </div>
        </div>

         <div class='control-group'>
          <div class="controls">
            <input type='submit' value='{{__('georigami.update')}}' class='vs-update btn'/>
          </div>
         </div>


      </form>
  </div>

<a id='profildownload' class='btn btn-primary pull-right span2' href='#' onclick="window.print();return false;">{{__('georigami.print')}}</a>

<div class='row'>
  <?php if ($showhslice!=='none') { ?>
  <div class='pull-left<?php
    if ($hidecut!='on') echo ' showcut';
    if ($hidetext!='on') echo ' showtext';
  ?>'>


    <!--h4>{{__('georigami.horizontal')}}</h4-->









    <?php
      $slicei=0;
      if ($showhslice=='north') $hsarray=$bloc->coords->h;
        else $hsarray=array_reverse($bloc->coords->h);
    ?>

  @foreach ($hsarray as $slice)
    <svg  width='{{$svgobj_width}}px' height='{{$svgobj_width*((($slice->m+0.12)*$svg_vscale)/( 1.02*$svg_hscale ))}}px'  class='svgprint pull-left' viewBox="{{ -0.01*$svg_hscale }} {{ -0.01*$svg_vscale }} {{ 1.02*$svg_hscale }} {{ ($slice->m+0.12)*$svg_vscale }}">
    <?php

      $coord='';
      $slicei++;

      if ($showhslice=='north') {
        $slicetitle=__('georigami.north').' '.$slicei;
        if ($slicei==1) $slicetitle=strtoupper(__('georigami.north'));
        foreach ($slice->c as $c) $coord.=($w-$c[0])*$svg_hscale.','.($slice->m-$c[1])*$svg_vscale.',';
      } else {
        $slicetitle=__('georigami.south').' '.$slicei;
        if ($slicei==1) $slicetitle=strtoupper(__('georigami.south'));
        $slice->c=array_reverse($slice->c);
        foreach ($slice->c as $c) $coord.=($c[0])*$svg_hscale.','.($slice->m-$c[1])*$svg_vscale.',';
      }






      echo "<polygon class='svgslice'  points='" . $coord . " ".(0*$svg_hscale).",".($slice->m+0.1)*$svg_vscale.",".($w*$svg_hscale).",".($slice->m+0.1)*$svg_vscale."'/>";

      $di=round( count($slice->c)/($bloc->vslices+1 ));
      for ($i=$di; $i <= $bloc->vslices*$di; $i=$i+$di) {
         if ($showhslice=='north') echo "<line class='svgcut'  x1='".(($w-$slice->c[$i][0])*$svg_hscale)."' x2='".(($w-$slice->c[$i][0])*$svg_hscale)."' y1='".($slice->m-($slice->c[$i][1]-0.1)/2)*$svg_vscale."' y2='".($slice->m+0.1)*$svg_vscale."'  />";
         else echo "<line class='svgcut'  x1='".(($slice->c[$i][0])*$svg_hscale)."' x2='".(($slice->c[$i][0])*$svg_hscale)."' y1='".($slice->m-($slice->c[$i][1]-0.1)/2)*$svg_vscale."' y2='".($slice->m+0.1)*$svg_vscale."'  />";
      }

    ?>
      <text x="0" y="0" transform="translate( {{0.003*$svg_hscale}}, {{($slice->m+0.09)*$svg_vscale}}), rotate(90)" text-anchor="end"><?=$slicetitle?></text>
    </svg>
  @endforeach
  </div>

   <?php }

   if ($showvslice!=='none') { ?>

  <div class='pull-left<?php
    if ($hidecut!='on') echo ' showcut';
    if ($hidetext!='on') echo ' showtext';
  ?>'>
  <!--h4>{{__('georigami.vertical')}}</h4-->










    <?php

    $slicei=0;
     if ($showvslice=='east') $vsarray=$bloc->coords->v;
        else $vsarray=array_reverse($bloc->coords->v);

    ?>
  @foreach ($vsarray as $slice)
    <svg width='{{$svgobj_width}}px' height='{{$svgobj_width*((($slice->m+0.12)*$svg_vscale)/( 1.02*$svg_hscale ))}}px'  class='svgprint' viewBox="{{ -0.01*$svg_hscale }} {{ -0.01*$svg_vscale }} {{ 1.02*$svg_hscale }} {{ ($slice->m+0.12)*$svg_vscale }}">
    <?php

      $coord='';
      $slicei++;

      if ($showvslice=='east') {
        if ($slicei==1) $slicetitle=strtoupper(__('georigami.east'));
          else $slicetitle=__('georigami.east').' '.$slicei;
        foreach ($slice->c as $c) $coord.=($h-$c[0])*$svg_hscale.','.($slice->m-$c[1])*$svg_vscale.',';
      } else {
        if ($slicei==1) $slicetitle=strtoupper(__('georigami.west'));
          else $slicetitle=__('georigami.west').' '.$slicei;
        $slice->c=array_reverse($slice->c);
        foreach ($slice->c as $c) $coord.=($c[0])*$svg_hscale.','.($slice->m-$c[1])*$svg_vscale.',';
      }



      echo "<polygon class='svgslice'  points='" . $coord . " ".(0*$svg_hscale).",".($slice->m+0.1)*$svg_vscale.",".($h*$svg_hscale).",".($slice->m+0.1)*$svg_vscale."'/>";

      $di=round( count($slice->c)/($bloc->hslices+1 ));
      for ($i=$di; $i <= $bloc->hslices*$di; $i=$i+$di) {
         if ($showvslice=='west') echo "<line class='svgcut'  x1='".(($slice->c[$i][0])*$svg_hscale)."' x2='".(($slice->c[$i][0])*$svg_hscale)."' y1='".($slice->m-($slice->c[$i][1]-0.1)/2)*$svg_vscale."' y2='".($slice->m-($slice->c[$i][1]))*$svg_vscale."'  />";
                             else echo "<line class='svgcut'  x1='".(($h-$slice->c[$i][0])*$svg_hscale)."' x2='".(($h-$slice->c[$i][0])*$svg_hscale)."' y1='".($slice->m-($slice->c[$i][1]-0.1)/2)*$svg_vscale."' y2='".($slice->m-($slice->c[$i][1]))*$svg_vscale."'  />";
      }

    ?>
       <text x="0" y="0" transform="translate( {{0.003*$svg_hscale}}, {{($slice->m+0.09)*$svg_vscale}}), rotate(90)" text-anchor="end"><?=$slicetitle?></text>
    </svg>
  @endforeach
  </div>
<?php }  ?>

</div>

@endsection

@section('bloc_menu')
          <li class=""><a data-action="profil"  href="{{ $bloc->get_url('profil') }}?vscale={{$vscale}}&amp;face={{$face}}">{{__('georigami.profil')}}</a></li>
          <li class=""><a data-action="3d"  href="{{ $bloc->get_url('3d') }}?vscale={{$vscale}}&amp;face={{$face}}">{{__('georigami.3dview')}}</a></li>
          <li class="active"><a href="#">{{__('georigami.printmodel')}}</a></li>
@endsection
