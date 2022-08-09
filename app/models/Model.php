<?php
/**
 * Model
 * 
 * @author Tim Daniëls
 */

namespace app\models;

use database\DB;

class Model {

   private static $modelTable;
   public $model;

   /**
    * Setting model table name if exists
    * 
    * @param string $table model
    * @return void
    */
   public static function table($table) {

      $getTable = DB::try()->raw("SELECT 1 FROM $table LIMIT 1")->first();

      if($getTable[1] === '1') {

         self::$modelTable = $table; 
      }
   }

   /**
    * Fetching all rows from table
    * 
    * @return array model and rows table
    */
   public static function all() {

      $model = self::createInstance();
      $query = DB::try()->select('*')->from(self::$modelTable)->fetch();
      $modelQuery = array_filter(array_merge($model, $query));

      return $modelQuery;
   }

   /**
    * Fetching row on id
    *
    * @param int $id column value
    * @return array model and row table
    */
    public static function get($id) {

      if($id !== null) {

         $model = self::createInstance();
         $query = DB::try()->select('*')->from(self::$modelTable)->where('id', '=', $id)->first();
         $modelQuery = array_filter(array_merge($model, $query));
         
         return $modelQuery;
      }
   }

   /**
    * Fetching row on condition
    *
    * @param string $column name
    * @param string $operator value
    * @param string $value column
    * @return array model and row table
    */
    public static function where($column, $operator, $value) {
     
      if($column !== null && $value !== null) {
         
         $model = self::createInstance();
         $query = DB::try()->select('*')->from(self::$modelTable)->where($column, $operator, $value)->first();
         $modelQuery = array_filter(array_merge($model, $query));
         
         return $modelQuery;
      }
   }

   /**
    * Inserting rows
    *
    * @param array column names and values
    * @return void
    */
    public static function insert($data) {
     
      if(!empty($data) && $data !== null) {
         
         $model = self::createInstance();
         $query = DB::try()->insert(self::$modelTable, $data);
      }
   }

   /**
    * Updating rows
    *
    * @param array $where column name, column value
    * @param array $data column names, column values
    * @return void
    */
    public static function update($where, $data) {
     
      if(!empty($where) && $where !== null && !empty($data) && $data !== null ) {
         
         foreach($where as $key => $value) {

            $model = self::createInstance();
            $query = DB::try()->update(self::$modelTable)->set($data)->where($key, '=', $value)->run();
         }
      }
   }

   /**
    * Create model instance
    *
    * @return object $instance model
    */
   public static function createInstance() {

      $model = get_called_class();

      if (class_exists($model)) {
         
         $instance = new $model;
         $arrayModel = (array) $instance;

         return $arrayModel;
      }
   }
}