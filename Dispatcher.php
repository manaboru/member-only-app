<?php

class Dispatcher extends BaseController {

//*****************************************************************************
//　ログイン認証後の分岐 - 権限ユーザー別分岐:runBranch()
//	0:管理者用
//	1:工学会スタッフ用

//*****************************************************************************
public function runBranch($role){

//		switch($role){
//			case 1:
//			$this->runJstaff();
//			break;
//			default:
			$this->runAdmin($role);
//		}

}//---runBranch()終了

//**************************************************************************
//	ユーザー「0」：管理者用画面・機能分岐メソッドrunAdmin
//**************************************************************************
public function runAdmin($role){

$dashboard = new dashboardController();

	if(isset($this->category) && isset($this->type)){
	
		if(class_exists($this->category)){

			$className = $this->category;
			$classObj = new $className();
		}else{
			$dashboard->screen_top($role);
		}

		if(method_exists($classObj,$this->type)){
			$methodName = $this->type;
			$classObj->$methodName();
		}else{
			$dashboard->screen_top($role);
		}

		

	}else{
		$dashboard->screen_top($role);
	}

}//---runAdmin終了

//***********************************************************************************
//	ユーザー「1」：スタッフ用画面・機能分岐メソッドrunJstaff
//***********************************************************************************
public function runJstaff(){

	$SystemController = new SystemController();
	$editorialfunction = new EditorialFunctionController();

	//typeパラメータとactionパラメータの両方が存在する場合
	if(isset($this->category) && isset($this->type)){
		switch($this->category){
			case 'article':
				if($this->type=='detail'){
				$editorialfunction->screen_detail();
				}
				else if($this->type=='modify'){
				$editorialfunction->screen_modify();
				}
				else if($this->type=='create'){
				$editorialfunction->screen_regist();
				}
				else if($this->type=='upload'){
				$editorialfunction->screen_regist();
				}
				else if($this->type=='addComment'){
				$editorialfunction->screen_comment();
				}
				break;

			case 'user':
				if($this->type=='create'){
				$editorialfunction->screen_createuser();
				}
				break;

			case 'file':
				if($this->type=='upload'){
				$upload = new UploadController ();

				$checkExtension = $upload -> check_upload_files('word');//拡張子のチェック
				if($checkExtension==true){
					$accordance = $upload -> checkAccordanceFiles('0');
				}else{
				echo "ダメ";
				}

				$upload -> csv_upload_files();
				}
				break;
		}

	}else{
				$this->screen_JstaffTop();
	}

}//---runJstaff終了

}//class Dispatcherの終了
?>
