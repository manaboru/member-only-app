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

}//class Dispatcherの終了
?>
