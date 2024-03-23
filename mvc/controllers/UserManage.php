<?php

class UserManage extends ControllerBase
{
    public function index($page = 1)
    {
        // Khởi tạo model
        $user = $this->model("UserManageModel");
        $userList = ($user->getAllAdmin($page['page'] ?? 1))->fetch_all(MYSQLI_ASSOC);
        $countPaging = $user->getCountPaging(8);

        $this->view("admin/userList", [
            "headTitle" => "Quản lý khách hàng",
            "adminList" => $userList,
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
            $userModel = $this->model("userManageModel");
            $data = [
                "fullName" => $_POST['fullName'],
                "email" => $_POST['email'],
                "dob" => $_POST['dob'],
                "address" => $_POST['address'],
                "password" => md5($_POST['password']),
                "roleId" => 2,
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
            $result = $userModel->insert($data);

            if ($result) {
                $this->view("admin/addNewUser", [
                    "headTitle" => "Quản lý khách hàng",
                    "cssClass" => "success",
                    "message" => "Thêm mới thành công!",
                    "provinceList" => $provinceList,
                    "districtList" => $districtList,
                    "wardList" => $warList
                ]);
            } else {
                $this->view("admin/addNewUser", [
                    "headTitle" => "Quản lý khách hàng",
                    "cssClass" => "error",
                    "message" => "Lỗi, vui lòng thử lại sau!",
                    "provinceList" => $provinceList,
                    "districtList" => $districtList,
                    "wardList" => $warList
                ]);
            }
        } else {
            $this->view("admin/addNewUser", [
                "headTitle" => "Thêm mới khách hàng",
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
        $userModel = $this->model("userManageModel");
        $provinceModel = $this->model("ProvinceModel");
        $districtModel = $this->model("DistrictModel");
        $wardModel = $this->model("WardModel");
        // Gọi hàm getByIdAdmin
        $c = $userModel->getByIdAdmin($id);
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
                $this->view("admin/editUser", [
                    "headTitle" => "Xem/Cập nhật khách hàng",
                    "cssClass" => "none",
                    "admin" => $c->fetch_assoc()
                ]);
                return;
            }

            // Gọi hàm update
            $r = $userModel->update($id, $dataForm);

            // Gọi hàm getByIdAdmin
            $new = $userModel->getByIdAdmin($id);
            if ($r) {
                $adminInfo = $new->fetch_assoc();
                $provinceList = $provinceModel->getAll();
                $districtList = $districtModel->getByProvince($adminInfo['provinceId']);
                $warList = $wardModel->getByDistrict($adminInfo['districtId']);
                $this->view("admin/editUser", [
                    "headTitle" => "Xem/Cập nhật khách hàng",
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
                $this->view("admin/editUser", [
                    "headTitle" => "Xem/Cập nhật khách hàng",
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
            $this->view("admin/editUser", [
                "headTitle" => "Xem/Cập nhật khách hàng",
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
            $userModel = $this->model("userManageModel");
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $type = $_POST['type'];
            $id = $_POST['id'];
            $checkEmail = $userModel->checkEmail($email, $type, $id);
            $checkPhone = $userModel->checkPhone($phone, $type, $id);
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
        $product = $this->model("userManageModel");
        $product->delete($id);
        $_SESSION['delete_success'] = 'Xoá khách hàng thành công!';
        $this->redirect("userManage");
    }
}
