<?php defined('BASEPATH') or exit('No direct script access allowed');

?>
<div class="form-group">
	<?php echo render_input('settings[telegram_name]', 'settings_telegram_name', get_option('telegram_name')); ?>
	
	<?php echo render_input('settings[telegram_user]', 'settings_telegram_user', get_option('telegram_user')); ?>
	
	<?php echo render_input('settings[telegram_token]', 'settings_telegram_token', get_option('telegram_token')); ?>
</div>

<hr />
