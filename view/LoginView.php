<?php
	namespace permag\view;

	/**
	 * View for logging in member
	 */
	class LoginView {

		// user messages
		const NO_MESSAGE = 'NO_MESSAGE';
		const USER_LOGGED_IN = 'USER_LOGGED_IN';
		const USER_LOGGED_OUT = 'USER_LOGGED_OUT';
		const LOGIN_ERROR = 'LOGIN_ERROR';
		const LOGIN_USERNAME_ERROR = 'LOGIN_USERNAME_ERROR';

		// form names
		private static $m_usernameField = 'usernameLoginField';
		private static $m_passwordField = 'passwordLoginField';
		private static $m_checkBoxRememberMe = 'checkBoxRememberMe';
		private static $m_loginButton = 'loginButton';
		private static $m_logoutButton = 'logoutButton';

		// cookie
		private static $m_loginCookie = 'loginCookie';
		private static $m_loginCookieLifespan = 0;

		public function __construct() {
			// set cookie lifespan
			self::$m_loginCookieLifespan = time()+999*999;
		}

		/**
		 * login form
		 * @return string HTML 
		 */
		public function doLoginBox($regLink) {
			// returnera html för inloggningsformulär
			$form = "<h2>Login</h2>
					<div id=\"loginView\">
						<form method=\"post\">
							Username: 
							<input type=\"text\" name=\"".self::$m_usernameField."\" />
							Password: 
							<input type=\"password\" name=\"".self::$m_passwordField."\" /><br />
							<span id=\"loginRememberMe\">Remember me:</span>
							<input type=\"checkbox\" name=\"".self::$m_checkBoxRememberMe."\" />
							<input type=\"submit\" name=\"".self::$m_loginButton."\" value=\"Login\" />
						</form>
						<br />
						<p><a href=\"".$regLink."\">Register here</a></p>
					</div>";

			return $form;
		}

		/**
		 * logout button
		 * @return string HTML 
		 */
		public function doLogoutBox() {
			// returnerar en logut-knapp
			$form = "<form method=\"post\">
						<input type=\"submit\" name=\"".self::$m_logoutButton."\" value=\"Logout\" />
					</form><br />";

			return $form;
		}

		/**
		 * get username
		 * @return string username
		 */
		public function getUsername() {
			// returnera username om sådant skickats
			$username = strip_tags(trim($_POST[self::$m_usernameField]));

			if ($username == '' || $username == null) {
				return null;
			} else {
				return $username;
			}
		}

		/**
		 * get password md5
		 * @return string password
		 */
		public function getPassword() {
			// returnera password om sådant skickats
			$password = $_POST[self::$m_passwordField];

			if ($password == '' || $password == null) {
				return null;
			} else {
				return md5($password);
			}
		}

		/**
		 * user pressed login button
		 * @return bool true = OK | bool false
		 */
		public function triedToLogIn() {
			if (isset($_POST[self::$m_loginButton])) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * user pressed logout button
		 * @return bool true = OK | bool false
		 */
		public function triedToLogOut() {
			if (isset($_POST[self::$m_logoutButton])) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 *	Remeber me checked in login form
		 * @return bool true if checked
		 */
		public function rememberMe() {
			if (isset($_POST[self::$m_checkBoxRememberMe])) {
				return true;
			}
		}

		/**
		 * save cookie
		 */
		public function saveCookie() {
			// make string separated with semicolon: username;password 
			$userInfo = $this->getUsername() . ';' . $this->getPassword();
			setcookie(self::$m_loginCookie, $userInfo, self::$m_loginCookieLifespan);
		}

		/**
		 * check if cookie exists
		 * @return bool true = OK
		 */
		public function cookieExists() {
			if (isset($_COOKIE[self::$m_loginCookie])) {
				return true;
			}
		}

		/**
		 * read cookie
		 * @return array(2) string
		 */
		public function readLoginCookie() {
			$cookie =  $_COOKIE[self::$m_loginCookie];
			$userLogin_a = explode(';', $cookie);
			// return array of username, password
			return $userLogin_a;
		}

		/**
		 * delete cookie
		 */
		public function killCookie() {
			setcookie(self::$m_loginCookie, '', time()-3600); // back in time to kill cookie
		}

		/**
		 * messages to user
		 * @param const value from controller class
		 * @return string HTML
		 */
		public function outputMessage($message) {
			$messageHTML = '';

			if ($message == self::USER_LOGGED_IN) {
				$messageHTML = '<h3>Logged in...</h3>';
			
			} else if ($message == self::LOGIN_ERROR) {
				$messageHTML = '<h3>Incorrect login...</h3>';
			
			} else if ($message == self::USER_LOGGED_OUT) {
				$messageHTML = '<h3>Logged out...</h3>';
			
			} else if ($message == self::LOGIN_USERNAME_ERROR) {
				$messageHTML = '<h3>Wrong username.</h3>';
			}

			if ($messageHTML != '') {
				return "<div class=\"errorMessages\">$messageHTML</div>";
			}
		}
	}