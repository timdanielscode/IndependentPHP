<?php
/**
 * Database
 * 
 * @author Tim Daniëls
 */
namespace database;

class DB {

    protected $_pdo, $_error;
    private static $_instance = null;
    public $stmt, $error = false;

    public $query = "";
    public $data;

    /**
     * Creating connection
     * 
     * @param string $host host
     * @param string $user username
     * @param string $password password
     * @param string $db database name
     * @return void
     */
    public function __construct($host, $user, $password, $db) {
        
        try {
            $this->_pdo = new \PDO("mysql:host=$host;dbname=$db", $user, $password);
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Getting values from config.ini file
     * 
     * @return object DB
     */    
    public static function try() {

        $file = '../config/database/config.ini';
        if(file_exists($file)) {
            
            if(!isset(self::$_instance)) {

                $ini = parse_ini_file($file);
                self::$_instance = new DB($ini['host'], $ini['user'], $ini['password'], $ini['db']);
            }
        } 
        return self::$_instance;
    }

    /**
     * Executes sql statement
     * 
     * @param string $sql query
     * @param array $data sql 
     */      
    public function execute_query($sql, $data = null) {

        $this->stmt = $this->_pdo->prepare($sql);
        if($data) {
            if(!$this->stmt->execute($data)) {
                return $this->error = true;            
            } 
        } else {
            $this->stmt->execute();
        }   
        $this->data = null; 
        return $this;
    }

    /**
     * Fetching sql statement in array
     */     
    public function fetch_query() {

        return $this->stmt->fetchAll();
    }

    /**
     * Fetching sql statement
     */     
    public function fetch_query_first() {

        return $this->stmt->fetch();
    }

    /** 
     * Fetching columns
     * Adding SELECT columns to query
     * 
     * @param string $columns name(s)
     * @return object DB
     */    
    public function select($colls) {
    
        $args = func_get_args();
        $columns = implode(',', $args);
        $this->query = "SELECT $columns";

        return $this;
    }

    /** 
     * Fetching all rows from table
     * Adding SELECT * FROM table to query
     * 
     * @param string $table name
     * @return object DB
     */ 
    public function all($table) {

        $this->query = "SELECT * FROM $table";
        return $this;
    }

    /** 
     * Fetching/executing rows on table name
     * Adding FROM table to query
     * 
     * @param string $table name
     * @return object DB
     */ 
    public function from($table) {

        $this->query .= " FROM $table";
        return $this;
    }

    /** 
     * Fetching/executing rows based on condition 
     * Adding WHERE column operator to query
     * ? as placeholder
     * 
     * @param string $column name
     * @param string $operator value
     * @param string $value column
     * @return object DB
     */ 
    public function where($column, $operator, $value) {

        if($this->data !== null) {
            array_push($this->data, $value);
        } else {
            $this->data = array($value);
        }
        $this->query .= " WHERE $column $operator ?";

        return $this;
    }

    /** 
     * Fetching/executing rows on double condition
     * Adding AND column operator to query
     * ? as placeholder
     * 
     * @param string $column name
     * @param string $operator value
     * @param string $value column
     * @return object DB
     */     
    public function and($column, $operator, $value) {

        $this->data[] = $value;
        $this->query .= " AND $column $operator ?";

        return $this;
    }

    /** 
     * Fetching/executing rows on optional second condition
     * Adding OR column operator to query
     * 
     * @param string $column name
     * @param string $operator value
     * @param string $value column
     * @return object DB
     */     
    public function or($column, $operator, $value) {

        $this->data[] = $value;
        $this->query .= " OR $column $operator ?";

        return $this;
    }

    /** 
     * Fetching limited rows based on argument 
     * Adding LIMIT num operator to query
     * 
     * @param mixed int|string $num 
     * @return object DB
     */     
    public function limit($num) {

        $this->query .= " LIMIT $num";
        return $this;
    }

    /** 
     * Inserting rows 
     * Adding INSERT INTO table (columns) VALUES (placeholder) to query
     * where placeholders are ?s
     * 
     * @param string $table name
     * @param array $data column names, column values
     * @return object DB
     */    
    public function insert($table, $data) {

        $this->data = [];
        $columns = [];
        $placeholders = [];

        foreach($data as $key => $val) {
            array_push($columns, $key);
            array_push($placeholders, '?');
            array_push($this->data, $val);
        }

        $columns = implode(',',$columns);
        $placeholders = implode(',',$placeholders);
        $this->query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $this->execute_query($this->query, $this->data);

        return $this;
    }

    /** 
     * Setting table name for set method 
     * Adding UPDATE table to query
     * 
     * @param string $table name
     * @return object DB
     */     
    public function update($table) {

        $this->query = "UPDATE $table";
        return $this;
    }

    /** 
     * Deleting rows 
     * Adding DELETE FROM table to query
     * 
     * @param string $table name
     * @return object DB
     */     
    public function delete($table) {

        $this->query = "DELETE FROM $table";
        return $this;        
    }

    /** 
     * Updating rows
     * Adding SET sets
     * where placeholders are ?s
     * where sets are columns + placeholder
     * 
     * @param array $data column names, column values
     * @return object DB
     */     
    public function set($data) {

        $values = [];
        $sets = [];

        foreach($data as $key => $value) {

            array_push($sets, $key."=? ");
            array_push($values, $value);
        }
        
        $sets = implode(",", $sets);
        $this->data = $values;
        $this->query .= " SET $sets";

        return $this;
    }

    /** 
     * Fetching ordered rows
     * Adding ORDER BY column to query
     * 
     * @param string $column name
     * @return object DB
     */     
    public function order($column) {

        $this->query .= " ORDER BY $column";
        return $this;
    }

    /** 
     * Fetching rows descending
     * Adding DESC to query
     * 
     * @return object DB
     */     
    public function desc() {

        $this->query .= " DESC";
        return $this;
    }

    /** 
     * Setting table to join for on method to fetch
     * Adding INNER JOIN table to query
     * 
     * @param string $table name
     * @return object DB
     */    
    public function join($table) {

        $this->query .= " INNER JOIN $table";
        return $this;
    }

    /** 
     * Fetching rows from two tables 
     * Adding ON col1 $operator col2 to query
     * 
     * @param string $col1 column name
     * @param string $operator value
     * @param string $col2 column name
     * @return object DB
     */    
    public function on($col1, $operator, $col2) {

        $this->query .= " ON $col1 $operator $col2";
        return $this;
    }

    /** 
     * Fetching last id from table 
     * 
     * @param string $table name
     * @return object DB
     */ 
    public function getLastId($table) {

        $this->query = "SELECT id FROM $table ORDER BY id DESC LIMIT 1";
        return $this;
    }

    /** 
     * Fetching/executing raw sql
     * Setting query to giving argument
     * 
     * @param string $sql query
     * @return object DB
     */     
    public function raw($sql) {

        $this->query = "$sql";
        return $this;
    }

    /** 
     * Executing query to fetch rows
     * 
     * @return object DB
     */ 
    public function first() {

        if($this->data) {
            return $this->execute_query($this->query, $this->data)->fetch_query_first();
        } else {
            return $this->execute_query($this->query)->fetch_query_first();
        }
    }

    /** 
     * Executing query to fetch rows in array
     * 
     * @param string $operand value
     * @return object DB
     */        
    public function fetch($operand = null) {

        if($this->data) {
            return $this->execute_query($this->query, $this->data)->fetch_query();
        } else {
            return $this->execute_query($this->query)->fetch_query();
        }
    }

    /** 
     * Getting the actual sql code
     * 
     * @return object DB
     */      
    public function getQuery() {

        return $this->query;
    }

    /** 
     * Executing query
     * 
     * @return object DB
     */    
    public function run() {
        
        return $this->execute_query($this->query, $this->data);
    }
}