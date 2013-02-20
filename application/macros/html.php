<?php


HTML::macro('showcount', function($val)
{   
	if ($val<2) return '';
	else return ' <span class="count">X'.$val.'<span>';	
});



HTML::macro('SVG_header', function()
{   
	$html='<?xml version="1.0" encoding="utf-8"?>'.PHP_EOL
	.'<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">'.PHP_EOL;
	
	return $html;	
});

?>