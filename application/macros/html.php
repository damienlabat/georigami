<?php


HTML::macro('showcount', function($val)
{   
	if ($val<2) return '';
	else return ' <span class="count">X'.$val.'<span>';	
});

?>