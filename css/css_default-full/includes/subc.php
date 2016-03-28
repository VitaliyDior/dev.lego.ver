<?php
/**
 * Created by PhpStorm.
 * User: legosp.net
 * Date: 16.06.15
 * Time: 16:29
 */
if ($categoryID && !$productID){
    $q=db_query('select name, categoryID, hurl, picture from '. CATEGORIES_TABLE.' where parent='.$categoryID);
    $subc=array();
    while ($row=db_assoc_q($q)){
        if( $row['hurl'] != "" && CONF_CHPU ){
            $row['hurl'] = REDIRECT_CATALOG . "/" . $row['hurl'];
        } else{
            $row['hurl'] = "index.php?categoryID=" . $row['categoryID'];
        }
        if (!$row['picture'] || !file_exists( ROOT_DIR.'/products_pictures/'. $row['picture']) ){
            $row['picture']='./css/css_'.CONF_COLOR_SCHEME.'/images/nophoto.jpg';
        }
        else {
            $row['picture'] = './products_pictures/' . $row['picture'];
        }
        $subc[]=$row;

    }

    $smarty->assign('subcategories_to_be_shown', $subc);
}