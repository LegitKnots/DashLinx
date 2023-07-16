<?php
$msg = "";
$nodisplay = true;
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
$stmt = $con->prepare('SELECT config_password, config_token FROM general');
$stmt->execute();
$stmt->bind_result($psw, $token);
$stmt->fetch();
$stmt->close();
if ($psw == null) {
    $pwsstatop = "On";
    $h2 = "Add A Configuration Password";
} else {
    $pwsstatop = "Off";
    $h2 = "Change Configuration Password";
}

if (isset($_POST['newpsw'])) {
    $newpsw = password_hash($_POST['newpsw'], PASSWORD_DEFAULT);
    $stmt = $con->prepare('UPDATE general SET config_password=?');
    $stmt->bind_param('s', $newpsw);
    $stmt->execute();
    $stmt->close();

    $token = bin2hex(random_bytes(32));
    $stmt = $con->prepare('UPDATE general SET config_token=?');
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->close();
    $expire_time = time() + (60 * 60 * 24 * 14);
    setcookie('config_token', $token, $expire_time, '', '', true, true);

    header("Refresh: 0");
}

if (isset($_POST['chng_status'])) {
    $null = null;
    $stmt = $con->prepare('UPDATE general SET config_password=?, config_token=?');
    $stmt->bind_param('ss', $null, $null);
    $stmt->execute();
    $stmt->close();
    $expire_time = time() - 3600;
    setcookie('config_token', $null, $expire_time, '', '', true, true);

    header("Refresh: 0");
}
?>
<div class="button-form">
    <h2><?=$h2?></h2>
    <form id="chng_psw" method="POST" action="">
        <input type="password" name="newpsw" placeholder="Password">
        <input type="submit" name="chng_psw" id="<?=$pwsstatop?>" value="Update"><br>

        <?php if ($psw != null) {?>
        <input type="submit" name="chng_status" id="<?=$pwsstatop?>" value="Turn <?=$pwsstatop?>">
        <?php } ?>

    </form>
</div>
