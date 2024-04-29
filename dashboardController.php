<?php

class dashboardController extends BaseController{

//*****************************************************************************
//  管理者用
//　トップ画面表示:screen_top()
//  MainControllerクラスのdo_authenticateメソッドの分岐により参照されたメソッド
//*****************************************************************************
public function screen_top($role){

	$system = new SystemModel();

	//現在受付中のイベント、および、終了済みのイベントを分けて、それぞれの変数に代入する
	$test_events = $system->getEvents(0);
	$current_events = $system->getEvents(1);
	$finished_events = $system->getEvents(2);

	//$roleに応じたテンプレートを選別する。
	//違いは、制御タブの有り無し→タグを生成するか否かを変数に込める
	if($role==0){
		$class = '';
	}else{
		$class = 'hide';
	}

	$draganddrop = TRUE;
	$this->title = 'ダッシュボード';
	$this->file = 'top.tpl';

	$arr=array('title'=>$this->title,'draganddrop'=>$draganddrop,'test_events'=>$test_events,'current_events'=>$current_events,'finished_events'=>$finished_events,'cnt'=>$cnt,'class'=>$class);
	$view= new view_object();
	$view->screen($arr,$this->file);

}//screen_top()終了

}//---class dashboardController終了

?>
