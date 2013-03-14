<?php echo HTML::SVG_header() ?>
<svg version="1.1" id="Calque_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
   width="100%" height="100%" viewBox="-0.1 -0.1 1.2 <?php echo max($max+0.2,0.5); ?>" 
   enable-background="-0.1 -0.1 1.2 <?php echo max($max+0.2,0.5); ?>" xml:space="preserve" >

<rect x='-100' y='-100' width='300' height='300' fill='white' />
	



 <defs>
      <linearGradient id="greygradient" x1="0%" x2="0%" y1="0" y2="100%">
        <stop offset="0%" stop-color="<?=$color?>" stop-opacity="1" />
        <stop offset="100%" stop-color="#fff" stop-opacity="1" />
      </linearGradient>
    </defs>


<?php    






$maxPoints=40;

foreach ($coords as $slice) {

  $div=floor( count($slice->c) / $maxPoints )+1;
	$coord='';

 // print_r($div);

	foreach ($slice->c as $k=>$c) 
    if ($k%$div==0) $coord.=($c[0]+(0.5-$dim/2)).','.($max-$c[1]).',';

  $coord.=($c[0]+(0.5-$dim/2)).','.($max-$c[1]).',';

	echo "<g>
        <polygon  points='".$coord . (0.5+$dim/2).",0,".(0.5+$dim/2).",".($max+0.1).",".(0.5-$dim/2).",".($max+0.1).",".(0.5-$dim/2).",0' style='fill:url(#greygradient);stroke:none' />
	      <polyline points='".$coord."' style='fill:none; stroke:black;stroke-width:0.002' />
        </g>
	      ";
}
?>

 <rect x='-100' y='-100' width='100' height='300' fill='white' />
 <rect x='1' y='-100' width='100' height='300' fill='white' />
 <rect x='-100' y='<?php echo $max+0.1; ?>' width='300' height='300' fill='white' />


</svg>
  
