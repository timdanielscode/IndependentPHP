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

        if(!empty(self::$_table) && self::$_table !== null) {

            return DB::try()->select('*')->from(self::$_table)->where('id', '=', $id)->first();
        }
    }

   /**
    * Fetching row on id but only given columns
    *
    * @param array $columns column names
    * @param string $id column value
    * @return object DB
    */
    public static function getColumns($columns, $id) {

        self::checkModel(new Tables());

        if(!empty(self::$_table) && self::$_table !== null) {

            $columnsString = implode(',', $columns);
            return DB::try()->select($columnsString)->from(self::$_table)->where('id', '=', $id)->first();
        }
    }

   /**
    * Fetching row on condition
    *
    * @param array $conditionValues column name, column value
    * @return object DB
    */
    public static function where($conditionValues) {

        self::checkModel(new Tables());

        if(!empty(self::$_table) && self::$_table !== null) {

            return DB::try()->select('*')->from(self::$_table)->where(key($conditionValues), '=', $conditionValues[key($conditionValues)])->fetch();
        }
    }

   /**
    * Fetching row on condition but only given columns
    *
    * @param array $columns column names
    * @param array $conditionValues column name, column value
    * @return object DB
    */
    public static function whereColumns($columns, $conditionValues) {

        self::checkModel(new Tables());

        if(!empty(self::$_table) && self::$_table !== null) {

            $columnsString = implode(',', $columns);
            return DB::try()->select($columnsString)->from(self::$_table)->where(key($conditionValues), '=', $conditionValues[key($conditionValues)])->fetch();
        }
    }

   /**
    * Inserting rows
    *
    * @param array column names and values
    * @return object DB
    */
    public static function insert($data) {

        self::checkModel(new Tables());

        if(!empty(self::$_table) && self::$_table !== null) {

            return DB::try()->insert(self::$_table, $data);
        }
   }

   /**
    * Updating rows
    *
    * @param array $where column name, column value
    * @param array $data column names, column values
    * @return object DB
    */
    public static function update($where, $data) {

        self::checkModel(new Tables());

        if(!empty(self::$_table) && self::$_table !== null) {

            foreach($where as $key => $value) {

                return DB::try()->update(self::$_table)->set($data)->where($key, '=', $value)->run();
            }
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

        if(!empty(self::$_table) && self::$_table !== null) {

            return DB::try()->delete(self::$_table)->where($column, '=', $value)->run();
        }
    }
}