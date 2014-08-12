<?php
	namespace permag\view;
	/**
	 * MasterView
	 */
	class MasterView {

		/**
		 * Site logo
		 * @return string HTML
		 */
		public function doLogo() {
			return "<h1 id='logo'><a href='./'>sayHELLO.</a></h1><br />";
		}
		
		/**
		 * Menu
		 * @param  \permag\view\RecordingListNavigationView $recListNavView instance to create links
		 * @return string HTML
		 */
		public function doNavigationMenu(\permag\view\RecordingListNavigationView $recListNavView) {
			$html = "
				<div id='sideMenu'>
					<ul>
						<li><a href='".$recListNavView->getInboxLink()."'>Inbox</a></li>
						<li><a href='".$recListNavView->getOutboxLink()."'>Outbox</a></li>
					</ul>
				</div>";

			return $html;
		}

		/**
		 * Site logo
		 * @return string HTML
		 */
		public function doDescription() {
			return "<h3></span>Send audio messages to your friends using your computer's microphone!</h3>";
		}


	}