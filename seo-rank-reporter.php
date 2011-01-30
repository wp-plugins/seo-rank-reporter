<?php

/*

Plugin Name: SEO Rank Reporter 

Plugin URI: http://www.kwista.com/

Description: Based on keywords you choose, the SEO Rank Reporter plugin will track your Google rankings every 3 days and report the data in an easy-to-read graph. You will also be able to visualize your traffic flow in response to ranking changes and receive emails notifying you of major rank changes. 

Author: David Scoville

Version: 1.0

Author URI: http://www.kwista.com

*/







register_activation_hook(__FILE__,'seoRankReporterInstall');



register_deactivation_hook (__FILE__, 'seoRankReporterDelete');



add_action('admin_menu', 'kw_seo_rank_menu');

add_action('start_cron_rank_checker', 'kw_cron_rank_checker');

add_action('wp_head', 'kw_get_search_keyword');



add_filter('cron_schedules', 'kw_seo_my_add_weekly');



$my_table ="seoRankReporter";



function kw_seo_rank_menu(){



	$kw_seo_rank_main = add_menu_page('SEO Rank Reporter', 'Rank Reporter', 'administrator', 'seo-rank-reporter', 'kw_seo_menu_make');

	

	add_submenu_page('seo-rank-reporter', 'Rank Report', 'Rank Report', 'administrator', 'seo-rank-reporter', 'kw_seo_menu_make');

	

	$kw_seo_rank_visits = add_submenu_page('seo-rank-reporter', 'Visits/Rank Report', 'Visits/Rank Report', 'administrator', 'seo-rank-visits', 'kw_seo_visits_menu');

	

	$kw_seo_keywords_add = add_submenu_page('seo-rank-reporter', 'Add Keywords', 'Add Keywords', 'administrator', 'seo-rank-keywords', 'kw_seo_keywords_menu');



	add_submenu_page('seo-rank-reporter', 'Email Notify', 'Email Notify', 'administrator', 'seo-rank-email', 'kw_seo_email_menu');

	

	add_action( 'admin_head-'. $kw_seo_rank_main, 'kw_seo_admin_header' );

	add_action( 'admin_head-'. $kw_seo_rank_visits, 'kw_seo_admin_header' );

	add_action( 'admin_head-'. $kw_seo_keywords_add, 'kw_seo_admin_header' );



}



//Make wp-cron run on a weekly schedule

function kw_seo_my_add_weekly( $schedules ) {

	$schedules['twiceweekly'] = array(

		'interval' => 259200, //that's how many seconds in 3 days, for the unix timestamp

		'display' => __('Twice Weekly')

	);

	return $schedules;

}



// Set up plugin options menu

function kw_seo_menu(){

	add_options_page('SEO Rank Reporter', 'SEO Rank Reporter', 8, basename(__FILE__), 'kw_seo_menu_make');

}



function kw_seo_menu_make(){ 

	require ('rank-report.php');

}



function kw_seo_email_menu() {

	require ('email-notify.php');

}



function kw_seo_keywords_menu() {

	require ('add-keywords.php');

}



function kw_seo_visits_menu() {

	require ('visits-graph.php');

}



function kw_seo_admin_header(){

  echo '<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />';

  echo '<link href="'.get_bloginfo('url').'/wp-content/plugins/seo-rank-reporter/style.css" rel="stylesheet" type="text/css" />';

  echo '<!--[if IE]><script language="javascript" type="text/javascript" src="'.get_bloginfo('url').'/wp-content/plugins/seo-rank-reporter/jscript/excanvas.min.js"></script><![endif]-->';

  echo '<script language="javascript" type="text/javascript" src="'.get_bloginfo('url').'/wp-content/plugins/seo-rank-reporter/jscript/jquery.js"></script>';

  echo '<script language="javascript" type="text/javascript" src="'.get_bloginfo('url').'/wp-content/plugins/seo-rank-reporter/jscript/jquery.flot.js"></script>';

  echo '<script language="javascript" type="text/javascript" src="'.get_bloginfo('url').'/wp-content/plugins/seo-rank-reporter/jscript/sorttable.js"></script>';

  echo '<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>';

}



