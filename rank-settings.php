<div class="wrap">
  <h2>SEO Rank Reporter Settings</h2>
  <div class="postbox-container" style="width:65%;">
    <?php
 //Add/Remove Email Notification
$updated_msg = "";
if ($_POST['kw_seo_emails'] !== "" && $_POST['kw_em_spots'] !== "" && $_POST['update_notifications'] == "Update Email Notifications" && $_POST['notify_me'] == "yes") {
	
	update_option('kw_seo_emails', $_POST['kw_seo_emails']);
	update_option('kw_em_spots', $_POST['kw_em_spots']);
	$updated_msg = "<div id='message' class='updated'>Email notification updated</div>";
}
if ($_POST['notify_me'] !== "yes" && $_POST['update_notifications'] == "Update Email Notifications") {
	update_option('kw_seo_emails', '');
	update_option('kw_em_spots', '');
	$updated_msg = "<div id='message' class='updated'>Email notification updated</div>";
}
if ($_POST['table_delete'] == "Delete All Data") {
	seoRankReporterDelete();
	$updated_msg = "<div id='message' class='updated'>All data has been removed. If you wish to completely remove the table, you may now deactivate the plugin.</div>";
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
		return confirm("Do you really want to delete all your data? This action cannot be undone.")
	}  
	
	<?php $kw_seo_sengine_select = get_option('kw_seo_sengine_country'); ?> 
	
	jQuery(document).ready( function() {
		jQuery('option[value="<?php echo $kw_seo_sengine_select; ?>"]').attr('selected', true);	
	});
	  
