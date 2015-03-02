<?php
namespace Butiken\HolQaH;  // --- Yes, it means 'Language help' in klingon.

// ====================================================================================
// Author: Staffan Lindsgård
// ------------------------------------------------------------------------------------
// This is a class that enables support for different languages in your project.
// Add new languages by adding new phrasebooks. The language code used is normally the
// ISO639-3 version, but ISO639-1 is also used at times since HTML uses that standard.
// ====================================================================================

/**
 * Class for displaying different language versions of a string.
 *
 * The Language class writes out strings.
 */
class Language
{


    // --- Loads the correct phrasebook based on what language has been set.
    public function __construct()
    {
        include dirname(__FILE__) . '/x.settings.php';  // --- Includes the settings file.
        $this->settings = $settings;             // --- Stores the settings in a class-wide variable.
        $specified_lang = (isset($_GET[$this->settings['lang_variable']]) ? $_GET[$this->settings['lang_variable']] : $this->settings['default_lang']);    // --- Recieves the incoming language variable and, if it isn't empty, assigns it to a variable.

        if (file_exists($this->settings['phrasebookdir'].$this->settings['default_lang'].'.php')) {   // --- If a phrasebook for the default language exists,
            include $this->settings['phrasebookdir'].$this->settings['default_lang'].'.php';  // --- include it in the script and...
            $default_phrasebook = $phrasebook;                                                      // --- ... load the default phrasebook into an array.
        } else {
            $default_phrasebook = array();                                                          // --- If the phrasebook doesn't exist we set an empty array.
        }

        if ($specified_lang!=$this->settings['default_lang'] && file_exists($this->settings['phrasebookdir'].$specified_lang.'.php')) {       // --- If the specified language isn't the same as the default and a phrasebook for the specified language exists,
            include_once $this->settings['phrasebookdir'].$specified_lang.'.php';    // --- include it in the script and...
            $specified_phrasebook = $phrasebook;                                    // --- ... load the specified phrasebook into an array.
        } else {
            $specified_phrasebook = array();                                        // --- If the phrasebook doesn't exist we set an empty array.
        }

        $this->phrasebook = (object) array_merge($default_phrasebook,$specified_phrasebook); // --- Merge the default and the specified phrasebooks, overwriting default values with specific ones.
    }



    // --- Looks for a specific phrase and prints it.
    public function phrase($phrase) {                                                                               // --- Takes the name of the phrase to print as an argument.
        return ( isset($this->phrasebook->$phrase) ? $this->phrasebook->$phrase : '{{ ' . $phrase . ' }}' );  // --- If the specified phrase exists, return it. Otherwise, print the error phrase.
    }



    // --- Returns information about the language.
    public function getinfo($type='iso6391') {          // --- The argument lets the user choose what information to return. Default is ISO6391-code.
        return $this->phrasebook->pbook_meta[$type];  // --- Returns the information specified.
    }



    // --- Prints a list of currently supported languages, as defined in the settings.
    public function langlist($tabs=0,$classname='',$flags=false) {
        $tabs_li = str_repeat(' ', $tabs+1);
        $tabs = str_repeat('    ', $tabs);

        $classname = ( $classname!='' ? $classname : $this->settings['lang_list_class'] );

        $language_list = "\n".$tabs.'<ul class="'.$classname.'">'."\n";
        $library = $this->getLibrary();
        foreach ($library as $code => $name) {
            if ($code=='_time' || $code=='_updated') {
            } else {
                $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
                $url .= ($code!=$this->settings['default_lang'] ? '?'.$this->settings['lang_variable'].'='.$code : '' );
                $namestring = ($flags==true ? (file_exists($this->settings['imagedir'].$name['iso6393'].'.png') ? '<img class="'.$classname.'_img" src="'.$this->settings['imagedirurl'].$name['iso6393'].'.png" alt="'.$name['iso6393'].'" /><span class="'.$classname.'_name"> '.$name['name'].'</span>' : '<span class="'.$classname.'_name">'.$name['name'].'</span>' ) : $name['name'] );
                $language_list .= $tabs_li.'<li class="'.$classname.'_'.$code.'"><a href="'.$url.'">'.$namestring.'</a></li>'."\n";
            }
        }
        $language_list .= $tabs.'</ul>'."\n";
        return $language_list;
    }



    // --- Reads the contents of a directory and returns an array with names and dates of modification for each file.
    public function getDir() {
        $ignore = array('.','..');                                              // --- We don't want to list everything.
        $phrasebookdir = opendir(rtrim($this->settings['phrasebookdir'],'/'));   // --- The directory is opened for reading.

        while ($file = readdir($phrasebookdir)) {                               // --- Looks for files in the directory.
            if (in_array($file,$ignore) == false) {                             // --- Ignores the filetypes specified above.
                $modified = filectime($this->settings['phrasebookdir'].$file);   // --- Checks the current file for time of modification.
                $files[substr($file, 0, -4)] = $modified;                       // --- Writes the filename (minus the extention) and modification time to an array.
            }
        }

        closedir(rtrim($this->settings['phrasebookdir'],'/'));   // --- When we're done with all files, the directory is closed.
        ksort($files);                                          // --- The array is sorted, keeping the key associations.
        return json_encode($files);                             // --- Then the array is converted into json format and sent.
    }



    // --- Checks for changes to the library folder.
    private function changed() {
        $changefile = (file_exists($this->settings['changefile']) ? file_get_contents($this->settings['changefile']) : '' );  // --- Looks for a file having the same kind of information as getDir() returns.
        if ($this->getDir() === $changefile) {   // --- Checks to see if the info in the file and the live info differs.
            return false;                       // --- If they are the same, the library has not been updated.
        } else {
            return true;                        // --- If they differ, the library has been updated.
        }
    }



    // --- Updates the library file.
    private function update() {
        $files = json_decode($this->getDir(),true);  // --- Gets a list of the current files and their timestamps in the directory.
        $library = array('_time' => time() );        // --- Adds a timestamp of modification to the library catalog.
        include_once 'function.prettyjson.php';

        foreach ($files as $filename => $modified) {
            include $this->settings['phrasebookdir'].$filename.'.php';
            foreach ($phrasebook['pbook_meta'] as $key => $value) {
                $library[$phrasebook['pbook_meta']['iso6393']][$key] = $value;
            }
        }

        file_put_contents($this->settings['libraryfile'], json_readable_encode($library));
        file_put_contents($this->settings['changefile'], $this->getDir());
    }



    private function getLibrary() {
        if ($this->changed() === true) {
            $this->update();
        }
        return json_decode(file_get_contents($this->settings['libraryfile']),true);
    }


}
