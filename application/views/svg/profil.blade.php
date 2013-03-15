<?php
echo $header;

$slCount= count($coords);

// GET VIEW BOX
$viewbox=array( 'left'=>1000, 'right'=>0, 'top'=>1000, 'bottom'=>0 );

foreach ($coords as $k=>$slice) {
    $SCALE= 1-$dscale*((($slCount-1)-$k)/$slCount);

    $bottom= ($k-$slCount/2) *$dy + $max/2*$svg_vscale;
    $top= 	 ($k-$slCount/2) *$dy + $max/2*$svg_vscale - $slice->m*$svg_vscale*$SCALE;

    $left= 	 ($k-$slCount/2) *$dx + $dim/2*$svg_hscale - $dim/2*$svg_hscale*$SCALE;
    $right=  ($k-$slCount/2) *$dx + $dim/2*$svg_hscale + $dim/2*$svg_hscale*$SCALE;

    if ( $top < $viewbox['top'] )   	$viewbox['top']=$top;
    if ( $bottom > $viewbox['bottom'] ) $viewbox['bottom']=$bottom;

    if ( $left < $viewbox['left'] )   $viewbox['left']=$left;
    if ( $right > $viewbox['right'] ) $viewbox['right']=$right;
}

 //marge 5%
    $width=  $viewbox['right']-$viewbox['left'];
    $height= $viewbox['bottom']-$viewbox['top'];

    $viewbox['left']  -= $width*0.05;
    $viewbox['right'] += $width*0.05;

    $viewbox['top']  -= $height*0.05;
    $viewbox['bottom'] += $height*0.05;

$VB= $viewbox['left'].' '.$viewbox['top'].' '.($viewbox['right']-$viewbox['left']).' '.($viewbox['bottom']-$viewbox['top']);

?><svg id='svgprofil' class='{{$style}}' viewBox="<?= $VB ?>" >
<rect class='rectbackground' x='-1000' y='-1000' width='5000' height='5000'/>
@render('svg.defs')
<?php

foreach ($coords as $k=>$slice) {
  $coord='';
  $X=($k-$slCount/2) *$dx;
  $Y=($k-$slCount/2) *$dy;
  $SCALE= 1-$dscale*((($slCount-1)-$k)/$slCount);

  foreach ($slice->c as $m=>$c) $coord.=($c[0]-($dim/2))*$svg_hscale.','.(-$c[1])*$svg_vscale.',';

  echo "
      <g id='slice".$k."' class='gslice' transform='translate(".$X.",".$Y.")'>
          <g transform='translate(".($dim/2)*$svg_hscale.",".$max*$svg_vscale.")'>
              <g class='gscale' transform='scale(".$SCALE.")'>
                <polygon  points='".$coord . ($dim/2)*$svg_hscale.",".(0)*$svg_vscale.",".-($dim/2)*$svg_hscale.",".(0)*$svg_vscale."' fill='white'/>
                <polyline class='topline' points='".$coord."'/>
            </g>
        </g>
    </g>".PHP_EOL;
}

?>

    </svg>
