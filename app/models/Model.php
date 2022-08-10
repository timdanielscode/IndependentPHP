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
    * @return object DB
    */
   public static function all() {

      self::createInstance();
      return DB::try()->select('*')->from(self::$modelTable)->fetch();
   }

   /**
    * Fetching row on id
    *
    * @param int $id column value
    * @return object DB
    */
    public static function get($id) {

      if($id !== null) {

         self::createInstance();
         return DB::try()->select('*')->from(self::$modelTable)->where('id', '=', $id)->first();
      }
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
     
      if($column !== null && $value !== null) {
         
         self::createInstance();
         return DB::try()->select('*')->from(self::$modelTable)->where($column, $operator, $value)->first();
      }
   }

   /**
    * Inserting rows
    *
    * @param array column names and values
    * @return object DB
    */
    public static function insert($data) {
     
      if(!empty($data) && $data !== null) {
         
         self::createInstance();
         return DB::try()->insert(self::$modelTable, $data);
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
     
      if(!empty($where) && $where !== null && !empty($data) && $data !== null ) {
         
         foreach($where as $key => $value) {

            self::createInstance();
            return DB::try()->update(self::$modelTable)->set($data)->where($key, '=', $value)->run();
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
     
      if(!empty($column) && $column !== null && !empty($value) && $value !== null) {

         self::createInstance();
         return DB::try()->delete(self::$modelTable)->where($column, '=', $value)->run();
      }
   }

   /**
    * Create model instance
    *
    * @return object model
    */
   public static function createInstance() {

      $model = get_called_class();
      if (class_exists($model)) {
         
         return new $model;
      }
   }
}