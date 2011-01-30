<div class="wrap">
<h2>Add Keywords</h2>

<script language="javascript">
	function confirmAdd() {
		return confirm("Do you really want to add this keyword?")
	} 
	
	function addKeywordNow() {
		jQuery(".addKeywordForm").attr("action", "../wp-admin/admin.php?page=seo-rank-reporter");
	}
	
jQuery(document).ready(function () { 

  jQuery(".kw_td_bg_color td").fadeTo("fast", 0.1);
  jQuery(".kw_td_bg_color td").fadeTo(1000, 1);
});

</script>
<?php if ($_POST['entry_url'] !== "" && $_POST['keywords']!== "" && $_POST['submit_keyw'] == "Add to Reporter" )  {
	$return_msg = kw_seoRankReporterAddKeywords($_POST['keywords'], $_POST['entry_url']);
	//$keyw_addition = TRUE;
}
if ($return_msg !== "") {
echo $return_msg;
}
?>


<?php if ($_POST['submit_keyw'] == "Check Rankings" && $_POST['keywords'] != "") {

	$kw_quick_keywords = explode("\n", $_POST['keywords']);
	$kw_quick_url = htmlspecialchars(stripslashes(trim($_POST['entry_url'])), ENT_QUOTES);

?>
<div style="background: #FFFFCC;border:solid 1px #FFFF66;padding:15px;">
        <h3>Current Rankings</h3>
        <p>Below are the current rankings for the keywords you just entered.</p>
        <table class="widefat">
          <thead>
            <tr>
              <th>Keyword</th>
              <th>URL</th>
              <th>Rank</th>
              <th>Page on Google</th>
            </tr>
          </thead>
          <?php

	foreach($kw_quick_keywords as $kw_quick_keyw) {
		$kw_quick_keyw = htmlspecialchars(stripslashes(trim($kw_quick_keyw)), ENT_QUOTES);
		$checked_rank = kw_rank_checker($kw_quick_keyw, $kw_quick_url,TRUE);
?>
          <tr>
            <td><?php echo $kw_quick_keyw; ?></td>
            <td><?php echo $checked_rank[1]; ?></td>
            <td><?php if ($checked_rank[4] == "-1") { $checked_rank[4] = "Not in top 100 results"; } echo $checked_rank[4]; ?></td>
            <td><?php if ($checked_rank[5] == "-1") { $checked_rank[5] = ""; } echo $checked_rank[5]; ?></td>
          </tr>
          <?php	}

	?>
        </table>
        </div>
        <br />
        <?php } ?>
        
        <form name="" method="post" action="" class="addKeywordForm">
          <table class="form-table">
          <tbody>
            <tr>
              <th scope="row"><label for="keywords_list">Keyword(s) (one per line)</label>
              </th>
              <td><textarea name="keywords" id="keywords_list" class="" style="width:23em" rows="7"><?php  if (!$keyw_addition) { echo stripslashes(trim($_POST['keywords'])); } ?>
</textarea>
                <br />
              </td>
            </tr>
            <tr>
              <th scope="row"><label for="entry_url">URL:</label>
              </th>
              <td><input type="text" name="entry_url" id="entry_url" value="<?php  if (!$keyw_addition) { echo stripslashes(trim($_POST['entry_url'])); } ?>" class="kw_url_input regular-text" />
                <br />
              </td>
            </tr>
            <tr>
              <th scope="row"></th>
              <td><input type="submit" value="Check Rankings" class="button" name="submit_keyw" />
                <input type="submit" value="Add to Reporter" class="button-primary" name="submit_keyw" onclick="addKeywordNow()" />
              </td>
            </tr>
            </tbody>
          </table>
        </form>
    
      <h3 class=""><span>Referring Keywords</span></h3>
     
<?php  

$kw_keyw_visits_array = get_option('kw_keyw_visits');

//print_r($kw_keyw_visits_array);

if($kw_keyw_visits_array == "") {
echo "No Referring Keywords<br><em>Generally, this means that you recently activated the plugin and no referring keywords have been recorded yet</em>";
} else {
?>

<table cellpadding="0" cellspacing="0" border="0" class="widefat sortable">
<thead>
<tr>
<th>#</th>
<th>Keyword</th>
<th>URL</th>
<th>Visits</th>
<th>Add to Reporter</th>
</tr>
</thead>
<tbody>
<?php
	asort($kw_keyw_visits_array, SORT_NUMERIC);
	$kw_keyw_visits_array = array_reverse($kw_keyw_visits_array);
	$keywurl_array = seoRankReporterGetKeywurl();
	$i = 0;
	foreach($keywurl_array as $keywurl) {
	
		$keywurl_array[$i] = implode('||', $keywurl);	

		$i++;
	}
	//print_r($kw_keyw_visits_array);
	
	$k = 1;
	while (list($kw_visits_key, $keyw_visits) = each($kw_keyw_visits_array)) {
 		$kw_vis = explode('||', $kw_visits_key);
		$vi_keyword = trim($kw_vis[0]);
		$vi_url = trim($kw_vis[1]);
 		
		if (in_array($kw_visits_key, $keywurl_array)) {
			$kw_td_bg_color = "kw_td_bg_color";
			$submit_reporter = "<em>Added</em>";
		} else {
			$kw_td_bg_color = "";
			$submit_reporter = "<form action='../wp-admin/admin.php?page=seo-rank-reporter' method='post'>
          <input type='hidden' name='keywords' value='".$vi_keyword."' />
          <input type='hidden' name='entry_url' value='".$vi_url."' />
          <input type='submit' value='Add to Reporter' class='button' name='submit_keyw' onclick='return confirmAdd()' />
        </form>";
		}
			
		
	?>
    <tr class="<?php echo $kw_td_bg_color; ?>">
      <td><?php echo $k; ?></td>
      <td><a href='http://www.google.com/search?q=<?php echo str_replace(" ", "+", $vi_keyword); ?>' target="_blank" title="Opens New Window"><?php echo $vi_keyword; ?></a></td>
      <td><a href="<?php echo $vi_url; ?>" target="_blank" title="Opens New Window"><?php echo $vi_url; ?></a></td>
      <td style="text-align:center"><?php echo $keyw_visits; ?></td>
      <td style="text-align:center;"><?php echo $submit_reporter; ?></td>
    </tr>
    <?php $k++;  }//next($kw_keyw_visits_array); }  ?>
    </tbody>

      </table>
      
      <?php } ?>
</div>