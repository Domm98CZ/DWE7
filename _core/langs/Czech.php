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
* File: Czech.php
* Filepath: /_core/langs/Czech.php
*/

if(!defined("AUTHORIZED")) { die("Access Denied"); }

if(empty($l) || !isset($l) || !is_array($l)) $l = array();

$langCode = "cs";
$localeCode = "cs_CZ";
$l = array_merge($l, array(
	"LANG" => "Czech",
	"LANGUAGE" => "Čeština",
	"RENDERTIME" => "Vygenerováno za <b><rendertime>%%%WEB_RENDER_TIME%%%%</rendertime>s</b>.",
	"JAVASCRIPT_DISABLED" => "Ale ne! Kde je JavaScript? Váš prohlížeč nemá zaplý JavaScript nebo jej nepodporuje. Prosím zapněte JavaScript ve Vašem prohlížeči ke správnému zobrazení této stránky, nebo přejděte k prohlížeči, který podporuje JavaScript",

	/* System Page - Register */
	"P_REGISTER_TITLE" => "Registrace",

	"P_REGISTER_L1" => "Uživatelské jméno",
	"P_REGISTER_L2" => "Heslo",
	"P_REGISTER_L3" => "Heslo <i>(znovu)</i>",
	"P_REGISTER_L4" => "E-Mailová adresa",
	"P_REGISTER_L5" => "E-Mailová adresa <i>(znovu)</i>",

	"P_REGISTER_E1" => "Nebyla vyplněna všechna pole.",
	"P_REGISTER_E2" => "Uživatelské jméno musí obsahovat nejméně 3 znaky, a nesmí obsahovat diakritiku.",
	"P_REGISTER_E3" => "Zadaná hesla se neshodují.",
	"P_REGISTER_E4" => "Neplatná e-mailová adresa.",
	"P_REGISTER_E5" => "Zadané e-mailové adresy se neshodují.",
	"P_REGISTER_E6" => "Uživatelské jméno je již používané.",
	"P_REGISTER_E7" => "E-Mailová adresa je již používána.",
	"P_REGISTER_E8" => "Heslo musí obsahovat alespoň 6 znaků.",

	"P_REGISTER_OK" => "Účet byl úspěšně zaregistrován, byl vám zaslán aktivační e-mail.",

	"P_REGISTER_B1" => "Již mám účet",
	"P_REGISTER_B2" => "Zaregistrovat se",

	"P_REGISTER_T1" => "Na E-mailovou adresu vám bude zaslán aktivační e-mail.",

	/* System Page - Login */
	"P_LOGIN_TITLE" => "Přihlášení",
	"P_LOGIN_L1" => "Uživatelské jméno",
	"P_LOGIN_L2" => "Heslo",
	"P_LOGIN_L3" => "Zapamatovat si přihlášení",

	"P_LOGIN_B1" => "Potřebuji se zaregistrovat",
	"P_LOGIN_B2" => "Přihlášení",
	"P_LOGIN_B3" => "Zapomenuté heslo",

	"P_LOGIN_E1" => "Nebyla vyplněna všechna pole.",
	"P_LOGIN_E2" => "Neplatná kombinace uživatelského jména a hesla.",
	"P_LOGIN_E3" => "Účet <b>%s</b> nebyl aktivován, <a href='%s'>aktivovat účet</a>.", // (User name, activate url)
	"P_LOGIN_E4" => "Účet <b>%s</b> je zablokován, pro více informací zkontrolujte e-mailovou schránku, spojenou s tímto účtem.", // (User name)

	"P_LOGIN_OK" => "Úpěšně přihlášen jako <b>%s</b>, budete přesměrován.", // (User name)

	/* System Page - Active */
	"P_ACTIVE_TITLE" => "Aktivace účtu",
	"P_ACTIVE_L1" => "Uživatelské jméno",

	"P_ACTIVE_B1" => "Přihlášení",
	"P_ACTIVE_B2" => "Odeslat aktivační e-mail",

	"P_ACTIVE_E1" => "Nebylo vyplněno uživatelské jméno.",
	"P_ACTIVE_E2" => "Uživatelský účet <b>%s</b> neexistuje.", // (User name)
	"P_ACTIVE_E3" => "Uživatelský účet byl již aktivován.",
	"P_ACTIVE_E4" => "Účet <b>%s</b> je zablokován, pro více informací zkontrolujte e-mailovou schránku, spojenou s tímto účtem.", // (User name)
	"P_ACTIVE_E5" => "Platnost odkazu bohužel vypršela. <a href='%s'>Vygenerujte si nový aktivační e-mail.</a>",

	"P_ACTIVE_OK1" => "Byl odeslán aktivační e-mail, na e-mailovou adresu, spojenou s tímto účtem.",
	"P_ACTIVE_OK2" => "Uživatelský účet <b>%s</b>, byl úspěšně aktivován, nyní se můžete přihlásit.",

	/* System Page - Password */
	"P_PASSWORD_TITLE" => "Zapomenuté heslo",
	"P_PASSWORD_L1" => "Uživatelské jméno",
	"P_PASSWORD_L2" => "E-mailová adresa",
	"P_PASSWORD_L3" => "Nové heslo",
	"P_PASSWORD_L4" => "Nové heslo <i>(znovu</i>)",

	"P_PASSWORD_T1" => "Vyplňte přihlašovací jméno účtu nebo e-mailovou adresu, spojenou s tímto účtem. Na e-mailovou adresu účtu vám bude zaslán odkaz pro změnu hesla.",
	"P_PASSWORD_T2" => "Žádost o změnu hesla byla úspěšně smazána.",

	"P_PASSWORD_B1" => "Zpět k přihlášení",
	"P_PASSWORD_B2" => "Odeslat žádost o změnu hesla",
	"P_PASSWORD_B3" => "Zrušit žádost o změnu hesla",
	"P_PASSWORD_B4" => "Změnit heslo",

	"P_PASSWORD_E1" => "Musí být vyplněno uživatelské jméno, nebo e-mailová adresa uživatelského účtu.",
	"P_PASSWORD_E2" => "Uživatelský účet neexistuje.",
	"P_PASSWORD_E3" => "Uživatelský účet je zablokován, pro více informací zkontrolujte e-mailovou schránku, spojenou s tímto účtem.",
	"P_PASSWORD_E4" => "Platnost odkazu bohužel vypršela. <a href='%s'>Vygenerujte si novou žádost o změnu hesla.</a>",
	"P_PASSWORD_E5" => "Musí být vyplněny obě pole.",
	"P_PASSWORD_E6" => "Zadaná hesla se neshodují.",

	"P_PASSWORD_OK1" => "Byla úspěšně vygenerována žádost o změnu hesla, na e-mailovou adresu spojenou s tímto účtem byl zaslán odkaz pro změnu hesla.",
	"P_PASSWORD_OK2" => "Heslo bylo úspěšně nastaveno, nyní se můžete přihlásit.",

	/* System Page - Logout*/
	"P_LOGOUT_TITLE" => "Odhlášení",
	"P_LOGOUT_T1" => "Probíhá odhlášení uživatele <b>%s</b>, budete přesměrován.",
	"P_LOGOUT_T2" => "Pokud se stránka nezobrazí do 10 sekund, klikněte sem.",

	/* System Page - Redirect */
	"P_REDIRECT_TITLE" => "Přesměrování",
	"P_REDIRECT_T1" => "Odkaz, který jste použili, odkazuje mimo webové stránky.",
	"P_REDIRECT_T2" => "Pro pokračování klikněte na tlačítko pod tímto textem.",
	"P_REDIRECT_T3" => "Odkaz, který jste použili není platný a proto vás nemůžeme přesměrovat.",
	"P_REDIRECT_B1" => "Pokračovat na externí stránku",
	"P_REDIRECT_B2" => "Zpět",

	/* System Page - Profile */
	"P_PROFILE_TITLE" => "Profil uživatele - %s", // (User name)

	"P_PROFILE_L1" => "Datum registrace:",
	"P_PROFILE_L2" => "Typ uživatelského účtu:",
	"P_PROFILE_L3" => "Poslední přihlášení:",
	"P_PROFILE_L4" => "Datum registrace:",
	"P_PROFILE_L5" => "E-mailová adresa:",

	"P_PROFILE_DESC" => "Uživatelský popis",
	
	"P_PROFILE_ONLINE" => "Právě aktivní",
	"P_PROFILE_AFK" => "Před chvílí aktivní",
	"P_PROFILE_OFFLINE" => "Neaktivní",
	"P_PROFILE_EDIT" => "Upravit profil",
	
	"P_PROFILE_E1" => "Chyba, uživatelský profil nebyl nalezen.",
	"PROFILE_NOTEXISTS" => "Neexistující uživatel",
	
	"P_PROFILE_B1" => "Poslat soukromou zprávu",
	"P_PROFILE_B2" => "Nahlásit uživatele",

	/* System Page - Groups */
	"P_GROUPS_TITLE_1" => "Uživatelská skupina - %s", // (group name)
	"P_GROUPS_TITLE_2" => "Uživatelské skupiny",
	
	"P_GROUPS_L1" => "Zpět na přehled uživatelských skupin",
	
	"P_GROUPS_T1" => "uživatelé",
	"P_GROUPS_T2" => "uživatel",
	"P_GROUPS_T3" => "uživatelů",
	
	"P_GROUPS_USERS_LIST" => "Uživatelé ve skupině <i>(%d)</i>:", //(users in group, count)
	"P_GROUPS_DESC_NONE" => "Uživatelská skupina nemá popis.",
	
	/* System Page - Report */
	"P_REPORT_T1" => "Nahlášení",
	"P_REPORT_L1" => "Nahlášený uživatel",
	"P_REPORT_L2" => "Nahlášený komentář",
	"P_REPORT_L3" => "Důvod nahlášení",
	
	"P_REPORT_OK" => "Nahlášení bylo úspěšně odesláno.",
	
	"P_REPORT_E1" => "Nahlášení nemůže být odesláno.",
	
	"P_REPORT_B1" => "Nahlásit",
	"P_REPORT_B2" => "Nenahlašovat",

	/* System Page - Settings */
	"P_SETTINGS_TITLE" => "Nastavení",
	"P_SETTINGS_T1" => "Hlavní nastavení",
	"P_SETTINGS_T2" => "Nastavení hesla",
	"P_SETTINGS_T3" => "Nastavení avatara",
	"P_SETTINGS_T4" => "Nastavení profilu",
	"P_SETTINGS_T5" => "Zařízení",

	"P_SETTINGS_L1" => "Přihlašovací jméno",
	"P_SETTINGS_L2" => "Zobrazované jméno",
	"P_SETTINGS_L3" => "E-Mailová adresa",
	"P_SETTINGS_L9" => "Jazyk webu",

	"P_SETTINGS_L4" => "Vaše aktuální heslo",
	"P_SETTINGS_L5" => "Nové heslo",
	"P_SETTINGS_L6" => "Nové heslo <i>(znovu)</i>",

	"P_SETTINGS_L7" => "Výběr avatara",
	"P_SETTINGS_L7-1" => "Použít základní avatar",
	"P_SETTINGS_L7-2" => "Použít <a href='%s' target='_blank'>gravatar.com</a> <i>(z e-mailové adresy %s)</i>", // gravatar link, user email
	"P_SETTINGS_L7-3" => "Použít nahraný avatar <i>(max. %s, %sx%s)</i>", // (max avatar size, max avatar size px, max avatar size px)

	"P_SETTINGS_L8" => "Aktuální avatar",

	"P_SETTINGS_L10" => "Zobrazovat emailovou adresu?",
	"P_SETTINGS_L10-1" => "Ano",
	"P_SETTINGS_L10-2" => "Ne",
	"P_SETTINGS_L11" => "Uživatelský podpis",
	"P_SETTINGS_L11-1" => "Uživatelský popis zatím nebyl vyplněn. Zde jej můžete vyplnit.",
	"P_SETTINGS_L12" => "Primární skupina",
	"P_SETTINGS_L12-1" => "Jste členem pouze jedné skupiny (<strong>%s</strong>).",
	"P_SETTINGS_L12-2" => "Zatím nejste členem žádné skupiny.",

	"P_SETTINGS_B1" => "Uložit nastavení",
	"P_SETTINGS_B2" => "Nastavit nové heslo",
	"P_SETTINGS_B3" => "Nastavit avatar",
	"P_SETTINGS_B4" => "Upravit profil",

	"P_SETTINGS_OK1" => "Nastavení úspěšně uloženo.",
	"P_SETTINGS_OK1-1" => "Nová emailová adresa byla úspěšně nastavena, nyní jí musíte ověřit pomocí odkazů, které byli zaslány na obě adresy.",
	"P_SETTINGS_OK2" => "Nové heslo úspěšně nastaveno, nyní jej ověřte pomocí odkazu v e-mailu, který vám byl zaslán.",
	"P_SETTINGS_OK3" => "Avatar byl úspěšně nastaven.",
	"P_SETTINGS_OK4" => "Nastavení profilu bylo úspěšně uloženo.",

	"P_SETTINGS_E1" => "Nebyla vyplněna všechna pole.",
	"P_SETTINGS_E2" => "Zobrazované jméno musí obsahovat alespoň 3 znaky, a nesmí obsahovat speciální znaky.",
	"P_SETTINGS_E3" => "Neplatná emailová adresa.",
	"P_SETTINGS_E4" => "Neplatné aktuální heslo.",
	"P_SETTINGS_E5" => "Zadaná hesla nesouhlasí.",
	"P_SETTINGS_E6" => "Heslo musí obsahovat alespoň 6 znaků.",
	"P_SETTINGS_E7" => "Nebyla zvolena metoda načítání avatara.",

	"P_SETTINGS_E8" => "Soubor, který jste nahráli, není obrázek.",
	"P_SETTINGS_E9" => "Obrázek musí být ve formátu *.png, *.jpg, nebo *.gif.",
	"P_SETTINGS_E10" => "Obrázek je moc velký, maximální velikost profilového obrázku je <b>%s</b>.", // (max avatar size)
	"P_SETTINGS_E11" => "Nastala chyba při nahrávání souboru.",
	"P_SETTINGS_E12" => "Obrázek je moc velký, maximální rozměry profilového obrázku jsou <b>%sx%s</b>", // (max avatar size px)

	"P_SETTINGS_PASS_ACCOUNT_TYPE" => "U vašeho účtu heslo změnit nelze.",
	"P_SETTINGS_EMAIL_ACCOUNT_TYPE" => "U vašeho účtu emailová adresa změnit nelze.",
	"P_SETTINGS_PASS_BLOCK" => "Žádost o změnu hesla již byla vytvořena, zkontrolujte prosím svojí e-mailovou schránku.",
	"P_SETTINGS_PASS_CLOSE" => "Zrušit žádost o změnu hesla",
	"P_SETTINGS_NO_DEVICE" => "Někde se stala chyba, k vašemu účtu nebylo nalezeno žádné zařízení.",
	"P_SETTINGS_EMAIL_NOTICE" => "Bude vyžadováno ověření pomocí e-mailové adresy.",
	"P_SETTINGS_EMAIL_CHANGE" => "Na e-mailovou adresu byl zaslán aktivační e-mail.",
	"P_SETTINGS_EMAIL" => "Pro změnu e-mailové adresy bude vyžadováno její ověření.",
	"P_SETTINGS_EMAIL_BLOCK" => "Žádost o změnu emailové adresy již byla vytvořena.",
	"P_SETTINGS_EMAIL_CLOSE" => "Nechci provádět změnu emailové adresy",
	"P_SETTINGS_EMAIL_CHANGE_E" => "Žádost o změnu emailové adresy",
	"P_SETTINGS_PASS_CHANGE_E" => "Žádost o změnu přihlašovacího hesla",

	"P_SETTINGS_DEVICE_T1" => "První přihlášení z tohoto zařízení.",
	"P_SETTINGS_DEVICE_T2" => "Poslední aktivita z tohoto zařízení.",

	"P_SETTINGS_DEVICE_TABLE_H1" => "Zařízení",
	"P_SETTINGS_DEVICE_TABLE_H2" => "Čas a datum",
	"P_SETTINGS_DEVICE_TABLE_H3" => "Akce",

	"P_SETTINGS_DEVICE_ACTION_1" => "Důvěryhodné",
	"P_SETTINGS_DEVICE_ACTION_2" => "Smazat",
	"P_SETTINGS_DEVICE_ACTION_3" => "Zablokovat",

	"P_DEVICE_BLOCKED" => "Toto zařízení bylo zablokováno.",
	"P_DEVICE_USED" => "Aktuálně používáte toto zařízení.",

	/* System Page - Error */
	"P_ERROR_T1" => "Stránka nenalezena",
	"P_ERROR_T2" => "Chyba - Stránka nenalezena",
	"P_ERROR_T3" => "Tato stránka neexistuje.",

	/* System Page - Messages*/ 
	"P_MESSAGES_TITLE" => "Soukromé zprávy",

	"P_MESSAGE_T1" => "Přijaté",
	"P_MESSAGE_T2" => "Nepřečtené",
	"P_MESSAGE_T3" => "Důležité",
	"P_MESSAGE_T4" => "Odeslané",
	"P_MESSAGE_T5" => "Konverzace",

	"P_MESSAGE_T6" => "Odesláno",
	"P_MESSAGE_T7" => "Zobrazeno",
	"P_MESSAGE_T8" => "Zpět do přehledu zpráv",
	"P_MESSAGE_T9" => "Soukromá zpráva od",
	"P_MESSAGE_T10" => "Zobrazeno uživatelem %s v %s",
	"P_MESSAGE_T11" => "Zobrazeno vámi v %s",
	"P_MESSAGE_T12" => "Soukromá zpráva pro",
	"P_MESSAGE_T13" => "Uživatel %s si zatím zprávu nepřečetl.",
	"P_MESSAGE_T14" => "Sem vložte text vaší zprávy..",
	"P_MESSAGE_T15" => "Odpovědět uživateli %s",
	"P_MESSAGE_T16" => "Odeslat další zprávu uživateli %s",

	"P_MESSAGE_L1" => "Ocitovat text zprávy a odeslat s novou zprávou",
	"P_MESSAGE_L2" => "Konverzace (#%s)",
	"P_MESSAGE_L3" => "Příjemce",
	"P_MESSAGE_L4" => "Předmět",
	"P_MESSAGE_L5" => "Obsah zprávy",

	"P_MESSAGE_CONVERSATION_OWNER" => "Správce konverzace",
	"P_MESSAGE_CONVERSATION_INVITED" => "Pozvaný do konverzace",
	"P_MESSAGE_UNREAD" => "Nová",
	"P_MESSAGE_NEXTUSERS_1" => "a další jeden uživatel",
	"P_MESSAGE_NEXTUSERS_2" => "a další %s uživatelé",
	"P_MESSAGE_NEXTUSERS_3" => "a dalších %s uživatelů",

	"P_MESSAGE_TABLE_H1" => "Předmět",
	"P_MESSAGE_TABLE_H2" => "Odeslal",
	"P_MESSAGE_TABLE_H3" => "Datum",
	"P_MESSAGE_TABLE_H4" => "Příjemce",

	"P_MESSAGE_NO_MESSAGES_1" => "Nemáte žádné přijaté zprávy",
	"P_MESSAGE_NO_MESSAGES_2" => "Zatím zde nejsou žádné odeslané zprávy",
	"P_MESSAGE_NO_MESSAGES_3" => "Nemáte žádné nepřečtené zprávy",
	"P_MESSAGE_NO_MESSAGES_4" => "Nemáte žádné důležité zprávy",
	
	"P_MESSAGE_B1" => "Nová zpráva",
	"P_MESSAGE_B2" => "Odeslat zprávu",
	
	"P_MESSAGE_E1" => "Zpráva nesmí být prázdná.",
	"P_MESSAGE_E2" => "Příjemce s nebyl nalezen v systému.",
	"P_MESSAGE_E3" => "Musí být vyplněna všechna pole zprávy.",
	"P_MESSAGE_E4" => "Nelze poslat zprávu sám sobě.",

	"P_MESSAGE_OK1" => "Vaše zpráva byla úspěšně odeslána",

	/* System Plugin - userpanel */
	"PLUGIN_USERPANEL_TITLE_1" => "Přihlášení",
	"PLUGIN_USERPANEL_TITLE_2" => "Profil - %s", // (User name)
	"PLUGIN_USERPANEL_L1" => "Uživatelské jméno", 
	"PLUGIN_USERPANEL_L2" => "Heslo", 
	"PLUGIN_USERPANEL_L3" => "Zapamatovat si přihlášení",
	"PLUGIN_USERPANEL_T1" => "Potřebuji se zaregistrovat", 
	"PLUGIN_USERPANEL_T2" => "Zapomenuté heslo", 
	"PLUGIN_USERPANEL_B1" => "Přihlásit se", 

	"PLUGIN_USERPANEL_L4" => "Nastavení",
	"PLUGIN_USERPANEL_L5" => "Soukromé zprávy",
	"PLUGIN_USERPANEL_L6" => "Administrace",
	"PLUGIN_USERPANEL_L7" => "Odhlásit se",

	/* System Plugin - googlelogin */
	"PLUGIN_GOOGLELOGIN_TITLE" => "Google Přihlášení",
	"PLUGIN_GOOGLELOGIN_B1" => "Přihlásit se pomocí",

	/* System Plugin - onlineusers */
	"PLUGIN_ONLINEUSERS_TITLE" => "Aktivní uživatelé",
	
	/* System Plugin - forum */
	"PLUGIN_FORUM_TITLE" => "Diskuzní fórum",

	/* Asset Plugin - dataTables */
	"AS_PLUGIN_DATATABLES_NO_DATA" => "Nejsou k dispozici žádná data.",
	"AS_PLUGIN_DATATABLES_BTN_FIRST" => "První",
	"AS_PLUGIN_DATATABLES_BTN_PREV" => "Předchozí",
	"AS_PLUGIN_DATATABLES_BTN_NEXT" => "Další",
	"AS_PLUGIN_DATATABLES_BTN_LAST" => "Poslední",
 
 	/* Comments */
 	"COMMENTS_T1" => "Komentáře",
 	"COMMENTS_T2" => "Komentář",
 	"COMMENTS_T3" => "Komentářů",
 	"COMMENTS_T4" => "Žádné komentáře",
 	"COMMENTS_T5" => "Zatím nikdo neokomentoval tento příspěvek.",
 	"COMMENTS_T6" => "Přidávání komentářů k tomuto příspěvku, bylo zakázáno.",
 	"COMMENTS_T7" => "<p>Opravdu chcete odstranit komentář?</p><p><i><b>(%s):</b> %s</i></p>", // comment date, comment
 	"COMMENTS_T8" => "Úprava komentáře",
 	"COMMENTS_T9" => "Všechny komentáře byly načteny.",

 	"COMMENTS_L1" => "Zpět na přehled novinek",
 	"COMMENTS_L2" => "Sem vložte text komentáře..",
 	"COMMENTS_L3" => "Upraveno",

 	"COMMENTS_E1" => "Komentář nesmí být prázdný.",
 	"COMMENTS_OK" => "Komentář byl přidán.",
 	"COMMENTS_OK_E" => "Komentář byl úspěšně upraven.",

 	"COMMENTS_B1" => "Přidat komentář",
 	"COMMENTS_B2" => "Ano, chci komentář smazat",
 	"COMMENTS_B3" => "Ne, nechci",

 	"COMMENTS_B4" => "Upravit komentář",
 	"COMMENTS_B5" => "Zrušit úpravy",

 	"COMMENTS_B6" => "Načíst všechny komentáře",
	
	"COMMENTS_L4" => "Upravit",
	"COMMENTS_L5" => "Odstranit",
	"COMMENTS_L6" => "Nahlásit",

 	/* News Categories */
 	"NEWS_CATEGORIES_T1" => "Kategorie novinek",
 	"NEWS_CATEGORIES_T2" => "příspěkvů",
 	"NEWS_CATEGORIES_T3" => "Nebyl vyplněn popis kategorie.",
 	"NEWS_CATEGORIES_T4" => "V kategorii nejsou žádné příspěvky.",
 	"NEWS_CATEGORIES_T5" => "Zpět na přehled kategorií",

 	/* News */
 	"NEWS_AUTHOR" => "Autor:",
 	"NEWS_DATE" => "Přidáno:",
 	"NEWS_CAT" => "Kategorie:",
	"NEWS_TITLE" => "Novinky",
	
	/* Reports */
	"REPORTS_T1" => "Nahlášení",
	"REPORTS_T2" => "Nahlásit komentář",

	"REPORTS_E1" => "Obsah náhlášení nesmí být prázdný.",

 	/* Mainterance */
 	"MAINTENANCE_T1" => "Údržba",
 	"MAINTENANCE_T2" => "Přihlášený uživatel <b>%s</b>",

 	"MAINTENANCE_ALERT_T1" => "Mód údržby",
	"MAINTENANCE_ALERT_T2" => "Webové stránky jsou nyní v módu údržby. Běžní uživatelé a návštěvníci se nyní na webové stránky nedostanou.",

 	"MAINTENANCE_B1" => "Odhlásit se",
	"MAINTENANCE_B2" => "Pokračovat na webové stránky",

	/* Setup */ 
	"SETUP_T1" => "Instalace systému",

	"SETUP_S1_T1" => "Výběr základního jazyka",
	"SETUP_S1_L1" => "Prosím vyberte základní jazyk pro webové stránky",
	"SETUP_S1_B1" => "Potvrdit výběr jazyka",
	"SETUP_S1_H1" => "Po výběru jazyka, bude instalace probíhat ve zvoleném jazyce, taktéž bude nastaven jako výchozí pro webové stránky.",

	"SETUP_S2_T1" => "Vítejte v instalaci systému",
	"SETUP_S2_T2" => 
	"Děkujeme, že jste se rozhodli používat systém <b>Domm Web Engine 7 (DWE7)</b>. Systém je navržený pro co možná největší optimalizaci, taktéž se o velkou většinu věcí stará sám. 
	Nyní následuje jednoduchá, několika bodová instalace, kterou vás systém provede.",
	"SETUP_S2_B1" => "Pokračovat v instalaci",

	"SETUP_S3_T1" => "Připojení k databázi",
	"SETUP_S3_L1" => "Databázový server",
	"SETUP_S3_L1_P" => "Sem vložte IP adresu/doménové jméno databázového serveru",
	"SETUP_S3_L2" => "Jméno databáze",
	"SETUP_S3_L2_P" => "Sem vložte jméno databáze",
	"SETUP_S3_L3" => "Uživatel databáze",
	"SETUP_S3_L3_P" => "Sem vložte uživatele databáze",
	"SETUP_S3_L4" => "Heslo uživatele",
	"SETUP_S3_L4_P" => "Sem vložte heslo uživatele databáze",
	"SETUP_S3_B1" => "Nainstalovat databázi",
	"SETUP_S3_B1_H" => "Tento krok může trvat o něco déle, systém musí správně nastavit a nainstalovat databázi.",

	"SETUP_S4_T1" => "Základní nastavení webových stránek",
	"SETUP_S4_L1" => "Název webových stránek",
	"SETUP_S4_L1_P" => "Sem vložte název vašich stránek",
	"SETUP_S4_L2" => "Email webových stránek", 
	"SETUP_S4_L2_P" => "Sem vložte emailovou adresu, která bude použita pro stránku.",
	"SETUP_S4_B1" => "Nastavit webové stránky",

	"SETUP_S5_T1" => "Vytvoření administátora",
	"SETUP_S5_L1" => "Přihlašovací jméno",
	"SETUP_S5_L1_P" => "Sem vložte přihlašovací jméno pro administrátora",
	"SETUP_S5_L2" => "Emailová adresa",
	"SETUP_S5_L2_P" => "Sem vložte emailovou adresu administrátora",
	"SETUP_S5_L3" => "Přihlašovací heslo",
	"SETUP_S5_L3_P" => "Sem vložte přihlašovací heslo pro administrátora",
	"SETUP_S5_L4" => "Přihlašovací heslo <i>(znovu)</i>",
	"SETUP_S5_L4_P" => "Sem vložte přihlašovací heslo pro administrátora",
	"SETUP_S5_B1" => "Vytvořit účet administrátora",
	"SETUP_S5_B1_H" => "V tomto kroce vytváříme administrátorký účet, díky kterému budete moci ovládat vaše webové stránky.",

	"SETUP_S6_T1" => "Instalace dokončena",
	"SETUP_S6_T2" => 
	"Systém je nyní nainstalován, můžete pokračovat na vaše webové stránky, o které se nyní stará <b>Domm Web Engine 7 (DWE7)</b>.",
	"SETUP_S6_B1" => "Pokračovat na webové stránky",

	"SETUP_S1_E1" => "Nebyl nalezen žádný jazykový balíček, systém je s velkou pravděpodobností poškozen. Doporučujeme vám, provés jeho nahrání na webový server ještě jednou.",
	
	"SETUP_S3_OK" => "Připojení k databázi bylo úspěšné.",
	"SETUP_S3_E1" => "Problém s připojením k databázi. (%s)",

	/* Administration */
	"ADMIN_T1" => "Administrace",

	"ADMIN_WINDOW_1" => "Aktivní",
	"ADMIN_WINDOW_2" => "Nastavení",
	"ADMIN_WINDOW_3" => "Odhlásit se",
	"ADMIN_WINDOW_4" => "Poslední aktivita",
	
	"ADMIN_WINDOW_5" => "Zobrazit stránky",
	"ADMIN_WINDOW_6" => "Nový příspěvek",

	"ADMIN_WINDOW_7" => "Zobrazit všechny",
	"ADMIN_WINDOW_8" => "<b>%s</b> napsal komentář k <b>%s</b>",

	"ADMIN_MENU_1" => "Hlavní nabídka",
	"ADMIN_MENU_1_1" => "Nástěnka",
	"ADMIN_MENU_2" => "Správa obsahu",
	"ADMIN_MENU_2_1" => "Novinky",
	"ADMIN_MENU_2_2" => "Kategorie novinek",
	"ADMIN_MENU_2_3" => "Komentáře",
	"ADMIN_MENU_2_4" => "Stránky",
	"ADMIN_MENU_2_5" => "Nahrané soubory",
	"ADMIN_MENU_3" => "Správa uživatelů",
	"ADMIN_MENU_3_1" => "Uživatelé",
	"ADMIN_MENU_3_2" => "Uživatelské skupiny",
	"ADMIN_MENU_3_3" => "Hromadná zpráva",
	"ADMIN_MENU_3_4" => "Nahlášení",
	"ADMIN_MENU_4" => "Nastavení stránek",
	"ADMIN_MENU_4_1" => "Hlavní nastavení",
	"ADMIN_MENU_4_2" => "Ostatní nastavení",
	"ADMIN_MENU_4_3" => "Správa sidebarů",
	"ADMIN_MENU_4_4" => "Správa pluginů",
	"ADMIN_MENU_4_5" => "Vzhled",
	"ADMIN_MENU_4_6" => "Menu",
	"ADMIN_MENU_4_7" => "Aktualizace",
	"ADMIN_MENU_5" => "Ostatní",

	"ADMIN_LEAVE_1" => "Byl jste úspěšně odhlášen z administrace.",
	"ADMIN_LEAVE_2" => "Vaše sezení uplynulo, byl jste odhlášen z administrace.",

	/* Administration - subpage dashboard */
	"ADMIN_DASHBOARD_T1" => "Nástěnka",

	"ADMIN_DASHBOARD_T2" => "Uživatelské účty",
	"ADMIN_DASHBOARD_T3" => "Uživatelské skupiny",
	"ADMIN_DASHBOARD_T4" => "Příspěvky",
	"ADMIN_DASHBOARD_T5" => "Verze systému",

	"ADMIN_DASHBOARD_T6" => "Uživatelé",
	"ADMIN_DASHBOARD_T7" => "Soubory",
	"ADMIN_DASHBOARD_T8" => "Příspěvky",
	"ADMIN_DASHBOARD_T9" => "DWE Novinky",

	"ADMIN_DASHBOARD_L1" => "Celkově uživatelů",
	"ADMIN_DASHBOARD_L2" => "Neaktivovaných uživatelů",
	"ADMIN_DASHBOARD_L3" => "Zablokovaných uživatelů",
	"ADMIN_DASHBOARD_L4" => "Nejnovější uživatel",
	"ADMIN_DASHBOARD_L5" => "Nahraných souborů",
	"ADMIN_DASHBOARD_L6" => "Velikost nahraných souborů",
	"ADMIN_DASHBOARD_L7" => "Počet novinek",
	"ADMIN_DASHBOARD_L8" => "Počet komentářů",

	"ADMIN_DASHBOARD_L9" => "Název webových stránek",
	"ADMIN_DASHBOARD_L10" => "Vzhled",
	"ADMIN_DASHBOARD_L13" => "Systém",

	"ADMIN_DASHBOARD_L11" => "Není možné načíst novinky systému.",
	"ADMIN_DASHBOARD_L12" => "Počet stránek",

	"ADMIN_DASHBOARD_B1" => "Zobrazit více",

	"ADMIN_AUTH_E1" => "Nastala chyba při ověření bezpečnostního tokenu.",
	"ADMIN_AUTH_E2" => "Nebyl nalezen ověřovací token.",
	"ADMIN_AUTH_OK" => "Přihlašuji vás, do administrace, pod účtem <b>%s</b>.",

	"ADMIN_N1" => "Budete přesměrován zpět na webové stránky.",
	"ADMIN_N2" => "Budete přesměrován do administrace webových stránek.",
	
	/* Administration - subpage updates*/
	

	/* Administration - subpage uploads*/ 
	"ADMIN_UPL_T1" => "Správa nahraných souborů",
	"ADMIN_UPL_T2" => "Nahrané soubory",
	"ADMIN_UPL_T3" => "Nahrání souboru",
	"ADMIN_UPL_T4" => "Zobrazení souboru - %s",
	
	"ADMIN_UPL_T5" => "Náhled tohoto souboru není podporován.",
	
	"ADMIN_UPL_L1" => "Název souboru",
	"ADMIN_UPL_L2" => "Typ souboru",
	"ADMIN_UPL_L3" => "Odkaz na soubor",

	"ADMIN_UPL_B1" => "Nahrát soubor",
	"ADMIN_UPL_B2" => "Zpět na přehled nahraných souborů",
	"ADMIN_UPL_B3" => "Odstranit soubor",

	/* Administration - subpage sidebars*/
	"ADMIN_SB_T1" => "Správa sidebarů",
	"ADMIN_SB_T2" => "Přehled sidebarů",
	"ADMIN_SB_T3" => "Úprava sidebaru",
	"ADMIN_SB_T4" => "Odstranění sidebaru",
	"ADMIN_SB_T5" => "Právě se chystáte nenávratně odstranit sidebar <b>%s</b>. Odstranění sidebaru je trvalé a nezvratné.<br>Opravdu chcete sidebar trvale odstranit?",

	"ADMIN_SB_L1" => "aktivní",

	"ADMIN_SB_L2" => "Název sidebaru",
	"ADMIN_SB_L3" => "Titulek sidebaru",
	"ADMIN_SB_L4" => "Obsah sidebaru",
	"ADMIN_SB_L4-1" => "Není k dispozici žádný plugin s vlastním sidebarem.",
	"ADMIN_SB_L5" => "Styl sidebaru",
	"ADMIN_SB_L6" => "Pořadí sidebaru",
	"ADMIN_SB_L7" => "Zobrazit sidebar",
	"ADMIN_SB_L8" => "Pozice sidebaru",

	"ADMIN_SB_POS:top" => "Nahoře",
	"ADMIN_SB_POS:bottom" => "Dole",
	"ADMIN_SB_POS:left" => "Vlevo",
	"ADMIN_SB_POS:right" => "Vpravo",

	"ADMIN_SB_B1" => "Vytvořit sidebar",
	"ADMIN_SB_B2" => "Vytvořit sidebar z pluginu",

	"ADMIN_SB_B3" => "Odstranit sidebar",
	"ADMIN_SB_B4" => "Upravit sidebar",
	"ADMIN_SB_B5" => "Zpět do přehledu sidebarů",
	"ADMIN_SB_B6" => "Nemazat",

	"ADMIN_SB_OK1" => "Sidebar byl úspěšně upraven.",
	"ADMIN_SB_OK2" => "Sidebar byl úspěšně odstraněn.",

	/* Administration - subpage news */
	"ADMIN_NEWS_T1" => "Správa příspěvků",
	"ADMIN_NEWS_T2" => "Přehled příspěvků",
	"ADMIN_NEWS_T3" => "Žádná kategorie",
	"ADMIN_NEWS_T4" => "Úprava příspěvku",
	"ADMIN_NEWS_T5" => "Nový příspěvek",
	"ADMIN_NEWS_T6" => "Nastavení publikace",
	"ADMIN_NEWS_T7" => "Datum vytvoření",
	"ADMIN_NEWS_T8" => "Autor",
	"ADMIN_NEWS_T9" => "Upraveno",
	"ADMIN_NEWS_T10" => "Odstranění příspěvku",
	"ADMIN_NEWS_T11" => "Právě se chystáte nenávratně odstranit příspěvek <b>%s</b>. Odstranění příspěvku je trvalé a nezvratné.<br>Opravdu chcete příspěvek trvale odstranit?",
	"ADMIN_NEWS_T12" => "Novinka nemá vyplněný svůj odkaz, proto není veřejná a nelze jí zobrazit, upravovat však lze.",

	"ADMIN_NEWS_B1" => "Nový příspěvek",
	"ADMIN_NEWS_B2" => "Zrušit úpravy",
	"ADMIN_NEWS_B3" => "Zobrazit příspěvek",
	"ADMIN_NEWS_B4" => "Uložit změny",
	"ADMIN_NEWS_B5" => "Smazat příspěvek",
	"ADMIN_NEWS_B6" => "Ano, smazat příspěvek",
	"ADMIN_NEWS_B7" => "Nemazat",
	
	"ADMIN_NEWS_L1" => "Název příspěvku",
	"ADMIN_NEWS_L2" => "Autor",
	"ADMIN_NEWS_L3" => "Kategorie",
	"ADMIN_NEWS_L4" => "Datum",

	"ADMIN_NEWS_L5" => "Název příspěvku",
	"ADMIN_NEWS_L6" => "Kategorie příspěvku",
	"ADMIN_NEWS_L6N" => "Žádná kategorie",
	"ADMIN_NEWS_L7" => "Obsah příspěvku",
	"ADMIN_NEWS_L8" => "Viditelnost příspěvku",
	"ADMIN_NEWS_L8-1" => "Neveřejný",
	"ADMIN_NEWS_L8-2" => "Veřejný",
	"ADMIN_NEWS_L9" => "Komentáře",
	"ADMIN_NEWS_L10" => "Povolit komentáře",
	"ADMIN_NEWS_L11" => "Odkaz",
	"ADMIN_NEWS_L12" => "Celý odkaz",
	"ADMIN_NEWS_L12-1" => "Použít odkaz: /<b>název_příspěvku</b>/",
	"ADMIN_NEWS_L12-2" => "Použít odkaz: /<b>ID_příspěvku</b>-<b>název_příspěvku</b>/",
	"ADMIN_NEWS_L12-3" => "Použít odkaz: /<b>rok</b>-<b>měsíc</b>-<b>den</b>-<b>název_příspěvku</b>/",
	"ADMIN_NEWS_L12-0" => "Použít vlastní",
	"ADMIN_NEWS_L12-4" => "Použít přednastavený",

	"ADMIN_NEWS_L13" => "Tagy příspěvku <i>(jednotlivé tagy oddělujte pouze pomocí <b>,</b>)</i>",
	"ADMIN_NEWS_L14" => "Keywords příspěvku <i>(jednotlivé keywords oddělujte pouze pomocí <b>,</b>)</i>",
	"ADMIN_NEWS_L15" => "Krátký popis toho o čem příspěvek je. <i>(bez html)</i>",
	
	"ADMIN_NEWS_E1" => "Nebyli nalezeny žádné příspěvky.", 
	"ADMIN_NEWS_OK1" => "Novinka byla úspěšně upravena.",
	"ADMIN_NEWS_OK2" => "Novinka byla úspěšně odstraněna.",

	/* Administration - subpage menu */
	"ADMIN_MN_T1" => "Menu",
	"ADMIN_MN_T2" => "Správa menu",
	"ADMIN_MN_T3" => "Úprava menu",
	"ADMIN_MN_T4" => "Odstranění menu",
	"ADMIN_MN_T5" => "Právě se chystáte nenávratně odstranit menu <b>%s</b>. Odstranění menu je trvalé a nezvratné.<br>Opravdu chcete menu trvale odstranit?",

	"ADMIN_MN_T6" => "Úprava odkazu",
	"ADMIN_MN_T7" => "Právě se chystáte nenávratně odstranit odkaz <b>%s</b>. Odstranění odkazu je trvalé a nezvratné.<br>Opravdu chcete odkaz trvale odstranit?",
	"ADMIN_MN_T8" => "Odstranění odkazu",
	"ADMIN_MN_T9" => "Odstranění kategorie menu",
	"ADMIN_MN_T10" => "Právě se chystáte nenávratně odstranit kategorii menu <b>%s</b>. Odstranění kategorie menu je trvalé a nezvratné.<br>Opravdu chcete kategorii menu trvale odstranit?",
	"ADMIN_MN_T11" => "Úprava kategorie menu",

	"ADMIN_MN_L1" => "Úprava menu",
	"ADMIN_MN_L1-L" => "Zvolte menu pro úpravu",
	"ADMIN_MN_L2" => "Primární menu",
	"ADMIN_MN_L3" => "Název nového menu",
	"ADMIN_MN_L4" => "Název menu",
	"ADMIN_MN_L5" => "Menu zatím neobsahuje žádné odkazy ani kategorie.",
	"ADMIN_MN_L5-L" => "Kategorie nemá žádné odkazy, proto se na webových stránkách nezobrazí.",

	"ADMIN_MN_L6" => "Odkaz",
	"ADMIN_MN_L7" => "Pořadí",
	"ADMIN_MN_L8" => "Název odkazu",
	"ADMIN_MN_L9" => "Název kategorie menu",

	"ADMIN_MN_B1" => "Upravit menu",
	"ADMIN_MN_B2" => "Nastavit primární menu",
	"ADMIN_MN_B3" => "Vytvořit menu",

	"ADMIN_MN_B4" => "Zavřít úpravu menu",
	"ADMIN_MN_B5" => "Uložit název menu",

	"ADMIN_MN_B6" => "Vytvořit odkaz",
	"ADMIN_MN_B7" => "Vytvořit kategorii",
	"ADMIN_MN_B8" => "Upravit odkaz",
	"ADMIN_MN_B9" => "Upravit kategorii",
	"ADMIN_MN_B10" => "Odstranit menu",


	"ADMIN_MN_B11" => "Ano, odstranit menu",
	"ADMIN_MN_B12" => "Nemazat",

	"ADMIN_MN_B13" => "Odstranit odkaz",
	"ADMIN_MN_B14" => "Odstranit kategorii",

	"ADMIN_MN_D:L" => "Odkaz",
	"ADMIN_MN_D:K" => "Kategorie",

	"ADMIN_MN_N:L" => "Nový odkaz",
	"ADMIN_MN_N:K" => "Nová kategorie",

	"ADMIN_MN_OK1" => "Menu bylo úspěšně odstraněno.",
	"ADMIN_MN_OK2" => "Menu bylo úspěšně nastaveno jako primární.",
	"ADMIN_MN_OK3" => "Menu bylo úspěšně vytvořeno.",
	"ADMIN_MN_OK4" => "Název menu byl úspěšně upraven.",
	"ADMIN_MN_OK5" => "Odkaz byl úspěšně upraven.",
	"ADMIN_MN_OK6" => "Odkaz byl úspěšně odstraněn.",
	"ADMIN_MN_OK7" => "Kategorie byla úspěšně odstraněna z menu.",
	"ADMIN_MN_OK8" => "Kategorie byla úspěšně upravena.",

	"ADMIN_MN_E1" => "Odkaz v menu musí být odkaz.",
	"ADMIN_MN_E2" => "Pořadí odkazu musí být číslo.",
	
	/* Administration - subpage news_categories */
	"ADMIN_NC_T1" => "Správa kategorií",
	"ADMIN_NC_T2" => "Přehled kategorií",
	"ADMIN_NC_T3" => "Úprava kategorie",
	
	"ADMIN_NC_T4" => "Odstranění kategorie",
	"ADMIN_NC_T5" => "Právě se chystáte nenávratně odstranit kategorii <b>%s</b>. Odstranění kategorie je trvalé a nezvratné.<br>Opravdu chcete kategorii trvale odstranit?",
	"ADMIN_NC_T6" => "Kategorie příspěkvů nemá vyplněný svůj odkaz, proto není veřejná a nelze jí zobrazit, upravovat však lze.",
	
	"ADMIN_NC_B1" => "Přidat kategorii",
	"ADMIN_NC_B2" => "Zrušit úpravy",
	"ADMIN_NC_B3" => "Zobrazit kategorii",
	"ADMIN_NC_B4" => "Uložit změny",
	"ADMIN_NC_B5" => "Smazat kategorii",
	"ADMIN_NC_B6" => "Ano, odstranit kategorii",
	"ADMIN_NC_B7" => "Nemazat",
	
	"ADMIN_NC_L1" => "Název kategorie",
	"ADMIN_NC_L2" => "Příspěvků v kategorii",
	"ADMIN_NC_L3" => "Popis kategorie",
	
	"ADMIN_NC_L4-1" => "příspěvky",
 	"ADMIN_NC_L4-2" => "příspěvek",
 	"ADMIN_NC_L4-3" => "příspěvků",
	//"ADMIN_NC_L4-4" => "příspěvků",
	"ADMIN_NC_L5" => "Kategorie nemá vyplněný popis",
	"ADMIN_NC_L6" => "Název kategorie",
	"ADMIN_NC_L7" => "Odkaz kategorie",
	"ADMIN_NC_L8" => "Popis kategorie",

	"ADMIN_NC_OK1" => "Kategorie příspěvků byla úspěšně odstraněna.",
	"ADMIN_NC_OK2" => "Kategorie příspěvků byla úspěšně upravena.",

	/* Administration - subpage pages */
	"ADMIN_P_T1" => "Správa stránek",
	"ADMIN_P_T2" => "Přehled stránek",
	"ADMIN_P_T3" => "Nebyla provedena žádná úprava.",
	"ADMIN_P_T4" => "Úprava stránky",
	"ADMIN_P_T5" => "Nastavení publikace",

	"ADMIN_P_T6" => "Datum vytvoření",
	"ADMIN_P_T7" => "Autor",
	"ADMIN_P_T8" => "Upraveno",
	"ADMIN_P_T9" => "Odstranění stránky",
	"ADMIN_P_T10" => "Právě se chystáte nenávratně odstranit stránku <b>%s</b>. Odstranění stránky je trvalé a nezvratné.<br>Opravdu chcete stránku trvale odstranit?",
	"ADMIN_P_T11" => "Stránka nemá vyplněný svůj odkaz, proto není veřejná a nelze jí zobrazit, upravovat však lze.",
	
	"ADMIN_P_L1" => "Název stránky",
	"ADMIN_P_L2" => "Autor stránek",
	"ADMIN_P_L3" => "Datum vytvoření",
	"ADMIN_P_L4" => "Poslední úprava",
	
	"ADMIN_P_L5" => "Název stránky",
	"ADMIN_P_L6" => "Obsah stránky",
	
	"ADMIN_P_L7" => "Viditelnost stránky",
	"ADMIN_P_L7-1" => "Neveřejná",
	"ADMIN_P_L7-2" => "Veřejná",
	"ADMIN_P_L8" => "Komentáře",
	"ADMIN_P_L9" => "Povolit komentáře",
	"ADMIN_P_L10" => "Odkaz",
	
	"ADMIN_P_B1" => "Nová stránka",
	"ADMIN_P_B2" => "Zrušit úpravy",
	"ADMIN_P_B3" => "Zobrazit stránku",
	"ADMIN_P_B4" => "Uložit změny",
	"ADMIN_P_B5" => "Smazat stránku",
	"ADMIN_P_B6" => "Ano, smazat stránku",
	"ADMIN_P_B7" => "Nemazat",

	"ADMIN_P_OK1" => "Stránka byla úspěšně odstraněna.", 
	"ADMIN_P_OK2" => "Stránka byla úspěšně upravena.",

	/* Administration - subpage plugins */
	"ADMIN_PL_T1" => "Správa pluginů",
	"ADMIN_PL_T2" => "Instalace pluginu",
	
	"ADMIN_PL_T3" => "Instalace pluginu <b>%s</b>.",
	"ADMIN_PL_T4" => "Nalezen instalační soubor pluginu.",
	"ADMIN_PL_T5" => "Zařazení pluginu mezi aktivní pluginy.",
	"ADMIN_PL_T6" => "Plugin bude bezpečně odebrán..",
	"ADMIN_PL_T6-2" => "Plugin bude odebrán..",
	
	"ADMIN_PL_T7" => "Odinstalace pluginu",
	"ADMIN_PL_T8" => "Odinstalace pluginu <b>%s</b>",
	
	"ADMIN_PL_T9" => "Nastavení pluginu",
	"ADMIN_PL_T10" => "Nastavení pluginu <b>%s</b>",
	
	"ADMIN_PL_OK1" => "Plugin byl úspěšně nainstalován.", 
	"ADMIN_PL_OK2" => "Plugin byl úspěšně odinstalován.",
	
	"ADMIN_PL_E1" => "Chyba při instalaci pluginu.",
	"ADMIN_PL_E2" => "Chyba při odinstalaci pluginu.",
	
	"ADMIN_PL_B1" => "Informace",
	"ADMIN_PL_B2" => "Odinstalovat",
	"ADMIN_PL_B3" => "Nainstalovat",
	"ADMIN_PL_B4" => "Nastavení",
	"ADMIN_PL_B5" => "Zpět do přehledu pluginů",
	
	/* Administration - subpage comments */
	"ADMIN_C_T1" => "Komentáře",
	"ADMIN_C_T2" => "Správa komentářů",
	"ADMIN_C_T3" => "Zobrazení komentáře",
	"ADMIN_C_T4" => "okomentoval příspěvek",
	"ADMIN_C_T5" => "Odstranění komentáře",
	"ADMIN_C_T6" => "Právě se chystáte nenávratně odstranit komentář uživatele <b>%s</b>, u příspěvku <b>%s</b>. Odstranění komentáře je trvalé a nezvratné.<br>Opravdu chcete komentář trvale odstranit?",
	"ADMIN_C_T7" => "Komentář byl úspěšně odstraněn.",

	"ADMIN_C_L1" => "Uživatel",
	"ADMIN_C_L2" => "Příspěvek",
	"ADMIN_C_L3" => "Datum",
	"ADMIN_C_L4" => "Komentář",

	"ADMIN_C_B1" => "Zavřít náhled komentáře",
	"ADMIN_C_B2" => "Smazat komentář",
	"ADMIN_C_B3" => "Ano, smazat komentář",
	"ADMIN_C_B4" => "Nemazat",

	/* Administration - subpage message*/
	"ADMIN_M_T1" => "Hromadná zpráva",
	"ADMIN_M_T2" => "Nová hromadná zpráva",
	
	"ADMIN_M_T3" => "Odeslané hromadné zprávy",

	"ADMIN_M_OK1" => "Hromadná zpráva úspěšně odeslána všem uživatelům webových stránek.",
	"ADMIN_M_E1" => "Nemáte žádné odeslané hromadné zprávy.",

	/* Administration - subpage main_settings */
	"ADMIN_MS_T1" => "Hlavní nastavení",
	"ADMIN_MS_T2" => "Hlavní nastavení webových stránek",

	"ADMIN_MS_L1" => "Název webových stránek",
	"ADMIN_MS_L2" => "E-mailová adresa webových stránek",
	"ADMIN_MS_L3" => "Url Adresa webových stránek",
	"ADMIN_MS_L4" => "Přístupový protokol", //http/https
	"ADMIN_MS_L5" => "Základní jazyk webových stránek",
	"ADMIN_MS_L6" => "Keywords stránky <i>(jednotlivé keywords oddělujte pouze pomocí <b>,</b>)</i>",
	"ADMIN_MS_L7" => "Krátký popis toho o čem stránka je. <i>(bez html)</i>",
	
	"ADMIN_MS_B1" => "Uložit nastavení",

	"ADMIN_MS_OK" => "Nastavení bylo úspěšně uloženo.",
	"ADMIN_MS_E1" => "Chybně zadaná emailová adresa webových stránek.",
	"ADMIN_MS_E2" => "Chybně zadaná url adresa webových stránek.",
	
	/* Administration - subpage other_settings */
	"ADMIN_OS_T1" => "Ostatní nastavení",
	"ADMIN_OS_T2" => "Ostatní nastavení webových stránek",

	"ADMIN_OS_L1" => "Nastavení odkazů novinek",
	"ADMIN_OS_L2" => "Počet novinek na stránku",
	"ADMIN_OS_L3" => "Počet komentářů v základním zobrazení",
	"ADMIN_OS_L4" => "Mód údržby",
	"ADMIN_OS_L4l" => "Popis módu údržby",
	
	"ADMIN_OS_L4-1" => "Vypnut",
	"ADMIN_OS_L4-3" => "Pouze pro plná oprávnění",

	"ADMIN_OS_L5" => "Velikost avatara <i>(v px)</i>, avatary jsou formovány do čtverců",
	"ADMIN_OS_L6" => "Velikost avatara <i>(v byte)</i>",

	"ADMIN_OS_L7" => "Nastavení úvodní stránky",
	"ADMIN_OS_L7-1" => "Stránka s novinkami",
	"ADMIN_OS_L7-2" => "Vlastní stránka", 
	
	"ADMIN_OS_B1" => "Uložit nastavení",
  
	"ADMIN_OS_OK" => "Nastavení bylo úspěšně uloženo.",
	"ADMIN_OS_E1" => "Hodnoty u počtu komentářů a novinek na stránce, musí být numerické.",
	
	/* Administration - subpage design */
	"ADMIN_D_T1" => "Nastavení vzhledu",
	"ADMIN_D_T2" => "Přehled vzhledů",
	"ADMIN_D_T3" => "Přehled vzhledů pro administraci",
	"ADMIN_D_T4" => "Přehled vzhledů pro webové stránky",
	"ADMIN_D_T5" => "Autor",
	"ADMIN_D_T6" => "Tento vzhled se aktuálně používá.",
	"ADMIN_D_T7" => "Tato šablona neobsahuje náhledový obrázek.",
	"ADMIN_D_T8" => "Šablona má poškozený konfigurační soubor.",
	"ADMIN_D_T9" => "Šablona má poškozené soubory.",

	"ADMIN_D_L1" => "Barva menu administrace",
	"ADMIN_D_L1-1" => "Tmavé",
	"ADMIN_D_L1-2" => "Světlé",

	"ADMIN_D_L2" => "Barva administrace",
	"ADMIN_D_L2-1" => "Modrá",
	"ADMIN_D_L2-2" => "Bílá",
	"ADMIN_D_L2-3" => "Fialová",
	"ADMIN_D_L2-4" => "Zelená",
	"ADMIN_D_L2-5" => "Červená",
	"ADMIN_D_L2-6" => "Žlutá",

	"ADMIN_D_B1" => "Přizpůsobit si vzhled",
	"ADMIN_D_B2" => "Nastavit základní vzhled",
	"ADMIN_D_B3" => "Nastavit tento vzhled",
	"ADMIN_D_B4" => "Aktuální vzhled",

	"ADMIN_D_E1" => "Musíte vyplnit všechna pole nastavení administrace.",
	"ADMIN_D_OK1" => "Vzhled webových stránek byl úspěšně změněn.",
	
	/* Administration - subpage users */
	"ADMIN_U_T1" => "Správa uživatelů",
	"ADMIN_U_T2" => "Přehled uživatelů",
	"ADMIN_U_T3" => "Uživatelský profil",
	"ADMIN_U_T4" => "Restartování avatara",
	"ADMIN_U_T5" => "Právě se chystáte nevratně restartovat avatara uživatele <b>%s</b>, uživateli bude nastaven základní avatar, a situaci bude informován soukromou zprávou.",
	
	"ADMIN_U_T6" => "Zablokování uživatele",
	"ADMIN_U_T7" => "Právě se chystáte zablokovat uživatele <b>%s</b>, uživatelský účet bude zablokován a již se k němu nebude dát přihlásit, dokud jej neodblokujete, uživatel bude o situaci informován e-mailovou zprávou.",

	"ADMIN_U_T8" => "Odstanění uživatele",
	"ADMIN_U_T9" => "Právě se chystáte nevratně odstranit uživatele <b>%s</b>, stránky a příspěvky uživatele zůstanou zachovány, odstraní se pouze jeho zprávy a komentáře. Uživatel bude o situaci informován e-mailovou zprávou.",

	"ADMIN_U_T10" => "Nedostatečná oprávnění",
	"ADMIN_U_T11" => "Nemáte dostatečná oprávnění k zobrazení profilu uživatele, který má vyšší oprávnění než vy.",
	
	"ADMIN_U_L1" => "Zobrazované jméno + <i>(uživatelské jméno)</i>",
	"ADMIN_U_L2" => "Primární skupina",
	"ADMIN_U_L3" => "Datum registrace",
	"ADMIN_U_L4" => "Datum poslední aktivity",
	"ADMIN_U_L5" => "Žádná primární skupina",
	
	"ADMIN_U_L6" => "Uživatelské jméno",
	"ADMIN_U_L7" => "Zobrazované jméno",
	"ADMIN_U_L8" => "Uživatelský avatar",
	"ADMIN_U_L9" => "Uživatelský email",
	"ADMIN_U_L10" => "Uživatelský podpis",
	"ADMIN_U_L11" => "Primární skupina",
	"ADMIN_U_L12" => "Důvod odstranění avatara",
	"ADMIN_U_L13" => "Základní oprávnění uživatele",
	"ADMIN_U_L14" => "Důvod zablokování uživatele",
	"ADMIN_U_L15" => "Důvod odstranění uživatele",

	"ADMIN_U_L16" => "Je členem pouze jedné skupiny (<strong>%s</strong>).",
	"ADMIN_U_L17" => "Zatím není členem žádné skupiny",
	
	"ADMIN_U_B1" => "Zavřít uživatelský profil",
	"ADMIN_U_B2" => "Restartovat avatar",
	"ADMIN_U_B3" => "Zobrazit profil",
	"ADMIN_U_B4" => "Zablokovat uživatele",
	"ADMIN_U_B5" => "Odstranit uživatele",
	"ADMIN_U_B6" => "Nerestartovat avatara",
	"ADMIN_U_B7" => "Uložit změny",
	"ADMIN_U_B8" => "Zablokovat uživatele",
	"ADMIN_U_B9" => "Neblokovat uživatele",
	"ADMIN_U_B10" => "Odstranit uživatele",
	"ADMIN_U_B11" => "Neodstraňovat uživatele",
	"ADMIN_U_B12" => "Zpět na přehled uživatelů",
	
	"ADMIN_U_OK1" => "Profilový obrázek (uživatelský avatar), byl uživateli úspěšně restartován.",
	"ADMIN_U_OK2" => "Uživatelský účet byl úspěšně zablokován.",
	"ADMIN_U_OK3" => "Uživatelský účet byl úspěšně odstraněn.",
	"ADMIN_U_OK4" => "Uživatelský účet byl úspěšně upraven.",
	
	/* Administration - subpage usergroups */
	"ADMIN_UG_T1" => "Správa uživatelských skupin",
	"ADMIN_UG_T2" => "Přehled uživatelských skupin",
	"ADMIN_UG_T3" => "Členové skupiny",
	"ADMIN_UG_T4" => "Správa skupiny - %s",
	"ADMIN_UG_T5" => "V této skupině nejsou žádní uživatelé.",
	"ADMIN_UG_T6" => "Odstranění skupiny",
	"ADMIN_UG_T7" => "Právě se chystáte nenávratně odstranit uživatelskou skupinu <b>%s</b>. Odstranění uživatelské skupiny je trvalé a nezvratné.<br>Opravdu chcete uživatelskou skupinu trvale odstranit?",

	"ADMIN_UG_T8" => "Přidání člena do skupiny",
	"ADMIN_UG_T9" => "Uživatelská skupina nemá vyplněný svůj odkaz, proto není veřejná a nelze do ní nikoho přidat, upravovat však lze.",

	"ADMIN_UG_L1" => "Uživatelská skupina",
	"ADMIN_UG_L2" => "Tag uživatelské skupiny",
	"ADMIN_UG_L3" => "Členové skupiny",

	"ADMIN_UG_L4" => "Uživatel",
	"ADMIN_UG_L5" => "Akce",

	"ADMIN_UG_L6" => "Název skupiny",
	"ADMIN_UG_L7" => "Text na tagu",
	"ADMIN_UG_L8" => "Barva tagu",
	"ADMIN_UG_L9" => "Barva textu na tagu",
	"ADMIN_UG_L10" => "Odkaz skupiny",
	"ADMIN_UG_L11" => "Popis skupiny",
 		
 	"ADMIN_UG_L12" => "Uživatelské jméno",
 	"ADMIN_UG_L12-L" => "Sem zadejte uživatelské jméno, pokud jej nevíte, můžete zkusit zadat zobrazované jméno.",

	"ADMIN_UG_B1" => "Nová skupina",
	"ADMIN_UG_B2" => "Zpět do správy skupin",
	"ADMIN_UG_B3" => "Odebrat ze skupiny",
	"ADMIN_UG_B4" => "Přidat uživatele",

	"ADMIN_UG_B5" => "Uložit změny",
	"ADMIN_UG_B6" => "Zobrazit skupinu",
	"ADMIN_UG_B7" => "Odstranit skupinu",

	"ADMIN_UG_B8" => "Ano, odstranit uživatelskou skupinu",
	"ADMIN_UG_B9" => "Nemazat",

	"ADMIN_UG_B10" => "Přidat člena",
	"ADMIN_UG_B11" => "Zpět do správy skupiny",

	"ADMIN_UG_OK1" => "Uživatelská skupina úspěšně odstraněna.",
	"ADMIN_UG_OK2" => "Uživatel byl úspěšně přidán do skupiny.",
	"ADMIN_UG_OK3" => "Uživatelská skupina byla úspěšně upravena.",

	"ADMIN_UG_E1" => "Uživatel nebyl nalezen.",

	/* Administration - subpage reports */ 
	"ADMIN_RP_T1" => "Správa nahlášení",
	"ADMIN_RP_T2" => "Přehled nahlášení",
	"ADMIN_RP_T3" => "Detail nahlášení",
	"ADMIN_RP_T4" => "Správa nahlášení",
	"ADMIN_RP_T5" => "Zpráva nahlášení",
	"ADMIN_RP_T5-L" => "Zpráva pro uživatele, který poslal nahlášení",
	"ADMIN_RP_T6" => "Odstranění nahlášení",
	"ADMIN_RP_T7" => "Právě se chystáte nenávratně odstranit nahlášení <b>%s</b>. Odstranění nahlášení je trvalé a nezvratné.<br>Opravdu chcete nahlášení trvale odstranit?",

	"ADMIN_RP_T8" => "Vyřešené nahlášení",

	"ADMIN_RP_L1" => "Nahlášení",
	"ADMIN_RP_L2" => "Typ nahlášení",
	"ADMIN_RP_L3" => "Uživatel",
	"ADMIN_RP_L4" => "Datum nahlášení",
	"ADMIN_RP_L5" => "Zobrazeno",

	"ADMIN_RP_L5-1" => "Nahlášení zobrazeno",
	"ADMIN_RP_L5-2" => "Nahlášení vyřešeno",
	
	"ADMIN_RP_N_TYPE_user" => "<b>%s</b> nahlásil uživatele <b>%s</b>.",
	"ADMIN_RP_N_TYPE_comment" => "<b>%s</b> nahlásil komentář.",
	
	"ADMIN_RP_TYPE_user" => "Nahlášení uživatele",
	"ADMIN_RP_TYPE_comment" => "Nahlášení komentáře",

	"ADMIN_RP_OK1" => "Nahlášení bylo úspěšně odstraněno.",
	"ADMIN_RP_OK2" => "Nahlášení bylo úspěšně vyřešeno.",

	"ADMIN_RP_E1" => "Neexistuje zpráva z nahlášení.",

	"ADMIN_RP_B1" => "Vyřešit nahlášení",
	"ADMIN_RP_B2" => "Odstranit nahlášení",
	"ADMIN_RP_B3" => "Neodstraňovat nahlášení",

	"ADMIN_RP_MESSAGE" => "<b>Zpráva z nahlášení</b><br>
	Nahlášení odesláno: <b>%s</b><br>
	<b>%s</b><br>
	<b>Text nahlášení:</b><br>
	%s
	<hr>
	Nahlášení vyřešeno - <b>%s</b> - %s<br>
	<b>Zpráva z nahlášení:</b><br>
	%s
	", // (Report Timestamp sent, report type, report content, report timestamp solved, report admin, report msg)

	/* Administration - subpage update */ 
	"ADMIN_UP_T1" => "Aktualizace systému",
	"ADMIN_UP_T2" => "Naposledy zjištěno: <b>%s</b>",
	"ADMIN_UP_T3" => "Poslední aktualizace: <b>%s</b> <i>(%s)</i>",

	"ADMIN_UP_T4" => "Jsou k dispozici nové aktualizace.",

	"ADMIN_UP_T5" => "Vyhledávání aktualizací",
	"ADMIN_UP_T6" => "Bylo spuštěno vyhledávání aktualizací..",

	"ADMIN_UP_T7" => "Průběh aktualizace",
	"ADMIN_UP_T8" => "Název aktualizace",
	"ADMIN_UP_T9" => "Obsah aktualizace",

	"ADMIN_UP_T10" => "Nejsou k dispozici žádné aktualizace.",

	"ADMIN_UP_B1" => "Aktualizovat",
	"ADMIN_UP_B2" => "Stáhnout znovu poslední aktualizaci",
	"ADMIN_UP_B3" => "Vyhledat aktualizace",

	"ADMIN_UP_E1" => "Nejsou dostupné žádné aktualizace systému, zkuste to prosím později.",

	"ADMIN_UP_UPDATED_FILEUPDATE" => "Soubor byl aktualizován",
	"ADMIN_UP_UPDATED_FILEDELETE" => "Soubor byl odstraněn",
	"ADMIN_UP_UPDATED_FILECREATE" => "Soubor byl vytvořen",
	"ADMIN_UP_UPDATED_DIRCREATED" => "Adresář byl vytvořen",
	"ADMIN_UP_UPDATED_DIRRM" => "Adresář byl odstraněn",
	"ADMIN_UP_UPDATED_DIRRMR" => "Adresář byl odstraněn i s jeho obsahem",
	"ADMIN_UP_UPDATED_UPDATESCRIPT" => "Byl spuštěn aktualizační script",

	/* User Rights admin subsub page */
	"ADMIN_R_T1" => "Správa oprávnění",
	"ADMIN_R_T2" => "a = uživatel | b - y = vlastní nastavení (b < y)  | z = plná oprávnění",
	
	"ADMIN_R_R:NONE" => "Základní oprávnění",
	"ADMIN_R_R:CUSTOM" => "Vlastní nastavení",
	"ADMIN_R_R:FULL" => "Plná oprávnění",
	
	"ADMIN_R_L0" => "Oprávnění",
	"ADMIN_R_web_access" => "Přístup k webovým stránkám",
	"ADMIN_R_administration_access" => "Přístup do administrace",
	"ADMIN_R_administration_news" => "Správa novinek",
	"ADMIN_R_administration_news_categories" => "Správa kategorií novinek",
	"ADMIN_R_administration_comments" => "Správa komentářů",
	"ADMIN_R_administration_pages" => "Správa stránek",
	"ADMIN_R_administration_uploads" => "Správa nahraných souborů",
	"ADMIN_R_administration_users" => "Správa uživatelů",
	"ADMIN_R_administration_users_permissions" => "Správa uživatelských oprávnění",
	"ADMIN_R_administration_usergroups" => "Správa uživatelských skupin",
	"ADMIN_R_administration_usergroups_permissions" => "Správa oprávnění uživatelských skupin",
	"ADMIN_R_administration_message" => "Hromadné zprávy",
	"ADMIN_R_administration_reports" => "Správa nahlášení",
	"ADMIN_R_administration_main_settings" => "Přístup k nastavení webových stránek",
	"ADMIN_R_administration_other_settings" => "Přístup k doplňujícím nastavení webových stránek",
	"ADMIN_R_administration_sidebars" => "Správa sidebarů",
	"ADMIN_R_administration_plugins" => "Správa pluginů",
	"ADMIN_R_administration_design" => "Správa designu",
	"ADMIN_R_administration_menu" => "Správa menu",
	"ADMIN_R_administration_update" => "Správa updatů webových stránek",
	
	"ADMIN_R_B1" => "Uložit oprávnění",
	"ADMIN_R_B2" => "Nastavit základní oprávnění",

	"ADMIN_R_OK" => "Oprávnění byla úspěšně uložena.",

	/* Administration Global Error types & callouts & notifications & etc. */
	"ADMIN_E_C" => "Chyba!",
	"ADMIN_E_W" => "Upozornění",
	"ADMIN_E_S" => "Úspěch!",
	"ADMIN_E_I" => "Info",

	"ADMIN_E_E1" => "Nebyla vyplněna všechna pole.",

	/* Administration Default types */
	"ADMIN_DEFAULT_NAME:POST_TYPE_NEWS" => "Nový příspěvek",
	"ADMIN_DEFAULT_NAME:POST_TYPE_PAGE" => "Nová stránka",
	"ADMIN_DEFAULT_NAME:NEWS_CATEGORY" => "Nová kategorie",
	"ADMIN_DEFAULT_NAME:SIDEBAR" => "Nový sidebar",
	"ADMIN_DEFAULT_NAME:USER_GROUP" => "Nová uživatelská skupina",

	/* E-mails */
	"E_EMAIL_ACCOUNT_BLOCK" => "<h1>Zablokování účtu %s</h1>\n
	Váš uživatelský účet <b>%s</b> na <b>%s</b>, byl zablokován z následujícíh důvodů.\n
	<br>
	<p>%s</p>
	<br>
	Tato emailová zpráva je pouze informativní.
	", // (User name, User name, page title, ban reason)

	"E_EMAIL_ACCOUNT_REMOVE" => "<h1>Odstranění účtu %s</h1>\n
	Váš uživatelský účet <b>%s</b> na <b>%s</b>, byl odstraněn z následujícíh důvodů.\n
	<br>
	<p>%s</p>
	<br>
	Tato emailová zpráva je pouze informativní.
	", // (User name, User name, page title, ban reason)

	"E_EMAIL_REGISTER" => "<h1>Registrace na %s</h1>\n 
	Vaše e-mailová adresa byla použita při registraci na %s.\n<br>
	<br>
	<b>Uživatelské jméno:</b> %s\n<br>
	<b>Heslo:</b> %s\n<br>
	<b>Aktivační odkaz:</b> %s\n<br>
	<br>
	<b>Pokud si nejste vědom(a) podané žádosti, pak tuto zprávu považujte za bezpředmětnou a ignorujte ji.</b><br>
	Tato žádost bude automaticky vymazána po 24 hodinách, od aktivace účtu.", // (Page title, page title, user name, user pass, activate link)

	"E_ACTIVATE_ACCOUNT" => "<h1>Aktivace účtu na %s</h1>\n
	Vaše e-mailová adresa byla použita při aktivaci účtu na %s.\n<br>
	<br>
	<b>Uživatelské jméno:</b> %s\n<br>
	<b>Aktivační odkaz:</b> %s\n<br>
	<br>
	<b>Pokud si nejste vědom(a) podané žádosti, pak tuto zprávu považujte za bezpředmětnou a ignorujte ji.</b><br>
	Tato žádost bude automaticky vymazána po 24 hodinách, od registrace účtu.", // (Page title, page title, user name, activate link)

	"E_ACTIVATED_ACCOUNT" => "<h1>Aktivace účtu na %s</h1>\n
	Váš účet byl na stránkách %s, byl aktivován pomocí této e-mailové adresy.\n<br>
	<br>
	<b>Uživatelské jméno:</b> %s\n<br>
	<br>
	Tato e-mailová adresa je nyní ověřena, a použitá s uživatelským účtem <b>%s</b>.", // (Page title, Page title, user name, user name)

	"E_PASSWORD_LOST" => "<h1>Zapomenuté heslo na %s</h1>\n
	Byla vygenerována žádost o změnu hesla na stránkách %s.\n<br>
	<br>
	<b>Uživatelské jméno:</b> %s\n<br>
	<b>Odkaz pro změnu hesla:</b> %s\n<br>
	<br>
	<b>Pokud si nejste vědom(a) podané žádosti, pak tuto zprávu považujte za bezpředmětnou a ignorujte ji.</b><br>
	Tato žádost bude automaticky vymazána po 24 hodinách, od podání žádosti.", // (Page title, Page title, user name, password change link)

	"E_PASSWORD_CHANGE" => "<h1>Zapomenuté heslo na %s</h1>\n
	Vaše heslo na stránkách %s, bylo úspěšně změněno.", // (Page title, Page title)

	"E_PASSWORD_CLOSE" => "<h1>Zapomenuté heslo na %s</h1>\n
	Žádost o změnu hesla na stránkách %s, byla úspěšně smazána.", // (Page title, Page title)
	
	"E_EMAIL_CHANGE" => "<h1>Změna emailové adresy na %s</h1>\n
	Byla vytvořena žádost o změnu emailové adresy na stránkách %s<br>\n
	<br>
	<b>Uživatelské jméno:</b> %s<br>\n
	<b>Nový uživatelský e-mail:</b> %s<br>\n
	<b>Odkaz pro potvzrzení změny:</b> %s<br>\n
	<br>
	<b>Pokud si nejste vědom(a) podané žádosti, pak tuto zprávu považujte za bezpředmětnou a ignorujte ji.</b><br>
	Tato žádost bude automaticky vymazána po 24 hodinách, od podání žádosti.", // (Page title, Page title, user name, this mail, email change link)
	
	"E_EMAIL_CHANGED" => "<h1>Změna emailové adresy na %s</h1>\n
	Vaše emailová adresa na stránkách %s byla úspěšně změněna na <b>%s</b>.", // (Page title, Page title, user mail)

	"E_PASS_CHANGE" => "<h1>Změna přihlašovacího hesla na %s</h1>\n
	Byla vytvořena žádost o změnu přihlašovacího hesla na stránkách %s<br>\n
	<br>
	<b>Uživatelské jméno:</b> %s<br>\n
	<b>Odkaz pro potvzrzení změny:</b> %s<br>\n
	<br>
	<b>Pokud si nejste vědom(a) podané žádosti, pak tuto zprávu považujte za bezpředmětnou a ignorujte ji.</b><br>
	Tato žádost bude automaticky vymazána po 24 hodinách, od podání žádosti.", // (Page title, Page title, user name, pass change link)

	"E_PASS_CHANGED" => "<h1>Změna přihlašovacího hesla na %s</h1>\n
	Vaše přihlašovací heslo na stránkách %s byla úspěšně změněno.", // (Page title, Page title)

	"E_EMAIL_END" => "S pozdravem,\n<br><b>%s</b>", // (Page title)

	/* User Roles */
	"USER_ROLE_banned" => "Zablokovaný uživatel",
	"USER_ROLE_unactive" => "Neaktivovaný uživatel",
	"USER_ROLE_user" => "Uživatel",
	"USER_ROLE_moderator" => "Moderátor",
	"USER_ROLE_main_moderator" => "Hlavní moderátor",
	"USER_ROLE_administrator" => "Administrátor",
	"USER_ROLE_main_administrator" => "Hlavní administrátor",
	
	/* Time */
	"T_YEAR" => "rok",
	"T_B_YEAR" => "rokem",
	"T_B_YEAR_P" => "let",

	"T_MONTH" => "měsíc",
	"T_B_MONTH" => "měsícem",
	"T_B_MONTH_P" => "měsící",

	"T_DAY" => "den",
	"T_B_DAY" => "dnem",
	"T_B_DAY_P" => "dny",

	"T_HOUR" => "hodina",
	"T_B_HOUR" => "hodinou",
	"T_B_HOUR_P" => "hodinami",

	"T_MINUTE" => "minuta", 
	"T_B_MINUTE" => "minutou",
	"T_B_MINUTE_P" => "minutami",

	"T_SECOND" => "sekunda",
	"T_B_SECOND" => "sekundou",
	"T_B_SECOND_P" => "sekundami",

	"T_NOW" => "Právě teď",
	"T_BEFORE" => "Před",

	/* Other*/
	"OTHER_1-1" => "a %d dalších uživatelů",
	"OTHER_1-2" => "a %d další uživatelé",
	"OTHER_1-3" => "a jeden další uživatel",
));
?>