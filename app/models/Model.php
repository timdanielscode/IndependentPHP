<?php

namespace app\models;

use database\DB;
use app\models\register\Tables;

class Model {

    private static $_table, $_model;

   /**
    * To check model class exists
    *
    * @param object $tables Tables
    */
    private static function checkModel($tables) {

        if (class_exists(get_called_class())) {

            self::$_model = get_called_class();
            self::checkRegisteredModel(substr(self::$_model, strrpos(self::$_model, '\\') + 1), $tables);
        }
    }

   /**
    * To check model name matches the model key name to set the table value
    *
    * @param string $name model name
    * @param object $tables Tables
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
    * To fetch a row on id to fetch data from table
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
    * To fetch a row on id but only given columns to fetch data from table
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
    * To fetch a row on condition to fetch data from table
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
    * To fetch a row on condition but only given columns to fetch data from table
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
    * To insert rows to store data in table
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
    * to update rows update data in table 
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
    * To delete rows to remove data from table
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