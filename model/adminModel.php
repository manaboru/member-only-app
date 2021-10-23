<?php

class adminModel extends BaseModel{

//***********************************************************************
//
//	<<利用ユーザーを登録する>>：insert_to_admin_users
//
//***********************************************************************
public function insert_to_admin_users(){

	$item = $this->encoding_post($_POST);

	try{
		$this->pdo->beginTransaction();
$sql="INSERT INTO tbl_admin_users(id,username,pwd,role,firstname,lastname)VALUES(:id,:uname,:pwd,:role,:fname,:lname)";
		$stmh=$this->pdo->prepare($sql);

		$stmh->bindValue(':id','',PDO::PARAM_STR);
		$stmh->bindValue(':uname',$item['Email'],PDO::PARAM_STR);
		$stmh->bindValue(':pwd',$item['pwd'],PDO::PARAM_STR);
		$stmh->bindValue(':role','',PDO::PARAM_STR);
		$stmh->bindValue(':fname',$item['firstname'],PDO::PARAM_STR);
		$stmh->bindValue(':lname',$item['lastname'],PDO::PARAM_STR);
		$stmh->execute();
		$stmh->closeCursor();

		$this->pdo->commit();

	}catch(PDOException $Exception){
		$this->pdo->rollBack();
		print "エラー:".$Exception->getMessage();

	}

	$modalTitle = 'Registered';
	$modalMessage = '利用ユーザーを登録しました';
	$modalflg = TRUE;

	return array($modalTitle,$modalMessage,$modalflg);

}//insert_to_admin_users()終了

//******************************************************************
//	<<Emailテンプレートの一覧を表示する>>：getListEmailTemplates
//******************************************************************
public function getListEmailTemplates(){

	try{

	$sql="SELECT * FROM cl_mail_template";

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
}//---getListEmailTemplates()終了


//***************************************************************
//	<<個別Emailテンプレートを表示する>>：getSingleEmailTemplate()
//***************************************************************
public function getSingleEmailTemplate($targetField,$search_key){

	try{

	$sql="SELECT * FROM cl_mail_template WHERE ".$targetField." = ".$search_key;

	$stmh=$this->pdo->prepare($sql);
	$stmh->execute();

	$data=$stmh->fetch(PDO::FETCH_ASSOC);

	}catch(PDOException $Exception){
	print "エラー：".$Exception->getMessage();
	}

	return $data;

}//---getSingleEmailTemplate()終了


//***************************************************************
//	<<新規Emailテンプレートを登録する>>：registerEmailTemplate
//***************************************************************
public function registerEmailTemplate(){


$item = $this->encoding_post($_POST);

	try{

	$this->pdo->beginTransaction();

	$sql="INSERT INTO cl_mail_template(templateName,subject,body,mailto) VALUES(:templateName,:subject,:body,:mailto)";
		$stmh=$this->pdo->prepare($sql);
		$stmh->bindValue(':templateName',$item['templateName'],PDO::PARAM_STR);
		$stmh->bindValue(':subject',$item['subject'],PDO::PARAM_STR);
		$stmh->bindValue(':body',$item['body'],PDO::PARAM_STR);
		$stmh->bindValue(':mailto',$item['mailto'],PDO::PARAM_STR);
		$stmh->execute();
		$stmh->closeCursor();
		$this->pdo->commit();

	}catch(PDOException $Exception){
		$this->pdo->rollBack();
		print "エラー:".$Exception->getMessage();
	}

	$modalTitle = 'Registered';
	$modalMessage = 'Emailテンプレートを挿入しました';
	$modalflg = TRUE;

	return array($modalTitle,$modalMessage,$modalflg);

}//---registerEmailTemplate()終了

//***************************************************************
//	<<Emailテンプレートを更新する>>：updateEmailTemplate
//***************************************************************
public function updateEmailTemplate($templateID){

	$item = $this->encoding_post($_POST);

		$this->pdo->beginTransaction();
	try{

	$sql="UPDATE cl_mail_template SET templateName = :templateName,subject = :subject,body = :body,mailto = :mailto WHERE templateID = :templateID";
		$stmh=$this->pdo->prepare($sql);
		$stmh->bindValue(':templateID',$templateID,PDO::PARAM_STR);
		$stmh->bindValue(':templateName',$item['templateName'],PDO::PARAM_STR);
		$stmh->bindValue(':subject',$item['subject'],PDO::PARAM_STR);
		$stmh->bindValue(':body',$item['body'],PDO::PARAM_STR);
		$stmh->bindValue(':mailto',$_POST['mailto'],PDO::PARAM_STR);
		$stmh->execute();
		$this->pdo->commit();
	}catch(PDOException $Exception){
		$this->pdo->rollBack();
		print "エラー:".$Exception->getMessage();
	}

	$modalTitle = 'Updated';
	$modalMessage = 'Emailテンプレートを更新しました';
	$modalflg = TRUE;

	return array($modalTitle,$modalMessage,$modalflg);

}//---updateEmailTemplate終了

//********************************************************************
//	<<入稿スケジュール一覧を取得する>>:getListSchedule()
//********************************************************************
public function getListSchedule(){

	try{

	$sql="SELECT * FROM cl_pubschedule";

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

}//---getListSchedule()終了

//**************************************************************************
//	<<入稿スケジュール個別データを取得する>>:getListSchedule()
//**************************************************************************
public function getSingleSchedule($dlID){

	try{

	$sql="SELECT * FROM cl_pubschedule WHERE dlID = :issueID";

	$stmh=$this->pdo->prepare($sql);
	$stmh->bindValue(':issueID',$dlID,PDO::PARAM_STR);
	$stmh->execute();

	$data=$stmh->fetch(PDO::FETCH_ASSOC);

	}catch(PDOException $Exception){
	print "エラー：".$Exception->getMessage();
	}

	return $data;

}//--getSingleSchedule()終了

//***************************************************************
//	<<入稿スケジュール個別データを更新する>>：updateEmailTemplate
//***************************************************************
public function updateSingleSchedule($dlID){

		$this->pdo->beginTransaction();
	try{

	$sql="UPDATE cl_pubschedule SET DL_date = :dl_date WHERE dlID = :dlid";
		$stmh=$this->pdo->prepare($sql);
		$stmh->bindValue(':dl_date',$_POST['DLtoUpload'],PDO::PARAM_STR);
		$stmh->bindValue(':dlid',$dlID,PDO::PARAM_STR);
		$stmh->execute();
		$this->pdo->commit();
	}catch(PDOException $Exception){
		$this->pdo->rollBack();
		print "エラー:".$Exception->getMessage();
	}

	$modalTitle = 'Updated';
	$modalMessage = '締め切りを更新しました';
	$modalflg = true;

	return array($modalTitle,$modalMessage,$modalflg);

}//---updateEmailTemplate終了

}//---class adminModel終了

?>

