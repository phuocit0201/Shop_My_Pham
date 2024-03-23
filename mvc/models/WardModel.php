<?php
class WardModel
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new WardModel();
        }

        return self::$instance;
    }

    public function getByDistrict($districtId)
    {
        $db = DB::getInstance();
        $sql = "SELECT * FROM ward where districtId = $districtId";
        $result = mysqli_query($db->con, $sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
