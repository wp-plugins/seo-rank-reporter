<?php
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
<h2><?php _e('Visits Graph', 'seo-rank-reporter'); ?></h2>
<?php
$keywurl_array = seoRankReporterGetKeywurl();
if (kw_isUrlInArray($keywurl_array)) {
	
		$kw_sengine_country = kw_get_sengine_url();	
?>
<?php kw_top_right_affiliate(); ?>
 <script language="javascript">
	function confirmRemove() {
		return confirm("<?php _e('Do you really want to remove this keyword? This action cannot be undone.', 'seo-rank-reporter'); ?>")
	}  
	</script>
 <div style="height:30px;">
			<?php _e('Date Range:', 'seo-rank-reporter'); ?><input type="text" id="mindatepicker" class="fav-first" value="" /> - <input type="text" id="maxdatepicker" class="fav-first" value="" />
	
		
</div>
<?php
 	$kw_keyw_visits_array = get_option('kw_keyw_visits');
	$kw_plot_graph_array = "";
foreach($keywurl_array as $keywurl) {
	$kw_url = trim($keywurl[url]);
	$kw_keyw = trim($keywurl[keyword]);
	
  if(stristr(trim($kw_url), get_bloginfo('url'))) {
	$current_rank = "<em>".$kw_not_yet_checked."</em>";
	$start_rank = "";
	$new_date = "";
	$old_date = "";
	$rank_change = "";
	$keyw_color = "";
	if (seoRankReporterGetResults($kw_keyw, $kw_url)  ) {
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
		
		$rank_change = (100-$current_rank).'+';
		$rank_box = "kw_green_arrow";
		$start_rank = "<em>".$kw_not_in_top."</em>";
	} elseif ($current_rank == "-1" && $start_rank > 0) {
		
		$rank_change = ($start_rank-100).'+';
		$rank_box = "kw_red_arrow";
		$current_rank = "<em>".$kw_not_in_top."</em>";
	} elseif ($current_rank == "-1") {
		$rank_box = "kw_gray_line";
		$current_rank = "<em>".$kw_not_in_top."</em>";
		$start_rank = "<em>".$kw_not_in_top."</em>";
	}
	
	$kw_num_visits = '';
	if (stristr(trim($kw_url), get_bloginfo('url')) ) {
		$kw_num_visits = $kw_keyw_visits_array[$kw_keyw.'||'.$kw_url];
	} 
	
	$keywurl_visits_array[] = array("kw_keyw" => $kw_keyw, "keyw_color" => $keyw_color, "kw_url" => $kw_url, "current_rank" => $current_rank, "rank_box" => $rank_box, "rank_change" => $rank_change, "start_rank" => $start_rank, "kw_num_visits" => $kw_num_visits, "old_date" => $old_date); 
	
} }
?>
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

 
  <script type="text/javascript">
    var currentPlaceHolder = 0;
var daMin = <?php echo sprintf('%f', strtotime($datepicker_min)*1000); ?>;
var daMax = <?php echo sprintf('%f', strtotime($datepicker_max)*1000); ?>;
$("#mindatepicker").val('<?php echo $datepicker_min_edit_start; ?>');
$("#maxdatepicker").val('<?php echo $datepicker_max_edit; ?>');
	$("#mindatepicker").datepicker({
		minDate: '<?php echo $datepicker_min_edit; ?>',
		maxDate: '<?php echo $datepicker_max_edit; ?>',
		showAnim: 'slideDown',
		dateFormat: 'd M yy', 
   		onSelect: function(dateText, inst) { 
			plotAccordingToDate(dateText,daMax, currentPlaceHolder);
			daMin = dateText;
		}
	});
	$("#maxdatepicker").datepicker({
		minDate: '<?php echo $datepicker_min_edit_start; ?>',
		maxDate: '<?php echo $datepicker_max_edit; ?>',
		showAnim: 'slideDown',
		dateFormat: 'd M yy', 
   		onSelect: function(dateText, inst) { 
			plotAccordingToDate(daMin,dateText,currentPlaceHolder);
			daMax = dateText;
		}
	});
       
$(document).ready(function() {
	plotAccordingToDate(daMin, daMax, currentPlaceHolder);
	
	$(".graph-radio-button").change( function() { 
		$('.myChangingDivs').each(function() {
			$(this).css('visibility', 'hidden');
		});
	 
		var divIdNum = $(".graph-radio-button:checked").attr('title');
		$('#'+divIdNum).css('visibility', 'visible');
		
		$("#kw_keyword_table tr").each(function() {
			$(this).removeClass("selected-row");
		});
		
		var checkboxParentTr = $(this).parents("tr");
		checkboxParentTr.addClass("selected-row");
		
		currentPlaceHolder = divIdNum.substring(1);
	
		plotAccordingToDate(daMin, daMax, currentPlaceHolder);
		
	});
});
var dDataArray = new Array(); 
var eDataArray = new Array(); 
</script>
  
<?php } ?>
<?php
if ($kw_graph_labels != "") {
$i=0;
$j = 0;
foreach ($kw_graph_labels as $kw_keyw_g) {
	if (stristr(trim($kw_graph_urls[$i]), get_bloginfo('url')) && $kw_rank_graph[$i] !== "" ) {
	 ?>
<div class="myChangingDivs mcd<?php echo $i; ?>" id="d<?php echo $i; ?>" style="<?php if ($j > 0) { echo "visibility:hidden;"; } ?>">
 
  <img src="<?php echo plugins_url('images/graph-bg-rank.png', __FILE__); ?>" class="rankLabel" style="margin-top:90px;position:absolute;left:40px;z-index:999999" />
  <div id="placeholder<?php echo $i; ?>" style="width:95%;height:450px;margin-left:0px;" class="aPlaceholder"></div>
  
</div>
<script id="source">
			var d<?php echo $i; ?> = [<?php echo $kw_rank_graph[$i]; ?>];
			<?php // if (stristr(trim($kw_graph_urls[$i]), get_bloginfo('url')) ) { ?>
			var e<?php echo $i; ?> = [<?php echo $kw_visits_graph[$i]; ?>];
			<?php
			$visits_paceolders_vars = "{ data: e".$i.", label: '".$kw_th_visits."', yaxis: 2 }, ";
			// } else { $visits_paceolders_vars = ""; }
			$rank_placeholders_vars = "{ data: d".$i.", label: '".$kw_i18n_rank."'}, ";
			
			echo 'dDataArray['.$i.'] = d'.$i.';'."\n";
				echo 'eDataArray['.$i.'] = e'.$i.';'."\n";
			  ?>
</script>
<?php $j++; }  $i++; } }  
?>

