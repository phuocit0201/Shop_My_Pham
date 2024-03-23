<?php
class UserManageModel
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new UserManageModel();
        }

        return self::$instance;
    }

    public function getAllAdmin($page = 1, $total = 8)
    {
        if ($page <= 0) {
            $page = 1;
        }
        $tmp = ($page - 1) * $total;
        $db = DB::getInstance();
        $sql = "SELECT * FROM users where roleID = 2 and delete_flg = 0 LIMIT $tmp,$total";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getById($Id)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM categories WHERE Id='$Id' AND status=1";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getByIdAdmin($Id)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM users where roleID = 2 and delete_flg = 0 and id='$Id'";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function insert($data)
    {
        $fillable = array_keys($data);
        $fillableString = implode(", ", $fillable);
        $valuesString = "";
        foreach($data as $value) {
            $valuesString .= "'$value',";
        }
        $valuesString = trim($valuesString, ',');
        $db = DB::getInstance();
        $sql = "INSERT INTO users($fillableString) VALUES ($valuesString)";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function update($id, $data)
    {
        $valueString = "";
        foreach($data as $key => $value) {
            $valueString .= $key . " = '" . $value . "',";
        }
        $valuesString = trim($valueString, ',');
        $db = DB::getInstance();
        $sql = "UPDATE users SET $valuesString  WHERE id=" . $id;
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getCountPaging($row = 8)
    {
        $db = DB::getInstance();
        $sql = "SELECT COUNT(*) FROM users where roleID = 2 and delete_flg = 0";
        $result = mysqli_query($db->con, $sql);
        if ($result) {
            $totalrow = intval((mysqli_fetch_all($result, MYSQLI_ASSOC)[0])['COUNT(*)']);
            return ceil($totalrow / $row);
        }
        return false;
    }

    public function checkEmail($email, $type, $id) {
        $db = DB::getInstance();
        $conditions = "";
        if ($type == 2) {
            $conditions = "and id != $id";
        }
        $sql = "SELECT * FROM users where email = '$email' and delete_flg = 0 $conditions";
        $result = mysqli_query($db->con, $sql);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows > 0) {
            return true;
        }

        return false;
    }

    public function checkPhone($phone, $type, $id) {
        $db = DB::getInstance();
        $conditions = "";
        if ($type == 2) {
            $conditions = "and id != $id";
        }
        $sql = "SELECT * FROM users where phone = '$phone' and delete_flg = 0 $conditions";
        $result = mysqli_query($db->con, $sql);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows > 0) {
            return true;
        }

        return false;
    }

    public function delete($id)
    {
        $db = DB::getInstance();
        $sql = "UPDATE users SET delete_flg = 1 WHERE id=" . $id;
        $result = mysqli_query($db->con, $sql);
        return $result;
    }
}
