<?php

class Auth{
	//セッションに関する処理
	private $authname; //認証情報の格納先
	private $sessname; //セッション名

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
	if($this->sessname !=""){
	session_name($this->sessname);
	}

	//セッション開始
	session_start();
}


//認証情報の確認
public function check(){
	if(isset($_SESSION[$this->get_authname()]) && $_SESSION['userinfo']['id']>=1){
	return true;
	}
}

//パスワードをハッシュして値を返す	
public function get_hashed_password($password){
	$hash = password_hash($password, PASSWORD_BCRYPT);
	return $hash;
}

//パスワードが一致したらtrueを返す
public function check_password($password, $hashed_password){
	if(password_verify($password, $hashed_password)){
	return true;
	}
}

//認証情報の取得
public function auth_ok($userdata){
	$_SESSION[$this->get_authname()] = $userdata;
}

public function auth_no(){
	return'Emailアドレスが未登録、あるいはパスワードが間違っています'."\n";
}

//認証情報の破棄
public function logout(){
$_SESSION = array();

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


