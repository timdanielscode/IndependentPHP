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

    private static $_table, $_model;

   /**
    * Checking model
    *
    * @param object $model model
    */
    private static function checkModel($tables) {

        if (class_exists(get_called_class())) {

            self::$_model = get_called_class();
            self::checkRegisteredModel(substr(self::$_model, strrpos(self::$_model, '\\') + 1), $tables);
        }
    }

   /**
    * Checking registered models and tables
    *
    * @param string $name model name
    * @param object $tables models/tables
    */
    private static function checkRegisteredModel($name, $tables) {

        foreach($tables->tables as $key => $value) {

            if($key === $name && !empty($tables->tables[$name])) {

                self::$_table = $tables->tables[$name];
                return;
            }
        }
    }

   /**
    * Fetching row on id
    *
    * @param int $id column value
    * @return object DB
    */
    public static function get($id) {

        self::checkModel(new Tables());
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

        self::checkModel(new Tables());
        return DB::try()->select('*')->from(self::$_table)->where($column, $operator, $value)->first();
    }

   /**
    * Inserting rows
    *
    * @param array column names and values
    * @return object DB
    */
    public static function insert($data) {

        self::checkModel(new Tables());
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

            self::checkModel(new Tables());
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

        self::checkModel(new Tables());
        return DB::try()->delete(self::$_table)->where($column, '=', $value)->run();
    }
}