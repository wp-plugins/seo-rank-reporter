<div class="wrap">

<h2>Email Notifications</h2>



<?php



 //Add/Remove Email Notification

$updated_msg = "";

if ($_POST['kw_seo_emails'] !== "" && $_POST['kw_em_spots'] !== "" && $_POST['update_notifications'] == "Update" && $_POST['notify_me'] == "yes") {

	

	update_option('kw_seo_emails', $_POST['kw_seo_emails']);

	update_option('kw_em_spots', $_POST['kw_em_spots']);

	$updated_msg = "<div id='message' class='updated'>Email notification updated</div>";



}



if ($_POST['notify_me'] !== "yes" && $_POST['update_notifications'] == "Update") {

	update_option('kw_seo_emails', '');

	update_option('kw_em_spots', '');

	$updated_msg = "<div id='message' class='updated'>Email notification updated</div>";



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

echo $updated_msg;

?>

        <form action="" method="post">

          <table class="form-table">

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

                <td colspan="2"><input type="submit" value="Update" class="button" name="update_notifications" id="update_notifications" /></td>

              </tr>

            </tfoot>

          </table>

        </form>



  

  </div>