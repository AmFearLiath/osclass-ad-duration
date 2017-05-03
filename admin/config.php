<?php 
if (!defined('OC_ADMIN') || OC_ADMIN!==true) exit('Access is not allowed.');
$checked = false;
$addur = new addur;

if (Params::getParam('ad_duration') == 'save') {
    if ($addur->_save(array(
        'activated' => Params::getParam('addur_checkbox'),
        'block_selected' => Params::getParam('block_selected'),
        'durations' => Params::getParam('addur_durations')
    ))) {
        $opt = array(
            'activated' => Params::getParam('addur_checkbox'),
            'block_selected' => Params::getParam('block_selected'),
            'durations' => Params::getParam('addur_durations')
        );    
    }       
}  else {
    $opt = array(
        'activated' => $addur->_get('activated'),
        'block_selected' => $addur->_get('block_selected'),
        'durations' => $addur->_get('durations')
    );
}

if ($opt['activated'] == '1') {
    $checked = true;
} if ($opt['block_selected'] == '1') {
    $blocked = true;
}
?>
<div class="addur_help">
    <form action="<?php echo osc_admin_render_plugin_url('ad_duration/admin/config.php');; ?>" method="POST">
        <input type="hidden" name="page" value="plugins" />
        <input type="hidden" name="action" value="renderplugin" />
        <input type="hidden" name="file" value="<?php echo osc_plugin_folder(__FILE__); ?>config.php" />
        <input type="hidden" name="ad_duration" value="save" />
        
        <div class="addur_header">
            <h1><?php _e('Ad Duration Time', 'ad_duration'); ?></h1>
            <p><?php _e('This plugin allows the user, to set the duration for new ads', 'ad_duration'); ?></p>
        </div>
        <div class="addur_content">
            <div class="form-group">
                <h3><strong><?php _e('Select Durations', 'ad_duration'); ?></strong></h3>
                <label for="addur_durations"><?php _e('Set the Durations that can be choose. (separated by ,)', 'ad_duration'); ?></label><br />
                <input type="text" name="addur_durations" id="addur_durations" placeholder="e.g. (1,3,7,14...)" value="<?php echo $opt['durations']; ?>"  /> <span><?php _e('Days', 'ad_duration'); ?></span>
            </div>
            <div class="form-group">
                <h3><strong><?php _e('Hide if already selected', 'ad_duration'); ?></strong></h3>
                <label for="addur_durations">
                    <input type="checkbox" name="block_selected" id="block_selected" value="1" <?php if ($blocked) { echo 'checked="checked"'; } ?> />
                    <?php _e('Should the Selectbox be hidden if duration already selected', 'ad_duration'); ?>
                </label>
            </div>
            <br />
            <h3 class="addur_title"><strong><?php _e('Selectbox on item post/edit', 'ad_duration'); ?></strong></h3>
            <p><?php _e('To display a Selectbox, where seller can change the duration of there ad, place the code below in item.php where you want to show the select box', 'ad_duration'); ?></p>
            <pre>&lt;?php if (function_exists('addur_adform')) { addur_adform(); } ?&gt;</pre>    
            <p><strong><?php _e('or', 'ad_duration'); ?></strong></p>
            <div class="form-group">
                <h3><strong><?php _e('Show Selectbox', 'ad_duration'); ?></strong></h3>
                <label>
                    <input type="checkbox" name="addur_checkbox" id="addur_checkbox" value="1" <?php if ($checked) { echo 'checked="checked"'; } ?> />
                    <?php _e('This option shows an Selectbox on the end of the form on item post/edit page', 'ad_duration'); ?>
                </label>
            </div>
            <br />            
            <div class="form-group">
                <button class="btn btn-submit" type="submit"><?php _e('Save', 'ad_duration'); ?></button>
            </div>
        </div>
        <div>
            <h1><?php _e('Form Styling', 'ad_duration'); ?></h1>
            <p><?php _e('To adjust the form styling to your needs, you only have to edit the <strong>form.php</strong> and fit it to your template.', 'ad_duration'); ?></p>
            <pre>../oc-content/ad_duration/views/form.php</pre>
        </div>            
    </form>
</div>