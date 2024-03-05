<?php
require 'template_init.php';


if (isset($_POST['id'])) {
    // update student

    if ($_FILES['pfpfile']['size'] > 0) {
        $filename = $_FILES['pfpfile']['name'];
        $tmpname = $_FILES['pfpfile']['tmp_name'];

        resize_image($tmpname, 128, 128, true);

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $newname = md5_file($tmpname);
        move_uploaded_file($tmpname, __DIR__ . "/../avatars/" . $newname . '.jpg');
        $change_profile_pic = ", profile_pic_hash = '$newname'";
    } else {
        $change_profile_pic = '';
    }

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // if change password
    if ($_POST['password'] != "") {
        $change_password = ", password = '" . password_hash($_POST['password'], PASSWORD_DEFAULT) . "'";
    } else {
        $change_password = "";
    }
    $id = $_SESSION['user']['id'];
    $sql = "UPDATE students SET first_name = '$first_name', last_name = '$last_name', email = '$email' " . $change_password . ", phone = '$phone' " . $change_profile_pic . " WHERE id = " . $_POST['id'];
    $db->exec($sql);
	motd('success', 'บันทึกข้อมูลเรียบร้อย');
}
require 'template_header.php';
?>

<style>
    .h-100 {
        height: 67% !important;
    }

    .center-container {
        margin-top: 20px;
        display: flex;
        justify-content: right;
        align-items: right;
    }
</style>

<body>

<?php
if (isset($_SESSION['user']['id'])) {
    $id = $_SESSION['user']['id'];
    {
        // display student detail
        ?>
        <div class="container" style="margin-bottom: 30px;">
            <h1>รายละเอียดนักเรียน</h1>
            <?php
            $student = $db->get_student($id);
            echo "<h2>{$student['name']}</h2>";
            ?>
            <div class="card">
                <div class="card-header">
                    แก้ไขข้อมูล
                </div>
                <div class="card-body">
                    <form action="profEdit.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $student['id'] ?>">
                        <div class="mb-3 row">
                            <div class="col mb-3">
                                <label for="username" class="form-label">ID</label>
                                <input type="text" class="form-control" id="id" name="id" value="<?php echo $student['id'] ?>" disabled>
                            </div>
                            <div class="col mb-3">
                                <label for="username" class="form-label">ชื่อผู้ใช้</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo $student['username'] ?>" disabled>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">รหัสผ่านใหม่</label>
                            <div class="mb-3 row">
                                <div class="col">
                                    <input class="form-control" id="password-input" name="password">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col mb-3">
                                <label for="first_name" class="form-label">ชื่อ</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $student['first_name'] ?>">
                            </div>
                            <div class="col mb-3">
                                <label for="last_name" class="form-label">นามสกุล</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $student['last_name'] ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">อีเมล</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $student['email'] ?>">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">เบอร์โทร</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $student['phone'] ?>">
                        </div>
                        <div class="mb-3">
                            <label for="pfplink" class="form-label">รูปโปรไฟล์</label>
                            <input type="file" class="form-control mb-4" id="pfpFile" name="pfpfile" accept="image/jpeg">
                            <img src="<?php echo $student['pfplink'] ?>" alt="avatar" width="64" height="64" class="rounded-circle me-4">
                        </div>
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
}
require 'template_footer.php';
?>
