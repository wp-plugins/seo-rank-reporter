 <div class="wrap">
<h2>Visits Graph</h2>
<?php
$keywurl_array = seoRankReporterGetKeywurl();
if (kw_isUrlInArray($keywurl_array)) {
	
		$kw_sengine_country = get_option('kw_seo_sengine_country');	
?>
<div style="float:right;font-size:11px;padding-right:20px;margin-top:-10px;text-align:right;z-index:9999999;"><strong>Get keyword rankings checked daily</strong> <br />
<a href="http://authoritylabs.com/?src=reporter-wp-plugin" target="_blank">Try Authority Labs</a> - 10 Keywords Free</div>
 <script language="javascript">
	function confirmRemove() {
		return confirm("Do you really want to remove this keyword? This action cannot be undone.")
	}  
	</script>
 <div style="height:30px;">
			<input type="text" id="mindatepicker" class="fav-first" value="" />
           to <input type="text" id="maxdatepicker" class="fav-first" value="" />
	
		
</div>
<?php
 	$kw_keyw_visits_array = get_option('kw_keyw_visits');
	$kw_plot_graph_array = "";
foreach($keywurl_array as $keywurl) {
	$kw_url = trim($keywurl[url]);
	$kw_keyw = trim($keywurl[keyword]);
	
  if(stristr(trim($kw_url), get_bloginfo('url'))) {
	$current_rank = "<em>Not yet checked</em>";
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
		$old_date = date('M', mktime(0, 0, 0, $old_date_array[1], $old_date_array[2], $old_date_array[0]) ).'-'.$old_date_array[2].'-'.$old_date_array[0];
	}
	if ($current_rank !== "<em>Not yet checked</em>" && $start_rank !== "-1" && $current_rank !== "-1" ) {
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
		$start_rank = "<em>Not in top 100</em>";
	} elseif ($current_rank == "-1" && $start_rank > 0) {
		
		$rank_change = ($start_rank-100).'+';
		$rank_box = "kw_red_arrow";
		$current_rank = "<em>Not in top 100</em>";
	} elseif ($current_rank == "-1") {
		$rank_box = "kw_gray_line";
		$current_rank = "<em>Not in top 100</em>";
		$start_rank = "<em>Not in top 100</em>";
	}
	
	$kw_num_visits = '';
	if (stristr(trim($kw_url), get_bloginfo('url')) ) {
		$kw_num_visits = $kw_keyw_visits_array[$kw_keyw.'||'.$kw_url];
	} 
	
	$keywurl_visits_array[] = array("kw_keyw" => $kw_keyw, "keyw_color" => $keyw_color, "kw_url" => $kw_url, "current_rank" => $current_rank, "rank_box" => $rank_box, "rank_change" => $rank_change, "start_rank" => $start_rank, "kw_num_visits" => $kw_num_visits, "old_date" => $old_date); 
	
} }
?>
 <?php $datepicker_array = explode("-", $datepicker_min);
  		$datepicker_min_edit = date('M j, Y', mktime(0, 0, 0, $datepicker_array[1], $datepicker_array[2], $datepicker_array[0])); 
		$datepicker_array = explode("-", $datepicker_max);
		$datepicker_max_edit = date('M j, Y', mktime(0, 0, 0, $datepicker_array[1], $datepicker_array[2], $datepicker_array[0]));
   ?>
 
  <script type="text/javascript">
    var currentPlaceHolder = 0;
