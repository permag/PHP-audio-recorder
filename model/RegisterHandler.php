<?php
	namespace permag\model;

	/**
	 * Model for handling registration of user
	 */
	class RegisterHandler {

		const USERNAME = 'username';
		const PASSWORD = 'password';
		const EMAIL = 'email';

		const _USERNAME = ':username';
		const _PASSWORD = ':password';
		const _EMAIL = ':email';
		
		private static $m_memberTable = 'member';

		// reserved usernames
		private static $m_exceptedUsernames = 'username, admin, root, staff';

		private $m_db = null;

		public function __construct(\permag\database\Database $db) {
			$this->m_db = $db;
		}
		
		/**
		 * Register member
		 * @param string, username
		 * @param string, password
		 * @param string, email
		 * @return bool, true = ok, false = fail
		 */
		public function doRegister($username, $password, $email) {
			$sql = 'INSERT INTO '.self::$m_memberTable.' ('.self::USERNAME.','.self::PASSWORD.','.self::EMAIL.') 
					VALUES ('.self::_USERNAME.','.self::_PASSWORD.','.self::_EMAIL.')';
			$param = array(self::_USERNAME => $username, self::_PASSWORD => $password, self::_EMAIL => $email);

			// insert, 1 = OK
			if ($this->m_db->insertUpdateDelete($sql, $param) == 1) {
				return true;
			
			} else {
				return false;
			}
		}

		/**
		 * Check if username exists member
		 * @param string, username
		 * @return bool, true = ok, false = fail
		 */
		public function usernameExists($username) {
			$sql = 'SELECT * FROM '.self::$m_memberTable.' WHERE '.self::USERNAME.' = '.self::_USERNAME.'';
			$param = array(self::_USERNAME => $username);	

			// try select username
			if ($this->m_db->selectCountAll($sql, $param) > 0) {
				return true;
			
			} else {
				return false;
			}
		}

		/**
		 * Check if username to register is in reserved list and cannot be registered
		 * @param  string $username 
		 * @return bool true  
		 */
		public function usernameIsReserved($username) {
			$reservedUsernames = array();
			$reservedUsernames = explode(',', self::$m_exceptedUsernames);

			foreach ($reservedUsernames as $r) {
				if ($username == trim($r)) {
					return true;
				}
			}
		}


		// TEST
		public function test() {

			self::$m_memberTable = 'test_member';

			// find user
			if ($this->usernameExists('usernameTest222') == false) {
				echo 'FEL! usernameExists fann inte user.';
				return false;
			}
			// try register user
			if ($this->doRegister('usernameTest222', 'password123', 'hej@hej.se') == false) {
				echo 'FEL! doRegister kunde inte registrera ny användare.';
				return false;
			}

			return true;
		}
	}