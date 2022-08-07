<?php
/**
 * Model
 * 
 * @author Tim Daniëls
 */

namespace app\models;

use database\DB;

class Model extends DB {

   private static $db, $modelTable, $model;

   /**
    * Setting model table name if exists
    * 
    * @param string $table model
    * @return void
    */
   public static function table($table) {

      self::$db = DB::try();
      $getTable = self::$db->raw("SELECT 1 FROM $table LIMIT 1")->first();

      if($getTable[1] === '1') {

         self::$modelTable = $table; 
      }
   }

   /**
    * Fetching all rows from table
    * 
    * @return array rows table
    */
   public static function getAll() {

      self::createInstance();

      $query = self::$db->select('*')->from(self::$modelTable)->fetch();
      return $query;
   }

   /**
    * Fetching row on id
    *
    * @param int $id column value
    * @return array row table
    */
    public static function get($id) {

      if($id !== null) {

         self::createInstance();

         $query = self::$db->select('*')->from(self::$modelTable)->where('id', '=', $id)->first();
         return $query;
      }
   }

   /**
    * Fetching row on condition
    *
    * @param string $column name
    * @param string $operator value
    * @param string $value column
    * @return array row table
    */
    public static function condition($column, $operator, $value) {
     
      if($column !== null && $value !== null) {
         
         self::createInstance();

         $query = self::$db->select('*')->from(self::$modelTable)->where($column, $operator, $value)->first();
         return $query;
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
         return $instance;
      }
   }
}