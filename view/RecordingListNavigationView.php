<?php
	namespace permag\view;

	/**
	 * View for navigation in the Recordings inbox/ outbox
	 * creating links
	 */
	class RecordingListNavigationView {

		const LIST_CONTROLLER = 'list';
		const INBOX = 'inbox';
		const OUTBOX = 'outbox';

		private $m_pageNavView = null;

		public function __construct(\permag\view\PageNavigationView $pageNavView) {
			$this->m_pageNavView = $pageNavView;
		}

		/**
		 * Get value of controller
		 * @return value for GET LIST_CONTROLLER
		 */
		public function getController() {

			if (isset($_GET[self::LIST_CONTROLLER]) && $_GET[self::LIST_CONTROLLER] != '') {
				
				return $_GET[self::LIST_CONTROLLER];
			} 
		}

		/**
		 * Get link 
		 * @return string link
		 */
		public function getInboxLink() {
			// mod_rewrite URL
			return $this->m_pageNavView->getHomeLink() . '/' . self::INBOX;
		}
		/**
		 * Get link 
		 * @return string link
		 */
		public function getOutboxLink() {
			// mod_rewrite URL
			return $this->m_pageNavView->getHomeLink() . '/' . self::OUTBOX;
		}

	}
