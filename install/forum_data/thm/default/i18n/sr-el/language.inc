<?php
/**
* copyright            : (C) 2001-2011 Advanced Internet Designs Inc.
* email                : forum@prohost.org
* $Id$
*
* This program is free software; you can redistribute it and/or modify it
* under the terms of the GNU General Public License as published by the
* Free Software Foundation; version 2 of the License.
**/

/* Plural form transformation for Serbian (cyrillic script) messages.
 * Derived from http://svn.wikimedia.org/viewvc/mediawiki/trunk/phase3/languages/classes/LanguageSr_ec.php
 */
function convertPlural($count, $forms) {
	if (!count($forms)) { return '';}

	// Pads array to requested amound of forms by copying the last one.
	while (count($forms) < 3) {
		$forms[] = $forms[count($forms)-1];
	}

	// Return correct form.
	if ( $count > 10 && floor( ( $count % 100 ) / 10 ) == 1 ) {
		return $forms[2];
	} else {
		switch ( $count % 10 ) {
			case 1:  return $forms[0];
			case 2:
			case 3:
			case 4:  return $forms[1];
			default: return $forms[2];
		}
	}
}