var daMin = <?php echo sprintf('%f', strtotime($datepicker_min)*1000); ?>;
var daMax = <?php echo sprintf('%f', strtotime($datepicker_max)*1000); ?>;
$("#mindatepicker").val('<?php echo $datepicker_min_edit; ?>');
$("#maxdatepicker").val('<?php echo $datepicker_max_edit; ?>');
	$("#mindatepicker").datepicker({
		minDate: '<?php echo $datepicker_min_edit; ?>',
		maxDate: '<?php echo $datepicker_max_edit; ?>',
		showAnim: 'slideDown',
		dateFormat: 'M d, yy', 
   		onSelect: function(dateText, inst) { 
			plotAccordingToDate(dateText,daMax, currentPlaceHolder);
			daMin = dateText;
		}
	});
	$("#maxdatepicker").datepicker({
		minDate: '<?php echo $datepicker_min_edit; ?>',
		maxDate: '<?php echo $datepicker_max_edit; ?>',
		showAnim: 'slideDown',
		dateFormat: 'M d, yy', 
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
 
  <img src="../wp-content/plugins/seo-rank-reporter/images/graph-bg-rank.png" class="rankLabel" style="margin-top:90px;position:absolute;left:40px;z-index:999999" />
  <div id="placeholder<?php echo $i; ?>" style="width:95%;height:450px;margin-left:0px;" class="aPlaceholder"></div>
  
</div>
<script id="source">
			var d<?php echo $i; ?> = [<?php echo $kw_rank_graph[$i]; ?>];
			<?php // if (stristr(trim($kw_graph_urls[$i]), get_bloginfo('url')) ) { ?>
			var e<?php echo $i; ?> = [<?php echo $kw_visits_graph[$i]; ?>];
			<?php
			$visits_paceolders_vars = "{ data: e".$i.", label: 'Visits', yaxis: 2 }, ";
			// } else { $visits_paceolders_vars = ""; }
			$rank_placeholders_vars = "{ data: d".$i.", label: 'Ranking'}, ";
			
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
<div style="clear:both;height:50px;margin-top:50px;"><strong>No data to graph</strong> - None of your keyword(s) for <em><?php bloginfo('url'); ?></em> have any ranking data to report in a graph. Your website must rank within the first 100 results to be tracked. </div>
<?php } else { ?>
<div style="clear:both;height:478px">&nbsp;</div>
<?php } ?>
<form action='../wp-admin/admin.php?page=seo-rank-reporter' method='post'>
  <table border="0" cellpadding="0" cellspacing="0" class="widefat sortable" id="kw_keyword_table">
    <thead>
      <tr>
        <th title="Click to Sort">#</th>
        <th title="You can only graph one set at a time">Graph</th>
        <th title="Click to Sort">Keywords</th>
        <th title="Click to Sort">URL</th>
        <th title="Click to Sort">Current Rank</th>
        <th title="Click to Sort">Rank Change</th>
        <th title="Click to Sort">Start Rank</th>
        <th title="Click to Sort">Visits</th>
        <th title="Click to Sort">Start Date</th>
        <th></th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th></th>
        <th>Graph</th>
        <th>Keywords</th>
        <th>URL</th>
        <th>Current Rank</th>
        <th>Rank Change</th>
        <th>Start Rank</th>
        <th>Visits</th>
        <th>Start Date</th>
        <th></th>
      </tr>
    </tfoot>
<?php 
$k=0;
foreach($keywurl_visits_array as $key_vis) { ?>
    <tr<?php if ($k==0) { echo ' class="selected-row" '; } ?>>
      <td><?php echo $k+1; ?></td>
      <td><input class="graph-radio-button" type="radio" <?php if($k==0) echo 'checked="checked" '; ?>name="graph-radio" value="<?php echo $k; ?>" style="margin-top:5px;" title="d<?php echo $k; ?>" <?php if(stristr($key_vis[current_rank], "Not in top 100")) echo 'disabled="disabled" '; ?>/>
      <td><a href='<?php echo $kw_sengine_country; ?>search?q=<?php echo urlencode($key_vis[kw_keyw]); ?>&pws=0' style="color:<?php echo $key_vis[keyw_color]; ?>;" target="_blank" title="Opens New Window"><?php echo $key_vis[kw_keyw]; ?></a></td>
      <td><a href="<?php echo $key_vis[kw_url]; ?>" target="_blank" title="Opens New Window"><?php echo substr(($key_vis[kw_url]), strlen(get_bloginfo('url'))); ?></a></td>
      <td><?php echo $key_vis[current_rank]; ?></td>
      <td><div class="<?php echo $key_vis[rank_box]; ?> kw_change"></div>
        <?php echo $key_vis[rank_change]; ?></td>
      <td><?php echo $key_vis[start_rank]; ?></td>
      <td><?php echo $key_vis[kw_num_visits]; ?></td>
      <td><?php echo $key_vis[old_date]; ?></td>
      <td><input type='hidden' name='kw_remove_keyword' value='<?php echo $key_vis[kw_keyw]; ?>' />
          <input type='hidden' name='kw_remove_url' value='<?php echo $key_vis[kw_url]; ?>' />
          <input type='submit' value='Remove' class='button' onclick='return confirmRemove()' />
      </tr>
    <?php $k++; } ?>
  </table>
  </form>
  <table class="widefat">
    <tr>
      <td style="border-bottom:none;"><?php $kw_date_next = date("M-d-Y", get_option('kw_rank_nxt_date'));
$kw_date_last = date("M-d-Y", get_option('kw_rank_nxt_date')-259200);
echo "Last rank check was on <strong>".$kw_date_last."</strong><br>Next rank check scheduled for <strong>".$kw_date_next."</strong>"; ?></td>
      <td style="border-bottom:none"> *When <strong>Rank Change</strong> includes <strong>+</strong>, this keyword started ranking outside the first 100 results<br />
       *Visits are the number of page visits since the last rank check (every 3 days). 
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
	
	$.plot($("#placeholder"+currentDivId), [ { data: dDataArray[currentDivId], label: 'Ranking'},  { data: eDataArray[currentDivId], label: 'Visits', yaxis: 2 },  ], { series: {
                   lines: { show: true },
                   points: { show: true }}, 
				   legend: { margin: 10, backgroundOpacity: .5, position: "sw" },
               	   grid: { hoverable: true, clickable: true, backgroundColor: { colors: ["#fff", "#fff"] } },
				   yaxis: { tickFormatter: negformat, max: "-1" }, 
				   xaxis: { mode: "time",  timeformat: "%b-%d-%y", min: (new Date(dmin)).getTime(), max: (new Date(dmax)).getTime()}, 
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
			'backgroundImage': 'url(../wp-content/plugins/seo-rank-reporter/images/graph-bg-visits.png)',
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