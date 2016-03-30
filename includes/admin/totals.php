<?php
    /*****************************************************************************
     *                                                                           *
     * LegoSP - legosp.net                                                       *
     * Copyright (c) 2010-2014  All rights reserved.             *
     *                                                                           *
     ****************************************************************************/
    if( !count( $_GET ) && !count( $_POST ) ){

        $total = db_assoc( 'SELECT sum( OC.Price * OC.Quantity ) revenue, count(DISTINCT O.orderID) orders FROM `' . ORDERS_TABLE . '` AS O JOIN `' . ORDERED_CARTS_TABLE . '` AS OC USING ( orderID )' );
        $total = array_merge( $total, db_assoc( 'SELECT sum( OC.Price * OC.Quantity ) revenue_today, count(DISTINCT(O.orderID)) orders_today FROM `' . ORDERS_TABLE . '` AS O INNER JOIN `' . ORDERED_CARTS_TABLE . '` AS OC USING ( orderID ) WHERE O.order_time >= CURDATE( ) ' ) );
        $total = array_merge( $total, db_assoc( 'SELECT sum( OC.Price * OC.Quantity ) revenue_yesterday, count(DISTINCT(O.orderID)) orders_yesterday FROM `' . ORDERS_TABLE . '` AS O INNER JOIN `' . ORDERED_CARTS_TABLE . '` AS OC USING ( orderID ) WHERE O.order_time >= (CURDATE()-1)  and O.order_time<CURDATE()' ) );
        #$total["revenue_yesterday"] = show_price(db_r('SELECT sum( OC.Price * OC.Quantity ) FROM `'.ORDERS_TABLE.'` AS O INNER JOIN `'.ORDERED_CARTS_TABLE.'` AS OC USING ( orderID ) WHERE O.order_time >= (CURDATE()-1)  and O.order_time<CURDATE()'));
        $total = array_merge( $total, db_assoc( 'SELECT sum( OC.Price * OC.Quantity ) revenue_thismonth, count(DISTINCT(O.orderID)) orders_thismonth  FROM `' . ORDERS_TABLE . '` AS O INNER JOIN `' . ORDERED_CARTS_TABLE . '` AS OC USING ( orderID ) WHERE O.order_time >= DATE_SUB(CURRENT_DATE, INTERVAL (DAYOFMONTH(NOW())-1) DAY)' ) );
        $total["revenue"] = show_price( db_r( 'SELECT sum( OC.Price * OC.Quantity ) FROM `' . ORDERS_TABLE . '` AS O INNER JOIN `' . ORDERED_CARTS_TABLE . '` AS OC USING ( orderID )' ) );
        // --- PRODUCTS ---
        $total = array_merge( $total, db_assoc( "select count(*) products,sum(Enabled) products_enabled from " . PRODUCTS_TABLE ) );
        // --- CATEGORIES ---
        $total = array_merge( $total, db_assoc( "select count(*) categories from " . CATEGORIES_TABLE ) );
        $total = array_merge( $total, db_assoc( "select count(*) options from " . PRODUCT_OPTIONS_TABLE) );
        $total = array_merge( $total, db_assoc( "select count(*) options from " . PRODUCT_OPTIONS_TABLE) );
        $total = array_merge( $total, db_assoc( "select count(*) brands from " . BRAND_TABLE) );
        $total = array_merge( $total, db_assoc( "select count(*) auxs from " . AUX_TABLE) );
        $total = array_merge( $total, db_assoc( "select count(*) news from " . NEWS_TABLE) );
        $total = array_merge( $total, db_assoc( "select count(*) pages from " . PAGES_TABLE) );
        $total = array_merge( $total, db_assoc( "select SUM(result) votes from " . VOTES_CONTENT_TABLE) );
        //safemode

        $smarty->assign( "safemode", 0 );
        $smarty->assign( "totals", $total );
        unset( $total );
        $xml = simplexml_load_file('http://legosp.net/core/core_rss_news.php');
       # $news = new SimpleXMLElement( $xml );
        $news_text='<dl>';
        foreach( $xml->channel->item as $story ){

           $news_text.='<dt><a target="_blank" href="'. $story->link.'" >'.$story->title.'</a> <time>'.$story->pubDate.'</time></dt>'."\n";
            $news_text .="<dd>". $story->description."</dd>\n";

        }
        $news_text .= '</dl>';
        $smarty->assign( 'news_text',$news_text);
        unset($news_text);
        $xml = simplexml_load_file( 'http://legosp.net/core/core_rss.php' );
        # $news = new SimpleXMLElement( $xml );
        $news_text = '<dl>';
        foreach( $xml->channel->item as $story ){
            pre($story);

            $news_text .= '<dt><a target="_blank" href="' . $story->link . '" >' . $story->title . '</a></dt>' . "\n";
            $news_text .= "<dd>" . $story->description . "</dd>\n";

        }
        $news_text .= '</dl>';
        $smarty->assign( 'products_text', $news_text );
        unset( $news_text );
    }