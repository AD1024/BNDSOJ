<?php
if(!isSuperUser(Auth::user())){
	Become404Page();
}
if(!isset($_POST['data'])) {
    echo json_encode(array("success" => false, "message" => "没有数据..."));
    die('Missing parameter');
}

$username = $_POST['data']['username'];
$new_password = $_POST['data']['password'];
$raw_query = 'SELECT * FROM user_info WHERE username='.$username;
$qry_data = DB::query(DB::escape($raw_query));
if(!$qry_data) {
    echo json_encode(array("success" => false, "message" => "未找到用户".$username));
    die();
} else {
    $ret_data = mysql_fetch_assoc($qry_data);
    if(count($ret_data) > 0) {
        if(validatePassword($new_password) && validateUsername($username)) {
            $password = md5($new_password, getPasswordClientSalt());
            $raw_query = 'UPDATE user_info SET password={$password} WHERE username={$username}';
            echo json_encode(array("success" => true, "message" => "修改成功！"));
        } else {
            echo json_encode(array("success" => false, "message" => "用户名或密码格式不可用"));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "未找到用户".$username));
        die();
    }
}
?>