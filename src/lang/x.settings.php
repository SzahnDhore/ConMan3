<?php

// ============================================================================
// Author: Staffan Lindsgård
// ----------------------------------------------------------------------------
// These are the settings for the language module. You should only need to edit
// these and the phrasebooks to make the system work for you.
// ============================================================================

$settings = array(
	'default_lang'		=> 'swe',			// --- Default language to use if no value is passed to the constructor. This language file must be present in the library and should be complete.
	'no_phrase'			=> '-x-',		// --- String to replace any non-existing phrase.
	'lang_variable'		=> 'l',			    // --- Name of $_GET-variable for the language.
	'lang_list_class'	=> 'languagelist',	// --- Class name of the language list.


// ============================================================================
// There shouldn't be any need to edit the following stuff at all.

// --- Settings for file- and directory names.
	'imagedir'		=> 'images',		// --- Directory where the images are located. Must be a child directory of the one this file resides in.
	'phrasebookdir'	=> 'library',		// --- Directory where the phrasebooks are located. Must be a child directory of the one this file resides in.
	'libraryfile' 	=> 'library.json',	// --- Name of the file that stores the static library info.
	'changefile' 	=> '.changefile',	// --- Name of the file used to check for changes in the library.
);

// --- A few modifications to the settings to make sure all the paths work as intended.
$installdir					= dirname(__FILE__).'/';								// --- The installation directory is the server directory with this file in it.
$settings['dirurl']			= 'http://'.$_SERVER['HTTP_HOST'].dirname(str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__));	// --- Creates an absolute URL for the installation directory.
$settings['imagedirurl']	= $settings['dirurl'].'/'.$settings['imagedir'].'/';	// ---
$settings['imagedir']		= $installdir.$settings['imagedir'].'/';
$settings['phrasebookdir']	= $installdir.$settings['phrasebookdir'].'/';
$settings['libraryfile']	= $installdir.$settings['libraryfile'];
$settings['changefile']		= $installdir.$settings['changefile'];

?>