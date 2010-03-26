<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('JSONifyAssocArr'))
{
	function JSONifyAssocArr($assocArr) // STRING
	{
		if (!isset($assocArr))
			return;
			
		$returnStr = "{";
		
		$keys = array_keys($assocArr);
		$values = array_values($assocArr);
		
		for ($i = 0; $i < count($keys); $i++)
		{
			$returnStr .= "\"" . $keys[$i] . "\"";
			$returnStr .= " : ";
			
			if (is_numeric($values[$i]))
			{
				$returnStr .= $values[$i];
			}
			else if (is_array($values[$i]))
			{
				if (is_assoc($values[$i]))
					$returnStr .= JSONifyAssocArr($values[$i]);
				else
					$returnStr .= arrayToString($values[$i]);
			}
			else if ($values[$i] === TRUE)
			{
				$returnStr .= "true";	
			}
			else if ($values[$i] === FALSE)
			{
				$returnStr .= "false";	
			}
			else
				$returnStr .= quotify($values[$i]);
			
			if ($i != count($keys) - 1)
				$returnStr .= ", ";
		}
		
		$returnStr .= "}";
		
		return $returnStr;
	}
}

if ( ! function_exists('arrayToString'))
{
	// TODO:  Incomplete.  This will convert everything into a string and should be changed to allow for different types.  Also may not work for associative works, not tested.
	function arrayToString($array)
	{
		$returnVal = "[";
		
		// Not sure if this if is the best way to go, but it works.
		if (is_assoc($array))
		{
			return JSONifyAssocArr($array);
		}
		
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

// Function by an anonymous user on php.net:  http://php.net/manual/en/function.is-array.php
function is_assoc($array) 
{
    return (is_array($array) && 0 !== count(array_diff_key($array,array_keys(array_keys($array)))));
}

?>