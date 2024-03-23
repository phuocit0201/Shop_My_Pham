<?php require APP_ROOT . '/views/admin/inc/head.php'; ?>

<body>
    <?php require APP_ROOT . '/views/admin/inc/sidebar.php'; ?>

    <div class="main-content">
        <main>
            <section class="recent">
                <div class="activity-grid">
                    <div class="activity-card">
                        <h3>Chỉnh sửa admin</h3>
                        <div class="form">
                            <form action="<?= URL_ROOT . '/adminManage/edit/' . $data['admin']['id']?>" method="POST" id="form" admin-id = <?=$data['admin']['id']?>>
                                <p class="<?= $data['cssClass'] ?>"><?= isset($data['message']) ? $data['message'] : "" ?></p>
                                <p><input type="text" placeholder="Họ tên" id="fullName" value="<?=$data['admin']['fullName']?>" name="fullName"></p>
                                <p class="error" id="error-fullName"></p>
                                <p><input type="email" placeholder="Email" id="email" value="<?=$data['admin']['email']?>" name="email" ></p>
                                <p class="error" id="error-email"><?= isset($data['messageEmail']) ? $data['messageEmail'] : "" ?></p>
                                <p><input type="text" placeholder="Số điện thoại" id="phone" value="<?=$data['admin']['phone']?>" name="phone" ></p>
                                <p class="error" id="error-phone"><?= isset($data['messagePhone']) ? $data['messagePhone'] : "" ?></p>
                                <p><input type="date" name="dob" id="dob" value="<?=$data['admin']['dob']?>"></p>
                                <p class="error" id="error-dob"></p>
                                <p>
                                  <select name="provinceId" id="ls_province">
                                    <?php foreach ($data['provinceList'] as $value){?>
                                      <?php if ($value['id'] == $data['admin']['provinceId']){ ?>
                                        <option value="<?=$value['id']?>" selected><?=$value['name']?></option>
                                      <?php } else {?>
                                        <option value="<?=$value['id']?>"><?=$value['name']?></option>
                                      <?php }?>
                                    <?php }?>
                                  </select>
                                </p>
                                <p>
                                  <select name="districtId" id="ls_district">
                                    <?php foreach ($data['districtList'] as $value){?>
                                      <?php if ($value['id'] == $data['admin']['districtId']){ ?>
                                        <option value="<?=$value['id']?>" selected><?=$value['name']?></option>
                                      <?php } else {?>
                                        <option value="<?=$value['id']?>"><?=$value['name']?></option>
                                      <?php }?>
                                    <?php }?>
                                  </select>
                                </p>
                                <p>
                                  <select name="wardId" id="ls_ward">
                                    <?php foreach ($data['wardList'] as $value){?>
                                      <?php if ($value['id'] == $data['admin']['wardId']){ ?>
                                        <option value="<?=$value['id']?>" selected><?=$value['name']?></option>
                                      <?php } else {?>
                                        <option value="<?=$value['id']?>"><?=$value['name']?></option>
                                      <?php }?>
                                    <?php }?>
                                  </select>
                                </p>
                                <p><input type="text" id="address" placeholder="Địa chỉ (Số nhà, tên đường,...)" value="<?=$data['admin']['address']?>" name="address"></p>
                                <p class="error" id="error-address"></p>
                                <p><input type="password" id="password" placeholder="Mật khẩu" name="password" ></p>
                                <p class="error" id="error-password"></p>
                                <p><input type="password" id="repassword" placeholder="Nhập lại mật khẩu" name="repassword"></p>
                                <p class="error" id="error-repassword"></p>
                                
                            </form>
                            <p>
                              <a href="<?= URL_ROOT . '/adminManage' ?>" class="back" style="margin-left: 30px;">Trở về</a>
                              <input type="submit" id="submit" value="Lưu">
                            </p>
                        </div>
                    </div>
                </div>
            </section>

        </main>

    </div>
  <script language='javascript' type='text/javascript'>
    function check(input) {
      if (input.value != document.getElementById('password').value) {
        input.setCustomValidity('Password Must be Matching.');
      } else {
        input.setCustomValidity('');
      }
    }
  </script>
  <script>
    $(document).on('change', '#ls_province', function(){
      let provinceId = $(this).val()
      let urlDistrict = '<?=URL_ROOT?>/apiLocal/district/' + provinceId
      $.ajax({
        type: 'GET',
        url: urlDistrict
      }).done((res)=>{
        let list = JSON.parse(res)
        let options = ''
        list.forEach(value => {
          options += `<option value="${value.id}">${value.name}</option>`
        })
        $('#ls_district').html(options)
        let districtId = $('#ls_district').val()
        selectWard(districtId)
      })
    });

    //validate
    $(document).on('click', '#submit', function(e){
      $('#error-email').text('');
      $('#error-phone').text('');
      $('#error-dob').text('');
      $('#error-address').text('');
      $('#error-password').text('');
      $('#error-repassword').text('');
      $('#error-fullName').text('');
      let email = $('#email').val().trim();
      let phone = $('#phone').val().trim();
      let dob = $('#dob').val().trim();
      let address = $('#address').val().trim();
      let password = $('#password').val().trim();
      let repassword = $('#repassword').val().trim();
      let fullName = $('#fullName').val().trim();
      let id = $('#form').attr('admin-id').trim();
      var errors = {};

      if (email.length == 0) {
        errors.email = "Vui lòng nhập email"
      }
      if (phone.length == 0) {
        errors.phone = "Vui lòng nhập số điện thoại"
      }
      if (dob.length == 0) {
        errors.dob = "Vui lòng nhập ngày sinh"
      }
      if (address.length == 0) {
        errors.address = "Vui lòng nhập địa chỉ"
      }
      if (fullName.length == 0) {
        console.log(123)
        errors.fullName = "Vui lòng nhập họ và tên"
      }
      if ((password.length > 0 && password !== repassword) || (repassword.length > 0 && password !== repassword)) {
        errors.repassword = "Mật khẩu không trùng khớp"
      }

      if (Object.values(errors).length > 0) {
        Object.keys(errors).forEach(key => {
          $('#error-' + key).text(errors[key])
        });
        return;
      }
      
      $.ajax({
        type: 'POST',
        url: '<?=URL_ROOT?>/adminManage/checkExistUser',
        data: {
          email: $('#email').val(),
          phone: $('#phone').val(),
          id: id,
          type: 2
        }
      }).done((res)=>{
        res = JSON.parse(res);
        console.log(res)
        if (res.status == 401) {
          Object.keys(res.errors).forEach(key => {
            $('#error-' + key).text(res.errors[key])
          })
        } else if (res.status == 200) {
          $('#form').submit()
        }
      })
    })

    $(document).on('change', '#ls_district', function(){
      let distrctId = $(this).val()
      selectWard(distrctId)
    })

    function selectWard(districtId) {
      let url = '<?=URL_ROOT?>/apiLocal/ward/' + districtId
      getLocal(url, '#ls_ward')
    }

    function getLocal(url, idE)
    {
      $.ajax({
        type: 'GET',
        url: url
      }).done((res)=>{
        let list = JSON.parse(res)
        let options = ''
        list.forEach(value => {
          options += `<option value="${value.id}">${value.name}</option>`
        })
        $(idE).html(options)
      })
    }
  </script>
</body>

</html>