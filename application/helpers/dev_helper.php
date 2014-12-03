<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * Display an array for easy reading.
 *
 * uses print_r and pre to nicely display an array or multiple arrays
 * for easy reading.
 *
 */	
function yell()
{
 print '<pre style="text-align:left">';
 $total  = func_num_args();
	$arrays = func_get_args();
	for ($i = 0; $i < $total; $i++) {
		print_r($arrays[$i]);
	} 
 print '</pre>';
}
	
/* End of file dev_helper.php */
/* Location: ./application/helpers/dev_helper.php */