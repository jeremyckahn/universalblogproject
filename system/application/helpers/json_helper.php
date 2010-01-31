<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('JSONifyAssocArr'))
{
	// SHIT!  THIS DOESN'T WORK AT ALL!!
	function JSONifyAssocArr($assocArr) // STRING
	{
		$returnStr = "{";
		// Do some crazy voodoo here
		
		$keys = array_keys($assocArr);
		$values = array_values($assocArr);
		
		for ($i = 0; $i < count($keys); $i++)
		{
			$returnStr = $returnStr + $keys[$i];
			$returnStr = $returnStr + " : ";
			$returnStr = $returnStr + "\"" + $values[$i] + "\"";
			
//			error_log($returnStr);
		}
		
		$returnStr = $returnStr + "}";
		
		return $returnStr;
	}	
}  

?>