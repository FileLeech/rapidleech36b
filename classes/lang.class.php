<?php
/*****************************************************
 * Rapidleech 36B Language Class
 *
 * $Id: lang.class.php - 05apr2010-Idx $
 *****************************************************/

class RxLang {

	var $path;
	var $settings;
	var $sections;
	var $say = array();

	/**
	 * Set the path for the language folder.
	 * @param string The path to the language folder.
	 */
	function set_path($path) {
		// strip last "/" if exist
		if (substr($path, -1) == PATH_SPLITTER) $path = substr($path, 0, -1);
		$this->path = $path;
	}

	/**
	 * Check if a specific language exists.
	 * @param string The language to check for.
	 * @return boolean True when exists, false when does not exist.
	 */
	function language_exists($language) {
		$language = preg_replace("#[^a-z0-9\-_]#i", "", $language);
		if (file_exists($this->path . "/" . $language . ".php")) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Set the language for an area.
	 * @param string The language to use.
	 */
	function set_language($language="english") {
		$language = preg_replace("#[^a-z0-9\-_]#i", "", $language);

		// Default language is English.
		if ($language == "") {
			$language = "english";
		}

		// Check if the language exists.
		if (!$this->language_exists($language)) {
			die("Language $language ($this->path" . PATH_SPLITTER . "$language) is not installed");
		}

		require $this->path . PATH_SPLITTER . $language . ".php";
		$this->settings = $langinfo;
		$this->sections = $l;
	}

	/**
	 * Load the language variables for all section.
	 * @param array the language variable we need to load globally
	 * @param boolean supress the error if the variable doesn't exist?
	 */
	function load($supress_error = false) {
		// Assign language variables.
		if (is_array($this->sections)) {
			foreach ($this->sections as $key => $val) {
				if (empty($this->$key) || $this->$key != $val) {
					//$this->$key = $val;
					$this->say[$key] = $val;
				}
			}
		} else {
			if ($supress_error != true) {
				die("$this->sections does not exist or not an array!");
			}
			
		}
	}

	function sprintf($string) {
		$arg_list = func_get_args();
		$num_args = count($arg_list);

		for ($i = 1; $i < $num_args; $i++) {
			$string = str_replace('{' . $i . '}', $arg_list[$i], $string);
		}

		return $string;
	}

	/**
	 * Get the language variables for a section.
	 * @return array The language variables.
	 */
	function get_languages($admin=0) {
		$dir = @opendir($this->path);
		while ($lang = readdir($dir)) {
			$ext = do_strtolower(get_extension($lang));
			if ($lang != "." && $lang != ".." && $ext == "php") {
				$lname = str_replace("." . $ext, "", $lang);
				require $this->path . PATH_SPLITTER . $lang;
				$languages[$lname] = $langinfo['name'];
			}
		}
		@ksort($languages);
		return $languages;
	}

}

// edited by vreets so it can load single php file without need to call lot of file (again...)

?>