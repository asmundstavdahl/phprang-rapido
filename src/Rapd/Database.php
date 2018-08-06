<?php

namespace Rapd;

class Database {
	use Prototype;

	public static $pdo = null;

	public static function findAll(string $entityClass) : array {
		self::assertInitialized();

		$table = $entityClass::getTable();

		$sql = "SELECT * FROM `{$table}`";
		$stmt = self::$pdo->prepare($sql);
		$stmt->execute();

		$rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		$entities = [];
		foreach($rows as $values){
			$entity = new $entityClass();
			foreach($values as $field => $value){
				$entity->{$field} = $value;
			}
			$entities[] = $entity;
		}

		return $entities;
	}

	public static function findById(string $entityClass, int $id) {
		self::assertInitialized();

		$table = $entityClass::getTable();

		$sql = "SELECT * FROM `{$table}` WHERE id = :id";
		$stmt = self::$pdo->prepare($sql);
		$stmt->execute([":id" => $id]);

		$values = $stmt->fetch(\PDO::FETCH_ASSOC);
		$entity = new $entityClass();
		foreach($values as $field => $value){
			$entity->{$field} = $value;
		}

		return $entity;
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

		$columns = array_keys($entity->getFields());
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

		$id = self::$pdo->lastInsertId();
		$entity->id = $id;
		return $id;
	}

	public static function update(object $entity){
		self::assertInitialized();

		$table = $entity->getTable();

		if(!$entity->id){
			throw new \Exception("Need an ID to update the entity");
		}

		$columns = array_keys($entity->getFields());

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

	public function assertInitialized(){
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
