<?php
	require_once('database/DBConfig.php');
	require_once('database/Database.php');

	###################################################################
	#                      INSTALL AND UNINSTALL                      #
	###################################################################

	$installationManager = new InstallationManager();
	$installationMangerView = new InstallationManagerView();

	// display menu
	echo $installationMangerView->doManagerBox();

	// if cliced Install button: install
	if ($installationMangerView->doInstall()) {
		$installationManager->install();
	}

	// if cliced Uninstall button: uninstall
	if ($installationMangerView->doUninstall()) {
		$installationManager->uninstall();
	}

	###################################################################

	/**
	 * MANAGER
	 */
	class InstallationManager {

		// table names
		const TABLE_MEMBER = 'member';
		const TABLE_RECORDING = 'recording';

		private $m_db = null;

		public function __construct() {
			// DB
			$dbConfig = new \permag\database\DBConfig();
			$db = new \permag\database\Database($dbConfig);
			$db->connect();
			$this->m_db = $db;
		}

		public function install() {
			$install = new Install($this->m_db);
			if ($install->init()) {
				echo '<h2>Installation succeeded.</h2>
						<p><a href="./">Go to application</a><p>';
			} else {
				echo '<h2>Installation failed.</h2>';
			}
		}

		public function uninstall() {
			$uninstall = new Uninstall($this->m_db);
			if ($uninstall->init()) {
				echo '<h2>Uninstallation succeeded.</h2>';
			} else {
				echo '<h2>Uninstallation failed.</h2>';
			}
		}

	} // end class

	/**
	 * MANAGER VIEW
	 */
	class InstallationManagerView {

		private static $m_installButton = 'install';
		private static $m_uninstallButton = 'uninstall';

		public function doManagerBox() {
			return "
				<h1>Installation manager</h1>
				<p>Choose to install or uninstall:</p>
				<form method='post'>
					<input type='submit' name='".self::$m_installButton."' value='Install' />
					<input type='submit' name='".self::$m_uninstallButton."' value='Uninstall' />
				</form>";
		}

		public function doInstall() {
			if (isset($_POST[self::$m_installButton])) {
				return true;
			}
		}

		public function doUninstall() {
			if (isset($_POST[self::$m_uninstallButton])) {
				return true;
			}
		}
	}



	###########################################################################################
	/**
	 * INSTALL
	 * create tables
	 * set write permission on directory for recordings
	 */
	class Install {

		private $m_db = null;

		public function __construct(\permag\database\Database $db) {
			$this->m_db = $db;
		}

		public function init() {
			// create tables
			// set write permisson to dir with recordings
			if ($this->createMemberTable() && 
				$this->createRecordingTable() &&
				$this->setDirPermission()) {
				
				return true;
			} else {
				return false;
			}

		}

		public function createMemberTable() {
			$sql = "CREATE TABLE IF NOT EXISTS ".InstallationManager::TABLE_MEMBER." (
					  `memberId` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `username` varchar(30) NOT NULL,
					  `password` varchar(32) NOT NULL,
					  `email` varchar(40) NOT NULL,
					  PRIMARY KEY (`memberId`)
					) ENGINE=InnoDB  DEFAULT CHARSET=latin1";
			
			try {
				$pdo = $this->m_db->returnPDO();
				$stmt = $pdo->prepare($sql);
				$stmt->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}

		public function createRecordingTable() {
			$sql = "CREATE TABLE IF NOT EXISTS ".InstallationManager::TABLE_RECORDING." (
					  `recordingId` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					  `filename` varchar(29) NOT NULL,
					  `memberId` int(11) unsigned NOT NULL,
					  `toMemberId` int(10) unsigned NOT NULL,
					  `new` tinyint(1) NOT NULL DEFAULT '1',
					  PRIMARY KEY (`recordingId`)
					) ENGINE=InnoDB  DEFAULT CHARSET=latin1";
			
			try {
				$pdo = $this->m_db->returnPDO();
				$stmt = $pdo->prepare($sql);
				$stmt->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}

		public function setDirPermission() {
			if (chmod('recs', 0777)) {
				return true;
			}
		}

	} // end class



	###########################################################################################
	/**
	 * UNINSTALL
	 * drop tables
	 */
	class Uninstall {

		private $m_db = null;

		public function __construct(\permag\database\Database $db) {
			$this->m_db = $db;
		}
		public function init() {
			if ($this->dropTables()) {
				return true;
			} else {
				return false;
			}
		}

		public function dropTables() {
			$sql = "DROP TABLES ".InstallationManager::TABLE_MEMBER.", ".InstallationManager::TABLE_RECORDING."";

			try {
				$pdo = $this->m_db->returnPDO();
				$stmt = $pdo->prepare($sql);
				$stmt->execute();
				return true;
			} catch (Exception $e) {
				return false;
			}
		}

	} // end class

