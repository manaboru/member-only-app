<?php

//BaseController.php

class BaseController{
	protected $category;
	protected $type;
	protected $action;
	protected $file;
	protected $form;
	protected $auth;
	protected $is_system=false;
	protected $view;
	protected $message;
	protected $auth_error_mess;
	protected $login_state;
	private $debug_str;

public function __construct($flag=false){
	$this->set_system($flag);
	//viewの準備
	$this->view_initialize();
}

public function set_system($flag){
	$this->is_system=$flag;
}

private function view_initialize(){
	//画面表示クラス
	$this->view = new Smarty;
	
	//Smarty関連ディレクトリの設定
	$this->view->template_dir	=_SMARTY_TEMPLATES_DIR;
	$this->view->compile_dir	=_SMARTY_TEMPLATES_C_DIR;
	$this->view->config_dir		=_SMARTY_CONFIG_DIR;
	$this->view->cache_dir		=_SMARTY_CACHE_DIR;

	//リクエスト変数　typeとactionで動作を決める
	if(isset($_REQUEST['category'])){
	$this->category=$_REQUEST['category'];
	}
	if(isset($_REQUEST['type'])){
	$this->type=$_REQUEST['type'];
	}
	if(isset($_REQUEST['action'])){
	$this->action=$_REQUEST['action'];
	}

	//共通の変数
	$this->view->assign('is_system',$this->is_system);
	$this->view->assign('SCRIPT_NAME',_SCRIPT_NAME);
	$this->view->assign('add_pageID',  $this->add_pageID());

}

//-------------------------------------------------------------
//フォームと変数を読み込んでテンプレートに組み込んで表示します。
//-------------------------------------------------------------
protected function view_display(){
	//ログイン状況の表示
	$this->disp_login_state();
	$this->view->assign('auth_error_mess', $this->auth_error_mess);
	$this->view->assign('disp_login_state',$this->login_state);
	$this->view->assign('debug_str',$this->debug_str);
	$this->view->display($this->file);
}

//-------------------------------------------------------------
//ログイン中の表示。
//-------------------------------------------------------------

private function disp_login_state(){
	$this->auth = new Auth();
	//2017年6月23日ログインステートディスプレイの分岐不備は、下記の2行set_○○の欠如によるものと判明。メソッドに値が入らないので、それをアテにしていたcheck()も機能しなかった
	$this->auth->set_authname(_MEMBER_AUTHINFO);
	$this->auth->set_sessname(_MEMBER_SESSNAME);
	if(is_object($this->auth) && $this->auth->check()){
	$this->login_state = ($this->is_system)?'管理者ログイン中':'ユーザーログイン中';
	}else{
	$this->login_state="ログイン処理が不備";
	}
}


}//----class終了
?>
