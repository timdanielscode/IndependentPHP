<?php

namespace database;

class DB {

    private $_pdo, $_stmt, $_query = "", $_data = [], $_columns = [], $_placeholders = [], $_setValues = [];

    /**
     * To create a database connection
     * 
     * @param string $host host
     * @param string $user username
     * @param string $password password
     * @param string $db database name
     */
    public function __construct($host, $user, $password, $db) {

        try {
            $this->_pdo = new \PDO("mysql:host=$host;dbname=$db", $user, $password);
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * To get database credentials from config.ini file (to create a connection)
     * 
     * @return object DB
     */    
    public static function try() {

        if(file_exists('../config/database/config.ini') ) {
            
            $ini = parse_ini_file('../config/database/config.ini');
            return new DB($ini['host'], $ini['user'], $ini['password'], $ini['db']);
        } 
    }

    /**
     * To run sql queries
     * 
     * @param string $sql query
     * @param array $data sql 
     */      
    private function execute_query() {

        $this->_stmt = $this->_pdo->prepare($this->_query);
    
        foreach ($this->_data as $key => $value) {

            if (is_int($value) === true) {
                
                $type = \PDO::PARAM_INT;
            } else {
                $type = \PDO::PARAM_STR;
            }

            $this->_stmt->bindParam($key + 1, $this->_data[$key], $type);
        }
    
        $this->_stmt->execute();
        $this->_data = [];
    
        return $this;
    }

    /**
     * To fetch sql queries
     */     
    private function fetch_query() {

        return $this->_stmt->fetchAll();
    }

    /**
     * To fetch sql queries but fetching first row
     */     
    private function fetch_query_first() {

        return $this->_stmt->fetch();
    }

    /** 
     * To add SELECT columns to query
     * 
     * @param string $columns name(s)
     * @return object DB
     */    
    public function select($colls) {
    
        $columns = implode(',', func_get_args());
        $this->_query = "SELECT $columns";

        return $this;
    }

    /** 
     * To add SELECT * FROM table to query
     * 
     * @param string $table name
     * @return object DB
     */ 
    public function all($table) {

        $this->_query = "SELECT * FROM $table";
        return $this;
    }

    /** 
     * To add FROM table to query
     * 
     * @param string $table name
     * @return object DB
     */ 
    public function from($table) {

        $this->_query .= " FROM $table";
        return $this;
    }

    /** 
     * To add WHERE column operator to query
     * 
     * @param string $column name
     * @param string $operator value
     * @param string $value column
     * @return object DB
     */ 
    public function where($column, $operator, $value) {

        $this->_data[] = $value;
        $this->_query .= " WHERE $column $operator ?";

        return $this;
    }

    /** 
     * To add WHERE column operator to query
     * 
     * @param string $column name
     * @param string $operator value
     * @param string $value column
     * @return object DB
     */ 
    public function whereNot($column, $operator, $value) {

        $this->_data[] = $value;
        $this->_query .= " WHERE NOT $column $operator ?";

        return $this;
    }    

    /** 
     * To add WHERE column NOT IN values to query
     * 
     * @param string $column name
     * @param array $values column values
     * @return object DB
     */ 
    public function whereNotIn($column, $values) {

        $this->_query .= " WHERE $column NOT IN ($values)";

        return $this;
    }

    /** 
     * To add AND column operator to query
     * 
     * @param string $column name
     * @param string $operator value
     * @param string $value column
     * @return object DB
     */     
    public function and($column, $operator, $value) {

        $this->_data[] = $value;
        $this->_query .= " AND $column $operator ?";

        return $this;
    }

    /** 
     * To add OR column operator to query
     * 
     * @param string $column name
     * @param string $operator value
     * @param string $value column
     * @return object DB
     */     
    public function or($column, $operator, $value) {

        $this->_data[] = $value;
        $this->_query .= " OR $column $operator ?";

        return $this;
    }

    /** 
     * To add LIMIT num operator to query
     * 
     * @param mixed int|string $num 
     * @return object DB
     */     
    public function limit($num) {

        $this->_query .= " LIMIT $num";
        return $this;
    }

    /** 
     * To add INSERT INTO table (columns) VALUES (placeholder) to query
     * 
     * @param string $table name
     * @param array $data column names, column values
     * @return object DB
     */    
    public function insert($table, $data) {

        foreach($data as $key => $value) {

            $this->_columns[] = $key;
            $this->_placeholders[] = '?';
            $this->_data[] = $value;
        }

        $columns = implode(',',$this->_columns);
        $placeholders = implode(',',$this->_placeholders);

        $this->_query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $this->execute_query();
    }

    /** 
     * To add UPDATE table to query
     * 
     * @param string $table name
     * @return object DB
     */     
    public function update($table) {

        $this->_query = "UPDATE $table";
        return $this;
    }

    /** 
     * To add DELETE FROM table to query
     * 
     * @param string $table name
     * @return object DB
     */     
    public function delete($table) {

        $this->_query = "DELETE FROM $table";
        return $this;        
    }

    /** 
     * To add SET set values to query
     * 
     * @param array $data column names, column values
     * @return object DB
     */     
    public function set($data) {

        foreach($data as $key => $value) {

            $this->_setValues[] = $key . '=?';
            $this->_data[] = $value;
        }
        
        $setValues = implode(",", $this->_setValues);
        $this->_query .= " SET $setValues";

        return $this;
    }

    /** 
     * To add ORDER BY column to query
     * 
     * @param string $column name
     * @return object DB
     */     
    public function order($column) {

        $this->_query .= " ORDER BY $column";
        return $this;
    }

    /** 
     * To add DESC to query
     * 
     * @return object DB
     */     
    public function desc($colunn = null) {

        if(!empty($column) && $column !== null) {

            $this->_query .= " $column DESC";
        } else {
            $this->_query .= " DESC";
        }

        return $this;
    }

    /** 
     * To add ASC to query
     * 
     * @return object DB
     */     
    public function asc($colunn = null) {

        if(!empty($column) && $column !== null) {

            $this->_query .= " $column ASC";
        } else {
            $this->_query .= " ASC";
        }

        return $this;
    }

    /** 
     * To add INNER JOIN table to query
     * 
     * @param string $table name
     * @return object DB
     */    
    public function join($table) {

        $this->_query .= " INNER JOIN $table";
        return $this;
    }

    /** 
     * To add LEFT JOIN table to query
     * 
     * @param string $table name
     * @return object DB
     */    
    public function joinLeft($table) {

        $this->_query .= " LEFT JOIN $table";
        return $this;
    }

    /** 
     * To add ON col1 $operator col2 to query
     * 
     * @param string $col1 column name
     * @param string $operator value
     * @param string $col2 column name
     * @return object DB
     */    
    public function on($col1, $operator, $col2) {

        $this->_query .= " ON $col1 $operator $col2";
        return $this;
    }

    /** 
     * To add SELECT id FROM table ORDER BY id DESC LIMIt 1 to query
     * 
     * @param string $table name
     * @return object DB
     */ 
    public function getLastId($table) {

        $this->_query = "SELECT id FROM $table ORDER BY id DESC LIMIT 1";
        return $this;
    }

    /** 
     * To fetch or run raw sql
     * 
     * @param string $sql query
     * @return object DB
     */     
    public function raw($sql) {

        $this->_query = "$sql";
        return $this;
    }

    /** 
     * To run the execute_query method
     * 
     * @return object DB
     */ 
    public function first() {

        return $this->execute_query($this->_query)->fetch_query_first();
    }

    /** 
     * To run the execute_query method
     * 
     * @param string $operand value
     * @return object DB
     */        
    public function fetch($operand = null) {

        return $this->execute_query($this->_query)->fetch_query();
    }

    /** 
     * To show the sql query
     * 
     * @return object DB
     */      
    public function getQuery() {

        return $this->_query;
    }

    /** 
     * To run the execute_query method
     * 
     * @return object DB
     */    
    public function run() {
        
        return $this->execute_query($this->_query);
    }
}