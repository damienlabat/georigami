<?php echo '<?xml version="1.0" encoding="utf-8"?>'.PHP_EOL; ?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg version="1.1" id="Calque_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
   width="100%" height="100%" viewBox="-0.1 -0.1 1.2 <?php echo max($data['max']+0.2,0.5); ?>" enable-background="-0.1 -0.1 1.2 <?php echo max($data['max']+0.2,0.5); ?>" xml:space="preserve">

	 <defs>
      <linearGradient id="glow" x1="0%" x2="0%" y1="0" y2="100%">
        <stop offset="0%" stop-color="#AAA" stop-opacity="1" />
        <stop offset="100%" stop-color="#fff" stop-opacity="1" />
      </linearGradient>
    </defs>


<?php
$e=0;
$de=1/count($data['coords']);

foreach ($data['coords'] as $slice) {
	$e= $e+$de;
	$coord='';
	foreach ($slice->c as $c) $coord.=($c[0]+(0.5-$data['dim']/2)).','.($data['max']-$c[1]).',';
	echo "<polygon  points='".$coord . (0.5+$data['dim']/2).",0,".(0.5+$data['dim']/2).",".($data['max']+0.1).",".(0.5-$data['dim']/2).",".($data['max']+0.1).",".(0.5-$data['dim']/2).",0' style='fill:url(#glow);stroke:none' />
	      <polyline points='".$coord."' style='fill:none; stroke:black;stroke-width:".($data['strokewidth']*$e)."' />
	      ";
}
?>

 <rect x='-100' y='-100' width='100' height='300' fill='white' />
 <rect x='1' y='-100' width='100' height='300' fill='white' />
 <rect x='-100' y='<?php echo $data['max']+0.1; ?>' width='300' height='300' fill='white' />


</svg>
  
