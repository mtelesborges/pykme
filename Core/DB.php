<?php

class DB
{

    private string $dbHost = "localhost";//"ahovonuf.mysql.db.internal";
    private string $dbUser = "ahovonuf_pykme";
    private string $dbPass = "ichwerdeimmermitgottsein31@";
    private string $dbName = "ahovonuf_pykme";
    public int $insert_id;

    public function query($sql, $params, $close)
    {
        /*
            $sql = Statement to execute;
            $params = array of type and values of the parameters (if any)
            $close = true to close $stmt (in inserts) false to return an array with the values;

            Usage:
            query($sql, $parameters, $close);

            Examples:
            query("INSERT INTO table(id, name) VALUES (?,?)", array('ss', $id, $name), true);
             *
            query(
             *              "SELECT * FROM table WHERE id = ?",
             *              array('i', $id),
             *              false
             *      );

            Credits:  canche_x at yahoo dot com
        */

        // prevent xss
        include_once("Vendors/xss/HTMLPurifier.auto.php");

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        // @TODO: substituir este for in
        for ($i = 1; $i < count($params); $i++) {
            $params[$i] = $purifier->purify($params[$i]);
        }


        $db = $this->launch();
        $db->set_charset('utf8');
        // prepare and bind
        $stmt = $db->prepare($sql);
        call_user_func_array(array($stmt, 'bind_param'), $this->refValues($params));
        $stmt->execute();

        if ($close) {
            $result = $db->affected_rows;
            $this->insert_id = $db->insert_id;
        } else {
            $meta = $stmt->result_metadata();
            $parameters = [];
            $row = [];
            $results = [];

            while ($field = $meta->fetch_field()) {
                $parameters[] = &$row[$field->name];
            }

            call_user_func_array(array($stmt, 'bind_result'), $this->refValues($parameters));

            while ($stmt->fetch()) {
                $x = array();
                foreach ($row as $key => $val) {
                    $x[$key] = $val;
                }
                $results[] = $x;
            }

            $result = $results;
        }

        $stmt->close();
        $db->close();

        return $result;
    }


    private function launch()
    {
        // Connect to databe and return dbObject
        $db = new mysqli($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);

        // Check connection
        if ($db->connect_error) {
            die("DB Error:" . $db->connect_error);
        } else {
            return $db;
        }

    }

    private function refValues($arr)
    {
        if (strnatcmp(phpversion(), '5.3') >= 0) //Reference is required for PHP 5.3+
        {
            $refs = array();
            foreach ($arr as $key => $value)
                $refs[$key] = &$arr[$key];
            return $refs;
        }
        return $arr;
    }

} //End DB
 







