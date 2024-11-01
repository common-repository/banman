<style>
label.error {
    display: block;
    color: red;
    font-style: italic;
}
</style>
<div class="wrap">
<h1><?php echo $isNew ? __('New banner *','banman') : __('Edit banner','banman'); ?></h1>
<form method="post" id="editForm" action="/wp-admin/admin.php?page=banman.php">
<table class="form-table">
<tbody>
<tr class="form-field form-required term-name-wrap">
    <th scope="row"><label><?php echo _e('Banner position'); ?></label></th>
    <td><?php echo BanMan::selectList(array(
        'empty'=>sprintf(' - %s - ', __('Select', 'banman')),
        'name'=>'row[position]',
        'values'=>BanMan::getPositions(),
        'select'=>$row['position'],
        'assoc'=>true,
    )); ?></td>
</tr>
<tr class="form-field form-required term-name-wrap">
    <th scope="row"><label><?php _e('Name','banman'); ?></label></th>
    <td><input name="row[name]" id="name" type="text" value="<?php echo $row['name']; ?>" style="width:80%;" required data-msg="<?php _e('Please enter the name for the banner!', 'banman'); ?>"></td>
</tr>
<tr class="form-field form-required">
    <th scope="row"><label><?php echo _e('Type banner', 'banman'); ?></label></th>
    <td>
        <label><input type="radio" name="row[type]" value="0" onclick="setTypeBanner(this.value);" <?php echo $row['type'] ? '' : 'checked '; ?>/> <?php echo _e('File','banman'); ?></label>&nbsp;&nbsp;&nbsp;
        <label><input type="radio" name="row[type]" value="1" onclick="setTypeBanner(this.value);"  <?php echo $row['type']==1 ? 'checked ' : ''; ?>/> <?php echo _e('Code', 'banman'); ?></label>
    </td>
</tr>
<tr id="banner_file" class="form-field form-required"<?php echo $row['type'] ? ' style="display:none;"' : ''; ?>>
    <th scope="row"><label><?php _e('File banner','banman'); ?></label></th>
    <td><?php BanMan::uploader('attach_id', $row); ?></td>
</tr>
<tr id="banner_code" class="form-field form-required"<?php echo $row['type'] ? '' : ' style="display:none;"'; ?>>
    <th scope="row"><label><?php _e('Code banner','banman'); ?></label></th>
    <td><textarea name="row[code]" style="width:80%;" rows="5"><?php echo $row['code']; ?></textarea></td>
</tr>
<tr class="form-field">
    <th scope="row"><label><?php _e('URL address links','banman'); ?></label></th>
    <td><input name="row[url]" type="text" value="<?php echo $row['url']; ?>" style="width:80%;"></td>
</tr>
<tr class="form-field">
    <th scope="row"><label><?php _e('Period display','banman'); ?></label></th>
    <td>
        <?php _e('from','banman'); ?> <input type="text" class="datepick" name="row[show_start]" value="<?php echo $row['show_start'] ? date('d.m.Y', strtotime($row['show_start'])) : date('d.m.Y'); ?>" size="10" style="width:auto;" />
         <?php _e('to','banman'); ?> <input type="text" class="datepick" name="row[show_end]" value="<?php echo ($row['show_end']=='0000-00-00' or $row['show_end']=='') ? '' : date('d.m.Y', strtotime($row['show_end'])); ?>" size="10" style="width:auto;" />
    </td>
</tr>
<tr class="form-field">
    <th scope="row"><label><?php _e('Width (px)','banman'); ?></label></th>
    <td>
        <input type="text" name="row[params][width]" value="<?php echo $row['params']['width']; ?>" style="width: 60px;" />
    </td>
</tr>
<tr class="form-field">
    <th scope="row"><label><?php _e('Height (px)','banman'); ?></label></th>
    <td>
        <input type="text" name="row[params][height]" value="<?php echo $row['params']['height']; ?>" style="width: 60px;" />
    </td>
