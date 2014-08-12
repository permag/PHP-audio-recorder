<?php
	namespace permag\view;

	require_once('view/MasterView.php');

	/**
	 * View for navigation of "top site"
	 * creating links
	 */
	class PageNavigationView {

		// url get name
		const PAGE_CONTROLLER = 'nav';

		// url get value, accesed from MasterController
		const LOGIN = 'login';
		const REGISTER = 'register';
		const HOME = 'home';

		/**
		 * Get value of controller
		 * @return value for GET PAGE_CONTROLLER or redirect to home
		 */
		public function getController() {

			$masterView = new \permag\view\MasterView();

			if (isset($_GET[self::PAGE_CONTROLLER]) && $_GET[self::PAGE_CONTROLLER] != '') {
				return $_GET[self::PAGE_CONTROLLER];
			
			} else {
				$this->redirectTo($this->getHomeLink());

			}
		}

		/**
		 * Redirects to other page using header location
		 * @param  string $link
		 */
		public function redirectTo($link) {
			header('location: ' . $link);
		}

		/**
		 * Get link
		 * @return string link
		 */
		public function getHomeLink() {
			// mod_rewrite URL
			return '/' . self::HOME;
		}
		/**
		 * Get link 
		 * @return string link
		 */
		public function getLoginLink() {
			// mod_rewrite URL
			return '/' . self::LOGIN;
		}
		/**
		 * Get link 
		 * @return string link
		 */
		public function getRegisterLink() {
			// mod_rewrite URL
			return '/' . self::REGISTER;
		}
		
	}