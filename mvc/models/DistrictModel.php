<?php
class DistrictModel
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new DistrictModel();
        }

        return self::$instance;
    }

    public function getByProvince($provinceId)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM district where provinceId = $provinceId";
        $result = mysqli_query($db->con, $sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
