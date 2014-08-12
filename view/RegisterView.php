<?php
	namespace permag\view;

	require_once('common/Validator.php');

	/**
	 * View for registering user
	 */
	class RegisterView {

		// user messages
		const USERNAME_ALREADY_EXISTS = 'USERNAME_ALREADY_EXISTS';
		const EMPTY_FIELDS = 'EMPTY_FIELDS';
		const PASSWORDS_DONT_MATCH = 'PASSWORDS_DONT_MATCH';

		const WRONG_USERNAME_FORMAT = 'WRONG_USERNAME_FORMAT';
		const WRONG_PASSWORD_FORMAT = 'WRONG_PASSWORD_FORMAT';
		const WRONG_EMAIL_FORMAT = 'WRONG_EMAIL_FORMAT';

		// form
		private static $m_usernameField = 'usernameRegisterField';
		private static $m_passwordField = 'passwordRegisterField';
		private static $m_passwordFieldAgain = 'passwordFieldAgain';
		private static $m_emailField = 'emailRegisterField';
		private static $m_registerButton = 'registerButton';

		// validator instance member
		private $m_validator = null;
		// valdiation errors
		private $m_validationError = '';

		public function __construct() {
			$this->m_validator = \permag\common\Validator::GetInstance();
		}

		/**
		 * Display register form
		 * @return string, HTML5
		 */
		public function doRegisterView() {
			$form = "<h2>Register</h2>
					<div id=\"registerView\">
						<form method=\"post\">
							Username: 
							<input type=\"text\" name=\"".self::$m_usernameField."\" />
							Password: 
							<input type=\"password\" name=\"".self::$m_passwordField."\" /><br />
							Password again: 
							<input type=\"password\" name=\"".self::$m_passwordFieldAgain."\" /><br />
							Email: 
							<input type=\"email\" name=\"".self::$m_emailField."\" /><br />
							<input type=\"submit\" name=\"".self::$m_registerButton."\" value=\"Register\" />
						</form>
					</div>";

			return $form;
		}

		/**
		 * Return register username
		 * @return string username
		 */
		public function getUsername() {
			// returnera username om sådant skickats
			$username = trim($_POST[self::$m_usernameField]);

			if ($username == '' || $username == null) {
				return null;
			} else {
				return $username;
			}
		}

		/**
		 * Return register password md5
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
		 * Check passwords from input match
		 * @return bool, true = ok
		 */
		public function inputPasswordsMatch() {
			if ($_POST[self::$m_passwordField] == $_POST[self::$m_passwordFieldAgain]) {
				return true;
			
			} else {
				return false;
			}
		}

		/**
		 * Return register email
		 * @return string email
		 */
		public function getEmail() {
			// returnera email om sådant skickats
			$email = trim($_POST[self::$m_emailField]);

			if ($email == '' || $email == null) {
				return null;
			} else {
				return $email;
			}
		}

		/**
		 * User pressed register button
		 * @return bool true = OK | bool false
		 */
		public function triedToRegister() {
			if (isset($_POST[self::$m_registerButton])) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Check if empty form fields exists
		 * @return bool, false = ok, true = fail
		 */
		public function emptyFieldsExists() {
			if ($this->getUsername() == '' || $this->getPassword() == '' || $this->getEmail() == '') {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Validate username
		 * @return bool, true = no error, false = error
		 */
		public function validateUsername() {
			if ($this->m_validator->ValidateUsername($this->getUsername()) == false) {
				$this->m_validationError = $this->m_validator->GetValidationError();
				return false;
			}
			return true; // no error
		}

		/**
		 * Validate password
		 * @return bool, true = no error, false = error
		 */
		public function validatePassword() {
			if ($this->m_validator->ValidatePassword($_POST[self::$m_passwordField]) == false) {
				$this->m_validationError = $this->m_validator->GetValidationError();
				return false;
			}
			return true; // no error
		}

		/**
		 * Validate email
		 * @return bool, true = no error, false = error
		 */
		public function validateEmail() {
			if ($this->m_validator->ValidateEmail($this->getEmail()) == false) {
				$this->m_validationError = $this->m_validator->GetValidationError();
				return false;
			}
			return true; // no error
		}

		/**
		 * Get current checked validation error
		 * @return bool, true = no error, false = error
		 */
		public function getValidationError() {
			return $this->m_validationError;
		}

		/**
		 * Output messages for user
		 * @param string, array, const values of errors
		 * @return string, html error messages
		 */
		public function outputMessage($message_a) {
			$messageHTML = '';

			foreach ($message_a as $message) {
				if ($message == self::USERNAME_ALREADY_EXISTS) {
					$messageHTML .= '<h3>Username already exists.</h3>';
				}
				if ($message == self::EMPTY_FIELDS) {
					$messageHTML .= '<h3>Form contains empty fields.</h3>';
				}
				if ($message == self::PASSWORDS_DONT_MATCH) {
					$messageHTML .= '<h3>Password fields don\'t match.</h3>';
				}
				if ($message == self::WRONG_USERNAME_FORMAT) {
					$messageHTML .= '<h3>Wrong username format.</h3><p>(a-z, A-Z, 0-9, 5-18 characters.)</p>';
				}
				if ($message == self::WRONG_PASSWORD_FORMAT) {
					$messageHTML .= '<h3>Wrong password format.</h3><p>(a-z, A-Z, 0-9, min 6 characters.)</p>';
				}
				if ($message == self::WRONG_EMAIL_FORMAT) {
					$messageHTML .= '<h3>Wrong e-mail format.</h3>';
				}
			}

			return "<div class=\"errorMessages\">$messageHTML</div>";
		}
		
	}