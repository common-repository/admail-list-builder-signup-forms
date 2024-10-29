/**
 * Enables/Disables the SiteWide Account ID and Form ID fields.
 *
 * @author     Michael Parisi <mgparisicpu@gmail.com>
 * @link       http://Admail.net
 * @since      1.0.0
 * @package    Admail
 * @subpackage Admail/admin/js
 * @copyright  2019 Admail.net All Rights Reserved
 */
(function($ ) {
	'use strict';

	$(document).ready(function () {
		$('#admail-admin-sitewide-modal-enable-ID').on('click', function () {
			if ($(this).is(':checked')) {
				$("#admail-admin-sitewide-modal-account-ID").prop('disabled', false);
				$("#admail-admin-sitewide-modal-form-ID").prop('disabled', false);
			} else {
				$("#admail-admin-sitewide-modal-account-ID").prop('disabled', true);
				$("#admail-admin-sitewide-modal-form-ID").prop('disabled', true);
			}
		});
	});
})( jQuery );
