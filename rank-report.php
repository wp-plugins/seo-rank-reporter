<div class="wrap">
  <h2>SEO Rank Reporter</h2>
  <?php

if (isset($_POST['kw_remove_keyword'] ) ) {
	$remove_msg = kw_seoRankReporterRemoveKeyword($_POST['kw_remove_keyword'], $_POST['kw_remove_url']);
}

if ($_POST['entry_url'] !== "" && $_POST['keywords']!== "" && $_POST['submit_keyw'] == "Add to Reporter" )  {
	$return_msg = kw_seoRankReporterAddKeywords($_POST['keywords'], $_POST['entry_url']);
	//$keyw_addition = TRUE;
}

?>
  
  <script language="javascript">

	function confirmRemove() {
		return confirm("Do you really want to remove this keyword? This action cannot be undone.")
	} 

	$(document).ready(function () { 

  $(".kw_td_bg_color td").fadeTo("fast", 0.1);
   $(".kw_td_bg_color td").fadeTo(1000, 1);
   
  
});
</script>

  
<?php if ($return_msg !== "") {
echo $return_msg;
}
if ($remove_msg !== "") {
echo $remove_msg; 
}
?>
<?php
$keywurl_array = seoRankReporterGetKeywurl();
if (empty($keywurl_array)) {

require('no-keywords.php');
} else {

?>
<div style="height:30px;">


			<input type="text" id="mindatepicker" class="fav-first" value="" />
           to <input type="text" id="maxdatepicker" class="fav-first" value="" />
	
		
</div>

<div id="placeholder" style="width:90%;height:400px;margin-left:10px;"></div><br />

  <table border="0" cellpadding="0" cellspacing="0" class="widefat sortable" id="kw_keyword_table">
    <thead>
      <tr>
        <th title="Click to Sort">#</th>
        <th title="Click to Sort">Graph</th>
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
	

	$kw_keyw_visits_array = get_option('kw_keyw_visits');

	$kw_plot_graph_array = "";
	$k = 1;
	$p = 0;
	$choices = "";

foreach($keywurl_array as $keywurl) {

	$kw_url = trim($keywurl[url]);
	$kw_keyw = trim($keywurl[keyword]);
	
	$current_rank = "<em>Not yet checked</em>";
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
		$old_date = date('M', mktime(0, 0, 0, $old_date_array[1], $old_date_array[2], $old_date_array[0]) ).'-'.$old_date_array[2].'-'.$old_date_array[0];
	}


	if ($current_rank !== "<em>Not yet checked</em>" && $start_rank !== "-1" && $current_rank !== "-1" ) {
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
		$start_rank = "<em>Not in top 100</em>";
	} elseif ($current_rank == "-1" && $start_rank > 0) {
		$choices = "choices".$p;
		$p++;
		$disabled = "";
		
		$rank_change = ($start_rank-100).'+';
		$rank_box = "kw_red_arrow";
		$current_rank = "<em>Not in top 100</em>";
	} elseif ($current_rank == "-1") {
		$rank_box = "kw_gray_line";
		$current_rank = "<em>Not in top 100</em>";
		$start_rank = "<em>Not in top 100</em>";
		
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
      <td><a href='http://www.google.com/search?q=<?php echo str_replace(" ", "+", $kw_keyw); ?>' style="color:<?php echo $keyw_color; ?>;" target="_blank" title="Opens New Window"><?php echo $kw_keyw; ?></a></td>
      <td><a href="<?php echo $kw_url; ?>" target="_blank" title="Opens New Window"><?php echo $kw_url; ?></a></td>
      <td><?php echo $current_rank; ?></td>
      <td><div class="<?php echo $rank_box; ?> kw_change"></div>
        <?php echo $rank_change; ?></td>
      <td><?php echo $start_rank; ?></td>
      <td><?php echo $kw_num_visits; ?></td>
      <td><?php echo $old_date; ?></td>
      <td><form action='' method='post'>
          <input type='hidden' name='kw_remove_keyword' value='<?php echo $kw_keyw; ?>' />
          <input type='hidden' name='kw_remove_url' value='<?php echo $kw_url; ?>' />
          <input type='submit' value='Remove' class='button' onclick='return confirmRemove()' />
        </form>
    </tr>
    <?php $k++;  } ?>
  </table>
  <table class="widefat">
    <tr>
      <td style="border-bottom:none;"><?php $kw_date_next = date("M-d-Y", get_option('kw_rank_nxt_date'));
$kw_date_last = date("M-d-Y", get_option('kw_rank_nxt_date')-259200);
echo "Last rank check was on <strong>".$kw_date_last."</strong><br>Next rank check scheduled for <strong>".$kw_date_next."</strong>"; ?></td>
      <td style="border-bottom:none"> *When <strong>Rank Change</strong> includes <strong>+</strong>, this keyword started ranking outside the first 100 results<br />
        *Visits will be blank if the url does not contain <strong><?php echo get_bloginfo('url'); ?></strong></td>
    </tr>
  </table>
  <div>
<form action="" method="post">
<input type="submit" value="Download CSV" name="dnload-csv" class="button" />
</form>
</div>


  <br />
  <?php $datepicker_array = explode("-", $datepicker_min);
  		$datepicker_min_edit = date('M j, Y', mktime(0, 0, 0, $datepicker_array[1], $datepicker_array[2], $datepicker_array[0])); 
		$datepicker_array = explode("-", $datepicker_max);
		$datepicker_max_edit = date('M j, Y', mktime(0, 0, 0, $datepicker_array[1], $datepicker_array[2], $datepicker_array[0]));
  
  
   ?>
  <script id="source" language="javascript" type="text/javascript">
  
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
			plotAccordingToDate(dateText,daMax);
		}
	});
	$("#maxdatepicker").datepicker({
		minDate: '<?php echo $datepicker_min_edit; ?>',
		maxDate: '<?php echo $datepicker_max_edit; ?>',
		showAnim: 'slideDown',
		dateFormat: 'M d, yy', 
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
	} else {
		checked = '';
	}
	
	
	if (val.data != "") {
   		choiceContainer.append('<input type="checkbox" name="' + key +
                           '" ' + checked + ' id="id' + key + '" class="kw_checkboxes" onclick="plotAccordingToChoices()" />');
	
		++icounter; //ftd = '';
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
                            item.series.label + "<br>Rank: " + y + "<br>" + months[monthNumber] + "-" + (myDate.getDate()) + "-" + myDate.getFullYear());
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
           grid: { hoverable: true, clickable: true, backgroundColor: { colors: ["#fff", "#fff"] } }, yaxis: { tickFormatter: negformat, max: "-1" }, xaxis: { mode: "time",  timeformat: "%b-%d-%y",  min: (new Date(daMin)).getTime(), max: (new Date(daMax)).getTime() }, selection: { mode: "x" },  });
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
			xaxis: { mode: "time",  timeformat: "%b-%d-%y",  min: (new Date(dmin)).getTime(), max: (new Date(dmax)).getTime() },
			selection: { mode: "x" },  
		});
	//}	
	daMin = dmin;
	daMax = dmax;
		
		
	
}
</script>
  
<?php } ?>
</div>
