<?php require APP_ROOT . '/views/admin/inc/head.php'; ?>

<body>
    <?php require APP_ROOT . '/views/admin/inc/sidebar.php'; ?>

    <div class="main-content">
        <main>
            <section class="recent">
                <div class="activity-grid">
                    <div class="activity-card">
                        <a href="<?= URL_ROOT . '/categoryManage/add' ?>" class="button right">Thêm mới</a>
                        <h3>Danh sách danh mục</h3>
                        <p style="padding-left: 16px; color:green; padding-bottom: 16px"><?php if (isset($_SESSION['delete_success'])) echo $_SESSION['delete_success']; unset($_SESSION['delete_success']);  ?></p>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên danh mục</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 0;
                                    foreach ($data['categoryList'] as $key => $value) {
                                    ?>
                                        <tr>
                                            <td><?= ++$count ?></td>
                                            <td><?= $value['name'] ?></td>
                                            <?php
                                            if ($value['status']) { ?>
                                                <td><span class="active">Kích hoạt</span></td>
                                            <?php } else { ?>
                                                <td><span class="block">Khóa</span></td>
                                            <?php }
                                            ?>
                                            <td>
                                                <?php
                                                if ($value['status']) { ?>
                                                    <a class="button-red" href="<?= URL_ROOT . '/categoryManage/changeStatus/' . $value['id'] ?>">Khóa</a>
                                                <?php } else { ?>
                                                    <a class="button-green" href="<?= URL_ROOT . '/categoryManage/changeStatus/' . $value['id'] ?>">Mở</a>
                                                <?php }
                                                ?>
                                                </a>
                                                <a class="button-normal" href="<?= URL_ROOT . '/categoryManage/edit/' . $value['id'] ?>">Chi tiết/Sửa</a>
                                                <a class="button-red" href="<?= URL_ROOT . '/categoryManage/delete/' . $value['id'] ?>">Xóa</a>
                                            </td>
                                        </tr>
                                    <?php }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination">
                            <a href="<?= URL_ROOT ?>/categoryManage?page=<?= (isset($_GET['page'])) ? (($_GET['page'] <= 1) ? 1 : $_GET['page'] - 1) : 1 ?>">&laquo;</a>
                            <?php
                            for ($i = 1; $i <= $data['countPaging']; $i++) {
                                if (isset($_GET['page'])) {
                                    if ($i == $_GET['page']) { ?>
                                        <a class="active" href="<?= URL_ROOT ?>/categoryManage?page=<?= $i ?>"><?= $i ?></a>
                                    <?php } else { ?>
                                        <a href="<?= URL_ROOT ?>/categoryManage?page=<?= $i ?>"><?= $i ?></a>
                                    <?php  }
                                } else {
                                    if ($i == 1) { ?>
                                        <a class="active" href="<?= URL_ROOT ?>/categoryManage?page=<?= $i ?>"><?= $i ?></a>
                                    <?php  } else { ?>
                                        <a href="<?= URL_ROOT ?>/categoryManage?page=<?= $i ?>"><?= $i ?></a>
                                    <?php   } ?>
                                <?php  } ?>
                            <?php }
                            ?>
                            <a href="<?= URL_ROOT ?>/categoryManage?page=<?= (isset($_GET['page'])) ? ($_GET['page'] == $data['countPaging'] ? $_GET['page'] : $_GET['page'] + 1) : 2 ?>">&raquo;</a>
                        </div>
                    </div>
                </div>
            </section>

        </main>

    </div>
</body>

</html>