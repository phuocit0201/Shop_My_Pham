<?php
class categoryModel
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new categoryModel();
        }

        return self::$instance;
    }

    public function getAllClient()
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM categories WHERE status=1 and delete_flg = 0";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getAllAdmin($page = 1, $total = 8)
    {
        if ($page <= 0) {
            $page = 1;
        }
        $tmp = ($page - 1) * $total;
        $db = DB::getInstance();
        $sql = "SELECT * FROM categories WHERE delete_flg = 0 LIMIT $tmp,$total";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getById($Id)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM categories WHERE Id='$Id' AND status=1 and delete_flg = 0";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getByIdAdmin($Id)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM categories WHERE Id='$Id' and delete_flg = 0";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function changeStatus($Id)
    {
        $db = DB::getInstance();
        $sql = "UPDATE categories SET status = !status WHERE Id='$Id'";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function insert($name)
    {
        $db = DB::getInstance();
        $sql = "INSERT INTO categories VALUES (NULL, '$name',1, 0)";
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function update($id, $name)
    {
        $db = DB::getInstance();
        $sql = "UPDATE categories SET name = '" . $name . "' WHERE id=" . $id ;
        $result = mysqli_query($db->con, $sql);
        return $result;
    }

    public function getCountPaging($row = 8)
    {
        $db = DB::getInstance();
        $sql = "SELECT COUNT(*) FROM categories where delete_flg = 0";
        $result = mysqli_query($db->con, $sql);
        if ($result) {
            $totalrow = intval((mysqli_fetch_all($result, MYSQLI_ASSOC)[0])['COUNT(*)']);
            return ceil($totalrow / $row);
        }
        return false;
    }

    public function deleteCategory($id)
    {
        $db = DB::getInstance();
        $sql = "UPDATE categories SET delete_flg = 1 WHERE id=" . $id;
        $result = mysqli_query($db->con, $sql);
        return $result;
    }
}