function my_in_array($needle, $haystack) {        

	if (is_array($haystack)) {

		return in_array($needle, $haystack);

	} else {

		return false;	

    }

}



function kw_get_search_keyword() {

	

	if (stristr($_SERVER['HTTP_REFERER'], 'google.com') !== false  && !is_user_logged_in()) {

		$referrer = $_SERVER['HTTP_REFERER'];

		$parsed = parse_url( $referrer, PHP_URL_QUERY );

		parse_str( $parsed, $query );

		$kw_searched_keyword = htmlspecialchars(stripslashes(strtolower(trim($query['q']))), ENT_QUOTES);



		if ($kw_searched_keyword !== "") {			

						

			$url_of_current_page = htmlspecialchars(stripslashes( ((substr(get_bloginfo('url'), -1) == '/') ? substr(get_bloginfo('url'), 0, -1) : get_bloginfo('url')) . $_SERVER['REQUEST_URI']));

			

			$r_keyw_visits_array = get_option('kw_keyw_visits');

	

			if (array_key_exists($kw_searched_keyword.'||'.$url_of_current_page, $r_keyw_visits_array) ) {

				$r_keyw_visits_array[$kw_searched_keyword.'||'.$url_of_current_page]++;

				update_option('kw_keyw_visits', $r_keyw_visits_array);

			} 

			

			elseif (count($r_keyw_visits_array) <= 99) { 

				$r_keyw_visits_array[$kw_searched_keyword.'||'.$url_of_current_page] = 1;

				update_option('kw_keyw_visits', $r_keyw_visits_array);

			}

		}			

	}

}





function seoRankReporterInstall(){

	

	wp_schedule_event(time(), 'twiceweekly', 'start_cron_rank_checker');

	$kw_next_date = time()+259200;

	add_option('kw_rank_nxt_date', $kw_next_date);

	add_option('kw_keyw_visits', array());

	

	// Create table for keyword ranking data

	global $wpdb,$my_table;

	require_once(ABSPATH . 'wp-admin/upgrade-functions.php');



	$table_name= $wpdb->prefix."seoRankReporter";	



	$sql = " CREATE TABLE $table_name(

		id mediumint(9) NOT NULL AUTO_INCREMENT,

	  	keyword tinytext NOT NULL,

	  	url tinytext NOT NULL,

	  	sengine tinytext NOT NULL,

	  	date date NOT NULL DEFAULT '0000-00-00',

	  	rank smallint(5) NOT NULL,

	  	page tinyint(3) NOT NULL,

		visits mediumint(9) NOT NULL,

	  	PRIMARY KEY ( `id` )	



	) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;";





	$wpdb->query($sql);



}





function seoRankReporterDelete(){



	global $wpdb;	

	global $my_table;





	require_once(ABSPATH . 'wp-admin/upgrade-functions.php');



	$table_name= $wpdb->prefix.$my_table;

	$sql = "DROP TABLE $table_name;";



	//$wpdb->query($sql);
	//Will fix this shortly
	

	//wp_clear_scheduled_hook('start_cron_rank_checker');

	

	//delete_option('kw_rank_nxt_date');

	//delete_option('kw_keywurl');

	//delete_option('kw_keyw_visits');



}





function seoRankReporterAddRow($keyword,$url,$sengine,$date,$rank,$page,$visits) {



		global $wpdb;

		global $my_table;		

		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');

		

		$table_name= $wpdb->prefix . $my_table;

		

		

		$wpdb->query( $wpdb->prepare( "

				INSERT INTO $table_name

				( keyword, url, sengine, date, rank, page, visits )

				VALUES ( %s, %s, %s, %s, %d, %d, %d)", 

        		$keyword, $url, $sengine, $date, $rank, $page, $visits ) );		

		

		

}



