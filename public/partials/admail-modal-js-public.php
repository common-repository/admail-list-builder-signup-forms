<?php
/**
 * @author     Michael Parisi <mgparisicpu@gmail.com>
 * @link       http://Admail.net
 * @since      1.0.0
 * @package    Admail
 * @subpackage Admail/public/partials
 * @copyright  2019 Admail.net
 */
//remove CR/LF from the admail-public.php output.
ob_start();
include 'admail-public.php';
$admail_html_content = str_replace( array( "\n", "\t", "\r" ), '', ob_get_clean() );

?>
<script>
	(function ($) {
		$(document).ready(function () {
			$(window).load(function () {
				'use strict';
				<?php
				$expire_open = isset( $admail_atts['expire_open'] ) && $admail_atts['expire_open'];
				if ($expire_open) :
				if ( ! isset( $admail_atts['cookie'] ) ) :
					$admail_atts['cookie'] = 'admail_global_cookie';
				endif
				?>
				console.log(getCookie('<?php echo $admail_atts['cookie'];?>'));
				if (getCookie('<?php echo $admail_atts['cookie'];?>') !== 'TRUE') {
					document.cookie = '<?php echo $admail_atts['cookie']; ?>=TRUE;';
					<?php endif ?>

					/* Initialize jQuery Colorbox*/
					$('a#<?php echo $admail_atts['id']; ?>').colorbox({
						transition: 'fade',
						opacity: .85,
						height: $('this iframe').height,
						innerWidth: '100%',
						maxHeight: '90%',
						maxWidth: '800',
						<?php if (
							( isset( $admail_atts['start_open'] ) && $admail_atts['start_open'] ) ||
							( $expire_open ) ) {
							?>open: 1,<?php
						} ?>
						html: '<?php echo $admail_html_content; ?>',
					});

					<?php if ($expire_open) : ?>
				}
				<?php endif  ?>
			});
		});
	})(jQuery);
</script>