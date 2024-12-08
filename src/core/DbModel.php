<?php

namespace app\core;

use Exception;

abstract class DbModel extends Model
{
    abstract public static function tableName(): string;
    abstract public function attributes(): array;
    abstract public static function primaryKey(): string;

    public function save(): bool
    {
        try {
            $tableName = static::tableName();
            $attributes = $this->attributes();
            $params = array_map(fn($attr) => ":{$attr}", $attributes);

            $statement = self::prepare("INSERT INTO $tableName (" . implode(',', $attributes) . ') VALUES (' . implode(',', $params) . ')');

            foreach ($attributes as $attribute) {
                $statement->bindValue(":$attribute", $this->{$attribute});
            }

            $statement->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function findOne($where)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $params = implode(' AND ', array_map(
            fn($attr) => "$attr = :$attr",
            $attributes
        ));
        $statement = static::prepare("SELECT * FROM $tableName WHERE $params");

        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }

        $statement->execute();
        return $statement->fetchObject(static::class);
    }


    public static function prepare($query)
    {
        return Application::$app->db->pdo->prepare($query);
    }
}