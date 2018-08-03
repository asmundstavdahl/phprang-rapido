<?php

namespace Rapd;

class Database {
	use Prototype;

	public static $pdo = null;

	public static function getAll(string $entityClass){
		self::assertInitialized();

		$table = $entityClass::getTable();

		$sql = "SELECT * FROM `{$table}`";
		$stmt = self::$pdo->prepare($sql);
		$stmt->setFetchMode(\PDO::FETCH_CLASS, $entityClass);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public static function getById(string $entityClass, int $id){
		self::assertInitialized();

		$table = $entityClass::getTable();

		$sql = "SELECT * FROM `{$table}` WHERE id = :id";
		$stmt = self::$pdo->prepare($sql);
		$stmt->setFetchMode(\PDO::FETCH_CLASS, $entityClass);
		$stmt->execute([":id" => $id]);
		return $stmt->fetch();
	}

	public static function save(object $entity){
		if($entity->id){
			return self::update($entity);
		} else {
			return self::insert($entity);
		}
	}

	public static function insert(object $entity){
		self::assertInitialized();

		$table = $entity->getTable();

		if($entity->id){
			throw new Exception("Entity already has an ID");
		}

		$columns = $entity->getColumns();
		$columns = array_filter($columns, function($column){
			return $column != "id";
		});

		$columnList = join(", ", $columns);

		$values = [];
		foreach($columns as $column){
			$values[] = ":{$column}";
		}
		$valueList = join(", ", $values);

		$valueBinds = [];
		foreach($columns as $column){
			$valueBinds[":{$column}"] = $entity->{$column};
		}

		$sql = "INSERT INTO `{$table}`
		({$columnList})
		VALUES
		({$valueList})";

		$stmt = self::$pdo->prepare($sql);
		$stmt->execute($valueBinds);
		return $stmt->fetchAll(\PDO::FETCH_CLASS, get_class($entity));
	}

	public static function update(object $entity){
		self::assertInitialized();

		$table = $entity->getTable();

		if(!$entity->id){
			throw new Exception("Need an ID to update the entity");
		}

		$columns = $entity->getColumns();

		$columnAssignmentArray = [];
		foreach($columns as $column){
			$columnAssignmentArray[] = "{$column} = :{$column}";
		}
		$columnAssignments = join(", ", $columnAssignmentArray);

		$valueBinds = [];
		foreach($columns as $column){
			$valueBinds[":{$column}"] = $entity->{$column};
		}

		$sql = "UPDATE `{$table}`
		SET {$columnAssignments}
		WHERE id = :id";

		$stmt = self::$pdo->prepare($sql);
		$stmt->execute($valueBinds);
		return $stmt->fetchAll(\PDO::FETCH_CLASS, get_class($entity));
	}

	public static function delete(object $entity){
		self::assertInitialized();

		$table = $entity->getTable();

		if(!$entity->id){
			throw new Exception("Can't delete entity without an ID");
		}

		$sql = "DELETE FROM `{$table}` WHERE id = :id";

		$stmt = self::$pdo->prepare($sql);
		return $stmt->execute([":id" => $entity->id]);
	}

	private function implyTable(string $entityClass){
		$table = array_pop(explode("\\", $entityClass));
		$table = preg_replace("/([a-z0-9])([A-Z])/", '$1_$2', $table);
		$table = strtolower($table);
		return $table;
	}

	private function assertInitialized(){
		if(self::$pdo === null){
			$dbFile = "{$_SERVER["DOCUMENT_ROOT"]}/../default.sqlite3";
			error_log("Database defaulting to sqlite at {$dbFile}");
			if(!file_exists($dbFile)){
				touch($dbFile);
			}
			self::$pdo = new \PDO("sqlite:{$dbFile}");
			self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}
	}
}
