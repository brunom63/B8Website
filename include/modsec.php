<?php

// CHECK FORMS
	
	foreach ($_REQUEST as $key => $value) {
		$ret = $value;
		
		$ret = preg_replace("/@SDK@1/", 'http:', $ret);
		$ret = preg_replace("/@SDK@2/", 'https:', $ret);
		$ret = preg_replace("/@SDK@3/", 'iframe', $ret);
		$ret = preg_replace("/@SDK@4/", 'img', $ret);

		$ret = preg_replace("/@SDK@5/", '<', $ret);
		$ret = preg_replace("/@SDK@6/", '>', $ret);
		$ret = preg_replace("/@SDK@7/", '&gt;', $ret);
		$ret = preg_replace("/@SDK@8/", '&lt;', $ret);

		$ret = preg_replace("/@SDK@9/", '/', $ret);
		$ret = preg_replace("/@SDK@10/", "\\", $ret);
		$ret = preg_replace("/@SDK@11/", '&frasl;', $ret);
		$ret = preg_replace("/@SDK@12/", '&#8260;', $ret);
		$ret = preg_replace("/@SDK@13/", '&#x2044;', $ret);		
		
		$_REQUEST[$key] = $ret;
		
	}

// End Forms

?>
