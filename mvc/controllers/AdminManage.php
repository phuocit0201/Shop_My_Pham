<?php

class AdminManage extends ControllerBase
{
    public function index($page = 1)
    {
        // Khởi tạo model
        $admin = $this->model("adminModel");
        $adminList = ($admin->getAllAdmin($page['page'] ?? 1))->fetch_all(MYSQLI_ASSOC);
        $countPaging = $admin->getCountPaging(8);

        $this->view("admin/adminList", [
            "headTitle" => "Quản lý admin",
            "adminList" => $adminList,
            'countPaging'=>$countPaging
        ]);
    }

    public function add()
    {
        $provinceModel = $this->model("ProvinceModel");
        $districtModel = $this->model("DistrictModel");
        $wardModel = $this->model("WardModel");
        $provinceList = $provinceModel->getAll();
        $districtList = $districtModel->getByProvince($provinceList[0]['id']);
        $warList = $wardModel->getByDistrict($districtList[0]['id']);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Khởi tạo model
            $adminModel = $this->model("adminModel");
            $data = [
                "fullName" => $_POST['fullName'],
                "email" => $_POST['email'],
                "dob" => $_POST['dob'],
                "address" => $_POST['address'],
                "password" => md5($_POST['password']),
                "roleId" => 1,
                "status" => 1,
                "captcha" => NULL,
                "isConfirmed" => 1,
                "phone" => $_POST['phone'],
                "provinceId" => $_POST['provinceId'],
                "districtId" => $_POST['districtId'],
                "wardId" => $_POST['wardId'],
                "delete_flg" => 0
            ];
            
            // Gọi hàm insert để thêm mới vào csdl
            $result = $adminModel->insert($data);

            if ($result) {
                $this->view("admin/addNewAdmin", [
                    "headTitle" => "Quản lý admin",
                    "cssClass" => "success",
                    "message" => "Thêm mới thành công!",
                    "provinceList" => $provinceList,
                    "districtList" => $districtList,
                    "wardList" => $warList
                ]);
            } else {
                $this->view("admin/addNewAdmin", [
                    "headTitle" => "Quản lý admin",
                    "cssClass" => "error",
                    "message" => "Lỗi, vui lòng thử lại sau!",
                    "provinceList" => $provinceList,
                    "districtList" => $districtList,
                    "wardList" => $warList
                ]);
            }
        } else {
            $this->view("admin/addNewAdmin", [
                "headTitle" => "Thêm mới admin",
                "cssClass" => "none",
                "provinceList" => $provinceList,
                "districtList" => $districtList,
                "wardList" => $warList
            ]);
        }
    }

    public function edit($id = "")
    {
        // Khởi tạo models
        $adminModel = $this->model("adminModel");
        $provinceModel = $this->model("ProvinceModel");
        $districtModel = $this->model("DistrictModel");
        $wardModel = $this->model("WardModel");
        // Gọi hàm getByIdAdmin
        $c = $adminModel->getByIdAdmin($id);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dataForm = [];
            foreach($_POST as $key => $value) {
                if (!empty($value) && $key != 'repassword') {
                    if ($key == 'password') {
                        $dataForm[$key] = md5($value);
                    } else {
                        $dataForm[$key] = $value;
                    }
                }
            }
            if (empty($dataForm)) {
                $this->view("admin/editAdmin", [
                    "headTitle" => "Xem/Cập nhật admin",
                    "cssClass" => "none",
                    "admin" => $c->fetch_assoc()
                ]);
                return;
            }

            // Gọi hàm update
            $r = $adminModel->update($id, $dataForm);

            // Gọi hàm getByIdAdmin
            $new = $adminModel->getByIdAdmin($id);
            if ($r) {
                $adminInfo = $new->fetch_assoc();
                $provinceList = $provinceModel->getAll();
                $districtList = $districtModel->getByProvince($adminInfo['provinceId']);
                $warList = $wardModel->getByDistrict($adminInfo['districtId']);
                $this->view("admin/editAdmin", [
                    "headTitle" => "Xem/Cập nhật admin",
                    "cssClass" => "success",
                    "message" => "Cập nhật thành công!",
                    "admin" => $adminInfo,
                    "provinceList" => $provinceList,
                    "districtList" => $districtList,
                    "wardList" => $warList
                ]);
            } else {
                $adminInfo = $new->fetch_assoc();
                $provinceList = $provinceModel->getAll();
                $districtList = $districtModel->getByProvince($adminInfo['provinceId']);
                $warList = $wardModel->getByDistrict($adminInfo['districtId']);
                $this->view("admin/editAdmin", [
                    "headTitle" => "Xem/Cập nhật admin",
                    "cssClass" => "error",
                    "message" => "Lỗi, vui lòng thử lại sau!",
                    "admin" => $adminInfo,
                    "provinceList" => $provinceList,
                    "districtList" => $districtList,
                    "wardList" => $warList
                ]);
            }
        } else {
            $adminInfo = $c->fetch_assoc();
            $provinceList = $provinceModel->getAll();
            $districtList = $districtModel->getByProvince($adminInfo['provinceId']);
            $warList = $wardModel->getByDistrict($adminInfo['districtId']);
            $this->view("admin/editAdmin", [
                "headTitle" => "Xem/Cập nhật admin",
                "cssClass" => "none",
                "admin" => $adminInfo,
                "provinceList" => $provinceList,
                "districtList" => $districtList,
                "wardList" => $warList
            ]);
        }
    }

    public function checkExistUser()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminModel = $this->model("adminModel");
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $type = $_POST['type'];
            $id = $_POST['id'] ?? null;
            $checkEmail = $adminModel->checkEmail($email, $type, $id);
            $checkPhone = $adminModel->checkPhone($phone, $type, $id);
            $errors = [];
            if ($checkEmail) {
                $errors['email'] = "Email này đã có người khác sử dụng";
            }
    
            if ($checkPhone) {
                $errors['phone'] = "Số điện thoại này đã có người khác sử dụng";
            }
    
            if (empty($errors)) {
                echo json_encode(['status' => "200"]);
                exit();
            }
            echo json_encode(['status' => "401", 'errors' => $errors]);
            exit();
        }
    }

    public function delete($id)
    {
        $product = $this->model("adminModel");
        $product->delete($id);
        $_SESSION['delete_success'] = 'Xoá admin thành công!';
        $this->redirect("adminManage");
    }
}
