<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('strContains'))
{
	function strContains($string, $subStr)
	{
		return (stripos($string, $subStr) === FALSE);
	}	
}

if ( ! function_exists('arrayToString'))
{
	// TODO:  Incomplete.  This will convert everything into a string and should be changed to allow for different types.  Also may not work for associative works, not tested.
	function arrayToString($array)
	{
		$returnVal = "[";
		
		for ($i = 0; $i < count($array); $i++)
		{
			if (is_array($array[$i]))
				$returnVal .= arrayToString($array[$i]);
			else
				$returnVal .= quotify($array[$i]);
			
			if ($i < count($array) - 1)
				$returnVal .= ", ";
		}
		
		$returnVal .= "]";
		
		return $returnVal;
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