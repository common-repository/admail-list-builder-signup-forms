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
if ( isset( $admail_atts['link_text'] ) ) {
	?>
	<div class="admail shortcode external">
		<a href="#colorbox" id="<?php echo $admail_atts['id']; ?>"><?php echo $admail_atts['link_text']; ?></a>
	</div>
	<?php
}
include_once( 'admail-modal-js-public.php' ); ?>