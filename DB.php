<?php
require './vendor/autoload.php';

class DB
{

	private static $instance = null;
	private $pdo = null;

	/**
	 * getInstance
	 * @return object
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * clone
	 * @throws Exception
	 */
	final public function __clone()
	{
		throw new Exception("this instance is singleton class.");
	}

	/**
	 * コンストラクタ
	 */
	private function __construct()
	{
		$options = [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		];

		$dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8'
			, constant('DB_HOST')
			, constant('DB_NAME')
		);

		$this->pdo = new PDO($dsn
			, constant('DB_USER')
			, constant('DB_PASSWORD')
			, $options
		);
	}

	/**
	 * トランザクション
	 */
	public function transaction()
	{
		if (!$this->pdo->inTransaction()) {
			$this->pdo->beginTransaction();
		}
	}

	/**
	 * ロールバック
	 */
	public function rollback()
	{
		if ($this->pdo->inTransaction()) {
			$this->pdo->rollBack();
		}
	}

	/**
	 * コミット
	 */
	public function commit()
	{
		if ($this->pdo->inTransaction()) {
			$this->pdo->commit();
		}
	}

	/**
	 * SELECT
	 * @param string $sql
	 * @param array $params
	 * @param int $start
	 * @param int $limit
	 * @param array recordset
	 */
	public function select($sql, array $params = [], $start = 0, $limit = null)
	{
		$sql = trim($sql);
		if (!is_null($limit) && 0 < $limit) {
			$sql .= sprintf(' LIMIT %d offset %d', $limit, $start);
		}
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);
		return $stmt->fetchAll();
	}

	/**
	 * UPDATE
	 * @param string $sql
	 * @param array $params
	 * @return int rowCount
	 */
	public function update($sql, array $params = [])
	{
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);
		return $stmt->rowCount();
	}

	/**
	 * INSERT
	 * @param string $sql
	 * @param array $params
	 * @return int lastInsertId
	 */
	public function insert($sql, array $params = [])
	{
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);
		return $this->pdo->lastInsertId();
	}

	/**
	 * DELETE
	 * @param string $sql
	 * @param array $params
	 * @return int rowCount
	 */
	public function delete($sql, array $params = [])
	{
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);
		return $stmt->rowCount();
	}

}