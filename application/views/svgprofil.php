<?php echo '<?xml version="1.0" encoding="utf-8"?>'.PHP_EOL; ?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg version="1.1" id="Calque_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
   width="100%" height="100%" viewBox="-0.1 -0.1 1.2 <?php echo $data['max']+0.2; ?>" enable-background="-0.1 -0.1 1.2 <?php echo $data['max']+0.2; ?>" xml:space="preserve">



<?php
$e=0;
$de=1/count($data['coords']);

foreach ($data['coords'] as $slice) {
	$e= $e+$de;
	$coord='';
	foreach ($slice->c as $c) $coord.=($c[0]+(0.5-$data['dim']/2)).','.($data['max']-$c[1]).',';
	echo "<polygon  points='".$coord."1,0,1,1,0,1,0,0' style='fill:white;stroke:none' />
	      <polyline points='".$coord."' style='fill:none; stroke:black;stroke-width:".($data['strokewidth']*$e)."' />
	      ";
}
?>

 <rect x='-100' y='-100' width='100' height='300' fill='white' />
 <rect x='1' y='-100' width='100' height='300' fill='white' />
 <rect x='-100' y='<?php echo $data['max']+0.1; ?>' width='300' height='300' fill='white' />


</svg>
  
