<?php
	class Database extends SQLite3 {
		function __construct() {
			$this->open('data.sqlite');
		}

		function getStudent($id) {
			$sql = "SELECT * FROM students WHERE id = $id";
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);
			return $row;
		}
	}
?>