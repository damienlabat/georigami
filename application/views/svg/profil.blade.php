<?php
if ($header) echo HTML::SVG_header();
$slCount= count($coords);

// GET VIEW BOX
$viewbox=array( 'left'=>1000, 'right'=>0, 'top'=>1000, 'bottom'=>0 );


foreach ($coords as $k=>$slice) {

    $SCALE= 1-$dscale*((($slCount-1)-$k)/$slCount);

    $left=   ($k-$slCount/2) *$dx + $dim/2*$svg_hscale - $dim/2*$svg_hscale*$SCALE;
    $right=  ($k-$slCount/2) *$dx + $dim/2*$svg_hscale + $dim/2*$svg_hscale*$SCALE;

    $bottom= ($k-$slCount/2) *$dy + $max*$svg_hscale;
    $top= 	 ($k-$slCount/2) *$dy + $max*$svg_hscale - $slice->m*$svg_vscale*$SCALE;

    

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
    $viewbox['bottom'] += $height*0.1;

    /*$rect_height=max($svg_vscale*$max/10, $svg_hscale*$dim/10);  // TODO! tester*/
    $rect_height=$svg_vscale*$max/10;

$VB= $viewbox['left'].' '.$viewbox['top'].' '.($viewbox['right']-$viewbox['left']).' '.($viewbox['bottom']-$viewbox['top']);

?><svg id='svgprofil' class='{{$style}}' version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
   width="100%" height="100%" viewBox="<?= $VB ?>" shape-rendering="geometricPrecision" xml:space="preserve"<?php
   if ($crop) echo '  preserveAspectRatio="xMidYMid slice"';
?>>

@include('svg.style')
<rect class='rectbackground' x='-1000' y='-1000' width='5000' height='5000'/>
@include('svg.defs')
<?php

foreach ($coords as $k=>$slice) {
  $coord='';
  $X=($k-$slCount/2) *$dx;
  $Y=($k-$slCount/2) *$dy;
  $SCALE= 1-$dscale*((($slCount-1)-$k)/$slCount);

  $di=1;
  if ($reduce) $di=floor( count($slice->c)/50 )+1;

  foreach ($slice->c as $m=>$c)
    if ($m%$di==0) $coord.=($c[0]-($dim/2))*$svg_hscale.','.(-$c[1])*$svg_vscale.',';

  echo "
      <g id='slice".$k."' class='gslice";
      if ($k%2==1) echo " odd";
      echo "' transform='translate(".$X.",".$Y.")'>
          <g transform='translate(".($dim/2)*$svg_hscale.",".$max*$svg_hscale.")'>
              <g class='gscale' transform='scale(".$SCALE.")'>
                <!--rect x='".-($svg_hscale*$dim/2)."' y='".-$svg_vscale*0.002."' width='".$svg_hscale*$dim."' height='".$rect_height."'/-->
                <polygon  points='".$coord . ($dim/2)*$svg_hscale.",".(0)*$svg_vscale.",".-($dim/2)*$svg_hscale.",".(0)*$svg_vscale."' fill='white'/>
                <polyline class='topline' points='".$coord."'/>

            </g>
        </g>
    </g>".PHP_EOL;
}

?>
    </svg>
