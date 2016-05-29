<?php
/**
 * -------------------------------------------------------------------------------------------------
 *
 * -------------------------------------------------------------------------------------------------
 * @param  [type] $d [description]
 * @return [type]    [description]
 * -------------------------------------------------------------------------------------------------
 */
function arrayToObject($d) {
  if (is_array($d)) {
    return (object) array_map(__FUNCTION__, $d);
  }
  else {
    // Return object
    return $d;
  }
}

function adminMenuExsits($menuItem) {
	global $menu;
	if(empty($menuItem)) {
		return false;
	}

	foreach($menu as $item) {
    if(strtolower($item[0]) == strtolower($menuItem)) {
       return true;
    }
	}
}