<?php
/**
 * @file
 * Base class for all table classes
 */
namespace Enigma;

/**
 * Base class for all table classes
 */
class Table {
    /**
     * Constructor
     * @param Site $site The site object
     * @param $name The base table name
     */
    public function __construct(Site $site, $name) {
        $this->site = $site;
        $this->tableName = $site->getTablePrefix() . $name;
    }

    /**
     * Get the database table name
     * @return The table name
     */
    public function getTableName() {
        return $this->tableName;
    }

    /**
     * Diagnostic routine that substitutes into an SQL statement
     * @param $query The queuy with : or ? parameters
     * @param $params The arguments to substitute (what you pass to execute)
     * @return string SQL statement with substituted values
     */
    public function sub_sql($query, $params) {
        $keys = array();
        $values = array();

        // build a regular expression for each parameter
        foreach ($params as $key => $value) {
            if (is_string($key)) {
                $keys[] = '/:' . $key . '/';
            } else {
                $keys[] = '/[?]/';
            }

            if (is_numeric($value)) {
                $values[] = intval($value);
            } else {
                $values[] = '"' . $value . '"';
            }
        }

        $query = preg_replace($keys, $values, $query, 1, $count);
        return $query;
    }

    /**
     * Database connection function
     * @returns PDO object that connects to the database
     */
    public function pdo() {
        return $this->site->pdo();
    }

    protected $site;        ///< The Site object
    protected $tableName;   ///< The table name to use
}