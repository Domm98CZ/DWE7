<?php
/*
------------------------------------------------------------------------
MIT License

Copyright (c) 2016 - 2017 Dominik Procházka

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
------------------------------------------------------------------------
* Author: Dominik Procházka
* File: Language.class.php
* Filepath: /_core/classes/Language.class.php
*/
if(!defined("AUTHORIZED")) { die("Access Denied"); }

class Language {
	private $DEFAULT_LANG 	= null;
	private $LANGS    			= null;
	private $LANGSCODES			= null;
	private $LANG				= null;
	private $USED_LANG			= null;

	// Nastaví správně třídu
	public function __construct() {
		global $web, $user, $SYSTEM_LANGS;
		$this->DEFAULT_LANG = @$web->getSettings("web:lang");
		if($this->DEFAULT_LANG == null) $this->DEFAULT_LANG = $SYSTEM_LANGS[0];
		if($user->getUserLang() != null) $this->USED_LANG = $user->getUserLang();
	}
	
	// Získá aktuálně použitý jazyk.
	public function usedLang() {
		if(isset($this->USED_LANG) && !empty($this->USED_LANG)) return $this->USED_LANG;	
		else return $this->DefaultLang();
	}

	// Získání defaultního jazyka.
	public function DefaultLang() {
		return $this->DEFAULT_LANG;
	}

	// Pole se všemi hláškami jazyka.
	public function getLangArray() {
		return $this->LANG;
	}

	// Získá kód jazyka z aktuálně používaného jazyka.
	public function getUsedLangCode() {
		return $this->getLangCode($this->usedLang());
	}

	// Získání označení konkrétního jazyka
	// Parametr $lang
	// Vrátí označení konkrétního jazyka.
	private function getLangCode($lang) {
		$filePath = DIR_LANGS.$lang.".php";
		if(file_exists($filePath)) {
			include $filePath;
			$lc = $localeCode;
			return $lc;
		}
		else return null;
	}

	// Získání konkrétní hlášky v konkrétním jazyce.
	// Parametr $localTitle
	// Parametr $language
	// Získá hlášku v konkrétním jazyce - v případě že hláška neexistuje, napíše místo hlášky chybovou hlášku o neexistenci této hlášky.
	public function getLocale($localeTitle, $language = null) {
		global $web;
		$failText = "Message ".(($language == null) ? $this->usedLang() : $language)." - ".$localeTitle." can't be found.";
		if(empty($this->LANG) || !is_array($this->LANG) || count($this->LANG) < 1 || $this->LANG['LANG'] != (($language == null) ? $this->usedLang() : $language)) {
			$this->getLang((($language == null) ? $this->usedLang() : $language));
		}
		if(isset($this->LANG[$localeTitle]) && !empty($this->LANG[$localeTitle])) return $this->LANG[$localeTitle];
		else return $failText; 
	}

	// Získání všech dostupných hlášek v konkrétním překladu.
	// Parametr $lang
	// Vrátí 1 (true) v případě úspěchu
	// Vrátí 0 (false) v případě neúspěchu
	public function getLang($lang) {
		$filePath = DIR_LANGS.$lang.".php";
		if(file_exists($filePath)) {
			include $filePath;
			$this->LANG = $l;
			unset($l);
			return 1;
		}
		else return 0;
	} 

	// Získání všech nainstalovaných jazyků do jednoho pole.
	// Vrátí všechny nainstalované jazyky jako pole.
	public function getInstalledLangs() {
	 	global $web;
	 	$langs = $web->getDirFiles(DIR_LANGS);
	 	if(count($langs) > 0) {
	 		for($i = 0;$i < count($langs);$i ++) {
	 			$langs[$i] = str_replace(".php", "", $langs[$i]);
	 		}
	 		return $langs;
	 	}
	 	else return null;
	}

	// Získání názvů všech dostupných překladů.
	// Parametr $opt 
	// - 0 = Označení + Název
	// - 1 = Název
	// Vrátí pole názvů všech dostupných jazyků.
	public function getInstalledLangsTitles($opt = 0) {
		$langs = $this->getInstalledLangs();
		$langsTitles = null;
		if(count($langs) > 0) {
			for($i = 0;$i < count($langs);$i ++) {
				$filePath = DIR_LANGS."".$langs[$i].".php";
				if(file_exists($filePath)) {
					include $filePath;
					if($opt == 1) { $langsTitles[$i] = $l['LANGUAGE']; }
					else if($opt == 2) { 
						$langsTitles[$i][0] = $l['LANG']; 
						$langsTitles[$i][1] = $l['LANGUAGE']; 
					}
					else $langsTitles[$i] = $langsTitles[] = $l['LANG'];;
					unset($l);
				}	
			}
			return $langsTitles;
		}
		else return null;
	}
	
	// Vytvoření správného tvaru slova dle počtu prvků.
	// Parametr $num - počet prvků
	// Parametr $loc1 
	// Parametr $loc2
	// Parametr $loc3
	// Parametr $loc4
	// Parametr $locale
	// Vrátí správný tvar slova dle počtu prvků
	public function plural_words_locale($num, $loc1 = null, $loc2 = null, $loc3 = null, $loc4 = null, $locale = null) {
		if($locale == null) $locale = $this->usedLang();
		if($locale == "English") {
			if($num == 0) return $loc2;
			else return $loc2;
		}
		else if($locale == "Czech") {
			if($num == 0) return $loc4 != null ? $loc4 : $loc2;
			else if($num == 1) return $loc1;
			else if($num > 1 && $num < 5) return $loc2;
			else if($num >= 5) return $loc3;
		}
	}
}
?>