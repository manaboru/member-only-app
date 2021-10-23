<?php

class Auth{
	//セッションに関する処理
	private $authname; //認証情報の格納先
	private $sessname; //セッション名

//public function _construct(){

//}

public function set_authname($name){
	$this->authname=$name;
}

public function get_authname(){
	return $this->authname;
}

public function set_sessname($name){
	$this->sessname=$name;
}

public function get_sessname(){
	return $this->sessname;
}

public function start(){
	//セッションが既に開始している場合には何もしない
//	if(session_status()===PHP_SESSION_ACTIVE){
//	return;
//	}
//	$sessionActive=session_name();
//	if($sessionActive){
//	echo $this->sessname;
//	return;
//	}
	if($this->sessname !=""){
	session_name($this->sessname);
	}

	//セッション開始
	session_start();

}


//認証情報の確認--echo でデバッグできます
public function check(){

	if(isset($_SESSION[$this->get_authname()]) && $_SESSION['userinfo']['id']>=1){
//echo "(Authで出力)".session_id();
	return true;
	}
}

public function get_hashed_password($password){
//コストパラメーター
//04から31までの範囲　大きくなれば堅牢になりますが、システムに負荷がかかる
//$cost =10;
//ランダムな文字列を生成
//$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)),'+','.');

//ソルトを生成
//$salt=sprintf("$2y$%02d$", $cost).$salt;
//$hash = crypt($password, $salt);

$hash = password_hash($password, PASSWORD_BCRYPT);

return $hash;
}




//パスワードが一致したらtrueを返す
public function check_password($password, $hashed_password){
//if(crypt($password, $hashed_password) == $hashed_password){
	if(password_verify($password, $hashed_password)){
	return true;
	}
}

//認証情報の取得
public function auth_ok($userdata){
//	session_regenerate_id(true);
	$_SESSION[$this->get_authname()] = $userdata;
	//echo $data=$this->get_authname();

}

public function auth_no(){
	return'Emailアドレスが未登録、あるいはパスワードが間違っています'."\n";
}

//認証情報の破棄
public function logout(){
//セッション変数を空にする
//$_SESSION=[];//この初期化方式はPHP5.4以降でしか動作しない！
$_SESSION = array();//なのでこの方式でセッション変数を空にする
//クッキーを削除
if(ini_get("session.use_cookies")){
$params=session_get_cookie_params();
setcookie(session_name(),'', time() - 42000,
$params['path'],$params['domain'],$params['secure'],$params['httponly']
);
}

//セッションを破棄
session_destroy();
}



}


