<?php

class admin extends BaseController{

//***************************************************
//	コンストラクタ
//***************************************************
public function __construct(){

	//データベースを操作します。
	$this->auth = new Auth();
	$this->systemModel = new SystemModel();
	$this->adminModel = new adminModel();
	$this->view = new view_object();

}//---コンストラクタ終了

//***************************************************
//	<<管理ユーザー登録画面構成>>:createUser()
//***************************************************
public function createUser(){

	//サブミットされたかどうかの判定 - 突合する
	if(isset($_POST['token']) && $_POST['token'] == $_SESSION['token']){
		//POSTデータのうち、Emailが入力されている
		if(isset($_POST['Email']) && $_POST['Email']!=''){

			//入力されたユーザー名(Email)が無い場合
			if(FALSE == $data = $this->systemModel->get_authinfo($_POST['Email'])){

				//パスワードをハッシュ値に変換
				$_POST['pwd'] = $this->auth->get_hashed_password($_POST['pwd']);

				//登録処理
				list($modalTitle,$message,$modalflg) = $this->adminModel->insert_to_admin_users();

			}else{

				$modalTitle = 'ERROR!';
				$message = '入力されたユーザー名は既に登録済みです';
				$modalflg = 'TRUE';
			}
			
		//突合後、セッショントークンを破棄する
		unset($_SESSION['token']);
		
		}else{

		$modalTitle = 'ERROR!';
		$message = 'データを入力してください';
		$modalflg = 'TRUE';

		}

	}else{
	//サブミットされていない

		$modalTitle = NULL;
		$message = NULL;
		$modalflg = 'False';
	}

	//--トークン発行
	$_SESSION['token'] = $this->systemModel->getToken();

	//画面構成要素（値）を取得
	//$element = $this->screenBuilder->getValuePageElements($_GET['type']);

	$arr=array('title'=>$element['title'],'category'=>'admin','type'=>'createUser','screen'=>$element['screen'],'modalTitle'=>$modalTitle,'modalflg'=>$modalflg,'message'=>$message,'token'=>$_SESSION['token']);

	$this->view->screen($arr,'page.admin.user.regist.tpl');

}//---createuser終了


//***************************************************
//	<<イベント登録画面構成>>:createEvent()
//***************************************************

public function createEvent(){

	//サブミットされたかどうかの判定 - 突合する
	if(isset($_POST['token']) && $_POST['token'] == $_SESSION['token']){

		//POSTデータのうち、Emailが入力されている
		if(isset($_POST['Email']) && $_POST['Email']!=''){

			//入力されたユーザー名(Email)が無い場合
			if(FALSE == $data = $this->systemModel->get_authinfo($_POST['Email'])){

				//パスワードをハッシュ値に変換
				$_POST['pwd'] = $this->auth->get_hashed_password($_POST['pwd']);

				//登録処理
				list($modalTitle,$message,$modalflg) = $this->adminModel->insert_to_admin_users();

			}else{

				$modalTitle = 'ERROR!';
				$message = '入力されたユーザー名は既に登録済みです';
				$modalflg = 'TRUE';
			}
			
		//突合後、セッショントークンを破棄する
		unset($_SESSION['token']);
		
		}else{

		$modalTitle = 'ERROR!';
		$message = 'データを入力してください';
		$modalflg = 'TRUE';

		}

	}else{
	//サブミットされていない

		$modalTitle = NULL;
		$message = NULL;
		$modalflg = 'False';
	}

	//--トークン発行
	$_SESSION['token'] = $this->systemModel->getToken();

	//画面構成要素（値）を取得
	//$element = $this->screenBuilder->getValuePageElements($_GET['type']);

	$arr=array('title'=>$element['title'],'category'=>'admin','type'=>'createUser','screen'=>$element['screen'],'modalTitle'=>$modalTitle,'modalflg'=>$modalflg,'message'=>$message,'token'=>$_SESSION['token']);

	$this->view->screen($arr,'page.admin.user.regist.tpl');


}//---createEvent終了




}//---class admin終了
?>
