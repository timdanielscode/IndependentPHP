<?php
/**
 * Model
 * 
 * @author Tim Daniëls
 */

namespace app\models;

use database\DB;
use app\models\register\Tables;

class Model {

    private static $_table;

   /**
    * Setting table
    *
    * @param object $model model
    */
    public static function setTable($tables) {
   
        self::$_table = $tables->tables[self::checkModel(get_called_class())];
    }

   /**
    * Checking model
    *
    * @param object $model model
    */
    public static function checkModel($model) {

        if (class_exists($model)) {

            return substr($model, strrpos($model, '\\') + 1);
        }
    }

   /**
    * Fetching row on id
    *
    * @param int $id column value
    * @return object DB
    */
    public static function get($id) {

        self::setTable(new Tables());
        return DB::try()->select('*')->from(self::$_table)->where('id', '=', $id)->first();
    }

   /**
    * Fetching row on condition
    *
    * @param string $column name
    * @param string $operator value
    * @param string $value column
    * @return object DB
    */
    public static function where($column, $operator, $value) {

        self::setTable(new Tables());
        return DB::try()->select('*')->from(self::$_table)->where($column, $operator, $value)->first();
    }

   /**
    * Inserting rows
    *
    * @param array column names and values
    * @return object DB
    */
    public static function insert($data) {

        self::setTable(new Tables());
        return DB::try()->insert(self::$_table, $data);
   }

   /**
    * Updating rows
    *
    * @param array $where column name, column value
    * @param array $data column names, column values
    * @return object DB
    */
    public static function update($where, $data) {

        foreach($where as $key => $value) {

            self::setTable(new Tables());
            return DB::try()->update(self::$_table)->set($data)->where($key, '=', $value)->run();
        }
    }

   /**
    * Deleting rows
    *
    * @param string $column name
    * @param string $value column
    * @return object DB
    */
    public static function delete($column, $value) {

        self::setTable(new Tables());
        return DB::try()->delete(self::$_table)->where($column, '=', $value)->run();
    }
}