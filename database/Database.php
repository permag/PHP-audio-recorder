<?php
	namespace permag\database;

	/**
	 * Database class using PDO
	 */
	class Database {
		
		private $m_config = null;
		private $pdo = null;

		public function __construct(\permag\database\DBConfig $config) {
			$this->m_config = $config;
		}
		
		/**
		 * Start a connection
		 * @return bool true on OK else false
		 */
		public function connect() {
			try {
				$this->pdo = new \PDO(
									$this->m_config->m_hostDB,
									$this->m_config->m_user,
									$this->m_config->m_pass
									);

				$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			
			} catch (\PDOException $e) {
 				echo 'Connection ERROR. ' . $e->getMessage();
 				return false;
 			}

 			return true;
		}

		/**
		 * Select all
		 * @param string, SQL-statement
		 * @return associative array, result
		 */
		public function selectCountAll($sqlQuery, Array $param) {
			try {
				$ret = 0;
				$stmt = $this->pdo->prepare($sqlQuery);
				$stmt->execute($param);
				$ret = $stmt->fetchColumn();
				$stmt = null;

				return $ret;

			} catch (\PDOException $e) {
				echo $e->getMessage();
				return false;
			}
		}

		/**
		 * Select with parameters
		 * @param string, SQL-statement
		 * @param associative array, SQL-parameters
		 * @return associative array, result
		 */
		public function selectParam($sqlQuery, Array $param) {
			try {
				$ret = 0;
				$stmt = $this->pdo->prepare($sqlQuery);
				$stmt->execute($param);
				$ret = $stmt->fetchAll(\PDO::FETCH_ASSOC);
				$stmt = null;

				return $ret;

			} catch (\PDOException $e) {
				echo $e->getMessage();
				return false;
			}
		}
		/**
		 * Get stmt in return
		 * @param string, SQL-statement
		 * @param associative array, SQL-parameters
		 * @return object STMT
		 */
		public function selectReturnSTMT($sqlQuery, Array $param) {
			try {
				$ret = 0;
				$stmt = $this->pdo->prepare($sqlQuery);
				$stmt->execute($param);
				
				return $stmt;

			} catch (\PDOException $e) {
				echo $e->getMessage();
				return false;
			}
		}

		/**
		 * Insert, update or delete queries.
		 * @param string, SQL-statement
		 * @param associative array, SQL-parameters
		 * @return int, number of rows affected
		 */
		public function insertUpdateDelete($sqlQuery, Array $param) {
			try {
				$ret = 0;
				$stmt = $this->pdo->prepare($sqlQuery);
				$stmt->execute($param);
				$ret = $stmt->rowCount();
				$stmt = null;

				return $ret;

			} catch (\PDOException $e) {
				echo $e->getMessage();
				return false;
			}
		}

		/**
		 * Delete multiple rows
		 * @param string, SQL-statement
		 * @param array, SQL-parameters
		 * @return int, number of rows affected
		 */
		public function deleteMultipleByParam($sqlQuery, $param) {
			try {
				$ret = 0;
				$stmt = $this->pdo->prepare($sqlQuery);
				foreach ($param as $k => $id) {
					$stmt->bindValue(($k+1), $id);
				}
				$stmt->execute();
				$ret = $stmt->rowCount();
				$stmt = null;

				return $ret;

			} catch (\PDOException $e) {
				echo $e->getMessage();
				return false;
			}

		}

		/**
		 * @return PDO instace
		 */
		public function returnPDO() {
			return $this->pdo;
		}

		/**
		 * Last inserted id
		 * @return int, number of last id
		 */
		public function lastInsertId() {
			try {
				return $this->pdo->lastInsertId();

			} catch (\PDOException $e) {
				echo $e->getMessage();
				return false;
			}
		}


		// TEST
		public function test() {

			// connection
			if ($this->connect() == false) {
				echo 'FEL! Kunde inte koppla upp db.';
				return false;
			}

			// select count
			if (count($this->selectCountAll('SELECT * FROM testTable', array())) !== 1) {
				echo '<p>FEL! SelectCountAll fel.</p>';
				return false;
			}

			// select with parameter, return
			$test_selectParamReturn = $this->selectParam('SELECT * FROM testTable WHERE testId = :testId', array(':testId' => 1));
			foreach ($test_selectParamReturn as $row) {
				if (count($row['test']) !== 1) {
					echo '<p>FEL! selectParam count fel.</p>';
					return false; 
				}
				if ($row['test'] !== 'heeejj') {
					echo '<p>FEL! selectParam return fel.</p>';
					return false;
				}
			}

			// insert
			if ($this->insertUpdateDelete('INSERT INTO testTable (test) VALUES (:test)', array(':test' => 'Detta är en test')) !== 1) {
				echo '<p>FEL! Insert returnerar inte 1.</p>';
				return false;
			}
			// delete, last inserted
			if ($this->insertUpdateDelete('DELETE FROM testTable WHERE testId = :testId',
							array(':testId' => $this->lastInsertId())) !== 1) {
				echo '<p>FEL! delete returnerar inte 1.</p>';
				return false;
			}

			// if all is ok
			return true;
		}
	}