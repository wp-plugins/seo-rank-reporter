<?php 


//NONCE for SECURITY
if( isset($_POST['first_submit_keyw']) ) {
  $nonce = $_REQUEST['_wpnonce'];
  if ( ! wp_verify_nonce( $nonce, 'kw_seo_rank_reporter_nonce' ) ) {
    exit; // Get out of here, the nonce is rotten!
  }
}


$kw_add_to_reporter = _x('Add to Reporter', '"Reporter" is Proper Noun', 'seo-rank-reporter');
$kw_opens_new_window = __('Opens New Window', 'seo-rank-reporter');
$kw_th_url = __('URL', 'seo-rank-reporter'); 
$kw_th_current_rank = __('Current Rank', 'seo-rank-reporter');
$kw_confirm_add_to_reporter = _x('Confirm and Add to Reporter', '"Reporter" is Proper Noun', 'seo-rank-reporter');
$kw_i18n_keyword = __('Keyword', 'seo-rank-reporter');
$kw_th_visits = __('Visits', 'seo-rank-reporter');
  ?>

<div class="wrap">
<h2><?php _e('Add Keywords', 'seo-rank-reporter'); ?></h2>
<div class="wrap">
<script language="javascript">
  function confirmAdd() {
    return confirm("<?php _e('Do you really want to add this keyword?', 'seo-rank-reporter'); ?>")
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
    $kw_sengine_country = kw_get_sengine_url(); 

$displayNone = "";



if ($_POST['first_submit_keyw'] == $kw_add_to_reporter && $_POST['keyword_item'] != "" && $_POST['entry_url'] != "http://" && $_POST['entry_url'] != "") {
  $kw_quick_url = trim($_POST['entry_url']);
  $kw_quick_keyw = stripslashes(trim($_POST['keyword_item']));
  $checked_rank = kw_rank_checker($kw_quick_keyw, $kw_quick_url,TRUE);
  $displayNone = 'display:none;';
  
  //print_r($checked_rank); 
  
?>
<form action='../wp-admin/admin.php?page=seo-rank-reporter' method='post'>

<div style="background: #FFFFCC;border:solid 1px #FFFF66;padding:15px;margin-bottom:15px;" class="addSecondStep">

    
        <h3 style="margin-top:0px"><?php echo $kw_i18n_keyword; ?>: <a href='<?php echo $kw_sengine_country; ?>search?q=<?php echo urlencode($kw_quick_keyw); ?>&pws=0' target="_blank" title="<?php echo $kw_opens_new_window; ?>"><?php echo $kw_quick_keyw; ?></a></h3>
    <?php if (count($checked_rank[1]) < 2) { ?>
        <p<?php _e('Please confirm that the URL below is correct.', 'seo-rank-reporter'); ?></p>
        <?php } else { ?>
        <p><?php _e('More than one URL of that domain was found. Please select the URL you\'d like to add to the reporter', 'seo-rank-reporter'); ?></p>
        <?php } ?>
      
            <input type="hidden" name="keyword_item" value="<?php echo $kw_quick_keyw; ?>" />
            <table cellspacing="0" cellpadding="0" border="0" style="width:550px;" class="widefat">
            <thead>
            <tr>           
             <th width="30" style="width:30px;"></th>

            <th><?php echo $kw_th_url; ?></th>
            <th><?php echo $kw_th_current_rank; ?></th>
            </tr>
            </thead>
         
            <?php 
      
        for ($i = 0; $i < count($checked_rank[1]); $i++) {
          
          ?>   <tr>         
            <td><input type="radio" name="entry_url" value="<?php echo $checked_rank[1][$i]; ?>" <?php if($i == 0) echo "checked='checked' "; ?>/></td>
            <td>
      <?php echo $checked_rank[1][$i];  ?>
            
            </td>
            
            <td><?php if ($checked_rank[4][$i] == "-1") { $checked_rank[4][$i] = __('Not in top 100 results', 'seo-rank-reporter'); } echo $checked_rank[4][$i]; ?></td>
            
            
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
                    <p><em><?php printf(__('*Search was made at %1$s', 'seo-rank-reporter'), "<a target='_blank' href='".$kw_sengine_country."'>".$kw_sengine_country."</a>"); ?></em></p>

<br />
        <input type="button" class="button-secondary" value="<?php _e('Cancel', 'seo-rank-reporter'); ?>" onclick="return cancelAddition()" />
            <input type="submit" class="button-primary add-to-reporter-button" name="submit_keyw" value="<?php echo $kw_confirm_add_to_reporter; ?>" />
<?php wp_nonce_field( 'kw_seo_rank_reporter_nonce_2' ); ?>
            </div>
            </form>
        <br />
        <?php }
    $kw_keyw_error_msg == "";
    $kw_url_error_msg == "";

    if ($_POST['first_submit_keyw'] == $kw_add_to_reporter && $_POST['keyword_item'] == "") {
      $kw_keyw_error_msg = "<span style='color:red;'>".__('You must add a keyword.', 'seo-rank-reporter')."</span>";
    }
    if ($_POST['first_submit_keyw'] == $kw_add_to_reporter && ($_POST['entry_url'] == "http://" || $_POST['entry_url'] == "")) {
      $kw_url_error_msg = "<span style='color:red;'>".__('You must add a full URL.', 'seo-rank-reporter')."</span>";
    }
    
     ?>
  <div style="<?php echo $displayNone; ?>" class="addFirstStep">  
          <form name="" method="post" action="" class="addKeywordForm">
    
          <table class="form-table add-keywords-table">
          <tbody>
            <tr>
              <th scope="row"><label for="keyword_item"><?php echo $kw_i18n_keyword; ?>:</label>
              </th>
              <td><input type="text" name="keyword_item" id="keyword_item" class="regular-text" value="<?php   echo stripslashes(trim($_POST['keyword_item']));  ?>" />
                <?php echo $kw_keyw_error_msg; ?><br />
              </td>
            </tr>
            <tr>
              <th scope="row"><label for="entry_url"><?php echo $kw_th_url; ?>:</label>
              </th>
              <td><input type="text" name="entry_url" id="entry_url" value="<?php  if (!empty($_POST['entry_url'])) { echo stripslashes(trim($_POST['entry_url'])); } else echo get_bloginfo('url'); ?>" class="kw_url_input regular-text" /><?php echo $kw_url_error_msg; ?>
                <br />
              </td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
              <th scope="row"></th>
              <td><input type="submit" value="<?php echo $kw_add_to_reporter; ?>" class="button-primary add-to-reporter-button" name="first_submit_keyw" />
                <?php wp_nonce_field( 'kw_seo_rank_reporter_nonce' ); ?>
              </td>
            </tr>
            </tfoot>
          </table>
        </form>
        
    
      <h3 class=""><span><?php _e('Referring Keywords', 'seo-rank-reporter'); ?></span></h3>
     
<?php  
$kw_keyw_visits_array = get_option('kw_keyw_visits');
if(empty($kw_keyw_visits_array)) {
  
_e('No Referring Keywords');
echo '<br><em>';  
_e('Usually, this means that you recently activated the plugin and no referring keywords have been recorded yet', 'seo-rank-reporter');
echo '</em>';
} else {
?>
<table cellpadding="0" cellspacing="0" border="0" class="widefat sortable">
<thead>
<tr>
<th>#</th>
<th><?php echo $kw_i18n_keyword; ?></th>
<th><?php echo $kw_th_url; ?></th>
<th><?php echo $kw_th_visits; ?></th>
<th><?php echo $kw_add_to_reporter; ?></th>
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
      $submit_reporter = "<em>".__('Added', 'seo-rank-reporter')."</em>";
    } else {
      $kw_td_bg_color = "";
      $submit_reporter = "<form action='../wp-admin/admin.php?page=seo-rank-reporter' method='post'>
          <input type='hidden' name='keyword_item' value='".$vi_keyword."' />
          <input type='hidden' name='entry_url' value='".$vi_url."' />
          <input type='submit' value='".$kw_add_to_reporter."' class='button' name='submit_keyw' onclick='return confirmAdd()' />
        </form>"; 
    }
      
    
  ?>
    <tr class="<?php echo $kw_td_bg_color; ?>">
      <td><?php echo $k; ?></td>
      <td><a href='<?php echo $kw_sengine_country; ?>search?q=<?php echo urlencode($vi_keyword); ?>&pws=0' target="_blank" title="<?php echo $kw_opens_new_window; ?>"><?php echo $vi_keyword; ?></a></td>
      <td><a href="<?php echo $vi_url; ?>" target="_blank" title="<?php echo $kw_opens_new_window; ?>"><?php echo $vi_url; ?></a></td>
      <td style="text-align:center"><?php echo $keyw_visits; ?></td>
      <td style="text-align:center;"><?php echo $submit_reporter; ?></td>
    </tr>
    <?php $k++;  }//next($kw_keyw_visits_array); }  ?>
    </tbody>
      </table>
      
      <?php } ?>
      </div>
</div>