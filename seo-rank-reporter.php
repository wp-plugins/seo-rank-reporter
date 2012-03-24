<?php
/*
Plugin Name: SEO Rank Reporter 
Plugin URI: http://www.kwista.com/
Description: Track your Google rankings every 3 days and view a report in an easy-to-read graph. Vizualize your traffic spikes and drops in relation to your rankings and get emails notifying you of ranking changes. 
Author: David Scoville
Version: 2.1.5
Author URI: http://www.kwista.com
*/
register_activation_hook(__FILE__,'seoRankReporterInstall');
register_deactivation_hook (__FILE__, 'kwSeoPluginDeactivate');
add_action('admin_menu', 'kw_seo_rank_menu');
add_action('start_cron_rank_checker', 'kw_cron_rank_checker');
add_action('wp_head', 'kw_get_search_keyword');
add_filter('cron_schedules', 'kw_seo_my_add_weekly');
add_action('wp_ajax_kw_sengineUrl', 'kw_seo_ajax_sengineUrl');

$kw_seoRankTable ="seoRankReporter";
function kw_seo_rank_menu(){
	$kw_seo_rank_main = add_menu_page('SEO Rank Reporter', 'Rank Reporter', 'administrator', 'seo-rank-reporter', 'kw_seo_menu_make');
	
	add_submenu_page('seo-rank-reporter', 'Rank Report', 'Rank Report', 'administrator', 'seo-rank-reporter', 'kw_seo_menu_make');
	
	$kw_seo_rank_visits = add_submenu_page('seo-rank-reporter', 'Visits/Rank Report', 'Visits/Rank Report', 'administrator', 'seo-rank-visits', 'kw_seo_visits_menu');
	
	$kw_seo_keywords_add = add_submenu_page('seo-rank-reporter', 'Add Keywords', 'Add Keywords', 'administrator', 'seo-rank-keywords', 'kw_seo_keywords_menu');
	$kw_seo_rank_settings = add_submenu_page('seo-rank-reporter', 'Settings', 'Settings', 'administrator', 'seo-rank-settings', 'kw_seo_settings_menu');
	
	add_action( 'admin_head-'. $kw_seo_rank_main, 'kw_seo_admin_header' );
	add_action( 'admin_head-'. $kw_seo_rank_visits, 'kw_seo_admin_header' );
	add_action( 'admin_head-'. $kw_seo_keywords_add, 'kw_seo_admin_header' );
	add_action('admin_head-'. $kw_seo_rank_settings, 'kw_seo_do_some_ajax');
	add_action('admin_head-'. $kw_seo_rank_settings, 'kw_seo_admin_header');
	add_action( 'admin_footer-'. $kw_seo_rank_settings, 'kw_seo_admin_footer' );
	add_action( 'admin_footer-'. $kw_seo_rank_main, 'kw_seo_admin_footer' );

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
function kw_seo_settings_menu() {
	require ('rank-settings.php');
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
  echo '<script language="javascript" type="text/javascript" src="'.get_bloginfo('url').'/wp-content/plugins/seo-rank-reporter/jscript/jquery-ui-1.8.13.custom.min.js"></script>';
    echo '<script language="javascript" type="text/javascript" src="'.get_bloginfo('url').'/wp-content/plugins/seo-rank-reporter/jscript/jquery.ui.datepicker.js"></script>';

} 

function kw_seo_admin_footer() {
	echo '<script type="text/javascript" charset="utf-8">
  var is_ssl = ("https:" == document.location.protocol);
  var asset_host = is_ssl ? "https://s3.amazonaws.com/getsatisfaction.com/" : "http://s3.amazonaws.com/getsatisfaction.com/";
  document.write(unescape("%3Cscript src=\'" + asset_host + "javascripts/feedback-v2.js\' type=\'text/javascript\'%3E%3C/script%3E"));
</script>

<script type="text/javascript" charset="utf-8">
  var feedback_widget_options = {};

  feedback_widget_options.display = "overlay";  
  feedback_widget_options.company = "seo_rank_reporter";
  feedback_widget_options.placement = "right"; 
  feedback_widget_options.color = "#222";
  feedback_widget_options.style = "idea";

  var feedback_widget = new GSFN.feedback_widget(feedback_widget_options);
</script>';

}

function kw_seo_do_some_ajax() {
	echo '<script type="text/javascript" >
	jQuery(document).ready(function($) {
		jQuery("select").change( function() {
		 var searchUrl = jQuery("select option:selected").val();
		  //alert(searchUrl);
			var data = {
				action: "kw_sengineUrl",
				sengineUrl: searchUrl
			};
			jQuery(".kw-update-message").html("<img src=\'../wp-content/plugins/seo-rank-reporter/images/loading.gif\' />");
			jQuery.post(ajaxurl, data, function(response) {
				//alert(response);
				jQuery(".kw-update-message").html(response);
				jQuery("#message").fadeIn(1000);
			}); 
		});
		
		
	});
	</script>';
}

function kw_seo_ajax_sengineUrl() {
	
	if (!empty($_POST['sengineUrl'])) {
		global $wpdb; // this is how you get access to the database
		$sengineUrlResponse = $_POST['sengineUrl'];
		update_option('kw_seo_sengine_country', $sengineUrlResponse);
		echo '<div id="message" class="updated" style="display:none">Country URL Updated. The Rank Reporter will now use <strong>'.$sengineUrlResponse.'</strong> when searching for rankings.<br><span style="color:red">Warning: Any data collected up to this point will still reflect the old Google URL.</div>';
		die(); // this is required to return a proper result
	}
}

function my_in_array($needle, $haystack) {        
	if (is_array($haystack)) {
		return in_array($needle, $haystack);
	} else {
		return false;	
    }
}
function kw_get_search_keyword() {

	$kw_sengine_country = get_option('kw_seo_sengine_country');	
	
	if (stristr($_SERVER['HTTP_REFERER'], $kw_sengine_country) !== false  && !is_user_logged_in()) {
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
	
	// Create table for keyword ranking data
	global $wpdb,$kw_seoRankTable;
	
	require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
	
	$sql = "SELECT * FROM $kw_seoRankTable_name;";
	$result = $wpdb->query($sql);
	
	if($result == 0) {
		$kw_seoRankTable_name= $wpdb->prefix."seoRankReporter";	
		$sql = " CREATE TABLE $kw_seoRankTable_name(
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
		
		$kw_next_date = time()+259200;
		add_option('kw_rank_nxt_date', $kw_next_date);
		add_option('kw_keyw_visits', array());
		add_option('kw_seo_sengine_country', 'http://www.google.com/');
	}
		
}
function seoRankReporterDelete(){
	global $wpdb;	
	global $kw_seoRankTable;
	require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
	$kw_seoRankTable_name= $wpdb->prefix.$kw_seoRankTable;
	$sql = "TRUNCATE TABLE $kw_seoRankTable_name;";
	$wpdb->query($sql);
	
	//update_option('kw_rank_nxt_date', "");
	update_option('kw_keyw_visits', array());
}
function kwSeoPluginDeactivate() {
	global $wpdb;	
	global $kw_seoRankTable;
	require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
	$kw_seoRankTable_name= $wpdb->prefix.$kw_seoRankTable;
	
	$sql = "SELECT * FROM $kw_seoRankTable_name;";
	$result = $wpdb->query($sql);
	 
	if ($result == 0) {
		$sql = "DROP TABLE $kw_seoRankTable_name;";
		$wpdb->query($sql);
	
		delete_option('kw_rank_nxt_date');
		delete_option('kw_keyw_visits');
		delete_option('kw_seo_emails');
		delete_option('kw_em_spots');
		delete_option('kw_seo_sengine_country');
	}
	wp_clear_scheduled_hook('start_cron_rank_checker'); 
}
function seoRankReporterAddRow($keyword,$url,$sengine,$date,$rank,$page,$visits) {
		global $wpdb;
		global $kw_seoRankTable;		
		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		
		$kw_seoRankTable_name= $wpdb->prefix . $kw_seoRankTable;
		
		$wpdb->query( $wpdb->prepare( "
				INSERT INTO $kw_seoRankTable_name
				( keyword, url, sengine, date, rank, page, visits )
				VALUES ( %s, %s, %s, %s, %d, %d, %d)", 
        		$keyword, $url, $sengine, $date, $rank, $page, $visits ) );		
		
}
function seoRankReporterDeleteRow($kw_keyw,$kw_url) {
	
		global $wpdb;
		global $kw_seoRankTable;	
		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		
		$kw_seoRankTable_name= $wpdb->prefix . $kw_seoRankTable;
		
		$wpdb->query( "
				DELETE FROM $kw_seoRankTable_name
				WHERE keyword = '$kw_keyw' AND url = '$kw_url'");		
	
}
function seoRankReporterGetResults($kw_keyword,$kw_url) {
		global $wpdb;
		global $kw_seoRankTable;	
		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		
		$kw_seoRankTable_name= $wpdb->prefix . $kw_seoRankTable;
		
		$results = $wpdb->get_results( "
				SELECT * FROM $kw_seoRankTable_name
				WHERE keyword = '$kw_keyword' AND url = '$kw_url'
				ORDER BY date ", ARRAY_A );		
		
		return $results;
}
function seoRankReporterGetKeywurl() {
		global $wpdb;
		global $kw_seoRankTable;	
		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		
		$kw_seoRankTable_name= $wpdb->prefix . $kw_seoRankTable;
		
		$results = $wpdb->get_results( "
				SELECT keyword, url FROM $kw_seoRankTable_name
				GROUP BY keyword, url
				ORDER BY date ", ARRAY_A );		
		
		return $results;
}
function seoRankReporterGetDates() {
		global $wpdb;
		global $kw_seoRankTable;	
		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		
		$kw_seoRankTable_name= $wpdb->prefix . $kw_seoRankTable;
		
		$results = $wpdb->get_results( "
				SELECT date FROM $kw_seoRankTable_name
				GROUP BY date
				ORDER BY date ", ARRAY_A );		
		
		return $results;
}
function kw_rank_checker($target_key,$entered_url,$first_time) {
	
	$target_keyword = urlencode(htmlspecialchars_decode($target_key, ENT_QUOTES));
	$entered_url = htmlspecialchars_decode($entered_url, ENT_QUOTES);
	$original_entered_url = $entered_url;
	
	$kw_sengine_country = get_option('kw_seo_sengine_country');	
	$kw_sengine_url = $kw_sengine_country.'search?q='.$target_keyword.'&num=100&pws=0';
	
	//Array of the most common user agents
		$userAgent_array = array(
			'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0',
			'Mozilla/5.0 (Windows; U; Windows NT 6.0; de; rv:1.9.0.15) Gecko/2009101601 Firefox 2.1 (.NET CLR 3.5.30729)',
			'Mozilla/6.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:2.0.0.0) Gecko/20061028 Firefox/3.0',
			'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.0',
			'Mozilla/5.0 (Windows; U; Windows NT 6.1; pl; rv:1.9.1) Gecko/20090624 Firefox/3.5 (.NET CLR 3.5.30729)',
			'Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2) Gecko/20100115 Firefox/3.6',
			'Mozilla/5.0 (Windows; U; Windows NT 6.1; pl; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3',
			'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)',
			'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)',
			'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)',
			'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; en-GB)',
			'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0; MS-RTC LM 8)',
			'(Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0))',
			'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; en) Opera 8.0',
			'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; en) Opera 8.50',
			'Opera/9.00 (Windows NT 5.1; U; en)',
			'Opera/9.20 (Windows NT 6.0; U; en)',
			'Opera/9.30 (Nintendo Wii; U; ; 2047-7;en)',
			'Opera 9.4 (Windows NT 6.1; U; en)',
			'Opera/9.99 (Windows NT 5.1; U; pl) Presto/9.9.9',
			'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) AppleWebKit/525.19 (KHTML, like Gecko) Chrome/1.0.154.42 Safari/525.19',
			'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/528.8 (KHTML, like Gecko) Chrome/2.0.156.0 Safari/528.8',
			'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) AppleWebKit/531.3 (KHTML, like Gecko) Chrome/3.0.193.0 Safari/531.3',
			'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) AppleWebKit/532.0 (KHTML, like Gecko) Chrome/4.0.202.0 Safari/532.0',
			'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/532.0 (KHTML, like Gecko) Chrome/4.0.202.0 Safari/532.0',
			'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/532.9 (KHTML, like Gecko) Chrome/5.0.307.1 Safari/532.9',
			'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.2 (KHTML, like Gecko) Chrome/6.0',
			'Mozilla/5.0 (Macintosh; U; Intel Mac OS X; de-de) AppleWebKit/522.11.1 (KHTML, like Gecko) Version/3.0.3 Safari/522.12.1',
			'Mozilla/5.0 (Windows; U; Windows NT 5.1; fr-FR) AppleWebKit/523.15 (KHTML, like Gecko) Version/3.0 Safari/523.15',
			'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) AppleWebKit/523.15 (KHTML, like Gecko) Version/3.0 Safari/523.15',
			'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_5_2; en-gb) AppleWebKit/526+ (KHTML, like Gecko) Version/3.1 iPhone',
			'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_5; en-us) AppleWebKit/525.25 (KHTML, like Gecko) Version/3.2 Safari/525.25',
			'Mozilla/5.0 (Windows; U; Windows NT 6.0; ru-RU) AppleWebKit/528.16 (KHTML, like Gecko) Version/4.0 Safari/528.16',
			'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_7; en-us) AppleWebKit/533.4 (KHTML, like Gecko) Version/4.1 Safari/533.4'
		);
		

	//Randomly select a user agent from the user agent array
	$userAgent = $uesrAgent_array[rand(0,count($uesrAgent_array)-1)];
	$stopSearch = FALSE;
	$rank = 1;
	$page_num = 1;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
		curl_setopt($ch, CURLOPT_URL,$kw_sengine_url);
		
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
			}
		$dom = new DOMDocument();
		@$dom->loadHTML($html);
		$xpath = new DOMXPath($dom);
		$cite_urls = $xpath->query('/html//div[@id="ires"]/ol/li//h3[not(ancestor::span)]/a');
		
		if($first_time) {
			$results = array(htmlspecialchars(urldecode($target_keyword), ENT_QUOTES), array(htmlspecialchars($entered_url, ENT_QUOTES)), $kw_sengine_country, date('Y-m-d'), array('-1'), '-1');
			

			$kw_parsed_entered_url = parse_url($entered_url);
					
			$host = explode('.', $kw_parsed_entered_url['host']);
			
			$host = array_slice($host, count($host)-2 );						
			
			$entered_len = strlen($kw_parsed_entered_url['host'].$kw_parsed_entered_url['path']);
			$result_found = FALSE;
			
			$o = 0;
			foreach ($cite_urls as $entry) {
				$url = $entry->getAttribute('href');
				$url = kw_fix_g_url($url);
				
				@$kw_parsed_url = parse_url($url); 
				
				$url_pos = stripos($kw_parsed_url['host'].$kw_parsed_url['path'], $kw_parsed_entered_url['host'].$kw_parsed_entered_url['path']);
				
				if (($kw_parsed_entered_url['host'].$kw_parsed_entered_url['path'] == substr($kw_parsed_url['host'].$kw_parsed_url['path'], 0, $entered_len)) || (stristr($kw_parsed_url['host'].$kw_parsed_url['path'], '.'.$kw_parsed_entered_url['host'].$kw_parsed_entered_url['path'])) ) {
									
				//if ($kw_parsed_entered_url['host'].$kw_parsed_entered_url['path'] == substr($kw_parsed_url['host'].$kw_parsed_url['path'], $url_pos, $entered_len) ) {
					$results[1][$o] = htmlspecialchars($url, ENT_QUOTES);
					$results[4][$o] = $rank;
					$results[5] = ceil($rank/10);
					$result_found = TRUE;
					$o++;
				}
				$rank++;
			}	
		
			if (!$result_found) {
				$results[1][0] = htmlspecialchars(kw_seo_GetURL($entered_url), ENT_QUOTES);
			}
			
		} else {
			$results = array($target_key, htmlspecialchars($original_entered_url, ENT_QUOTES), $kw_sengine_country, date('Y-m-d'), '-1', '-1');
			
			foreach ($cite_urls as $entry) {
				$url = $entry->getAttribute('href');
				$url = kw_fix_g_url($url);
				
				if ($original_entered_url == $url) {
					$results[1] = htmlspecialchars($url, ENT_QUOTES);
					$results[4] = $rank;
					$results[5] = ceil($rank/10);
					$result_found = TRUE;
					break;
				}
				$rank++;
			}	
		}
	
		
	$rand_num = '0.'.rand(1,99);
	$rand_num = $rand_num * (rand(1,2));
	sleep($rand_num);
	
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
			
			$checked_rank = kw_rank_checker($kw_keyw,$kw_url,FALSE);
			
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
					$current_rank = "Not in top 100";
				}
				if ($previous_rank == 100) {
					$previous_rank = "Not in top 100";
				}
				if (($kw_rnk_change >= $kw_em_spots) || ($kw_rnk_change*(-1) >= $kw_em_spots)) {
					if ($kw_rnk_change < 0 ) {
						$email_msg .= '<tr><td>'.$kw_keyw.'</td><td>'.$kw_url.'</td><td>'.$current_rank.'</td><td>'.$previous_rank.'</td><td style="color:red;">'.$kw_rnk_change.' '.$rank_plus.'</td></tr>';
					} else {
						$email_msg .= '<tr><td>'.$kw_keyw.'</td><td>'.$kw_url.'</td><td>'.$current_rank.'</td><td>'.$previous_rank.'</td><td style="color:green;">'.$kw_rnk_change.' '.$rank_plus.'</td></tr>';
					}
					$plain_email_msg .= $kw_keyw." - ".$kw_url." \r\nCurrent Rank: ".$current_rank.",  Previous Rank: ".$previous_rank.",  Rank Change: ".$kw_rnk_change.$rank_plus. " \r\n \r\n";
				}
			}
			
		} //end foreach	
				
		if ($email_msg != "") {
			kw_seoRankReporterSendEmail($email_msg, $plain_email_msg);
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
function kw_seoRankReporterSendEmail($email_msg, $plain_email_msg) {
		$kw_seo_emails = get_option('kw_seo_emails');
		
		$kw_date_last = date("M-d-Y", get_option('kw_rank_nxt_date')-259200);
		$email_msg = '<h2>Keyword Ranking Changes from ' . get_bloginfo("url") . '</h2><p><em>The following keywords have changed ranking positions on Google since the last rank check on <strong>' . $kw_date_last .'</strong>:</em></p><table cellpadding="7" cellspacing="0"><thead><tr bgcolor="#FFFF99"><th>Keyword</th><th>URL</th><th>Current Rank</th><th>Previous Rank</th><th>Rank Change</th></tr></thead>' . $email_msg . '</table><br><p style="font-size:10px;color:#999999"><a href="'. get_bloginfo("url") .'/wp-admin/admin.php?page=seo-rank-settings">Change email settings</a> - Rank notifications brought to you by SEO Rank Reporter - <a href="http://www.kwista.com">Kwista</a>.</p>';
		$plain_email_msg = "Keyword Ranking Changes from ".get_bloginfo('url') . " \r\n \r\n". "The following keywords have changed ranking positions on Google since the last rank check on ".$kw_date_last . ". \r\n \r\n". $plain_email_msg . " \r\n \r\n Change email settings at ". get_bloginfo("url") ."/wp-admin/admin.php?page=seo-rank-settings \r\n \r\n Rank notifications brought to you by SEO Rank Reporter - http://wordpress.org/extend/plugins/seo-rank-reporter/";
		
		$to = $kw_seo_emails;
		$subject = "ALERT: Keyword Ranking Changes from " . str_replace('http://', '', get_bloginfo('url'));
		$headers = "From: Rank Reporter | " . get_bloginfo('name') . " <" . get_bloginfo('admin_email') . "> \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
		if(mail($to, $subject, $email_msg, $headers)) {
		} else { mail($to, $subject, $plain_email_msg); }
}
function kw_seoRankReporterAddKeywords($kw_keyword, $kw_url) {
	$kw_url = htmlspecialchars(stripslashes(trim($kw_url)), ENT_QUOTES);
	$kw_keyw = htmlspecialchars(stripslashes(trim($kw_keyword)), ENT_QUOTES);
		
	$keywurl_array_add = seoRankReporterGetKeywurl();
	
	$visits_array = get_option('kw_keyw_visits');
		
	$success_msg = "";
	$error_msg = "";
				
	//foreach($kw_keywords as $kw_keyw) {
	//$kw_keyw = htmlspecialchars(stripslashes(strtolower(trim($kw_keyw))), ENT_QUOTES);
				
	$checked_rank = kw_rank_checker(trim($kw_keyw),trim($kw_url),FALSE);
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
		
		if ($checked_rank[4] == '-1') { $checked_rank[4] = "Not in top 100 Results"; } 
		$success_msg .= "<li><strong>" . $checked_rank[0] . " - " . $checked_rank[1] . "</strong></li><li>Current Rank: " . $checked_rank[4] . "</li>";
					
	}
	
	
	if ($success_msg !== "") {
		$success = "<div id='message' class='updated'>Keyword added to the Rank Reporter:<ul style='margin:10px 0 0 10px;'>".$success_msg."</ul></div>";
	} else {
		$success = "";
	}
	if ($error_msg !== "") { 
		$error = "<div class='error'>Keyword already added to Rank Reporter:<ul style='margin-left:10px;'>" . $error_msg . "</ul></div>";
	} else {
		$error = "";
	}
		
	return $success.$error;	
}

function kw_seoRankReporterRemoveKeyword($kw_remove_keyword, $kw_remove_url) {
	$kw_remove_keyword = htmlspecialchars(stripslashes(trim($kw_remove_keyword)), ENT_QUOTES);
	$kw_remove_url = htmlspecialchars(stripslashes(trim($kw_remove_url)), ENT_QUOTES);
	
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

function kw_seo_GetURL($the_url) {
			
    $chu = curl_init($the_url);
    curl_setopt($chu,CURLOPT_FOLLOWLOCATION,true);
    curl_setopt($chu,CURLOPT_RETURNTRANSFER,true);
    curl_exec($chu);
    $code = curl_getinfo($chu, CURLINFO_EFFECTIVE_URL);
    curl_close($chu);
    return $code;
}

if ($_POST['dnload-csv'] == "Download CSV") {
	if (seoRankReporterGetKeywurl() != "") {
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
}

function kw_fix_g_url($url) {
	if (substr($url, 0, 7) == '/url?q=') {
		$url = str_ireplace('/url?q=', '', $url);
	}
	
	if (stristr($url, '&sa=U&')) {
		$g_clean_url = explode('&sa=U&', $url);
		$url = $g_clean_url[0];
	}
	return $url;
}
		$kw_sengine_country = get_option('kw_seo_sengine_country');	
?>