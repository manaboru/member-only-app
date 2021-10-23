<?php

class view_object extends BaseController{

  //***************************************************
  //	コンストラクタ
  //***************************************************
//public function __construct(){

	//データベースを操作します。
//	$this->systemModel = new systemModel();
//	$this->articleModel = new articleModel();
//	$this->editorialModel = new editorialModel();
//	$this->convert = new convertValues();


//}//---コンストラクタ終了

public function screen($arr,$file){

	//ログインユーザー名の表示
	$this->view->assign('lastname',$_SESSION[_MEMBER_AUTHINFO]['lastname']);
	$this->view->assign('firstname',$_SESSION[_MEMBER_AUTHINFO]['firstname']);

	
	//引数を繰り返しassign
	foreach($arr as $key =>$value){

	$this->view->assign($key,$value);

	}

	//引数のうち、ファイル名を代入
	$this->file=$file;
	$this->view_display();

}

}

?>