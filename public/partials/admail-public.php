<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @author     Michael Parisi <mgparisicpu@gmail.com>
 * @link       http://Admail.net
 * @since      1.0.0
 * @package    Admail
 * @subpackage Admail/public/partials
 * @copyright  2019 Admail.net
 */
?>
<div class="admail external">
	<iframe class="admail-form" src="//www.admail.net/form/<?php echo $admail_atts['account_id']; ?>/<?php echo $admail_atts['form_id']; ?>/?org=<?php echo get_site_url(
	); ?>" frameborder="0" style="width:100%" scrolling="no"></iframe>
</div>
