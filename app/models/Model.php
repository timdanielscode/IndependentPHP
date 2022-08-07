<?php
/**
 * Model
 * 
 * @author Tim Daniëls
 */

namespace app\models;

use database\DB;

class Model extends DB {

   public static $db;
   public static $modelTable;

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
}