<?php

$option = '';
$options = explode(",", addur::newInstance()->_get('durations'));

foreach($options as $k => $v) {
    $option .= '<option value="'.$v.'">'.$v.' '.($v == '1' ? __('Day', 'ad_duration') : __('Days', 'ad_duration')).'</option>';
}
?>
<div class="form-group">
    <label for="ads_duration"><?php _e('Select the duration for you ad', 'ad_duration'); ?></label>
    <select name="ads_duration" class="form-control">
        <option value="-1"><?php _e('No changes', 'ad_duration'); ?></option>
        <?php echo $option; ?>
    </select>
</div>