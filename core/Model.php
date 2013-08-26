<?php

class Model
{

    private $db;
    private $tables;

    function __construct($db)
    {
        $this->db = $db;
        $this->tables = array();
    }

    public function getTableStructure()
    {
        $sql = 'SHOW TABLES';
        $set = $this->db->query($sql);
        $this->tables = array();
        $result = array('tables', 'fk');
        if ($set) {
            while ($table = $this->db->fetchArray($set)) {

                $description = $this->db->query('DESCRIBE ' . $table[0]);

                while ($field = $this->db->fetchAssoc($description)) {
                    $properties = array();
                    foreach ($field as $key => $value) {
                        $properties[$key] = $value;
                    }
                    $this->tables[$table[0]][] = $properties;
                }
            }
        }
        $result['tables'] = $this->tables;
        $result['fk'] = $this->getFK();

        return $result;
    }

    public function generateData($rows = 10)
    {
        if (empty($this->tables)) {
            $this->tables = $this->getTableStructure();
        }
        return array();
    }

    public function getData($rows = 0)
    {
        return array();
    }


    private function getFK()
    {
        $dbname = $this->db->getDbname();
        $sql = "select
                    concat(table_name, '.', column_name) as 'Foreign key',
                    concat(referenced_table_name, '.', referenced_column_name) as 'References'
                from
                    information_schema.key_column_usage
                where
                    referenced_table_name is not null
                and table_schema = '{$dbname}'";
        $res = $this->db->query($sql);
        $fk = array();
        while ($row = $this->db->fetchAssoc($res)) {
            $fk[] = $row;
        }
        return $fk;
    }
}