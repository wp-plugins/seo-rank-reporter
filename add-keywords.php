<div class="wrap">
<h2>Add Keywords</h2>
<div class="wrap">
<script language="javascript">
	function confirmAdd() {
		return confirm("Do you really want to add this keyword?")
	} 
	
	function cancelAddition() {
		jQuery(".addSecondStep").slideUp(300, function() {
			jQuery(".addFirstStep").fadeIn(500);
		});
		
	}
	
jQuery(document).ready(function () { 
  jQuery(".kw_td_bg_color td").fadeTo("fast", 0.1);
  jQuery(".kw_td_bg_color td").fadeTo(1000, 1);
});
</script>


<?php 
		$kw_sengine_country = get_option('kw_seo_sengine_country');	

$displayNone = "";
if ($_POST['first_submit_keyw'] == "Add to Reporter" && $_POST['keyword_item'] != "" && $_POST['entry_url'] != "http://" && $_POST['entry_url'] != "") {
	$kw_quick_url = trim($_POST['entry_url']);
	$kw_quick_keyw = stripslashes(trim($_POST['keyword_item']));
	$checked_rank = kw_rank_checker($kw_quick_keyw, $kw_quick_url,TRUE);
	$displayNone = 'display:none;';
	
	//print_r($checked_rank); 
	
?>
<form action='../wp-admin/admin.php?page=seo-rank-reporter' method='post'>

<div style="background: #FFFFCC;border:solid 1px #FFFF66;padding:15px;margin-bottom:15px;" class="addSecondStep">

		
        <h3 style="margin-top:0px">Keyword: <a href='<?php echo $kw_sengine_country; ?>search?q=<?php echo urlencode($kw_quick_keyw); ?>&pws=0' target="_blank" title="Opens New Window"><?php echo $kw_quick_keyw; ?></a></h3>
		<?php if (count($checked_rank[1]) < 2) { ?>
        <p>Please confirm that the URL below is correct.</p>
        <?php } else { ?>
        <p>More than one URL of that domain was found. Please select the URL you'd like to add to the reporter</p>
        <?php } ?>
			
            <input type="hidden" name="keyword_item" value="<?php echo $kw_quick_keyw; ?>" />
            <table cellspacing="0" cellpadding="0" border="0" style="width:550px;" class="widefat">
            <thead>
            <tr>           
             <th width="30" style="width:30px;"></th>

            <th>URL</th>
            <th>Current Rank</th>
            </tr>
            </thead>
         
            <?php 
			
				for ($i = 0; $i < count($checked_rank[1]); $i++) {
					
					?>   <tr>         
            <td><input type="radio" name="entry_url" value="<?php echo $checked_rank[1][$i]; ?>" <?php if($i == 0) echo "checked='checked' "; ?>/></td>
            <td>
			<?php echo $checked_rank[1][$i];  ?>
            
            </td>
            
            <td><?php if ($checked_rank[4][$i] == "-1") { $checked_rank[4][$i] = "Not in top 100 results"; } echo $checked_rank[4][$i]; ?></td>
            
            
            		</tr><?php	
			} ?>
            <?php if (!in_array($kw_quick_url, $checked_rank[1])) { 
			
					$kw_quick_url_parsed = parse_url($kw_quick_url);
					if (empty($kw_quick_url_parsed['scheme'])) {
						$kw_quick_url = 'http://'.$kw_quick_url;
					}
					
			
				?>
            <!-- <tr>
                <td><input type="radio" name="entry_url" value="<?php echo $kw_quick_url; ?>" /></td>
                <td><?php echo $kw_quick_url; ?></td>
                <td>Not in top 100 results</td>
            </tr> --><?php } ?> 

            </table>
<br />
        <input type="button" class="button-secondary" value="Cancel" onclick="return cancelAddition()" />
            <input type="submit" class="button-primary add-to-reporter-button" name="submit_keyw" value="Confirm and Add to Reporter" />
            </div>
            </form>
        <br />
        <?php }
		$kw_keyw_error_msg == "";
		$kw_url_error_msg == "";

		if ($_POST['first_submit_keyw'] == "Add to Reporter" && $_POST['keyword_item'] == "") {
			$kw_keyw_error_msg = "<span style='color:red;'> You must add a keyword.</span>";
		}
		if ($_POST['first_submit_keyw'] == "Add to Reporter" && ($_POST['entry_url'] == "http://" || $_POST['entry_url'] == "")) {
			$kw_url_error_msg = "<span style='color:red;'> You must add a full URL.</span>";
		}
		
		 ?>
  <div style="<?php echo $displayNone; ?>" class="addFirstStep">  
          <form name="" method="post" action="" class="addKeywordForm">
    
          <table class="form-table add-keywords-table">
          <tbody>
            <tr>
              <th scope="row"><label for="keyword_item">Keyword:</label>
              </th>
              <td><input type="text" name="keyword_item" id="keyword_item" class="regular-text" value="<?php   echo stripslashes(trim($_POST['keyword_item']));  ?>" />
                <?php echo $kw_keyw_error_msg; ?><br />
              </td>
            </tr>
            <tr>
              <th scope="row"><label for="entry_url">URL:</label>
              </th>
              <td><input type="text" name="entry_url" id="entry_url" value="<?php  if (!empty($_POST['entry_url'])) { echo stripslashes(trim($_POST['entry_url'])); } else echo "http://"; ?>" class="kw_url_input regular-text" /><?php echo $kw_url_error_msg; ?>
                <br />
              </td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
              <th scope="row"></th>
              <td><input type="submit" value="Add to Reporter" class="button-primary add-to-reporter-button" name="first_submit_keyw" />
              </td>
            </tr>
            </tfoot>
          </table>
        </form>
        
    
      <h3 class=""><span>Referring Keywords</span></h3>
     
<?php  
$kw_keyw_visits_array = get_option('kw_keyw_visits');
if(empty($kw_keyw_visits_array)) {
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
          <input type='hidden' name='keyword_item' value='".$vi_keyword."' />
          <input type='hidden' name='entry_url' value='".$vi_url."' />
          <input type='submit' value='Add to Reporter' class='button' name='submit_keyw' onclick='return confirmAdd()' />
        </form>";
		}
			
		
	?>
    <tr class="<?php echo $kw_td_bg_color; ?>">
      <td><?php echo $k; ?></td>
      <td><a href='<?php echo $kw_sengine_country; ?>search?q=<?php echo urlencode($vi_keyword); ?>&pws=0' target="_blank" title="Opens New Window"><?php echo $vi_keyword; ?></a></td>
      <td><a href="<?php echo $vi_url; ?>" target="_blank" title="Opens New Window"><?php echo $vi_url; ?></a></td>
      <td style="text-align:center"><?php echo $keyw_visits; ?></td>
      <td style="text-align:center;"><?php echo $submit_reporter; ?></td>
    </tr>
    <?php $k++;  }//next($kw_keyw_visits_array); }  ?>
    </tbody>
      </table>
      
      <?php } ?>
      </div>
</div>