</tr>
<tr class="form-field">
    <th scope="row"><label><?php _e('Enable banner','banman'); ?></label></th>
    <td>
        <label><input type="radio" name="row[publish]" value="1" <?php echo !$row['publish'] ? '' : 'checked '; ?> /> <?php _e('Yes','banman'); ?></label>&nbsp;&nbsp;&nbsp;
        <label><input type="radio" name="row[publish]" value="0" <?php echo $row['publish'] ? '' : 'checked '; ?> /> <?php _e('No','banman'); ?></label>
    </td>
</tr>
</tbody>
</table>
<style>
#accordion h3 {
    padding-left: 30px;
    font-size: 16px;
    height: 30px;
    line-height: 30px;
    margin: 0px;
}
</style>
<div id="accordion">
  <h3><?php _e('Advanced settings','banman'); ?></h3>
  <div>
    <table class="form-table">
        <tbody>
        <tr class="form-field">
            <th scope="row"><label><?php _e('Prevent indexing links','banman'); ?></label></th>
            <td>
                 <label><input type="radio" name="row[params][nofollow]" value="0" <?php echo $row['params']['nofollow'] ? '' : 'checked '; ?>  /> <?php _e('Yes','banman'); ?></label>&nbsp;&nbsp;&nbsp;
                 <label><input type="radio" name="row[params][nofollow]" value="1" <?php echo !$row['params']['nofollow'] ? '' : 'checked '; ?> /> <?php _e('No','banman'); ?></label>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label><?php _e('Open links in new window','banman'); ?></label></th>
            <td>
                 <label><input type="radio" name="row[params][blank]" value="0" <?php echo $row['params']['blank'] ? '' : 'checked '; ?> /> <?php _e('Yes','banman'); ?></label>&nbsp;&nbsp;&nbsp;
                 <label><input type="radio" name="row[params][blank]" value="1" <?php echo !$row['params']['blank'] ? '' : 'checked '; ?> /> <?php _e('No','banman'); ?></label>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label><?php _e('Include statistics for','banman'); ?></label></th>
            <td>
                <br><label><input type="checkbox" name="row[params][on_count_show]" value="1" <?php echo !$row['params']['on_count_show'] ? '' : 'checked '; ?>> <?php _e('shows','banman'); ?></label><br>
                <label><input type="checkbox" name="row[params][on_count_click]" value="1" <?php echo !$row['params']['on_count_click'] ? '' : 'checked '; ?>> <?php _e('clicks','banman'); ?></label>
            </td>
        </tr>
        </tbody>
    </table>    
  </div>
</div>
<?php submit_button($isNew ? __('Save', 'banman') : __('Save changes', 'banman'),'primary'); ?>
<input type="hidden" name="action" value="<?php echo $isNew ? 'addSave' : 'editSave'; ?>" />
<input type="hidden" name="row[id]" value="<?php echo $row['id']; ?>" />
</form>
</div>
<script type="text/javascript">
function setTypeBanner(value)
{
    if (value==1) {
        jQuery('#banner_file').hide();
        jQuery('#banner_code').show();
    } else {
        jQuery('#banner_code').hide();
        jQuery('#banner_file').show();
    }
}
jQuery(document).ready(function() {
    jQuery('.datepick').datepicker({
        dateFormat : 'dd.mm.yy',
    });
    jQuery('#accordion').accordion({
        active: false,
        collapsible: true,
    });
    jQuery('#editForm').validate();
    
    jQuery('.upload_image_button').click(function(){
        var send_attachment_bkp = wp.media.editor.send.attachment;
        var button = jQuery(this);
        wp.media.editor.send.attachment = function(props, attachment) {
            var fileUrl = attachment.url, parts, ext = ( parts = fileUrl.split("/").pop().split(".") ).length > 1 ? parts.pop() : "";
            if (ext=='swf') {
                var html = '<object><param name="movie" value="' + attachment.url + '"><embed src="' + attachment.url + '"></embed></object>';
            } else {
                var html = '<img src="' + attachment.url + '" />';
            }
            jQuery('#uploader-box div').html(html);
            jQuery('#attach_id').val(attachment.id);
            wp.media.editor.send.attachment = send_attachment_bkp;
        }
        wp.media.editor.open(button);
        return false;    
    });
});
</script>
