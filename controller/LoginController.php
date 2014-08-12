<?php 
	namespace permag\controller;

	require_once('view/LoginView.php');

	class LoginController {

		public function doControl(\permag\model\LoginHandler $lh, \permag\view\PageNavigationView $pageNavView) {
			$outputHTML = '';
			$lw = new \permag\view\LoginView();

			$message = '';

			// logged in?
			if ($lh->isLoggedIn()) {

				// clicked logout?
				if ($lw->triedToLogOut()) {
					$lh->doLogout();
					$lw->killCookie();
					$pageNavView->redirectTo($pageNavView->getLoginLink());
				}
			// not logged in?
			} else {

				// login cookie is set
				if ($lw->cookieExists()) {
					// auto login using username/password from cookie
					$userInfo_a = array();
					$userInfo_a = $lw->readLoginCookie();
					if ($lh->doLogin($userInfo_a[0], $userInfo_a[1])) {
						$message = $lw::USER_LOGGED_IN;
					}
				}

				// clicked login?
				if ($lw->triedToLogIn()) {
					// if username/passw is correct
					if ($lh->doLogin($lw->getUsername(), $lw->getPassword())) {
						// if remember me is checked, save to cookie
						if ($lw->rememberMe()) {
							$lw->saveCookie();
						}
						$pageNavView->redirectTo($pageNavView->getHomeLink());
						
					} else {
						$message = $lw::LOGIN_ERROR;
					}
				}
			}
			// logged in again?
			if ($lh->isLoggedIn()) {
				$outputHTML .= $lh->getLoggedInUsername();
				$outputHTML .= $lw->doLogoutBox();

			} else {
				
				$regLink = $pageNavView->getRegisterLink();
				$outputHTML .= $lw->doLoginBox($regLink);
			}

			// get the user message from the view
			$messageHTML = $lw->outputMessage($message);

			return $outputHTML . $messageHTML;
		}

	}