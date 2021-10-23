<?php

//BaseController.php

class BaseController{
	protected $category;
	protected $type;
	protected $action;
	protected $next_type;
	protected $next_action;
	protected $file;
	protected $form;
	//protected $renderer;
	protected $auth;
	protected $is_system=false;
	protected $view;
	protected $title;
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

	//入力チェック用フラグ
	//$this->form = new HTML_QuickForm();
	//JavaScriptのメッセージを日本語に修正
	//$this->formsetJsWarnings("入力エラーです。","上記項目を修正してください")；

	//HTML_QickFormとSmartyを使うためのクラス
	//$this->rernderer = new HTML_QuickForm_Renderer_ArraySmarty($this->view);

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
	//セッション変数などの内容の表示
//	$this->debug_display();//このメソッドは、このファイルにこの後追加するものなので、とりあえずキャンセル

//	$this->view->assign('lastname',$_SESSION[_MEMBER_AUTHINFO]['lastname']);
//	$this->view->assign('firstname',$_SESSION[_MEMBER_AUTHINFO]['firstname']);

	//ログイン状況の表示
	$this->disp_login_state();

//	$this->view->assign('title',$this->title);
	$this->view->assign('auth_error_mess', $this->auth_error_mess);
//	$this->view->assign('message',$this->message);
	$this->view->assign('disp_login_state',$this->login_state);
//	$this->view->assign('category',$this->next_category);
//	$this->view->assign('type',$this->next_type);
//	$this->view->assign('action',$this->next_action);
	$this->view->assign('debug_str',$this->debug_str);
	//$this->form->accept($this->renderer);
//	$this->view->assign('form',$this->renderer->toArray());
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

//----------------------------------------------------
// 検索処理関係
//----------------------------------------------------
//
// pageIDをURLに追加。
//
public function add_pageID(){
	if( $this->type !== 'list' ){ return;}

	$add_pageID = "";
		if(isset($_GET['pageID']) && $_GET['pageID'] != ""){
		$add_pageID = '&pageID=' . $_GET['pageID'];
		$_SESSION['pageID'] = $_GET['pageID'];
		}else if(isset($_SESSION['pageID']) && $_SESSION['pageID'] != ""){
		$add_pageID = '&pageID=' . $_SESSION['pageID'];
		}
	return $add_pageID;
}

//-------------------------------------------------------------
//　ページネーション
//-------------------------------------------------------------
public function make_page_link($data){

//require_once 'Jumping.php';//これ、これだよここ。Xamppでは既にインストールされているので、インクルードファイルから既に読み込み済みだからredeclareになってしまう。fatal Error候補。

	$params = array(
	            'mode'      => 'Jumping',
	            'perPage'   => 10,
	            'delta'     => 10,
	            'itemData'  => $data
	        );

	$pager = new Pager_Jumping($params);
        $data  = $pager->getPageData();
        $links = $pager->getLinks();

return array($data, $links);

//return $data;
}




}//----class終了
?>
