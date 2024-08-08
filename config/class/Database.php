<?php
require_once realpath(__DIR__ . '/../connect.php');
require_once realpath(__DIR__ . "/../functions/functions.php");
require_once realpath(__DIR__ . '/../config.php');


class Database
{

    private $server;
    private $username;
    private $password;
    private $dbname;
    private $conn;

    public function __construct()
    {
        $this->server = $_ENV['DATABASE_SERVER'];
        $this->username = $_ENV['DEV_DATABASE_USERNAME'];
        $this->password = $_ENV['DEV_DATABASE_PASSWORD'];
        $this->dbname = $_ENV['DEV_DATABASE_NAME'];
    }

    public function connect()
    {
        $this->conn = NULL;
        $this->conn = new mysqli(
            $this->server,
            $this->username,
            $this->password,
            $this->dbname
        );

        if ($this->conn->connect_error) {

            echo "Connection Error:" . $this->conn->connect_error;
            exit;
        }
        return $this->conn;
    }


    public function deleteOne($conn, $table, $col, $value, $addons = "")
    {
        $query = "DELETE FROM $table WHERE $col = ? $addons";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $value);
        if ($stmt->execute()) {
            return true;
        }
        return false;
        $conn->close();
        $stmt->close();
    }

    public function selectAll($conn, $table, $addons = "")
    {
        $query = "SELECT * FROM $table $addons";
        $stmt = $conn->prepare($query);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return array(
                    "code" => 200,
                    "data" => $data,
                    "total" => count($data)

                );
            } else {
                // Returned no results
                return array(
                    "code" => 404,
                    "data" => array(),
                    "total" => 0
                );
            }
        }
        // Query failed - Internal error
        return array(
            "code" => 500,
            "data" => array(),
            "total" => 0
        );
        $conn->close();
        $stmt->close();
    }
    public function selectSpecific($conn, $table, $col, $value, $addons = "", $not = "")
    {
        $sign = "=";
        if ($not) {
            $sign = "!=";
        }

        $query = "SELECT * FROM $table WHERE $col $sign ?  $addons";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $value);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return array(
                    "code" => 200,
                    "data" => $data,
                    "total" => count($data)
                );
            } else {
                // Returned no results
                return array(
                    "code" => 404,
                    "data" => array(),
                    "total" => 0
                );
            }
        }
        // Query failed - Internal error
        return array(
            "code" => 500,
            "data" => array(),
            "total" => 0
        );
        $conn->close();
        $stmt->close();
    }

    public function selectMultiCols($conn, $table, $cols, $values, $addons = "")
    {
        // Ensure the columns and values are arrays and have the same length
        if (is_array($cols) && is_array($values) && count($cols) === count($values)) {
            // Start building the query
            $query = "SELECT * FROM $table WHERE ";

            // Add each column with a placeholder for binding
            $conditions = [];
            foreach ($cols as $col) {
                $conditions[] = "$col = ?";
            }
            // Join all conditions with 'AND'
            $query .= implode(' AND ', $conditions) . " " . $addons;

            // Prepare the statement
            $stmt = $conn->prepare($query);


            // Create the types string for bind_param
            $types = "";
            foreach ($values as $item) {
                $types .= variableType($item)['sym'];
            }


            // Use call_user_func_array to bind parameters dynamically
            $stmt->bind_param($types, ...$values);


            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $data = [];
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                    return array(
                        "code" => 200,
                        "data" => $data,
                        "total" => count($data)
                    );
                } else {
                    // Returned no results
                    return array(
                        "code" => 404,
                        "data" => array(),
                        "total" => 0
                    );
                }
            } else {
                // Query failed - Internal error
                return array(
                    "code" => 500,
                    "data" => array(),
                    "total" => 0
                );
            }

            $stmt->close();
            $conn->close();
        } else {
            // Invalid input
            return array(
                "code" => 400,
                "data" => array(),
                "total" => 0,
                "message" => "Columns and values must be arrays of the same length."
            );
        }
    }

    public function selectMultiColsWithJoin($conn, $table, $cols, $values, $joinTables = [], $joinConditions = [], $addons = "")
    {
        // Ensure the columns and values are arrays and have the same length
        if (is_array($cols) && is_array($values) && count($cols) === count($values)) {
            // Start building the query
            $query = "SELECT * FROM $table";

            // Add JOIN clauses if provided
            if (!empty($joinTables) && !empty($joinConditions)) {
                foreach ($joinTables as $index => $joinTable) {
                    $condition = isset($joinConditions[$index]) ? $joinConditions[$index] : '';
                    $query .= " JOIN $joinTable ON $condition";
                }
            }

            // Add WHERE conditions
            $query .= " WHERE ";
            $conditions = [];
            foreach ($cols as $col) {
                $conditions[] = "$col = ?";
            }
            // Join all conditions with 'AND'
            $query .= implode(' AND ', $conditions) . " " . $addons;

            // Prepare the statement
            $stmt = $conn->prepare($query);

            // Create the types string for bind_param
            $types = "";
            foreach ($values as $item) {
                $types .= variableType($item)['sym'];
            }

            // Use call_user_func_array to bind parameters dynamically
            $stmt->bind_param($types, ...$values);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $data = [];
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                    return [
                        "code" => 200,
                        "data" => $data,
                        "total" => count($data)
                    ];
                } else {
                    // Returned no results
                    return [
                        "code" => 404,
                        "data" => [],
                        "total" => 0
                    ];
                }
            } else {
                // Query failed - Internal error
                return [
                    "code" => 500,
                    "data" => [],
                    "total" => 0
                ];
            }

            $stmt->close();
            $conn->close();
        } else {
            // Invalid input
            return [
                "code" => 400,
                "data" => [],
                "total" => 0,
                "message" => "Columns and values must be arrays of the same length."
            ];
        }
    }

    function insert($conn, $tableName, $fields, $values)
    {


        // Generate placeholders for prepared statement
        $placeholders = implode(', ', array_fill(0, count($fields), '?'));
        $fieldsStr = implode(', ', $fields);

        // Prepare the SQL statement
        $sql = "INSERT INTO $tableName ($fieldsStr) VALUES ($placeholders)";
        $stmt = $conn->prepare($sql);


        // Create the types string for bind_param
        $types = "";
        foreach ($values as $item) {
            $types .= variableType($item)['sym'];
        }


        // Use call_user_func_array to bind parameters dynamically
        $stmt->bind_param($types, ...$values);

        // Execute the statement
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }

    function update($conn, $tableName, $fieldsValues, $condition, $conditionValues, $addons = "")
    {


        // Generate field placeholders for prepared statement
        $fields = array_keys($fieldsValues);
        $placeholders = implode(' = ?, ', $fields) . ' = ?';

        // Combine field values and condition values
        $values = array_values($fieldsValues);
        $values = array_merge($values, $conditionValues);

        // Prepare the SQL statement
        $sql = "UPDATE $tableName SET $placeholders WHERE $condition $addons";
        $stmt = $conn->prepare($sql);
        // Create the types string for bind_param
        $types = str_repeat('s', count($values));

        // Use call_user_func_array to bind parameters dynamically
        $stmt->bind_param($types, ...$values);

        // Execute the statement
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
}
