<?php $kw_update_email_notifications = __('Update Email Notifications', 'seo-rank-reporter');
  $kw_delete_all_data = __('Delete All Data', 'seo-rank-reporter');


//update_option('kw_seo_sengine_country', "http://www.google.com/"); 
//$kw_sengine_url = kw_get_sengine_url();
//print_r($kw_sengine_url);

?>

<div class="wrap">
  <h2><?php _e('SEO Rank Reporter Settings', 'seo-rank-reporter'); ?></h2>
  <div class="postbox-container" style="width:65%;">
    <?php
 //Add/Remove Email Notification
$updated_msg = "";
if ($_POST['kw_seo_emails'] !== "" && $_POST['kw_em_spots'] !== "" && $_POST['update_notifications'] == $kw_update_email_notifications && $_POST['notify_me'] == "yes") {
  
  update_option('kw_seo_emails', $_POST['kw_seo_emails']);
  update_option('kw_em_spots', $_POST['kw_em_spots']);
  $updated_msg = "<div id='message' class='updated'>".__('Email notification updated', 'seo-rank-reporter')."</div>";
}
if ($_POST['notify_me'] !== "yes" && $_POST['update_notifications'] == $kw_update_email_notifications) {
  update_option('kw_seo_emails', '');
  update_option('kw_em_spots', '');
  $updated_msg = "<div id='message' class='updated'>".__('Email notification updated', 'seo-rank-reporter')."</div>";
}
if ($_POST['table_delete'] == $kw_delete_all_data && $_POST['delete_data_check'] == 'delete_approved') {
  seoRankReporterDelete();
  $updated_msg = "<div id='message' class='updated'>".__('All data has been removed. If you wish to completely remove the table, you may now deactivate the plugin.', 'seo-rank-reporter')."</div>";
}
?>
    <?php 
$notify_checkbox = "";
$notify_emails = get_bloginfo('admin_email');
$notify_spots = "10";
$notify_details_visibility = 'style="display:none"';
$kw_em_spots = trim(get_option('kw_em_spots'));
$kw_seo_emails = trim(get_option('kw_seo_emails'));
if ($kw_em_spots !== "" && $kw_seo_emails !== "") { 
  $notify_checkbox = ' checked="checked"';
  $notify_emails = get_option('kw_seo_emails');
  $notify_spots = get_option('kw_em_spots');
  $notify_details_visibility = 'style="display:block"';
}
?>
    <div class="kw-update-message"> <?php echo $updated_msg; ?> </div>
    <script language="javascript">
  function confirmRemove() {
    return confirm("<?php _e('Do you really want to delete all your data? This action cannot be undone.', 'seo-rank-reporter'); ?>")
  }  
  
  <?php $kw_seo_sengine_select = kw_get_sengine_url(); ?> 
  
  jQuery(document).ready( function() {
    jQuery('option[value="<?php echo $kw_seo_sengine_select; ?>"]').attr('selected', true); 
  });
    
