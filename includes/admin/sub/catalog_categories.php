<?php
/*****************************************************************************
 *                                                                           *
 * LegoSP - lego.shop-script.org                                             *
 * Copyright (c) 2012  All rights reserved.                  * 
 *                                                                           *
 ****************************************************************************/

if(!defined('WORKING_THROUGH_ADMIN_SCRIPT'))
{
	die;
}




	//show new orders page if selected
	if (!strcmp($sub, "categories"))
	{
		if(isset($_REQUEST['action'])){
			if(isset($_POST['id']))
				$cid =(int)$_POST['id'];


				switch($_REQUEST['action']){
					case 'open':
						$html = $smarty->fetch('category_product_list.tpl.html');
						aResp($html,1);
						exit;
						break;
					case 'delete':
						pre($_REQUEST);
						exit;
						break;
					case 'edit':
						$sql = "SELECT * FROM ".CATEGORIES_TABLE." WHERE categoryID=".$cid;

						$category = db_assoc($sql) or die(db_error());
						$smarty->assign('category',$category);
						$html = $smarty->fetch('category_edit.tpl.html');
						aResp($html,1);

						exit;
						break;
					case 'enable':
						pre($_REQUEST);
						exit;
						break;
					case 'check_url':
						$sql = "SELECT * FROM ".CATEGORIES_TABLE." WHERE hurl='".$_POST['url']."'";
						$res = db_query($sql) or die(db_error());
						if($res->num_rows>0){
							aResp('<span class="help-block">Значение поля должно быть уникально!</span>',0);
						}else{
							aResp('',1);
						}
						break;
					default:
						aResp('<div class="alert alert-warning"><b>Вы пытаетесь применить неизвестное действие</b></div>',0);
						break;
				}
//			}else{
//				aResp('<div class="alert alert-danger"><b>Ошибка загрузки категории!</b></div>',0);
//			}


		}



           
		if (isset($_POST["categories_update"]))
                {               
		 $q = db_query('SELECT categoryID FROM '.CATEGORIES_TABLE) or die (db_error());
		 while ($row=db_fetch_row($q))
		 {
		    if (!isset($_POST["category_".$row[0]])) $category_enable = 0;
		    elseif (!strcmp($_POST["category_".$row[0]], "on")) $category_enable = 1;
		    else $category_enable = 0;
                    db_query('UPDATE '.CATEGORIES_TABLE.' SET enabled='.$category_enable.' WHERE categoryID='.$row[0]);
		 }
		header('Location: '.CONF_ADMIN_FILE.'?dpt=catalog&sub=categories');
		exit;
		}
		if (isset($_GET["del"]) && isset($_GET["c_id"])) //delete category
		{

			$_GET["c_id"]=(int)$_GET["c_id"];
			$picture = db_r("SELECT picture FROM ".CATEGORIES_TABLE." WHERE categoryID='".$_GET["c_id"]."' and categoryID<>0");
			if ($picture && file_exists('./products_pictures/'.$picture)) unlink('./products_pictures/'.$picture);
			//delete from db
			$q = db_query("DELETE FROM ".CATEGORIES_TABLE." WHERE categoryID='".$_GET["c_id"]."' and categoryID<>0") or die (db_error());
			deleteSubCategories($_GET["c_id"]);
			update_products_Count_Value_For_Categories(0);
			header("Location: ".CONF_ADMIN_FILE."?dpt=catalog&sub=categories");
		}
		//calculate how many products are there in the root category
		$cnt = db_r("SELECT count(*) FROM ".PRODUCTS_TABLE." WHERE categoryID=0");
		$smarty->assign("products_in_root_category",$cnt);
		//create a category tree
               	//$smarty->assign("categories", All_Categories(0,0,0));
               	$smarty->assign("categories", getCategoriesTree());
                unset($c);
		//set main template
		$smarty->assign("admin_sub_dpt", "catalog_categories.tpl.html");
	}

