<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('strContains'))
{
	function strContains($haystack, $needle)
	{
		return ((stripos($haystack, $needle) === FALSE) == 1) ? TRUE : FALSE;
	}	
}

if ( ! function_exists('quotify'))
{
	function quotify($string)
	{
		return "\"" . $string . "\"";
	}	
}

?>