<?php 


//NONCE for SECURITY
if( isset($_POST['submit_keyw']) ) {
  $nonce = $_REQUEST['_wpnonce'];
  if ( ! wp_verify_nonce( $nonce, 'kw_seo_rank_reporter_nonce_2' ) ) {
    exit; // Get out of here, the nonce is rotten!
  }
}


//For i18n
$kw_i18n_plugin_name = __('SEO Rank Reporter', 'seo-rank-reporter');
	$kw_add_to_reporter = _x('Add to Reporter', '"Reporter" is Proper Noun', 'seo-rank-reporter');
	$kw_confirm_add_to_reporter = _x('Confirm and Add to Reporter', '"Reporter" is Proper Noun', 'seo-rank-reporter');
	$kw_check_rankings_now = __('Check Rankings Now', 'seo-rank-reporter');
	$kw_not_yet_checked = __('Not yet checked', 'seo-rank-reporter');
	$kw_not_in_top = __('Not in top 100', 'seo-rank-reporter');
	$kw_click_to_sort = __('Click to Sort', 'seo-rank-reporter');
	$kw_opens_new_window = __('Opens New Window', 'seo-rank-reporter');
	$kw_i18n_remove = __('Remove', 'seo-rank-reporter');
	$kw_download_csv = _x('Download CSV', 'CSV is excel filetype - means "comma-separated"', 'seo-rank-reporter');
	$kw_i18n_rank = __('Rank', 'seo-rank-reporter');
	$kw_th_graph = __('Graph', 'seo-rank-reporter');
	$kw_th_keywords = __('Keywords', 'seo-rank-reporter');
	$kw_i18n_keyword = __('Keyword', 'seo-rank-reporter'); 
	$kw_th_url = __('URL', 'seo-rank-reporter'); 
	$kw_th_current_rank = __('Current Rank', 'seo-rank-reporter');
	$kw_th_rank_change = __('Rank Change', 'seo-rank-reporter');
	$kw_th_start_rank = __('Start Rank', 'seo-rank-reporter');
	$kw_th_visits = __('Visits', 'seo-rank-reporter');
	$kw_th_start_date = __('Start Date', 'seo-rank-reporter');
?>

<div class="wrap">
  <h2><?php echo $kw_i18n_plugin_name; ?></h2>
  <?php
  		$kw_sengine_country = kw_get_sengine_url();	

if (isset($_POST['kw_remove_keyword'] ) ) {
	$remove_msg = kw_seoRankReporterRemoveKeyword($_POST['kw_remove_keyword'], $_POST['kw_remove_url']);
}
if ($_POST['entry_url'] !== "" && $_POST['keyword_item']!== "" && ($_POST['submit_keyw'] == $kw_add_to_reporter ) || $_POST['submit_keyw'] == $kw_confirm_add_to_reporter)  {
	$return_msg = kw_seoRankReporterAddKeywords($_POST['keyword_item'], $_POST['entry_url']);
}

if ($_POST['check-rankings-now'] == $kw_check_rankings_now && (date("d", get_option('kw_rank_nxt_date')-259200)) < date('d') ){
	kw_cron_rank_checker();
	$kw_check_now_button = '';
} elseif ($_POST['check-rankings-now'] == $kw_check_rankings_now && date("d", get_option('kw_rank_nxt_date')-259200) >= date('d')) {
	$return_msg = "<div class='error'>".__('Rankings were not checked. Rankings can only be checked once per day.', 'seo-rank-reporter')."</div>"; 
	$kw_check_now_button = '';
} elseif (date("d", get_option('kw_rank_nxt_date')-259200) < date('d')) {
	$kw_check_now_button = '<form method="post" action="" class="kw-check-now-form"><input type="submit" name="check-rankings-now" class="button-primary kw-check-now" value="'.$kw_check_rankings_now.'" /></form>';
}

?>
  
  <script language="javascript">
	function confirmRemove() {
		return confirm("<?php _e('Do you really want to remove this keyword? This action cannot be undone.', 'seo-rank-reporter'); ?>")
	} 
	$(document).ready(function () { 
  $(".kw_td_bg_color td").fadeTo("fast", 0.1);
   $(".kw_td_bg_color td").fadeTo(1000, 1);
   
  	$(".kw_checkboxes").change( function() {
		var checkboxParentTr = $(this).parents("tr");
		
		if ($(this).is(":checked")) {
			checkboxParentTr.addClass("selected-row");
		} else {
			checkboxParentTr.removeClass("selected-row");
		}
	});
  
});
</script>
  
