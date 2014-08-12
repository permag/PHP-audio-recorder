<?php
	namespace permag\controller;

	require_once('model/RegisterHandler.php');
	require_once('view/RegisterView.php');
	
	/**
	 * Controls registration of user
	 */
	class RegisterController {

		public function doControl(\permag\model\LoginHandler $lh, \permag\database\Database $db, \permag\view\PageNavigationView $pageNavView) {

			$outputHTML = '';
			$message_a = array();

			$regHandler = new \permag\model\RegisterHandler($db);
			$regView = new \permag\view\RegisterView();
			if ($lh->isLoggedIn() == false) {

				$outputHTML .= $regView->doRegisterView();

				// clicked register button
				if ($regView->triedToRegister()) {
					// check empty input
					if ($regView->emptyFieldsExists()) {
						$message_a[] = \permag\view\RegisterView::EMPTY_FIELDS;
						
					} else {
						$validationErrorCount = 0;
						// validate input
						if ($regView->validateUsername() == false) {
							// add error messages
							$message_a[] = $regView->getValidationError();
							$validationErrorCount++;
						} 
						if ($regView->validatePassword() == false) {
							// add error messages
							$message_a[] = $regView->getValidationError();
							$validationErrorCount++;
						}
						if ($regView->validateEmail() == false) {
							// add error messages
							$message_a[] = $regView->getValidationError();
							$validationErrorCount++;
						}
						if ($validationErrorCount == 0) {

							// check if username exists
							if ($regHandler->usernameExists($regView->getUsername()) || $regHandler->usernameIsReserved($regView->getUsername())) {
								// username exists already, or is reserved, show message
								$message_a[] = \permag\view\RegisterView::USERNAME_ALREADY_EXISTS;
							
							} else {
								// check password fields match
								if ($regView->inputPasswordsMatch()) {

									// register (username, password, email)
									if ($regHandler->doRegister($regView->getUsername(), $regView->getPassword(), $regView->getEmail())) {
										$pageNavView->redirectTo($pageNavView->getLoginLink());
									}

								} else {
									$message_a[] = \permag\view\RegisterView::PASSWORDS_DONT_MATCH;
								}
							}
						}

					}

				}

				$messageHTML = $regView->outputMessage($message_a);

				return $outputHTML . $messageHTML;
			
			} else {
				$pageNavView->redirectTo($pageNavView->getHomeLink());
			}

		}
	}