<?php
/****************************************************************************
*                                                                           *
* Lego SP - legosp.net                                                      *
* Copyright (c) 2012  All rights reserved.                  *
*                                                                           *
****************************************************************************/
class Language {
    var $description; 
    var $filename; 
}
$lang_list = array();
$lang_list[0] = new Language();
$lang_list[0]->description = "Русский";
$lang_list[0]->filename = "russian.php";
$lang_list[0]->image = "images/ru.png";
$lang_list[0]->hurl = "ru/";
$lang_list[1] = new Language();
$lang_list[1]->description = "English";
$lang_list[1]->filename = "english.php";
$lang_list[1]->image = "images/en.png";
$lang_list[1]->hurl = "en/";
?>