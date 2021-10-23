<?php
ini_set('display_errors',1);
class SystemModel extends BaseModel{

//****************************************************************
//	1-(1)
//	【メソッド名】：get_authinfo
//	【機能説明】  ：利用ユーザーを検索
//****************************************************************
public function get_authinfo($username){

	$data=array();

	try{
		$sql="SELECT * FROM tbl_admin_users WHERE username=:username limit 1";
		$stmh=$this->pdo->prepare($sql);
		$stmh->bindValue(':username',$username,PDO::PARAM_STR);
		$stmh->execute();
		$data=$stmh->fetch(PDO::FETCH_ASSOC);

	}catch(PDOException $Exception){
		print "エラー:".$Exception->getMessage();
	}

	return $data;

}//---get_authinfo()終了

//***************************************************************************
//	メソッド名：getToken()
//	機能説明：ワンタイムトークンを発行する
//	改定日：2019年5月3日
//	参考：https://techacademy.jp/magazine/19300
//***************************************************************************
public function getToken(){

	// 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
	$toke_byte = openssl_random_pseudo_bytes(16);
	$csrf_token = bin2hex($toke_byte);
	// 生成したトークンをセッションに保存します
	$_SESSION['csrf_token'] = $csrf_token;
	return $_SESSION['csrf_token'];

}//---getToken()終了


//****************************************************************
//	Dashboard：任意のステータスのイベント情報を取得
//	getEvents()
//****************************************************************
public function getEvents($status){

$sql="SELECT * FROM tbl_jfes_event_info WHERE eventStatus = ".$status;

	try{

		$stmh=$this->pdo->prepare($sql);
		$stmh->execute();

		//検索結果を多次元配列で取得
		$count = $stmh->rowCount();
		$i=0;
		$data = array();

			while($row=$stmh->fetch(PDO::FETCH_ASSOC)){
					foreach($row as $key => $value){
						$data[$i][$key]=$value;
						}
			$i++;
			}

	}catch(PDOException $Exception){
		print "エラー：".$Exception->getMessage();
	}

	return $data;

}

//****************************************************************
//	Dashboard：イベント数を取得
//	getNumEvents()
//****************************************************************
public function getNumEvents($status){

$sql="SELECT * FROM tbl_jfes_event_info WHERE eventStatus = ".$status;

	try{

		$stmh=$this->pdo->prepare($sql);
		$stmh->execute();

		//検索結果を多次元配列で取得
		$cnt = $stmh->rowCount();

	}catch(PDOException $Exception){
		print "エラー：".$Exception->getMessage();
	}

	return $cnt;

}


//****************************************************************
//	Dashboard：任意の個別イベント名を取得
//	getSingleEvent()
//****************************************************************
public function getSingleEvent($eventID){

	try{

	$sql="SELECT * FROM tbl_jfes_event_info WHERE eventID=".$eventID;

	$stmh = $this->pdo->prepare($sql);
	$stmh->execute();

	$data = $stmh->fetch(PDO::FETCH_ASSOC);

	}catch(PDOException $Exception){
		print "エラー：".$Exception->getMessage();
	}

	return $data;

}


//******************************************************************
//	Dashboard：任意のイベントのレコード群を取得
//	getParticipants
//******************************************************************
public function getParticipants($eventID){

	try{

	$sql="SELECT * FROM tbl_jfes_event".$eventID;

	$stmh=$this->pdo->prepare($sql);
	$stmh->execute();

	//検索結果を多次元配列で取得
//	$count = $stmh->rowCount();
	$i=0;
	$data = array();

		while($row=$stmh->fetch(PDO::FETCH_ASSOC)){
//		while($row=$stmh->fetchAll()){
				foreach($row as $key => $value){
					$data[$i][$key]=$value;
					}
		$i++;
		}

	}catch(PDOException $Exception){
	print "エラー：".$Exception->getMessage();
	}

	return $data;
}//---getParticipants()終了

//******************************************************************
//	Dashboard：任意のイベントの参加人数を取得
//	getNumParticipants
//******************************************************************
public function getNumParticipants($eventID){

	try{

	$sql="SELECT * FROM tbl_jfes_event".$eventID;

	$stmh=$this->pdo->prepare($sql);
	$stmh->execute();

	$res = $stmh->fetchAll();
	$num = count($res);

	}catch(PDOException $Exception){
	print "エラー：".$Exception->getMessage();
	}

	return $num;
}//---getNumParticipants()終了

//******************************************************************
//	Dashboard：任意のイベントの参加人数(学生)を取得
//	getNumStudents
//******************************************************************
public function getNumStudents($eventID){

	try{

	$sql="SELECT * FROM tbl_jfes_event".$eventID." WHERE student = 1";

	$stmh=$this->pdo->prepare($sql);
	$stmh->execute();

	$res = $stmh->fetchAll();
	$num = count($res);

	}catch(PDOException $Exception){
	print "エラー：".$Exception->getMessage();
	}

	return $num;
}//---getNumStudents()終了

//******************************************************************
//	Dashboard：任意のイベントの参加人数(学生)を取得
//	getNumMixer
//******************************************************************
public function getNumMixer($eventID){

	try{

	$sql="SELECT * FROM tbl_jfes_event".$eventID." WHERE mixer = 1";

	$stmh=$this->pdo->prepare($sql);
	$stmh->execute();

	$res = $stmh->fetchAll();
	$num = count($res);

	}catch(PDOException $Exception){
	print "エラー：".$Exception->getMessage();
	}

	return $num;
}//---getNumMixer()終了

//******************************************************************
//	CSVファイルを生成する
//	createCSV
//******************************************************************
public function createCSV($eventID){

	//CSVファイル名に付加するDL日時
	$date=date("YmdHis");
	//ＣＳＶファイルの書き込み指定。まずは書き込み先（ファイル名）を指定。
	$file_name = './csv/list_registrants'.$date.'.csv';
	//次にファイルポインタ変数を作成し、新規作成型でファイルをオープン。
	$fp = fopen( $file_name,"w");

	try{

	$sql="SELECT * FROM tbl_jfes_event".$eventID;

	$stmh=$this->pdo->prepare($sql);
	$stmh->execute();

			fputs($fp,"\"");//見出し行始め
			fputs($fp,mb_convert_encoding('通し番号','sjis','utf-8'));
			fputs($fp,"\",\"");
			fputs($fp,mb_convert_encoding('申込番号','sjis','utf-8'));
			fputs($fp,"\",\"");
			fputs($fp,mb_convert_encoding('氏名','sjis','utf-8'));
			fputs($fp,"\",\"");
			fputs($fp,mb_convert_encoding('ふりがな','sjis','utf-8'));
			fputs($fp,"\",\"");
			fputs($fp,mb_convert_encoding('E-mail','sjis','utf-8'));
			fputs($fp,"\",\"");
			fputs($fp,mb_convert_encoding('勤務先・所属・役職','sjis','utf-8'));
			fputs($fp,"\",\"");
			fputs($fp,mb_convert_encoding('所属学会','sjis','utf-8'));
//			fputs($fp,"\",\"");
//			fputs($fp,mb_convert_encoding('学生フラグ[0:一般、1:学生]','sjis','utf-8'));
//			fputs($fp,"\",\"");
//			fputs($fp,mb_convert_encoding('年齢','sjis','utf-8'));
//			fputs($fp,"\",\"");
//			fputs($fp,mb_convert_encoding('交流会フラグ[0:欠席、1:出席]','sjis','utf-8'));
			fputs($fp,"\"");
			fputs($fp,"\n");//見出し行終了

		while($row = $stmh->fetch(PDO::FETCH_ASSOC)){
		
			fputs($fp,"\"");
			fputs($fp,$row['serialID']);
			fputs($fp,"\",\"");
			fputs($fp,$row['registID']);
			fputs($fp,"\",\"");
			fputs($fp,mb_convert_encoding($row['lastname'],'sjis','utf-8')." ".mb_convert_encoding($row['firstname'],'sjis','utf-8'));
			fputs($fp,"\",\"");
			fputs($fp,mb_convert_encoding($row['lastnameKana'],'sjis','utf-8')." ".mb_convert_encoding($row['firstnameKana'],'sjis','utf-8'));
			fputs($fp,"\",\"");
			fputs($fp,$row['Email']);
			fputs($fp,"\",\"");
			fputs($fp,mb_convert_encoding($row['office'],'sjis','utf-8')." ".mb_convert_encoding($row['affiliation'],'sjis','utf-8')." ".mb_convert_encoding($row['title'],'sjis','utf-8'));
			fputs($fp,"\",\"");
			fputs($fp,mb_convert_encoding($row['society'],'sjis','utf-8'));
//			fputs($fp,"\",\"");
//			fputs($fp,$row['student']);
//			fputs($fp,"\",\"");
//			fputs($fp,$row['age']);
//			fputs($fp,"\",\"");
//			fputs($fp,$row['mixer']);
			fputs($fp,"\"");
			fputs($fp,"\n");

		}

	//ファイルを閉じる。
	fclose($fp);

	// HTTPヘッダを設定
	header('Content-Type: application/octet-stream');
	header('Content-Length: '.filesize($file_name));
	header('Content-Disposition: attachment; filename=list_registrants'.$date.'.csv');

	// ファイル出力
	readfile($file_name);

//	header('location: '._ROOT_DIR.'/csv/list_registrants'.$date.'csv');

	}catch(PDOException $Exception){
	print "エラー：".$Exception->getMessage();
	}



}//csvDL終了

//******************************************************************
//	CSVファイルを生成する(CPD協議会バージョン)
//	createCSV_cpd
//******************************************************************
public function createCSV_cpd($eventID){

	//CSVファイル名に付加するDL日時
	$date=date("YmdHis");
	//ＣＳＶファイルの書き込み指定。まずは書き込み先（ファイル名）を指定。
	$file_name = './csv/list_registrants'.$date.'.csv';
	//次にファイルポインタ変数を作成し、新規作成型でファイルをオープン。
	$fp = fopen( $file_name,"w");

	try{

	$sql="SELECT * FROM tbl_jfes_event".$eventID;

	$stmh=$this->pdo->prepare($sql);
	$stmh->execute();

			fputs($fp,"\"");//見出し行始め
			fputs($fp,mb_convert_encoding('通し番号','sjis','utf-8'));
			fputs($fp,"\",\"");
			fputs($fp,mb_convert_encoding('申込番号','sjis','utf-8'));
			fputs($fp,"\",\"");
			fputs($fp,mb_convert_encoding('氏名','sjis','utf-8'));
			fputs($fp,"\",\"");
			fputs($fp,mb_convert_encoding('フリガナ','sjis','utf-8'));
			fputs($fp,"\",\"");
			fputs($fp,mb_convert_encoding('所属学協会','sjis','utf-8'));
			fputs($fp,"\",\"");
			fputs($fp,mb_convert_encoding('E-mail','sjis','utf-8'));
			fputs($fp,"\",\"");
			fputs($fp,mb_convert_encoding('交流会フラグ[0:欠席、1:出席]','sjis','utf-8'));
			fputs($fp,"\",\"");
			fputs($fp,mb_convert_encoding('勤務先・所属・役職','sjis','utf-8'));
			fputs($fp,"\",\"");
			fputs($fp,mb_convert_encoding('学生フラグ[0:一般、1:学生]','sjis','utf-8'));
			fputs($fp,"\",\"");
			fputs($fp,mb_convert_encoding('年齢','sjis','utf-8'));
			fputs($fp,"\"");
			fputs($fp,"\n");//見出し行終了

		while($row = $stmh->fetch(PDO::FETCH_ASSOC)){
		
			fputs($fp,"\"");
			fputs($fp,$row['serialID']);
			fputs($fp,"\",\"");
			fputs($fp,$row['registID']);
			fputs($fp,"\",\"");
			fputs($fp,mb_convert_encoding($row['lastname'],'sjis','utf-8')." ".mb_convert_encoding($row['firstname'],'sjis','utf-8'));
			fputs($fp,"\",\"");
			fputs($fp,mb_convert_encoding($row['lastnameKana'],'sjis','utf-8')." ".mb_convert_encoding($row['firstnameKana'],'sjis','utf-8'));
			fputs($fp,"\",\"");
			fputs($fp,mb_convert_encoding($row['society'],'sjis','utf-8'));
			fputs($fp,"\",\"");
			fputs($fp,$row['Email']);
			fputs($fp,"\",\"");
			fputs($fp,$row['mixer']);
			fputs($fp,"\",\"");
			fputs($fp,mb_convert_encoding($row['office'],'sjis','utf-8')." ".mb_convert_encoding($row['affiliation'],'sjis','utf-8')." ".mb_convert_encoding($row['title'],'sjis','utf-8'));
			fputs($fp,"\",\"");
			fputs($fp,$row['student']);
			fputs($fp,"\",\"");
			fputs($fp,$row['age']);
			fputs($fp,"\"");
			fputs($fp,"\n");

		}

	//ファイルを閉じる。
	fclose($fp);

	// HTTPヘッダを設定
	header('Content-Type: application/octet-stream');
	header('Content-Length: '.filesize($file_name));
	header('Content-Disposition: attachment; filename=list_registrants_cpd'.$date.'.csv');

	// ファイル出力
	readfile($file_name);

//	header('location: '._ROOT_DIR.'/csv/list_registrants'.$date.'csv');

	}catch(PDOException $Exception){
	print "エラー：".$Exception->getMessage();
	}



}//csvDL終了

//******************************************************************
//	リマインドメールスクリプト
//	sendMail
//	2021/9/10記述
//	※メールタイトルはイベント名をDBから取得してセットすること(2021/9/23)

//******************************************************************

public function sendMail($email,$mailSubject,$mailBody){

	//言語設定
	mb_language("Japanese");
	mb_internal_encoding("UTF-8");

	//宛先、メールタイトル設定
	$to      = $email;
	$subject = $mailSubject;

	//メール本文
	$message = $mailBody;

	//メールヘッダー
	$headers.= 'From:' .mb_encode_mimeheader("日本工学会事務局") ."<eng@jfes.or.jp>";

	//メール送信
	mb_send_mail($to, $subject, $message, $headers);


}



//--class終了
}

?>