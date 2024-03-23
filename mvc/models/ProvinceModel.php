<?php
class ProvinceModel
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new ProvinceModel();
        }

        return self::$instance;
    }

    public function getAll()
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM province";
        $result = mysqli_query($db->con, $sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