<?php 

if ($return_msg !== "") { 
echo $return_msg;
}
if ($remove_msg !== "") {
echo $remove_msg; 
}

$keywurl_array = seoRankReporterGetKeywurl();
if (empty($keywurl_array)) {
require('no-keywords.php');
} else {
kw_top_right_affiliate(); 
?>

<div style="height:30px;">
			<?php _e('Date Range:', 'seo-rank-reporter'); ?> <input type="text" id="mindatepicker" class="fav-first" value="" /> - <input type="text" id="maxdatepicker" class="fav-first" value="" />
			<?php echo $kw_check_now_button; ?>	
</div>
<div id="placeholder" style="width:95%;height:400px;margin-left:10px;"></div><br />

  <table border="0" cellpadding="0" cellspacing="0" class="widefat sortable" id="kw_keyword_table">
    <thead>
      <tr>
        <th title="<?php echo $kw_click_to_sort; ?>">#</th>
        <th title="<?php echo $kw_click_to_sort; ?>"><?php echo $kw_th_graph; ?></th>
        <th title="<?php echo $kw_click_to_sort; ?>"><?php echo $kw_th_keywords; ?></th>
        <th title="<?php echo $kw_click_to_sort; ?>"><?php echo $kw_th_url; ?></th>
        <th title="<?php echo $kw_click_to_sort; ?>"><?php echo $kw_th_current_rank; ?></th>
        <th title="<?php echo $kw_click_to_sort; ?>"><?php echo $kw_th_rank_change; ?></th>
        <th title="<?php echo $kw_click_to_sort; ?>"><?php echo $kw_th_start_rank; ?></th>
        <th title="<?php echo $kw_click_to_sort; ?>"><?php echo $kw_th_visits; ?></th>
        <th title="<?php echo $kw_click_to_sort; ?>"><?php echo $kw_th_start_date; ?></th>
        <th></th>
      </tr>
    </thead>
   
    <?php
	
	
	
	$kw_keyw_visits_array = get_option('kw_keyw_visits');
	$kw_plot_graph_array = "";
	$k = 1;
	$p = 0;
	$choices = "";
foreach($keywurl_array as $keywurl) {
	$kw_url = trim($keywurl[url]);
	$kw_keyw = trim($keywurl[keyword]);
	
	$current_rank = "<em>".$kw_not_yet_checked."</em>";
	$start_rank = "";
	$new_date = "";
	$old_date = "";
	$rank_change = "";
	$keyw_color = "";
	if (seoRankReporterGetResults($kw_keyw, $kw_url)) {
		$results_array = seoRankReporterGetResults($kw_keyw, $kw_url);
		
		$graph_it = FALSE;
		$kw_rank_plot_graph = "";
		$kw_visits_plot_graph = "";
		$kw_graph_labels[] = htmlspecialchars_decode($kw_keyw, ENT_QUOTES);
		$kw_graph_urls[] = htmlspecialchars_decode($kw_url, ENT_QUOTES);
		foreach($results_array as $data) {
			$x_value_array = explode("-", $data[date]);
			$x_value = sprintf('%f', (mktime(0, 0, 0, $x_value_array[1], $x_value_array[2], $x_value_array[0]))*1000);
			$y_value = -1*$data[rank];
			$visits_y_value = $data[visits];
			if ($y_value == 1) {
				
			} else {
				$kw_rank_plot_graph .= '['.$x_value.', '.$y_value.'], ';
				$kw_visits_plot_graph .= '['.$x_value.', '.$visits_y_value.'], ';
				$graph_it = TRUE;
			}
		}
		
		$last_results_array = end($results_array);
		$date_limits[] = array("start" => $results_array[0][date], "end" => $last_results_array);
		
		if(!isset($datepicker_min)) {
			$datepicker_min = $results_array[0][date];
		}
		if(!isset($datepicker_max)) {
			$datepicker_max = $last_results_array[date];
		}
		if(greaterDate($last_results_array[date],$datepicker_max)) {
			$datepicker_max = $last_results_array[date];
		}
		
		$kw_rank_graph[] = $kw_rank_plot_graph;
		$kw_visits_graph[] = $kw_visits_plot_graph;
		$end_results_array = end($results_array);
		$current_rank = $end_results_array[rank];
		$current_page = $end_results_array[page];
		$start_rank = $results_array[0][rank];
		$start_page = $results_array[0][page];
		$old_date_array = explode("-", $results_array[0][date]);
		$old_date = date('j M Y', mktime(0, 0, 0, $old_date_array[1], $old_date_array[2], $old_date_array[0]) );
	}
	if ($current_rank !== "<em>".$kw_not_yet_checked."</em>" && $start_rank !== "-1" && $current_rank !== "-1" ) {
		$choices = "choices".$p;
		$p++;
		$disabled = "";
		
		$rank_change = $start_rank-$current_rank;
		if ($rank_change > 0 ) {
			$rank_box = "kw_green_arrow";
		} elseif ($rank_change < 0) {
			$rank_box = "kw_red_arrow";
			$keyw_color = "red";
		} elseif ($rank_change == 0) {
			$rank_box = "kw_blue_line";
		} else {$rank_box = "kw_display_none"; }
	} elseif ($start_rank == "-1" && $current_rank > 0) {
		$choices = "choices".$p;
		$p++;
		$disabled = "";
		
		$rank_change = (100-$current_rank).'+';
		$rank_box = "kw_green_arrow";
		$start_rank = "<em>".$kw_not_in_top."</em>";
	} elseif ($current_rank == "-1" && $start_rank > 0) {
		$choices = "choices".$p;
		$p++;
		$disabled = "";
		
		$rank_change = ($start_rank-100).'+';
		$rank_box = "kw_red_arrow";
		$current_rank = "<em>".$kw_not_in_top."</em>";
	} elseif ($current_rank == "-1") {
		$rank_box = "kw_gray_line";
		$current_rank = "<em>".$kw_not_in_top."</em>";
		$start_rank = "<em>".$kw_not_in_top."</em>";
		
		if ($graph_it) {
			$choices = "choices".$p;
			$p++;
		} else {
			$choices = "";
		}
	} else { $rank_box = "kw_display_none";
		$choices = "";
	}
	
	$kw_num_visits = '';
	if (stristr(trim($kw_url), get_bloginfo('url')) ) {
		$kw_num_visits = $kw_keyw_visits_array[$kw_keyw.'||'.$kw_url];
		$kw_td_bg_color = "kw_td_bg_color";
	} else {
		$kw_td_bg_color = "";  
	} 
?> 
    <tr class="<?php echo $kw_td_bg_color; ?>">
      <td><?php echo $k; ?></td>
      <td id="<?php echo $choices; ?>"><?php echo $disabled; ?></td>
      <td><a href='<?php echo $kw_sengine_country; ?>search?q=<?php echo urlencode($kw_keyw); ?>&pws=0' style="color:<?php echo $keyw_color; ?>;" target="_blank" title="<?php echo $kw_opens_new_window; ?>"><?php echo $kw_keyw; ?></a></td>
      <td class="b-url"><a href="<?php echo $kw_url; ?>" target="_blank" title="<?php echo $kw_opens_new_window; ?>"><?php echo $kw_url; ?></a></td>
      <td><?php echo $current_rank; ?></td>
      <td><div class="<?php echo $rank_box; ?> kw_change"></div>
        <?php echo $rank_change; ?></td>
      <td><?php echo $start_rank; ?></td>
      <td><?php echo $kw_num_visits; ?></td>
      <td><?php echo $old_date; ?></td>
      <td><form action='' method='post'>
          <input type='hidden' name='kw_remove_keyword' value='<?php echo $kw_keyw; ?>' />
          <input type='hidden' name='kw_remove_url' value='<?php echo $kw_url; ?>' />
          <input type='submit' value='<?php echo $kw_i18n_remove; ?>' class='button' onclick='return confirmRemove()' />
        </form>
    </tr>
    <?php $k++;  } ?>
  </table>
  <table class="widefat">
    <tr>
      <td style="border-bottom:none;"><?php $kw_date_next = date("j M Y", get_option('kw_rank_nxt_date'));
$kw_date_last = date("j M Y", get_option('kw_rank_nxt_date')-259200);

printf(__('Last rank check was on %1$sNext rank check scheduled for %2$s', 'seo-rank-reporter'), "<strong>".$kw_date_last."</strong><br>", "<strong>".$kw_date_next."</strong>");
//echo "Last rank check was on <strong>".$kw_date_last."</strong><br>Next rank check scheduled for <strong>".$kw_date_next."</strong>"; ?></td>
 
      <td style="border-bottom:none"><?php printf(__('*When %1$sRank Change%2$s includes %1$s+%2$s, this keyword started ranking outside the first 100 results%3$s *Visits are the number of page visits since the last rank check (every 3 days). Visits will be blank if the URL does not contain %4$s', 'seo-rank-reporter'), "<strong>", "</strong>", "<br />", "<strong>".get_bloginfo('url')."</strong>"); ?> </td>
    </tr>
  </table>
  <div>
<form action="" method="post">
<input type="submit" value="<?php echo $kw_download_csv; ?>" name="dnload-csv" class="button" />
</form>
</div>
  <br />
  <?php $datepicker_array = explode("-", $datepicker_min);
  		$datepicker_min_edit = date('j M Y', mktime(0, 0, 0, $datepicker_array[1], $datepicker_array[2], $datepicker_array[0])); 


  		$datepicker_min_graph = $datepicker_min;
		

  		$datepicker_min_edit_unix = strtotime($datepicker_array[1]."/".$datepicker_array[2]."/".$datepicker_array[0]);
  		
		$datepicker_array = explode("-", $datepicker_max);
		$datepicker_max_edit = date('j M Y', mktime(0, 0, 0, $datepicker_array[1], $datepicker_array[2], $datepicker_array[0]));
  		
  		$datepicker_max_edit_unix = strtotime($datepicker_array[1]."/".$datepicker_array[2]."/".$datepicker_array[0]);
  		
  		if($datepicker_min_edit_unix < ($datepicker_max_edit_unix-7776000)) {
  			$datepicker_min_edit_start = gmdate('j M Y', $datepicker_max_edit_unix-7776000);
  			$datepicker_min = gmdate('Y-m-d', $datepicker_max_edit_unix-7776000);
  		}


   ?>
  <script id="source" language="javascript" type="text/javascript">
// var daMinGraph = <?php echo sprintf('%f', strtotime($datepicker_min_graph)*1000); ?>;
var daMin = <?php echo sprintf('%f', strtotime($datepicker_min)*1000); ?>;
var daMax = <?php echo sprintf('%f', strtotime($datepicker_max)*1000); ?>;
$("#mindatepicker").val('<?php echo $datepicker_min_edit_start; ?>');
$("#maxdatepicker").val('<?php echo $datepicker_max_edit; ?>');
	$("#mindatepicker").datepicker({
		minDate: '<?php echo $datepicker_min_edit; ?>',
		maxDate: '<?php echo $datepicker_max_edit; ?>',
		showAnim: 'slideDown',
		dateFormat: 'd M yy',
		altFormat: 'd M yy', 
   		onSelect: function(dateText, inst) { 
			plotAccordingToDate(dateText,daMax);
		}
	});
	$("#maxdatepicker").datepicker({
		minDate: '<?php echo $datepicker_min_edit_start; ?>',
		maxDate: '<?php echo $datepicker_max_edit; ?>',
		showAnim: 'slideDown',
		dateFormat: 'd M yy',
		altFormat: 'd M yy', 
   		onSelect: function(dateText, inst) { 
			plotAccordingToDate(daMin,dateText);
		}
	});
var datasets = {
<?php
$i = 0;
foreach ($kw_graph_labels as $kw_keyw_g) {
if ($kw_rank_graph[$i] !== "") {
$kw_keyw_gr = str_replace('"', "'", $kw_keyw_g);
$kw_url_gr = str_replace('"', "'", $kw_graph_urls[$i]);
?>
    "<?php echo $kw_keyw_gr . ' - ' . $kw_url_gr; ?>": {
		
		limits: [<?php echo $date_limits[$i][start]. ", ". $date_limits[$i][end]; ?>], 
		kwurl: "<?php echo $kw_url_gr; ?>",
        label: "<?php echo "<strong>".$kw_keyw_gr."</strong> - ".$kw_url_gr; ?>",
        data: [<?php echo $kw_rank_graph[$i]; ?>]
    },
	<?php } $i++; } ?>
};
var i = 0;
$.each(datasets, function(key, val) {
    val.color = i;
    ++i;
});



var choiceContainer = $("#choices0");
var icounter = 0;
var checked = 'checked="checked"';
var blogUrl =  '<?php echo get_bloginfo('url'); ?>';
var urlCounter = 0;
var itemsArray = <?php echo $i; ?>;
$.each(datasets, function(key, val) {
	choiceContainer = $("#choices" + icounter);
	
	if (urlCounter < 5) {
		checked = 'checked="checked"';
		++urlCounter;
		var parentTr = choiceContainer.parents("tr");
		parentTr.addClass("selected-row");
	} else {
		checked = '';
	}
	
	if (val.data != "") {
   		choiceContainer.append('<input type="checkbox" name="' + key +
                           '" ' + checked + ' id="id' + key + '" class="kw_checkboxes" onclick="plotAccordingToChoices()" />');
		
		++icounter;
	}
});
function negformat(val,axis){
		return -val.toFixed(axis.tickDecimals);
}
function showTooltip(x, y, contents) {
    $('<div id="tooltip">' + contents + '</div>').css( {
        position: 'absolute',
        display: 'none',
        top: y + 5,
        left: x - 10,
        border: '1px solid #fdd',
        padding: '2px',
        'background-color': '#fee',
        opacity: 0.80
    }).appendTo("body").fadeIn(200);
}
var previousPoint = null;
$("#placeholder").bind("plothover", function (event, pos, item) {
    $("#x").text(pos.x.toFixed(2));
    $("#y").text(pos.y.toFixed(2));
    //if ($("#enableTooltip:checked").length > 0) {
        if (item) {
            if (previousPoint != item.datapoint) {
                previousPoint = item.datapoint;
                $("#tooltip").remove();
                var x = item.datapoint[0].toFixed(2),
                    y = item.datapoint[1].toFixed(2);
					y = y*-1;
					x = parseFloat(x)+86400000;
					//x.replace(".00", ""));
					var months = new Array(12);
					months[0]  = "Jan";
				   months[1]  = "Feb";
				   months[2]  = "Mar";
				   months[3]  = "Apr";
				   months[4]  = "May";
				   months[5]  = "June";
				   months[6]  = "July";
				   months[7]  = "Aug";
				   months[8]  = "Sep";
				   months[9]  = "Oct";
				   months[10] = "Nov";
				   months[11] = "Dec";
				var myDate = new Date(x);
				var monthNumber = myDate.getMonth();
                showTooltip(item.pageX, item.pageY,
                            item.series.label + "<br><?php echo $kw_i18n_rank; ?>: " + y + "<br>" + (myDate.getDate()) + " " + months[monthNumber] + " " + myDate.getFullYear());
            }
			
        else {
            $("#tooltip").remove();
            previousPoint = null;
        }
    }
});

plotAccordingToChoices();
//plotAccordingToDate(dmind,dmaxd);
function plotAccordingToChoices() {
	
    var data = [];
    var icounter = 0;	
	$.each(datasets, function(key, val) {
		
		if (val.data != "") {
		choiceContainer = $("#choices" + icounter);
		
		choiceContainer.find("input:checked").each(function () {
	        var key = $(this).attr("name");
	        if (key && datasets[key])
	            data.push(datasets[key]);
	    });
		
		
		++icounter;
		}
	});
    if (data.length > 0)
        $.plot($("#placeholder"), data, { series: {
               lines: { show: true },
               points: { show: true }
           }, legend: { margin: 10, backgroundOpacity: .5, position: "sw" },
           grid: { hoverable: true, clickable: true, backgroundColor: { colors: ["#fff", "#fff"] } }, 
		   yaxis: { tickFormatter: negformat, max: "-1" }, xaxis: { mode: "time",  timeformat: "%d %b %y",  min: (new Date(daMin)).getTime(), max: (new Date(daMax)).getTime() }, selection: { mode: "x" },  });
		   
	
		   
}
function plotAccordingToDate(dmin,dmax) {
	
    var data = [];
    var icounter = 0;	
	$.each(datasets, function(key, val) {
		
		if (val.data != "") {
		choiceContainer = $("#choices" + icounter);
		
		choiceContainer.find("input:checked").each(function () {
	        var key = $(this).attr("name");
	        if (key && datasets[key])
	            data.push(datasets[key]);
	    });
		
		
		++icounter; //ftd = '';
		}
	});
	  if (data.length > 0)
        $.plot($("#placeholder"), data, { series: {
        	lines: { show: true },
            points: { show: true }}, 
			legend: { margin: 10, backgroundOpacity: .5, position: "sw" },
           	grid: { hoverable: true, clickable: true, backgroundColor: { colors: ["#fff", "#fff"] } }, 
			yaxis: { tickFormatter: negformat, max: "-1" }, 
			xaxis: { mode: "time",  timeformat: "%d %b %y",  min: (new Date(dmin)).getTime(), max: (new Date(dmax)).getTime() },
			selection: { mode: "x" },  
		});
	//}	
	daMin = dmin;
	daMax = dmax;
		
		
	
}
</script>
  
<?php } ?>
</div>