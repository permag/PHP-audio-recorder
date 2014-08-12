<?php
	namespace permag\view;

	/**
	 * View for sharing recorded sound
	 */
	class ShareView {
		
		const SHARE_TO_USERNAME_CONTAINER = 'shareToUsernameContainer';
		const SHARE_TO_USERNAME = 'shareToUsername';

		/**
		 * Share to username
		 * @return string HTML
		 */
		public function doShareToUsername() {
			$html = "
					<div id=\"".self::SHARE_TO_USERNAME_CONTAINER."\">
						<input type=\"text\" id=\"".self::SHARE_TO_USERNAME."\" value=\"username\" />
						<button id=\"send\">Send recording</button>
					</div>";

			return $html;
		}
	}