</script>
    <h3><?php _e('Google Country URL', 'seo-rank-reporter'); ?></h3>
    <table class="form-table">
      <tbody>
        <tr>
          <th><?php _e('Select which URL you\'d like the Rank Reporter to use', 'seo-rank-reporter'); ?></th>
          <td><select name="se_country_url">
              <option value="http://www.google.com/"><?php _e('Default', 'seo-rank-reporter'); ?> - Google.com (http://www.google.com/)</option>
              <option value="http://www.google.as/"><?php _e('American Samoa', 'seo-rank-reporter'); ?> (http://www.google.as/)</option>
              <option value="http://www.google.off.ai/"><?php _e('Anguilla', 'seo-rank-reporter'); ?> (http://www.google.off.ai/)</option>
              <option value="http://www.google.com.ag/"><?php _e('Antigua and Barbuda', 'seo-rank-reporter'); ?> (http://www.google.com.ag/)</option>
              <option value="http://www.google.com.ar/"><?php _e('Argentina', 'seo-rank-reporter'); ?> (http://www.google.com.ar/)</option>
              <option value="http://www.google.com.au/"><?php _e('Australia', 'seo-rank-reporter'); ?> (http://www.google.com.au/)</option>
              <option value="http://www.google.at/"><?php _e('Austria', 'seo-rank-reporter'); ?> (http://www.google.at/)</option>
              <option value="http://www.google.az/"><?php _e('Azerbaijan', 'seo-rank-reporter'); ?> (http://www.google.az/)</option>
              <option value="http://www.google.be/"><?php _e('Belgium', 'seo-rank-reporter'); ?> (http://www.google.be/)</option>
              <option value="http://www.google.com.br/"><?php _e('Brazil', 'seo-rank-reporter'); ?> (http://www.google.com.br/)</option>
              <option value="http://www.google.vg/"><?php _e('British Virgin Islands', 'seo-rank-reporter'); ?> (http://www.google.vg/)</option>
              <option value="http://www.google.bi/"><?php _e('Burundi', 'seo-rank-reporter'); ?> (http://www.google.bi/)</option>
              <option value="http://www.google.ca/"><?php _e('Canada', 'seo-rank-reporter'); ?> (http://www.google.ca/)</option>
              <option value="http://www.google.td/"><?php _e('Chad', 'seo-rank-reporter'); ?> (http://www.google.td/)</option>
              <option value="http://www.google.cl/"><?php _e('Chile', 'seo-rank-reporter'); ?> (http://www.google.cl/)</option>
              <option value="http://www.google.com.co/"><?php _e('Colombia', 'seo-rank-reporter'); ?> (http://www.google.com.co/)</option>
              <option value="http://www.google.co.cr/"><?php _e('Costa Rica', 'seo-rank-reporter'); ?> (http://www.google.co.cr/)</option>
              <option value="http://www.google.ci/"><?php _e('Côte d\'Ivoire', 'seo-rank-reporter'); ?> (http://www.google.ci/)</option>
              <option value="http://www.google.com.cu/"><?php _e('Cuba', 'seo-rank-reporter'); ?> (http://www.google.com.cu/)</option>
              <option value="http://www.google.cd/"><?php _e('Dem. Rep. of the Congo', 'seo-rank-reporter'); ?> (http://www.google.cd/)</option>
              <option value="http://www.google.dk/"><?php _e('Denmark', 'seo-rank-reporter'); ?> (http://www.google.dk/)</option>
              <option value="http://www.google.dj/"><?php _e('Djibouti', 'seo-rank-reporter'); ?> (http://www.google.dj/)</option>
              <option value="http://www.google.com.do/"><?php _e('Dominican Republic', 'seo-rank-reporter'); ?> (http://www.google.com.do/)</option>
              <option value="http://www.google.com.ec/"><?php _e('Ecuador', 'seo-rank-reporter'); ?> (http://www.google.com.ec/)</option>
              <option value="http://www.google.com.sv/"><?php _e('El Salvador', 'seo-rank-reporter'); ?> (http://www.google.com.sv/)</option>
              <option value="http://www.google.fm/"><?php _e('Federated States of Micronesia', 'seo-rank-reporter'); ?> (http://www.google.fm/)</option>
              <option value="http://www.google.com.fj/"><?php _e('Fiji', 'seo-rank-reporter'); ?> (http://www.google.com.fj/)</option>
              <option value="http://www.google.fi/"><?php _e('Finland', 'seo-rank-reporter'); ?> (http://www.google.fi/)</option>
              <option value="http://www.google.fr/"><?php _e('France', 'seo-rank-reporter'); ?> (http://www.google.fr/)</option>
              <option value="http://www.google.gm/"><?php _e('The Gambia', 'seo-rank-reporter'); ?> (http://www.google.gm/)</option>
              <option value="http://www.google.ge/"><?php _e('Georgia', 'seo-rank-reporter'); ?> (http://www.google.ge/)</option>
              <option value="http://www.google.de/"><?php _e('Germany', 'seo-rank-reporter'); ?> (http://www.google.de/)</option>
              <option value="http://www.google.com.gi/"><?php _e('Gibraltar', 'seo-rank-reporter'); ?> (http://www.google.com.gi/)</option>
              <option value="http://www.google.com.gr/"><?php _e('Greece', 'seo-rank-reporter'); ?> (http://www.google.com.gr/)</option>
              <option value="http://www.google.gl/"><?php _e('Greenland', 'seo-rank-reporter'); ?> (http://www.google.gl/)</option>
              <option value="http://www.google.gg/"><?php _e('Guernsey', 'seo-rank-reporter'); ?> (http://www.google.gg/)</option>
              <option value="http://www.google.hn/"><?php _e('Honduras', 'seo-rank-reporter'); ?> (http://www.google.hn/)</option>
              <option value="http://www.google.com.hk/"><?php _e('Hong Kong', 'seo-rank-reporter'); ?> (http://www.google.com.hk/)</option>
              <option value="http://www.google.co.hu/"><?php _e('Hungary', 'seo-rank-reporter'); ?> (http://www.google.co.hu/)</option>
              <option value="http://www.google.co.in/"><?php _e('India', 'seo-rank-reporter'); ?> (http://www.google.co.in/)</option>
              <option value="http://www.google.ie/"><?php _e('Ireland', 'seo-rank-reporter'); ?> (http://www.google.ie/)</option>
              <option value="http://www.google.co.im/"><?php _e('Isle of Man', 'seo-rank-reporter'); ?> (http://www.google.co.im/)</option>
              <option value="http://www.google.co.il/"><?php _e('Israel', 'seo-rank-reporter'); ?> (http://www.google.co.il/)</option>
              <option value="http://www.google.it/"><?php _e('Italy', 'seo-rank-reporter'); ?> (http://www.google.it/)</option>
              <option value="http://www.google.com.jm/"><?php _e('Jamaica', 'seo-rank-reporter'); ?> (http://www.google.com.jm/)</option>
              <option value="http://www.google.co.jp/"><?php _e('Japan', 'seo-rank-reporter'); ?> (http://www.google.co.jp/)</option>
              <option value="http://www.google.co.je/"><?php _e('Jersey', 'seo-rank-reporter'); ?> (http://www.google.co.je/)</option>
              <option value="http://www.google.kz/"><?php _e('Kazakhstan', 'seo-rank-reporter'); ?> (http://www.google.kz/)</option>
              <option value="http://www.google.co.kr/"><?php _e('Korea', 'seo-rank-reporter'); ?> (http://www.google.co.kr/)</option>
              <option value="http://www.google.lv/"><?php _e('Latvia', 'seo-rank-reporter'); ?> (http://www.google.lv/)</option>
              <option value="http://www.google.co.ls/"><?php _e('Lesotho', 'seo-rank-reporter'); ?> (http://www.google.co.ls/)</option>
              <option value="http://www.google.li/"><?php _e('Liechtenstein', 'seo-rank-reporter'); ?> (http://www.google.li/)</option>
              <option value="http://www.google.lt/"><?php _e('Lithuania', 'seo-rank-reporter'); ?> (http://www.google.lt/)</option>
              <option value="http://www.google.lu/"><?php _e('Luxembourg', 'seo-rank-reporter'); ?> (http://www.google.lu/)</option>
              <option value="http://www.google.mw/"><?php _e('Malawi', 'seo-rank-reporter'); ?> (http://www.google.mw/)</option>
              <option value="http://www.google.com.my/"><?php _e('Malaysia', 'seo-rank-reporter'); ?> (http://www.google.com.my/)</option>
              <option value="http://www.google.com.mt/"><?php _e('Malta', 'seo-rank-reporter'); ?> (http://www.google.com.mt/)</option>
              <option value="http://www.google.mu/"><?php _e('Mauritius', 'seo-rank-reporter'); ?> (http://www.google.mu/)</option>
              <option value="http://www.google.com.mx/"><?php _e('México', 'seo-rank-reporter'); ?> (http://www.google.com.mx/)</option>
              <option value="http://www.google.ms/"><?php _e('Montserrat', 'seo-rank-reporter'); ?> (http://www.google.ms/)</option>
              <option value="http://www.google.com.na/"><?php _e('Namibia', 'seo-rank-reporter'); ?> (http://www.google.com.na/)</option>
              <option value="http://www.google.com.np/"><?php _e('Nepal', 'seo-rank-reporter'); ?> (http://www.google.com.np/)</option>
              <option value="http://www.google.nl/"><?php _e('Netherlands', 'seo-rank-reporter'); ?> (http://www.google.nl/)</option>
              <option value="http://www.google.co.nz/"><?php _e('New Zealand', 'seo-rank-reporter'); ?> (http://www.google.co.nz/)</option>
              <option value="http://www.google.com.ni/"><?php _e('Nicaragua', 'seo-rank-reporter'); ?> (http://www.google.com.ni/)</option>
              <option value="http://www.google.com.nf/"><?php _e('Norfolk Island', 'seo-rank-reporter'); ?> (http://www.google.com.nf/)</option>
              <option value="http://www.google.com.pk/"><?php _e('Pakistan', 'seo-rank-reporter'); ?> (http://www.google.com.pk/)</option>
              <option value="http://www.google.com.pa/"><?php _e('Panamá', 'seo-rank-reporter'); ?> (http://www.google.com.pa/)</option>
              <option value="http://www.google.com.py/"><?php _e('Paraguay', 'seo-rank-reporter'); ?> (http://www.google.com.py/)</option>
              <option value="http://www.google.com.pe/"><?php _e('Perú', 'seo-rank-reporter'); ?> (http://www.google.com.pe/)</option>
              <option value="http://www.google.com.ph/"><?php _e('Philippines', 'seo-rank-reporter'); ?> (http://www.google.com.ph/)</option>
              <option value="http://www.google.pn/"><?php _e('Pitcairn Islands', 'seo-rank-reporter'); ?> (http://www.google.pn/)</option>
              <option value="http://www.google.pl/"><?php _e('Poland', 'seo-rank-reporter'); ?> (http://www.google.pl/)</option>
              <option value="http://www.google.pt/"><?php _e('Portugal', 'seo-rank-reporter'); ?> (http://www.google.pt/)</option>
              <option value="http://www.google.com.pr/"><?php _e('Puerto Rico', 'seo-rank-reporter'); ?> (http://www.google.com.pr/)</option>
              <option value="http://www.google.cg/"><?php _e('Rep. of the Congo', 'seo-rank-reporter'); ?> (http://www.google.cg/)</option>
              <option value="http://www.google.ro/"><?php _e('Romania', 'seo-rank-reporter'); ?> (http://www.google.ro/)</option>
              <option value="http://www.google.ru/"><?php _e('Russia', 'seo-rank-reporter'); ?> (http://www.google.ru/)</option>
              <option value="http://www.google.rw/"><?php _e('Rwanda', 'seo-rank-reporter'); ?> (http://www.google.rw/)</option>
              <option value="http://www.google.sh/"><?php _e('Saint Helena', 'seo-rank-reporter'); ?> (http://www.google.sh/)</option>
              <option value="http://www.google.sm/"><?php _e('San Marino', 'seo-rank-reporter'); ?> (http://www.google.sm/)</option>
              <option value="http://www.google.com.sg/"><?php _e('Singapore', 'seo-rank-reporter'); ?> (http://www.google.com.sg/)</option>
              <option value="http://www.google.sk/"><?php _e('Slovakia', 'seo-rank-reporter'); ?> (http://www.google.sk/)</option>
              <option value="http://www.google.co.za/"><?php _e('South Africa', 'seo-rank-reporter'); ?> (http://www.google.co.za/)</option>
              <option value="http://www.google.es/"><?php _e('Spain', 'seo-rank-reporter'); ?> (http://www.google.es/)</option>
              <option value="http://www.google.se/"><?php _e('Sweden', 'seo-rank-reporter'); ?> (http://www.google.se/)</option>
              <option value="http://www.google.ch/"><?php _e('Switzerland', 'seo-rank-reporter'); ?> (http://www.google.ch/)</option>
              <option value="http://www.google.com.tw/"><?php _e('Taiwan', 'seo-rank-reporter'); ?> (http://www.google.com.tw/)</option>
              <option value="http://www.google.co.th/"><?php _e('Thailand', 'seo-rank-reporter'); ?> (http://www.google.co.th/)</option>
              <option value="http://www.google.tt/"><?php _e('Trinidad and Tobago', 'seo-rank-reporter'); ?> (http://www.google.tt/)</option>
              <option value="http://www.google.com.tr/"><?php _e('Turkey', 'seo-rank-reporter'); ?> (http://www.google.com.tr/)</option>
              <option value="http://www.google.com.ua/"><?php _e('Ukraine', 'seo-rank-reporter'); ?> (http://www.google.com.ua/)</option>
              <option value="http://www.google.ae/"><?php _e('United Arab Emirates', 'seo-rank-reporter'); ?> (http://www.google.ae/)</option>
              <option value="http://www.google.co.uk/"><?php _e('United Kingdom', 'seo-rank-reporter'); ?> (http://www.google.co.uk/)</option>
              <option value="http://www.google.com.uy/"><?php _e('Uruguay', 'seo-rank-reporter'); ?> (http://www.google.com.uy/)</option>
              <option value="http://www.google.uz/"><?php _e('Uzbekistan', 'seo-rank-reporter'); ?> (http://www.google.uz/)</option>
              <option value="http://www.google.vu/"><?php _e('Vanuatu', 'seo-rank-reporter'); ?> (http://www.google.vu/)</option>
              <option value="http://www.google.co.ve/"><?php _e('Venezuela', 'seo-rank-reporter'); ?> (http://www.google.co.ve/)</option>
            </select>
            <div class="option_saved"></div></td>
        </tr>
      </tbody>
    </table>
    <br />
    <h3><?php _e('Email Notifications', 'seo-rank-reporter'); ?></h3>
    <form action="" method="post">
      <table class="form-table" style="padding:30px;">
        <thead>
          <tr>
            <th colspan="2"><input type="checkbox" name="notify_me" value="yes"<?php echo $notify_checkbox; ?> onClick="document.getElementById('notify_details').style.display = this.checked ? 'block' : 'none';document.getElementById('update_notifications').style.border = '2px solid red'" id="notify_me" />
              <label for="notify_me"><strong><?php _e('Check this box to turn on email notifications', 'seo-rank-reporter'); ?></strong></label></th>
          </tr>
        </thead>
        <tbody id="notify_details" <?php echo $notify_details_visibility; ?>>
          <tr>
            <th scope="row"><label for="kw_seo_emails"><?php _e('Email Recipient(s)', 'seo-rank-reporter'); ?>:<br />
                <?php _e('(separate by comma)', 'seo-rank-reporter'); ?></label></th>
            <td><input type="text" name="kw_seo_emails" id="kw_seo_emails" value="<?php echo $notify_emails; ?>" class="regular-text" onClick="document.getElementById('update_notifications').style.border = '2px solid red'" /></td>
          </tr>
          <tr>
            <th scope="row" colspan="2"><label for="kw_em_spots"><?php printf(__('Notify me when a keyword changes rank %1$s positions (up or down)', 'seo-rank-reporter'), '</label>
              <input type="text" name="kw_em_spots" id="kw_em_spots" value="'. $notify_spots.'" style="width:40px;" onClick="document.getElementById(\'update_notifications\').style.border = \'2px solid red\'" />'); ?>
              </th>
          </tr>
        </tbody>
        </table>
                <input type="submit" value="<?php echo $kw_update_email_notifications; ?>" class="button" name="update_notifications" id="update_notifications" />

    </form>
    <br />
    <h3><?php _e('Remove Ranking Data', 'seo-rank-reporter'); ?></h3>
    <form action="" method="post">
      <p><?php _e('To avoid accidentally losing your data, the SEO Rank Reporter plugin will not delete your data when it is deactivated or upgraded. Clicking the delete button below will remove all data collected (this action cannot be undone). Only use this button if you wish to completely remove the plugin and all its corresponding data from Wordpress.', 'seo-rank-reporter'); ?></p>
            <input type="hidden" name="delete_data_check" value="delete_approved" />
            <input type="submit" class="button" value="<?php echo $kw_delete_all_data; ?>" name="table_delete" onclick='return confirmRemove()' /></td>
     
    </form>
  </div>
  <div class="postbox-container side" style="width:25%;">
    <div class="metabox-holder">
      <div class="meta-box-sortables ui-sortable">
        <div id="toc" class="postbox">
          <div class="handlediv" title="Click to toggle"><br />
          </div>
          <h3 class="hndle"><span><?php _e('Try other Rank Tracking Tools', 'seo-rank-reporter'); ?></span></h3>
          <div class="inside">
            <p><?php _e('Use these other sources to watch your site rankings:', 'seo-rank-reporter'); ?></p>
            <ul>
              <li><a href="http://authoritylabs.com?src=5311b0e9ca023ce41b5bc5190fdd754d" target="_blank"><?php _ex('AuthorityLabs - Try it for 30 days', "AuthorityLabs is a company name"); ?></a></li>
            </ul><br />
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="postbox-container side" style="width:25%;">
    <div class="metabox-holder">
      <div class="meta-box-sortables ui-sortable">
        <div id="toc" class="postbox" style="border:2px solid #009933;">
          <div class="handlediv" title="Click to toggle"><br />
          </div>
          <h3 class="hndle"><span><?php _e('Like this Plugin?', 'seo-rank-reporter'); ?></span></h3>
          <div class="inside">
            <p><?php _e('Show your love by doing something below:', 'seo-rank-reporter'); ?></p>
            <ul>
              <li><a href="http://wordpress.org/extend/plugins/seo-rank-reporter/" target="_blank"><?php _e('Rate it on Wordpress.org', 'seo-rank-reporter'); ?></a></li>
              <li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZZ82CCVP65RNN" target="_blank"><?php _e('Donate!', 'seo-rank-reporter'); ?></a></li>
              <li><a href="http://wordpress.org/extend/plugins/seo-rank-reporter/" target="_blank"><?php _e('Vote that this version works', 'seo-rank-reporter'); ?></a></li>
            </ul>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
