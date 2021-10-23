<?php

class participant extends BaseController{

  //***************************************************
  //	コンストラクタ
  //***************************************************
public function __construct(){

	//データベースを操作します。
	$this->systemModel = new SystemModel();
	$this->view = new view_object();

}//---コンストラクタ終了


//******************************************************************
//	<<イベント参加者一覧を表示する>>：viewList()
//******************************************************************

public function viewList(){


	$participants = $this->systemModel->getParticipants($_GET['eid']);
	$current_events = $this->systemModel->getSingleEvent($_GET['eid']);
	$cnt = $this->systemModel->getNumParticipants($_GET['eid']);
//	$cnt_student = $this->systemModel->getNumStudents($_GET['eid']);
//	$cnt_mixer = $this->systemModel->getNumMixer($_GET['eid']);
	$draganddrop = TRUE;
	$this->title = '参加者一覧';
	$this->file = 'list.participants'.$_SESSION['role'].'.tpl';


	$arr=array('title'=>$this->title,'draganddrop'=>$draganddrop, 'participants'=>$participants,'current_events'=>$current_events,'cnt'=>$cnt,'cnt_student'=>$cnt_student,'cnt_mixer'=>$cnt_mixer);
	$view= new view_object();
	$view->screen($arr,$this->file);

}//---viewList()終了


//******************************************************************
//	<<参加者一覧をCSVでダウンロードさせる>>：dlcsv()
//******************************************************************
public function dlcsv(){

	if($_GET['eid']==190829){
		$this->systemModel->createCSV_cpd($_GET['eid']);
	}else{

	$this->systemModel->createCSV($_GET['eid']);

	}
}//---dlcsv終了


//******************************************************************
//	<<参加者に向けてメール送信ダイアログを表示>>：formReminder()
//	【作成中】---- 2021年9月4日設置（日本工学会用）
//	URLに渡すパラメータ:
//	category=participant
//	type=formReminder
//	status=(input,confirm,complete)
//******************************************************************
public function formReminder(){

	$participants = $this->systemModel->getParticipants($_GET['eid']);
	$current_events = $this->systemModel->getSingleEvent($_GET['eid']);

switch($_GET['status']){

case 'input':
	$this->title = 'メール内容入力';
	$this->file = 'form.input.reminder.tpl';
	$arr=array('title'=>$this->title,'current_events'=>$current_events);
	$view= new view_object();
	$view->screen($arr,$this->file);
	break;

case 'confirm':


	//フォーム入力された値をセッション変数に格納
	//$_SESSION['reminderTitle']=$_POST['reminderTitle'];
	$_SESSION['mailBody'] = $_POST['mailBody'];
	$this->title = 'メール内容確認';
	$this->file = 'form.confirm.reminder.tpl';
	$arr=array('title'=>$this->title,'current_events'=>$current_events,'mailBody'=>$_SESSION['mailBody'],'participants'=>$participants);
	$view= new view_object();
	$view->screen($arr,$this->file);
	break;
default:
	//念のため、イベントステータスが有効な場合のみを送信条件とする
	if($current_events['eventStatus']!==2){

	//イベント名称の取得→メールタイトルの構成
	$mailSubject = "【リマインド】".$current_events['eventName'];

	
	//繰り返し実行(配列から値を取り出し、Emailの値だけセット)
	foreach($participants as $value){

		$this->systemModel->sendMail($value['Email'],$mailSubject,$_SESSION['mailBody']);

	}
	}

	//リダイレクトし完了画面を表示
	$this->title = 'メール送信完了';
	$this->file = 'form.complete.reminder.tpl';
	$arr=array('title'=>$this->title);
	$view= new view_object();
	$view->screen($arr,$this->file);

}//---switch文終了

}//---formReminder()終了



}//---class participant()終了
?>