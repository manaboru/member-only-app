<?php

class BaseModel{
    
    protected $pdo;
            
    public function __construct(){
        $this->db_connect();
    }

    //----------------------------------------------------
    // データベース接続
    //----------------------------------------------------
    private function db_connect(){
        try {
          $this->pdo = new PDO(_DSN, _DB_USER, _DB_PASS,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET `utf8`")
	);
          $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        }catch(PDOException $Exception){
          die('エラー :' . $Exception->getMessage());
        }
    }


	public function encoding_post($postdata){

	$val=array();

		foreach($postdata as $key => $val){
			$rec[$key] = mb_convert_encoding($val,'utf8','auto');
		}

	return $rec;

	}


}

?>