</script>
    <h3>Google Country URL</h3>
    <table class="form-table">
      <tbody>
        <tr>
          <th>Select which URL you'd like the Rank Reporter to use</th>
          <td><select name="se_country_url">
              <option value="http://www.google.com/">Default - Google.com (http://www.google.com/)</option>
              <option value="http://www.google.as/">American Samoa (http://www.google.as/)</option>
              <option value="http://www.google.off.ai/">Anguilla (http://www.google.off.ai/)</option>
              <option value="http://www.google.com.ag/">Antigua and Barbuda (http://www.google.com.ag/)</option>
              <option value="http://www.google.com.ar/">Argentina (http://www.google.com.ar/)</option>
              <option value="http://www.google.com.au/">Australia (http://www.google.com.au/)</option>
              <option value="http://www.google.at/">Austria (http://www.google.at/)</option>
              <option value="http://www.google.az/">Azerbaijan (http://www.google.az/)</option>
              <option value="http://www.google.be/">Belgium (http://www.google.be/)</option>
              <option value="http://www.google.com.br/">Brazil (http://www.google.com.br/)</option>
              <option value="http://www.google.vg/">British Virgin Islands (http://www.google.vg/)</option>
              <option value="http://www.google.bi/">Burundi (http://www.google.bi/)</option>
              <option value="http://www.google.ca/">Canada (http://www.google.ca/)</option>
              <option value="http://www.google.td/">Chad (http://www.google.td/)</option>
              <option value="http://www.google.cl/">Chile (http://www.google.cl/)</option>
              <option value="http://www.google.com.co/">Colombia (http://www.google.com.co/)</option>
              <option value="http://www.google.co.cr/">Costa Rica (http://www.google.co.cr/)</option>
              <option value="http://www.google.ci/">Côte d'Ivoire (http://www.google.ci/)</option>
              <option value="http://www.google.com.cu/">Cuba (http://www.google.com.cu/)</option>
              <option value="http://www.google.cd/">Dem. Rep. of the Congo (http://www.google.cd/)</option>
              <option value="http://www.google.dk/">Denmark (http://www.google.dk/)</option>
              <option value="http://www.google.dj/">Djibouti (http://www.google.dj/)</option>
              <option value="http://www.google.com.do/">Dominican Republic (http://www.google.com.do/)</option>
              <option value="http://www.google.com.ec/">Ecuador (http://www.google.com.ec/)</option>
              <option value="http://www.google.com.sv/">El Salvador (http://www.google.com.sv/)</option>
              <option value="http://www.google.fm/">Federated States of Micronesia (http://www.google.fm/)</option>
              <option value="http://www.google.com.fj/">Fiji (http://www.google.com.fj/)</option>
              <option value="http://www.google.fi/">Finland (http://www.google.fi/)</option>
              <option value="http://www.google.fr/">France (http://www.google.fr/)</option>
              <option value="http://www.google.gm/">The Gambia (http://www.google.gm/)</option>
              <option value="http://www.google.ge/">Georgia (http://www.google.ge/)</option>
              <option value="http://www.google.de/">Germany (http://www.google.de/)</option>
              <option value="http://www.google.com.gi/">Gibraltar (http://www.google.com.gi/)</option>
              <option value="http://www.google.com.gr/">Greece (http://www.google.com.gr/)</option>
              <option value="http://www.google.gl/">Greenland (http://www.google.gl/)</option>
              <option value="http://www.google.gg/">Guernsey (http://www.google.gg/)</option>
              <option value="http://www.google.hn/">Honduras (http://www.google.hn/)</option>
              <option value="http://www.google.com.hk/">Hong Kong (http://www.google.com.hk/)</option>
              <option value="http://www.google.co.hu/">Hungary (http://www.google.co.hu/)</option>
              <option value="http://www.google.co.in/">India (http://www.google.co.in/)</option>
              <option value="http://www.google.ie/">Ireland (http://www.google.ie/)</option>
              <option value="http://www.google.co.im/">Isle of Man (http://www.google.co.im/)</option>
              <option value="http://www.google.co.il/">Israel (http://www.google.co.il/)</option>
              <option value="http://www.google.it/">Italy (http://www.google.it/)</option>
              <option value="http://www.google.com.jm/">Jamaica (http://www.google.com.jm/)</option>
              <option value="http://www.google.co.jp/">Japan (http://www.google.co.jp/)</option>
              <option value="http://www.google.co.je/">Jersey (http://www.google.co.je/)</option>
              <option value="http://www.google.kz/">Kazakhstan (http://www.google.kz/)</option>
              <option value="http://www.google.co.kr/">Korea (http://www.google.co.kr/)</option>
              <option value="http://www.google.lv/">Latvia (http://www.google.lv/)</option>
              <option value="http://www.google.co.ls/">Lesotho (http://www.google.co.ls/)</option>
              <option value="http://www.google.li/">Liechtenstein (http://www.google.li/)</option>
              <option value="http://www.google.lt/">Lithuania (http://www.google.lt/)</option>
              <option value="http://www.google.lu/">Luxembourg (http://www.google.lu/)</option>
              <option value="http://www.google.mw/">Malawi (http://www.google.mw/)</option>
              <option value="http://www.google.com.my/">Malaysia (http://www.google.com.my/)</option>
              <option value="http://www.google.com.mt/">Malta (http://www.google.com.mt/)</option>
              <option value="http://www.google.mu/">Mauritius (http://www.google.mu/)</option>
              <option value="http://www.google.com.mx/">México (http://www.google.com.mx/)</option>
              <option value="http://www.google.ms/">Montserrat (http://www.google.ms/)</option>
              <option value="http://www.google.com.na/">Namibia (http://www.google.com.na/)</option>
              <option value="http://www.google.com.np/">Nepal (http://www.google.com.np/)</option>
              <option value="http://www.google.nl/">Netherlands (http://www.google.nl/)</option>
              <option value="http://www.google.co.nz/">New Zealand (http://www.google.co.nz/)</option>
              <option value="http://www.google.com.ni/">Nicaragua (http://www.google.com.ni/)</option>
              <option value="http://www.google.com.nf/">Norfolk Island (http://www.google.com.nf/)</option>
              <option value="http://www.google.com.pk/">Pakistan (http://www.google.com.pk/)</option>
              <option value="http://www.google.com.pa/">Panamá (http://www.google.com.pa/)</option>
              <option value="http://www.google.com.py/">Paraguay (http://www.google.com.py/)</option>
              <option value="http://www.google.com.pe/">Perú (http://www.google.com.pe/)</option>
              <option value="http://www.google.com.ph/">Philippines (http://www.google.com.ph/)</option>
              <option value="http://www.google.pn/">Pitcairn Islands (http://www.google.pn/)</option>
              <option value="http://www.google.pl/">Poland (http://www.google.pl/)</option>
              <option value="http://www.google.pt/">Portugal (http://www.google.pt/)</option>
              <option value="http://www.google.com.pr/">Puerto Rico (http://www.google.com.pr/)</option>
              <option value="http://www.google.cg/">Rep. of the Congo (http://www.google.cg/)</option>
              <option value="http://www.google.ro/">Romania (http://www.google.ro/)</option>
              <option value="http://www.google.ru/">Russia (http://www.google.ru/)</option>
              <option value="http://www.google.rw/">Rwanda (http://www.google.rw/)</option>
              <option value="http://www.google.sh/">Saint Helena (http://www.google.sh/)</option>
              <option value="http://www.google.sm/">San Marino (http://www.google.sm/)</option>
              <option value="http://www.google.com.sg/">Singapore (http://www.google.com.sg/)</option>
              <option value="http://www.google.sk/">Slovakia (http://www.google.sk/)</option>
              <option value="http://www.google.co.za/">South Africa (http://www.google.co.za/)</option>
              <option value="http://www.google.es/">Spain (http://www.google.es/)</option>
              <option value="http://www.google.se/">Sweden (http://www.google.se/)</option>
              <option value="http://www.google.ch/">Switzerland (http://www.google.ch/)</option>
              <option value="http://www.google.com.tw/">Taiwan (http://www.google.com.tw/)</option>
              <option value="http://www.google.co.th/">Thailand (http://www.google.co.th/)</option>
              <option value="http://www.google.tt/">Trinidad and Tobago (http://www.google.tt/)</option>
              <option value="http://www.google.com.tr/">Turkey (http://www.google.com.tr/)</option>
              <option value="http://www.google.com.ua/">Ukraine (http://www.google.com.ua/)</option>
              <option value="http://www.google.ae/">United Arab Emirates (http://www.google.ae/)</option>
              <option value="http://www.google.co.uk/">United Kingdom (http://www.google.co.uk/)</option>
              <option value="http://www.google.com.uy/">Uruguay (http://www.google.com.uy/)</option>
              <option value="http://www.google.uz/">Uzbekistan (http://www.google.uz/)</option>
              <option value="http://www.google.vu/">Vanuatu (http://www.google.vu/)</option>
              <option value="http://www.google.co.ve/">Venezuela (http://www.google.co.ve/)</option>
            </select>
            <div class="option_saved"></div></td>
        </tr>
      </tbody>
    </table>
    <br />
    <h3>Email Notifications</h3>
    <form action="" method="post">
      <table class="form-table" style="padding:30px;background:#ffffff;">
        <thead>
          <tr>
            <th colspan="2"><input type="checkbox" name="notify_me" value="yes"<?php echo $notify_checkbox; ?> onClick="document.getElementById('notify_details').style.display = this.checked ? 'block' : 'none';document.getElementById('update_notifications').style.border = '2px solid red'" id="notify_me" />
              <label for="notify_me"><strong>Check this box to turn on email notifications</strong></label></th>
          </tr>
        </thead>
        <tbody id="notify_details" <?php echo $notify_details_visibility; ?>>
          <tr>
            <th scope="row"><label for="kw_seo_emails">Email Recipient(s):<br />
                (separate by comma)</label></th>
            <td><input type="text" name="kw_seo_emails" id="kw_seo_emails" value="<?php echo $notify_emails; ?>" class="regular-text" onClick="document.getElementById('update_notifications').style.border = '2px solid red'" /></td>
          </tr>
          <tr>
            <th scope="row" colspan="2"><label for="kw_em_spots">Notify me when a keyword changes rank</label>
              <input type="text" name="kw_em_spots" id="kw_em_spots" value="<?php echo $notify_spots; ?>" style="width:40px;" onClick="document.getElementById('update_notifications').style.border = '2px solid red'" />
              positions (up or down)</th>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="2"><input type="submit" value="Update Email Notifications" class="button" name="update_notifications" id="update_notifications" /></td>
          </tr>
        </tfoot>
      </table>
    </form>
    <br />
    <h3>Remove Ranking Data</h3>
    <form action="" method="post">
      <table class="form-table">
        <tbody>
          <tr>
            <th colspan="2"> <p>To avoid accidentally losing your data, the SEO Rank Reporter plugin will not delete your data when it is deactivated or upgraded. Clicking the delete button below will remove all data collected (this action cannot be undone). Only use this button if you wish to completely remove the plugin and all its corresponding data from Wordpress.</p>
            </th>
          </tr>
          <tr>
            <th>Remove keywords and all ranking data</th>
            <td><input type="submit" class="button" value="Delete All Data" name="table_delete" onclick='return confirmRemove()' /></td>
          </tr>
        </tbody>
      </table> 
    </form>
  </div>
  <div class="postbox-container side" style="width:25%;">
    <div class="metabox-holder">
      <div class="meta-box-sortables ui-sortable">
        <div id="toc" class="postbox">
          <div class="handlediv" title="Click to toggle"><br />
          </div>
          <h3 class="hndle"><span>Try other Rank Tracking Tools</span></h3>
          <div class="inside">
            <p>Use these other sources to watch your site rankings:</p>
            <ul>
              <li><a href="http://authoritylabs.com/?src=reporter-wp-plugin-settings" target="_blank">AuthorityLabs - 10 keywords free</a></li>
              <li><a href="http://go.seomoz.org/aff_c?offer_id=1&aff_id=1660&aff_sub=wp-rank-settings&url=http%3A//www.seomoz.org/rank-tracker" target="_blank">SEOMoz's Rank Tracker - The Best SEO Software</a></li>
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
          <h3 class="hndle"><span>Like this Plugin?</span></h3>
          <div class="inside">
            <p>Show your love by doing something below:</p>
            <ul>
              <li><a href="http://wordpress.org/extend/plugins/seo-rank-reporter/" target="_blank">Rate it on Wordpress.org</a></li>
              <li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZZ82CCVP65RNN" target="_blank">Donate!</a></li>
              <li><a href="http://wordpress.org/extend/plugins/seo-rank-reporter/" target="_blank">Say that it works</a></li>
            </ul><br />
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
