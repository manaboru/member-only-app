<?php

class SystemController extends BaseController {
	
//***************************************************
//　ユーザーかゲストかの判定
//***************************************************
public function run(){

	//セッション開始　認証に利用します
	$this->auth = new Auth();
	$this->auth->set_authname(_MEMBER_AUTHINFO);
	$this->auth->set_sessname(_MEMBER_SESSNAME);
	$this->auth->start();

	//この段階で認証チェックをかけている。ログイン後の遷移は全てここでtrueとならなければlogin画面に行く
	if(!$this->auth->check() && $this->type != 'authenticate'){
		//未認証
		$this->type='login';
	}


//***************************************************
//　メニュー分岐
//***************************************************

	//問題はタイプ。タイプがあれば以下のswitch文の判定に行く。これはdispacherとしてはどうなんだろう？
	if(isset($this->type) || !isset($this->action)){
		switch($this->type){
			case 'login':
				$this->screen_login();
				break;
			case 'logout':
				$this->auth->logout();
				$this->screen_login();
				break;
			case 'authenticate':
				$this->do_authenticate();
				break;
			default:
				$dispatcher = new Dispatcher();
				$dispatcher->runBranch($_SESSION['role']);
				}
	}

}//run method ends

//***************************************************
//　ログイン画面表示:screen_login()
//***************************************************
public function screen_login(){

	$this->title='ログイン画面';
	$this->next_type='authenticate';
	$this->file='page.login.tpl';

//	$arr=array('title'=>$this->title,'type'=>$this->next_type);
//	$view= new view_object();
//	$view->screen($arr,$this->file);

	$this->view->assign('title', $this->title);
	$this->view->assign('error_mess', $this->auth_error_mess);
	$this->view->assign('type', $this->next_type);
	$this->file=$this->file;
	$this->view_display();

}

//***************************************************
//　認証⇒画面出力:do_authenticate()
//	OK:分岐スクリプトへ
//	NG:ログイン画面(screen_login())表示
//***************************************************
public function do_authenticate(){
	//データベースを操作します。
	$systemModel = new SystemModel();
	$userdata = $systemModel->get_authinfo($_POST['username']);//ログイン画面で入力されたユーザー名（Email）をもってDBに問い合わせ。結果配列を$userdataに格納。

	//passwordが入力されていて、かつ、パスワードが一致したときのtrueであれば
	if(!empty($userdata['pwd']) && $this->auth->check_password($_POST['password'],$userdata['pwd'])){

		//ユーザー情報を投入し、authから認証情報を取得する：セッションIDを新たに生成＆
		$this->auth->auth_ok($userdata);

		//ログインチケット用にPOSTデータをセッション変数に格納しておく
		$_SESSION['username']=$_POST['username'];

		//ユーザー権限をセッション変数に格納しておく
		$_SESSION['role'] = $userdata['role'];

		//利用ユーザーによりトップページの分岐.ここを削除すると画面が白くなる。
		$dispatcher = new Dispatcher();
		$dispatcher->runBranch($userdata['role']);//初ログイン時はここからリダイレクト。

	}else{
	$this->auth_error_mess = $this->auth->auth_no();
	$this->screen_login();
	}
}


//---class終了
}
?>
