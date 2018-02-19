<?php
	final class Database {
		private $connection;

		public function __construct($hostname, $username, $password, $database, $port = '3306'){
			$this->connection = new mysqli($hostname, $username, $password, $database, $port);

			if ($this->connection->connect_error) {
				throw new Exception('Error: ' . $this->connection->error . '<br />Error No: ' . $this->connection->errno);
				die();
			}
		}

		public function query($sql) {
			$query = $this->connection->query($sql);

			if (!$this->connection->errno) {
				if ($query instanceof mysqli_result) {
					$data = array();

					while ($row = $query->fetch_assoc()) {
						$data[] = $row;
					}

					$result = new \stdClass();
					$result->num_rows = $query->num_rows;
					$result->row = isset($data[0]) ? $data[0] : array();
					$result->rows = $data;

					$query->close();

					return $result;
				} else {
					return true;
				}
			} else {
				throw new Exception('Error: ' . $this->connection->error  . '<br />Error No: ' . $this->connection->errno . '<br />' . $sql);
			}
		}

		public function escape($value) {
			return $this->connection->real_escape_string($value);
		}
	} 
?>