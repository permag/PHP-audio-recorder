<?php
	namespace permag\model;

	class LoginHandler {

		// name of session variable for logged in user
		const SESSION_USERLOGGEDIN = 'LoginHandler::userLoggedIn';
		const SESSION_USERNAME = 'LoginHandler::username';

		private static $m_memberId = 'memberId';

		private $m_db = null;

		public function __construct(\permag\database\Database $db) {
			$this->m_db = $db;
		}

		/**
		 * Check if user is logged in
		 * @return boolean true if logged in
		 */
		public function isLoggedIn() {
			// returnera true om inloggad annars false
			if (isset($_SESSION[self::SESSION_USERLOGGEDIN])) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Get memberId from logged in user
		 * @return int
		 */
		public function getLoggedInMemberId() {
			return $_SESSION[self::SESSION_USERLOGGEDIN];
		}

		public function getLoggedInUsername() {
			$username = $_SESSION[self::SESSION_USERNAME];
			return "<p id='username'>$username</p>";
		}

		/**
		 * Login
		 * @param  string $username 
		 * @param  string $password 
		 * @return bool
		 */
		public function doLogin($username, $password) {
			// returnera true om inloggning lyckas annars false
			$ret = 0;
			$sql = 'SELECT memberId 
					FROM member 
					WHERE username = :username 
					AND password = :password
					LIMIT 1';
			$param = array(':username' => $username, ':password' => $password);
			
			$ret = $this->m_db->selectParam($sql, $param);
			$count = 0;
			foreach ($ret as $row) {
				$count = count($row[self::$m_memberId]);
				$memberId = $row[self::$m_memberId];
			}

			if ($count === 1) {
				$_SESSION[self::SESSION_USERLOGGEDIN] = $memberId;
				$_SESSION[self::SESSION_USERNAME] = $username;
				return true;
			
			} else {
				return false;}

		}

		/**
		 * Log out user by killing session
		 */
		public function doLogout() {
			// ta bort session
			unset($_SESSION[self::SESSION_USERLOGGEDIN]);
			unset($_SESSION[self::SESSION_USERNAME]);
		}

		
		// test
		public function test() {
			// logga ut user
			$this->doLogout();
			
			// test IsLoggedIn - ska returnera false eftersom utloggad
			if ($this->isLoggedIn()) {
				echo 'FEL! IsLoggedIn returnerar true.';
				return false;
			} 
			if ($this->doLogin('usernameTestWrong','wrongPassword')) { // test DoLogIn med felaktig inloggning
				echo 'FEL! Felaktig inloggning returnerade true.';
				return false;
			} 
			if ($this->doLogin('usernameTest','5f4dcc3b5aa765d61d8327deb882cf99') == false) { // test DoLogin med rätt inloggning md5('password')
				echo 'FEL! Korrekt inloggning returnerar false.';
				return false;
			} 
			if ($this->isLoggedIn() == false) { // test IsLoggedIn igen - nu ska den om korrekt, returnera true.
				echo 'FEL! IsLoggedIn returnerar false.';
				return false;
			}

			// logga ut user
			$this->doLogout();

			// test DoLogin med rätt username och fel password
			if ($this->doLogin('usernameTest','wrongPassword')) {
				echo 'FEL! Felaktigt lösenord returnerade true.';
				return false;
			}

			return true;

		} // end test func
	}