<?php if (kw_isUrlInArray($keywurl_array)) {  ?>
<?php

$kw_q_check = implode("",$kw_rank_graph);
if (empty($kw_q_check)) { ?>


<div style="clear:both;height:50px;margin-top:50px;"><?php printf(__('%1$sNo data to graph%2$s - None of your keyword(s) for %3$s have any ranking data to report in a graph. Your website must rank within the first 100 results to be tracked.', 'seo-rank-reporter'), "<strong>", "</strong>", "<em>".bloginfo('url')."</em>"); ?> </div>
<?php } else { ?>
<div style="clear:both;height:478px">&nbsp;</div>
<?php } ?>
<form action='../wp-admin/admin.php?page=seo-rank-reporter' method='post'>
  <table border="0" cellpadding="0" cellspacing="0" class="widefat sortable" id="kw_keyword_table">
    <thead>
        <tr>
        <th title="<?php echo $kw_click_to_sort; ?>">#</th>
        <th title="<?php _e('You can only graph one set at a time', 'seo-rank-reporter'); ?>"><?php echo $kw_th_graph; ?></th>
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
$k=0;
foreach($keywurl_visits_array as $key_vis) { ?>
    <tr<?php if ($k==0) { echo ' class="selected-row" '; } ?>>
      <td><?php echo $k+1; ?></td>
      <td><input class="graph-radio-button" type="radio" <?php if($k==0) echo 'checked="checked" '; ?>name="graph-radio" value="<?php echo $k; ?>" style="margin-top:5px;" title="d<?php echo $k; ?>" <?php if(stristr($key_vis[current_rank], $kw_not_in_top)) echo 'disabled="disabled" '; ?>/>
      <td><a href='<?php echo $kw_sengine_country; ?>search?q=<?php echo urlencode($key_vis[kw_keyw]); ?>&pws=0' style="color:<?php echo $key_vis[keyw_color]; ?>;" target="_blank" title="<?php echo $kw_opens_new_window; ?>"><?php echo $key_vis[kw_keyw]; ?></a></td>
      <td><a href="<?php echo $key_vis[kw_url]; ?>" target="_blank" title="<?php echo $kw_opens_new_window; ?>"><?php echo substr(($key_vis[kw_url]), strlen(get_bloginfo('url'))); ?></a></td>
      <td><?php echo $key_vis[current_rank]; ?></td>
      <td><div class="<?php echo $key_vis[rank_box]; ?> kw_change"></div>
        <?php echo $key_vis[rank_change]; ?></td>
      <td><?php echo $key_vis[start_rank]; ?></td>
      <td><?php echo $key_vis[kw_num_visits]; ?></td>
      <td><?php echo $key_vis[old_date]; ?></td>
      <td><input type='hidden' name='kw_remove_keyword' value='<?php echo $key_vis[kw_keyw]; ?>' />
          <input type='hidden' name='kw_remove_url' value='<?php echo $key_vis[kw_url]; ?>' />
          <input type='submit' value='<?php echo $kw_i18n_remove; ?>' class='button' onclick='return confirmRemove()' />
      </tr>
    <?php $k++; } ?>
  </table>
  </form>
  <table class="widefat">
    <tr>
      <td style="border-bottom:none;"><?php $kw_date_next = date("j M Y", get_option('kw_rank_nxt_date'));
$kw_date_last = date("j M Y", get_option('kw_rank_nxt_date')-259200);
printf(__('Last rank check was on %1$sNext rank check scheduled for %2$s', 'seo-rank-reporter'), "<strong>".$kw_date_last."</strong><br>", "<strong>".$kw_date_next."</strong>");
 ?></td>
      <td style="border-bottom:none"><?php printf(__('*When %1$sRank Change%2$s includes %1$s+%2$s, this keyword started ranking outside the first 100 results%3$s *Visits are the number of page visits since the last rank check (every 3 days). Visits will be blank if the URL does not contain %4$s', 'seo-rank-reporter'), "<strong>", "</strong>", "<br />", "<strong>".get_bloginfo('url')."</strong>"); ?>
        </td>
    </tr>
  </table>
  
  
  
  <script>
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
  
  function plotAccordingToDate(dmin,dmax,currentDivId) {
	
	var previousPoint = 0;
	
	$.plot($("#placeholder"+currentDivId), [ { data: dDataArray[currentDivId], label: '<?php echo $kw_i18n_rank; ?>'},  { data: eDataArray[currentDivId], label: '<?php echo $kw_th_visits; ?>', yaxis: 2 },  ], { series: {
                   lines: { show: true },
                   points: { show: true }}, 
				   legend: { margin: 10, backgroundOpacity: .5, position: "sw" },
               	   grid: { hoverable: true, clickable: true, backgroundColor: { colors: ["#fff", "#fff"] } },
				   yaxis: { tickFormatter: negformat, max: "-1" }, 
				   xaxis: { mode: "time",  timeformat: "%d %b %y", min: (new Date(dmin)).getTime(), max: (new Date(dmax)).getTime()}, 
				   y2axis: { }, selection: { mode: "x" },
			});
		$("#placeholder"+currentDivId).bind("plothover", function (event, pos, item) {
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
						x = parseFloat(x);
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
					
					if (item.series.label == 'Visits') {
						y = y*-1;
					}
                    showTooltip(item.pageX, item.pageY,
                                item.series.label + " " + y + "<br>" + months[monthNumber] + "-" + (myDate.getDate()+1) + "-" + myDate.getFullYear());
                }
            else {
                $("#tooltip").remove();
                previousPoint = null; 
            }
        }
    });	
	$('.aPlaceholder canvas:nth-child(2)').each(function() {
		$(this).css( {
			'backgroundImage': 'url(<?php echo plugins_url('images/graph-bg-visits.png', __FILE__); ?>)',
			'backgroundPosition': '97% center',
			'backgroundRepeat': 'no-repeat'
		});
	});
			
			
	
}
console.log(dDataArray);
  </script>
  
  
<?php } else { 
require('no-keywords.php');
} 
 ?>
</div>