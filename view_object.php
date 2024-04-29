<?php

class view_object extends BaseController{

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