function seoRankReporterDeleteRow($kw_keyw,$kw_url) {

	

		global $wpdb;

		global $my_table;	

		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');

		

		$table_name= $wpdb->prefix . $my_table;

		

		$wpdb->query( "

				DELETE FROM $table_name

				WHERE keyword = '$kw_keyw' AND url = '$kw_url'");		

	



}



function seoRankReporterGetResults($kw_keyword,$kw_url) {



		global $wpdb;

		global $my_table;	

		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');

		

		$table_name= $wpdb->prefix . $my_table;

		

		$results = $wpdb->get_results( "

				SELECT * FROM $table_name

				WHERE keyword = '$kw_keyword' AND url = '$kw_url'

				ORDER BY date ", ARRAY_A );		

		

		return $results;

}



function seoRankReporterGetKeywurl() {

		global $wpdb;

		global $my_table;	

		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');

		

		$table_name= $wpdb->prefix . $my_table;

		

		$results = $wpdb->get_results( "

				SELECT keyword, url FROM $table_name

				GROUP BY keyword, url

				ORDER BY date ", ARRAY_A );		

		

		return $results;

}



function seoRankReporterGetDates() {

		global $wpdb;

		global $my_table;	

		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');

		

		$table_name= $wpdb->prefix . $my_table;

		

		$results = $wpdb->get_results( "

				SELECT date FROM $table_name

				GROUP BY date

				ORDER BY date ", ARRAY_A );		

		

		return $results;

}





function kw_rank_checker($target_key,$entered_url,$first_time) {

	

	$target_keyword = urlencode(htmlspecialchars_decode($target_key, ENT_QUOTES));

	$entered_url = htmlspecialchars_decode($entered_url, ENT_QUOTES);

	$original_entered_url = $entered_url;

	

	if(substr($entered_url, 0, 7) == "http://" || substr($entered_url, 0, 8) == "https://") { 

	} else { 

		$entered_url = "http://" . $entered_url;

	}



		

	if (substr($entered_url, -1) == '/') {

		$entered_url = substr($entered_url, 0, -1);

	} 

	if (substr($entered_url, 7, 4) == "www." && substr($entered_url, 0, 7) == "http://" ) {

		$entered_url_www = $entered_url;

		$entered_url = substr_replace($entered_url_www, "", 7, 4);

	} elseif (substr($entered_url, 7, 4) != "www." && substr($entered_url, 0, 7) == "http://" ) {

		$entered_url_www = substr_replace($entered_url, "www.", 7, 0);

	} 

	if (substr($entered_url, 8, 4) == "www." && substr($entered_url, 0, 8) == "https://" ) {

		$entered_url_www = $entered_url;

		$entered_url = substr_replace($entered_url_www, "", 8, 4);

	} elseif (substr($entered_url, 8, 4) != "www." && substr($entered_url, 0, 8) == "https://") {

		$entered_url_www = substr_replace($entered_url, "www.", 8, 0);

	}



	//Array of all 10 pages of Google search results

	$google_url_array = array('http://www.google.com/search?q='.$target_keyword,

					   'http://www.google.com/search?q='.$target_keyword.'&start=10',

					   'http://www.google.com/search?q='.$target_keyword.'&start=20',

					   'http://www.google.com/search?q='.$target_keyword.'&start=30',

					   'http://www.google.com/search?q='.$target_keyword.'&start=40',

					   'http://www.google.com/search?q='.$target_keyword.'&start=50',

					   'http://www.google.com/search?q='.$target_keyword.'&start=60',

					   'http://www.google.com/search?q='.$target_keyword.'&start=70',

					   'http://www.google.com/search?q='.$target_keyword.'&start=80',

					   'http://www.google.com/search?q='.$target_keyword.'&start=90'

					   );



	//Array of the most common user agents

	$userAgent_array = array('Mozilla/5.0 (Windows; U; Win95; it; rv:1.8.1) Gecko/20061010 Firefox/2.0',

						'Mozilla/5.0 (Windows; U; Windows NT 6.0; zh-HK; rv:1.8.1.7) Gecko Firefox/2.0',

						'Mozilla/5.0 (Windows; U; Windows NT 5.1; pt-BR; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15',

						'Mozilla/5.0 (Windows; U; Windows NT 6.1; es-AR; rv:1.9) Gecko/2008051206 Firefox/3.0',

						'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_6 ; nl; rv:1.9) Gecko/2008051206 Firefox/3.0',

						'Mozilla/5.0 (Windows; U; Windows NT 5.1; es-AR; rv:1.9.0.11) Gecko/2009060215 Firefox/3.0.11',

						'Mozilla/5.0 (X11; U; Linux x86_64; cy; rv:1.9.1b3) Gecko/20090327 Fedora/3.1-0.11.beta3.fc11 Firefox/3.1b3',

						'Mozilla/5.0 (Windows; U; Windows NT 6.1; ja; rv:1.9.2a1pre) Gecko/20090403 Firefox/3.6a1pre',

						'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)',

						'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)',

						'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; Win64; x64; SV1)',

						'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.2; .NET CLR 1.1.4322)',

						'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; .NET CLR 1.1.4322; InfoPath.2; .NET CLR 3.5.21022)',

						'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET CLR 1.1.4322; Tablet PC 2.0; OfficeLiveConnector.1.3; OfficeLivePatch.1.3; MS-RTC LM 8; InfoPath.3)',

						'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; FDM; .NET CLR 2.0.50727; InfoPath.2; .NET CLR 1.1.4322)',

						'Mozilla/4.0 (compatible; MSIE 6.0; Mac_PowerPC; en) Opera 9.00',

						'Mozilla/5.0 (X11; Linux i686; U; en) Opera 9.00',

						'Mozilla/4.0 (compatible; MSIE 6.0; Mac_PowerPC; en) Opera 9.00',

						'Opera/9.00 (Nintindo Wii; U; ; 103858; Wii Shop Channel/1.0; en)',

						'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 6.0; pt-br) Opera 9.25',

						'Opera/9.50 (Macintosh; Intel Mac OS X; U; en)',

						'Opera/9.61 (Windows NT 6.1; U; zh-cn) Presto/2.1.1',

						'Mozilla/5.0 (Windows NT 5.0; U; en-GB; rv:1.8.1) Gecko/20061208 Firefox/2.0.0 Opera 9.61',

						'Opera/10.00 (X11; Linux i686; U; en) Presto/2.2.0',

						'Mozilla/5.0 (Macintosh; PPC Mac OS X; U; en; rv:1.8.1) Gecko/20061208 Firefox/2.0.0 Opera 10.00',

						'Mozilla/4.0 (compatible; MSIE 6.0; X11; Linux i686 ; en) Opera 10.00',

						'Opera/9.80 (Windows NT 6.0; U; fi) Presto/2.2.0 Version/10.00',

						'Mozilla/5.0 (Windows; U; Windows NT 6.1; da) AppleWebKit/522.15.5 (KHTML, like Gecko) Version/3.0.3 Safari/522.15.5',

						'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_4_11; ar) AppleWebKit/525.18 (KHTML, like Gecko) Version/3.1.1 Safari/525.18',

						'Mozilla/5.0 (Mozilla/5.0 (iPhone; U; CPU iPhone OS 2_0_1 like Mac OS X; hu-hu) AppleWebKit/525.18.1 (KHTML, like Gecko) Version/3.1.1 Mobile/5G77 Safari/525.20',

						'Mozilla/5.0 (iPod; U; CPU iPhone OS 2_2_1 like Mac OS X; es-es) AppleWebKit/525.18.1 (KHTML, like Gecko) Version/3.1.1 Mobile/5H11 Safari/525.20',

						'Mozilla/5.0 (Windows; U; Windows NT 6.0; he-IL) AppleWebKit/528.16 (KHTML, like Gecko) Version/4.0 Safari/528.16',

						'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_1; zh-CN) AppleWebKit/530.19.2 (KHTML, like Gecko) Version/4.0.2 Safari/530.19'

						);

	//Randomly select a user agent from the user agent array

	$userAgent = $uesrAgent_array[rand(0,count($uesrAgent_array)-1)];

	$proxy = "67.209.231.5:8095";

	$proxy_user_pass = "[seocom]:[staticips]";



	$stopSearch = FALSE;

	$rank = 1;

	$page_num = 1;

	foreach($google_url_array as $value) {

		

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);

		curl_setopt($ch, CURLOPT_URL,$value);

		

		curl_setopt($ch, CURLOPT_FAILONERROR, true);

		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

		curl_setopt($ch, CURLOPT_AUTOREFERER, true);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);

		curl_setopt($ch, CURLOPT_TIMEOUT, 10);

		$html = curl_exec($ch);

			if (curl_errno($ch) != 0) {

				echo "<br />cURL error number:" .curl_errno($ch);

				echo "<br />cURL error:" . curl_error($ch);

				echo "<br />url: " . $value;

				echo "<br />";

				break;

			}



		$dom = new DOMDocument();

		@$dom->loadHTML($html);

		$xpath = new DOMXPath($dom);

		$cite_urls = $xpath->query("/html//div[@id='ires']/ol//h3/a");

		

		if($first_time) {

			$results = array(htmlspecialchars(urldecode($target_keyword), ENT_QUOTES), htmlspecialchars($entered_url_www, ENT_QUOTES), 'Google', date('Y-m-d'), '-1', '-1');

		} else {

			$results = array($target_key, htmlspecialchars($original_entered_url, ENT_QUOTES), 'Google', date('Y-m-d'), '-1', '-1');

		}

		

		foreach ($cite_urls as $entry) {

			$url = $entry->getAttribute('href');



			if (substr($entered_url, 0, strlen($entered_url)) == substr($url, 0, strlen($entered_url)) || substr($entered_url_www, 0, strlen($entered_url_www)) == substr($url, 0, strlen($entered_url_www)) ) {

				$results[1] = htmlspecialchars($url, ENT_QUOTES);

				$results[4] = $rank;

				$results[5] = $page_num;

				$stopSearch = TRUE;

				break;

			}



			$rank++;

		}

		if ($stopSearch == TRUE) { break; }



		$rand_num = '0.'.rand(1,99);

		$rand_num = $rand_num * (rand(1,2));

		sleep($rand_num);



		$page_num++;



	}



	return $results;



}





function kw_cron_rank_checker() {

	

	if (seoRankReporterGetKeywurl() != "" ) {

		$keywurl_array = seoRankReporterGetKeywurl();

		$visits_array = get_option('kw_keyw_visits');

		$kw_em_spots = get_option('kw_em_spots');

		$kw_seo_emails = get_option('kw_seo_emails');

		

		foreach($keywurl_array as $keywurl) {

			$kw_url = trim($keywurl[url]);

			$kw_keyw = trim($keywurl[keyword]); 

			

			$checked_rank = kw_rank_checker(trim($kw_keyw),trim($kw_url),FALSE);

			

			$kw_visits = $visits_array[$kw_keyw.'||'.$kw_url];

			seoRankReporterAddRow($checked_rank[0], $checked_rank[1], $checked_rank[2], $checked_rank[3], $checked_rank[4], $checked_rank[5], $kw_visits);

			

			if ($kw_seo_emails != "") {

				//Notification script

				$end_results_array = end(seoRankReporterGetResults($kw_keyw, $kw_url));



				$previous_rank = $end_results_array[rank];

				$current_rank = $checked_rank[4];

				$rank_plus = "";

				if ($previous_rank == -1) {

					$previous_rank = 100;

					$rank_plus = '+';

				}

				if ($current_rank == -1) {

					$current_rank = 100; 

					$rank_plus = '+';

				}

				

				$kw_rnk_change = $previous_rank-$current_rank;

				if ($current_rank == 100) {

					$current_rank = "<em>Not in top 100</em>";

				}

				if ($previous_rank == 100) {

					$previous_rank = "<em>Not in top 100</em>";

				}

				if (($kw_rnk_change >= $kw_em_spots) || ($kw_rnk_change*(-1) >= $kw_em_spots)) {

					if ($kw_rnk_change < 0 ) {

						$email_msg .= '<tr><td>'.$kw_keyw.'</td><td>'.$kw_url.'</td><td>'.$current_rank.'</td><td>'.$previous_rank.'</td><td style="color:red;">'.$kw_rnk_change.' '.$rank_plus.'</td></tr>';

					} else {

						$email_msg .= '<tr><td>'.$kw_keyw.'</td><td>'.$kw_url.'</td><td>'.$current_rank.'</td><td>'.$previous_rank.'</td><td style="color:green;">'.$kw_rnk_change.' '.$rank_plus.'</td></tr>';

					}

				}

			}

					

		} //end foreach	

		

		if ($email_msg != "") {

			kw_seoRankReporterSendEmail($email_msg);



		}

		

		$kw_next_date = time()+259200;

		update_option('kw_rank_nxt_date', $kw_next_date);

		

		//Make the visits_array values all zero

		while (list($kw_keywurli, $visits) = each($visits_array)) {	

			$visits_array[$kw_keywurli] = 0;

		}

		

		update_option('kw_keyw_visits', $visits_array);

	}	

	

}	



function kw_seoRankReporterSendEmail($email_msg) {



		$kw_seo_emails = get_option('kw_seo_emails');

		

		$kw_date_last = date("M-d-Y", get_option('kw_rank_nxt_date')-259200);



		$email_msg = '<h2>Keyword Ranking Changes from ' . get_bloginfo("url") . '</h2><p><em>The following keywords have changed ranking positions on Google since the last rank check on <strong>' . $kw_date_last .'</strong>:</em></p><table cellpadding="7" cellspacing="0"><thead><tr bgcolor="#FFFF99"><th>Keyword</th><th>URL</th><th>Current Rank</th><th>Previous Rank</th><th>Rank Change</th></tr></thead>' . $email_msg . '</table><br><p style="font-size:10px;color:#999999"><a href="'. get_bloginfo("url") .'/wp-admin/admin.php?page=seo-rank-email">Change email settings</a> - Rank notifications brought to you by SEO Rank Reporter - <a href="http://www.kwista.com">Kwista</a>.</p>';

		

		$to = $kw_seo_emails;



		$subject = "ALERT: Keyword Ranking Changes from " . str_replace('http://', '', get_bloginfo('url'));



		$headers = "From: Rank Reporter | " . get_bloginfo('name') . " <" . get_bloginfo('admin_email') . "> \r\n";

		$headers .= "MIME-Version: 1.0\r\n";

		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

			

		mail($to, $subject, $email_msg, $headers);

}





function kw_seoRankReporterAddKeywords($kw_keywords, $kw_url) {



	$kw_url = htmlspecialchars(stripslashes(trim($_POST['entry_url'])), ENT_QUOTES);

	$kw_keywords = explode("\n", $_POST['keywords']);

		

		$keywurl_array_add = seoRankReporterGetKeywurl();

	

		$visits_array = get_option('kw_keyw_visits');

		

		$success_msg = "";

		$error_msg = "";

		

		foreach($kw_keywords as $kw_keyw) {



			$kw_keyw = htmlspecialchars(stripslashes(strtolower(trim($kw_keyw))), ENT_QUOTES);

				

			$checked_rank = kw_rank_checker(trim($kw_keyw),trim($kw_url),TRUE);

			$kw_url = trim($checked_rank[1]);

				

			

			$keyw_is_in_array = FALSE;

			foreach ($keywurl_array_add as $keywurl_add) {

				if ($keywurl_add[keyword] == $kw_keyw && $keywurl_add[url] == $kw_url) {

					$error_msg .= "<li><strong>$kw_keyw - $kw_url</strong></li>";//Error Message

					$keyw_is_in_array = TRUE;

				} 

			}

				

				

			if (!$keyw_is_in_array) {

				

				$theVisits = "";

				if (stristr($kw_url, htmlspecialchars(get_bloginfo('url'), ENT_QUOTES)) ) {

					if (array_key_exists($kw_keyw.'||'.$kw_url, $visits_array)) {

						$theVisits = $visits_array[$kw_keyw.'||'.$kw_url];

					} else {

						$theVisits = 0;

						$visits_array[$kw_keyw.'||'.$kw_url] = 0;

						update_option('kw_keyw_visits', $visits_array);

					}

				}

			

				seoRankReporterAddRow($checked_rank[0], $checked_rank[1], $checked_rank[2], $checked_rank[3], $checked_rank[4], $checked_rank[5], $theVisits);

				$success_msg .= "<li><strong>" . $checked_rank[0] . " - " . $checked_rank[1] . "</strong> &nbsp;&nbsp; Current Rank: " . $checked_rank[4] . "</li>";

					

			}



			//Sleep

			$rand_num = '0.'.rand(1,99);

			$rand_num = $rand_num * (rand(1,2));

			sleep($rand_num);



		}

	

	if ($success_msg !== "") {

		$success = "<div id='message' class='updated'>Keyword(s) added to the Rank Reporter:<ul>".$success_msg."</ul></div>";

	} else {

		$success = "";

	}

	if ($error_msg !== "") { 

		$error = "<div class='error'>Keyword(s) already added to Rank Reporter:<ul>" . $error_msg . "</ul></div>";

	} else {

		$error = "";

	}

		

	return $success.$error;	

}





function kw_seoRankReporterRemoveKeyword($kw_remove_keyword, $kw_remove_url) {

	$kw_remove_keyword = stripslashes(trim($kw_remove_keyword));

	$kw_remove_url = stripslashes(trim($kw_remove_url));

	

	seoRankReporterDeleteRow($kw_remove_keyword,$kw_remove_url);

	

	$r_keyw_visits_array = get_option('kw_keyw_visits');

	

	if (array_key_exists($kw_remove_keyword.'||'.$kw_remove_url, $r_keyw_visits_array) && count($r_keyw_visits_array) >= 45) {

		unset($r_keyw_visits_array[$kw_remove_keyword.'||'.$kw_remove_url]);

		update_option('kw_keyw_visits', $r_keyw_visits_array);

	} 

	

	return "<div id='message' class='updated'>Keyword removed: <strong>$kw_remove_keyword - $kw_remove_url</strong></div>";

	

}



function kw_isUrlInArray($keywurl_arra) {

	$return = FALSE;

	foreach($keywurl_arra as $kywrl) {

  		if(stristr(trim($kywrl[url]), get_bloginfo('url'))) {

			$return = TRUE;

			break;



		}

	}

	return $return;

}



function greaterDate($start_date,$end_date) {

	$start = strtotime($start_date);

	$end = strtotime($end_date);

  	if ($start-$end > 0)

   		return 1;

	else

   		return 0;

}





if (seoRankReporterGetKeywurl() != "" && $_POST['dnload-csv'] == "Download CSV") {

	header("Content-type: application/octet-stream");

	header("Content-Disposition: attachment; filename=\"rank-reporter-data.csv\"");

	

	$keywurl_array = seoRankReporterGetKeywurl();



	$theDates = seoRankReporterGetDates();

	

	foreach($keywurl_array as $keywurl) {

		

		$kw_url = str_replace(",","",trim($keywurl[url]));

		$kw_keyw = str_replace(",","",trim($keywurl[keyword]));

		

		$csv .= "," . $kw_keyw . " - " . $kw_url;

	}

	

	$csv .= "\n";

	

	foreach ($theDates as $aDate) {

				

		$csv .= $aDate[date];

		

		foreach($keywurl_array as $keywurl) {

			

			$kw_url = trim($keywurl[url]);

			$kw_keyw = trim($keywurl[keyword]);

			

			$results_array = seoRankReporterGetResults($kw_keyw, $kw_url);

	

			foreach($results_array as $data) {

				

				if ($data[date] == $aDate[date]) {

					if ($data[rank] == -1) {

						$data[rank] = "Not in top 100";

					}

					$csv .= "," . $data[rank];

					$blank = FALSE;

					break;

				} else {

					$blank = TRUE;

				}

			}

			if ($blank) {

				$csv .= ",";

			}

		

		}

		

		$csv .= "\n";			

		

	}	

	echo $csv;

	

	exit();

	

}





?>

