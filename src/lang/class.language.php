<?php
namespace HolQaH;
// --- Yes, it means 'Language help' in klingon.

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

    public function __construct()
    {
        // session_start();
        include dirname(__FILE__) . '/x.settings.php';
        $this->settings = $settings;
        $specified_lang = (isset($_GET[$this->settings['lang_variable']]) ? $_GET[$this->settings['lang_variable']] : (isset($_SESSION['lang']) ? $_SESSION['lang'] : $this->settings['default_lang']));
        $_SESSION['lang'] = $specified_lang;

        if (file_exists($this->settings['phrasebookdir'] . $this->settings['default_lang'] . '.php')) {
            include $this->settings['phrasebookdir'] . $this->settings['default_lang'] . '.php';
            $default_phrasebook = $phrasebook;
        } else {
            $default_phrasebook = array();
        }

        if ($specified_lang != $this->settings['default_lang'] && file_exists($this->settings['phrasebookdir'] . $specified_lang . '.php')) {
            include_once $this->settings['phrasebookdir'] . $specified_lang . '.php';
            $specified_phrasebook = $phrasebook;
        } else {
            $specified_phrasebook = array();
        }

        $this->phrasebook = (object) array_merge($default_phrasebook, $specified_phrasebook);
    }

    public function phrase($phrase)
    {
        return (isset($this->phrasebook->$phrase) ? $this->phrasebook->$phrase : '-- ' . $phrase . ' --');
    }

    public function getinfo($type = 'iso6391')
    {
        return $this->phrasebook->pbook_meta[$type];
    }

    public function langlist($tabs = 0, $classname = '', $flags = false)
    {
        $tabs_li = str_repeat(' ', $tabs + 1);
        $tabs = str_repeat('    ', $tabs);

        $classname = ($classname != '' ? $classname : $this->settings['lang_list_class']);

        $language_list = "\n" . $tabs . '<ul class="' . $classname . '">' . "\n";
        $library = $this->getLibrary();
        foreach ($library as $code => $name) {
            if ($code == '_time' || $code == '_updated') {
            } else {
                $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
                $url .= ($code != $this->settings['default_lang'] ? '?' . $this->settings['lang_variable'] . '=' . $code : '');
                $namestring = ($flags == true ? (file_exists($this->settings['imagedir'] . $name['iso6393'] . '.png') ? '<img class="' . $classname . '_img" src="' . $this->settings['imagedirurl'] . $name['iso6393'] . '.png" alt="' . $name['iso6393'] . '" /><span class="' . $classname . '_name"> ' . $name['name'] . '</span>' : '<span class="' . $classname . '_name">' . $name['name'] . '</span>') : $name['name']);
                $language_list .= $tabs_li . '<li class="' . $classname . '_' . $code . '"><a href="' . $url . '">' . $namestring . '</a></li>' . "\n";
            }
        }
        $language_list .= $tabs . '</ul>' . "\n";
        return $language_list;
    }

    public function getDir()
    {
        $ignore = array(
            '.',
            '..'
        );
        $phrasebookdir = opendir(rtrim($this->settings['phrasebookdir'], '/'));

        while ($file = readdir($phrasebookdir)) {
            if (in_array($file, $ignore) == false) {
                $modified = filectime($this->settings['phrasebookdir'] . $file);
                $files[substr($file, 0, -4)] = $modified;
            }
        }

        closedir(rtrim($this->settings['phrasebookdir'], '/'));
        ksort($files);
        return json_encode($files);
    }

    private function changed()
    {
        $changefile = (file_exists($this->settings['changefile']) ? file_get_contents($this->settings['changefile']) : '');
        if ($this->getDir() === $changefile) {
            return false;
        } else {
            return true;
        }
    }

    private function update()
    {
        $files = json_decode($this->getDir(), true);
        $library = array('_time' => time());
        include_once 'function.prettyjson.php';

        foreach ($files as $filename => $modified) {
            include $this->settings['phrasebookdir'] . $filename . '.php';
            foreach ($phrasebook['pbook_meta'] as $key => $value) {
                $library[$phrasebook['pbook_meta']['iso6393']][$key] = $value;
            }
        }

        file_put_contents($this->settings['libraryfile'], json_readable_encode($library));
        file_put_contents($this->settings['changefile'], $this->getDir());
    }

    private function getLibrary()
    {
        if ($this->changed() === true) {
            $this->update();
        }
        return json_decode(file_get_contents($this->settings['libraryfile']), true);
    }

}
