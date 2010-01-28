<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('strContains'))
{
	function strContains($string, $subStr)
	{
		return (stripos($string, $subStr) === FALSE);
	}	
}  ?>