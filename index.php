<?php error_reporting(E_ALL);
	session_start();
	
	require_once('common/PageView.php');
	require_once('view/PageNavigationView.php');
	require_once('view/PageCompositionView.php');
	require_once('database/DBConfig.php');
	require_once('database/Database.php');
	require_once('controller/LoginController.php');
	require_once('controller/RegisterController.php');
	require_once('controller/RecordController.php');
	require_once('controller/RecordingController.php');
	require_once('model/LoginHandler.php');
	require_once('view/MasterView.php');
	require_once('view/RecordingListNavigationView.php');


	class MasterController {

		const TITLE = 'sayHello';

		public function doControl() {

			$loginOutput = null;
			$registerOutput = null;
			$recBoxOutput = null;
			$body = '';

			// instances
			$dbConfig = new \permag\database\DBConfig();
			$db = new \permag\database\Database($dbConfig);
			// db connect
			$db->connect();

			$loginHandler = new \permag\model\LoginHandler($db);
			$pageView = new \permag\common\PageView();
			$masterView = new \permag\view\MasterView();
			$pageNavigationView = new \permag\view\PageNavigationView();
			$pageCompositionView = new \permag\view\PageCompositionView();
			$recListNavView = new \permag\view\RecordingListNavigationView($pageNavigationView);

			// show logo
			$pageCompositionView->addToLeftSection($masterView->doLogo());

			// login doControl
			$loginController = new \permag\controller\LoginController();
			$loginOutput = $loginController->doControl($loginHandler, $pageNavigationView);


			// page navigation
			switch ($pageNavigationView->getController()) {

				default:
				case \permag\view\PageNavigationView::HOME:
					if ($loginHandler->isLoggedIn()) {
						// recorder doControl
						$recController = new \permag\controller\RecordController($loginHandler, $db);
						$recordingController = new \permag\controller\RecordingController($loginHandler, $db, $recListNavView);
						// page comp
						$pageCompositionView->addToLeftSection($masterView->doNavigationMenu($recListNavView));
						$pageCompositionView->addToLeftSection($loginOutput);
						$pageCompositionView->addToRightSection($recController->doControlBox());
						$pageCompositionView->addToMainSection($recordingController->doControlRecordingsList());

					} else {
						$pageNavigationView->redirectTo($pageNavigationView->getLoginLink());
					}
					break;

				case \permag\view\PageNavigationView::LOGIN:
					// page comp
					$pageCompositionView->addToRightSection($masterView->doDescription());
					$pageCompositionView->addToMainSection($loginOutput);
					break;


				case \permag\view\PageNavigationView::REGISTER:
					// register doControl
					$regController = new \permag\controller\RegisterController();
					$registerOutput = $regController->doControl($loginHandler, $db, $pageNavigationView);
					// page comp
					$pageCompositionView->addToRightSection($masterView->doDescription());
					$pageCompositionView->addToMainSection($registerOutput);
					break;

			}

			// kill db conn
			$db = null;


			// composition
			$body = $pageCompositionView->mergeSectionsToPage();
			// page view
			return $pageView->getHTMLPage(self::TITLE, $body);
		}
	}

	$masterController = new MasterController();
	echo $masterController->doControl();

