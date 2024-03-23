<?php

class ApiLocal extends ControllerBase
{
    public function province()
    {
        $provinceModel = $this->model("ProvinceModel");
        $list = $provinceModel->getAll();
        echo json_encode($list);
    }

    public function district($provinceId)
    {
        $districtModel = $this->model("DistrictModel");
        $list = $districtModel->getByProvince($provinceId);
        echo json_encode($list);
    }

    public function ward($districtId)
    {
        $wardModel = $this->model("WardModel");
        $list = $wardModel->getByDistrict($districtId);
        echo json_encode($list);
    }
}