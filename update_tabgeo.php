<?php
/**
 * Created by PhpStorm.
 * User: Виталий
 * Date: 30.03.2016
 * Time: 21:59
 */
$db_md5 = file_get_contents('http://tabgeo.com/api/v4/country/db/md5/');
if(md5_file('tabgeo_country_v4.dat') <> $db_md5){
    $db_content = file_get_contents('http://tabgeo.com/api/v4/country/db/get/');
    if($db_md5 == md5($db_content)){
        file_put_contents('tabgeo_country_v4.dat', $db_content);
    }
}$db_md5 = file_get_contents('http://tabgeo.com/api/v4/country/db/md5/');
if(md5_file('tabgeo_country_v4.dat') <> $db_md5){
    $db_content = file_get_contents('http://tabgeo.com/api/v4/country/db/get/');
    if($db_md5 == md5($db_content)){
        file_put_contents(ROOT_DIR.'/core/tabgeo_country_v4.dat', $db_content);
    }
}
