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

// Function by an anonymous user on php.net:  http://php.net/manual/en/function.is-array.php
function is_assoc($array) 
{
    return (is_array($array) && 0 !== count(array_diff_key($array,array_keys(array_keys($array)))));
}

?>