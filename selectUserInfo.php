<?php
session_start();

//匯入資料庫
require_once 'db.inc.php';

//預設訊息
$obj['success'] = false;
$obj['info'] = "查詢失敗";

//確認所有傳過來的表單資料是否完整
if( isset($_SESSION['email']) ){
    try{
        //查詢使用者的 SQL 語法
        $sql = "SELECT `email`, `name`, `address`
                FROM `users` 
                WHERE `email` = '{$_SESSION['email']}'
                AND `isActivated` = 1 ";

        //執行 SQL 語法
        $stmt = $pdo->query($sql);

        //判斷是否寫入資料
        if($stmt->rowCount() > 0){
            //修改預設訊息
            $obj['success'] = true;
            $obj['info'] = "查詢成功";
            $obj['result'] = $stmt->fetch();
        }
    } catch(PDOException $e){
        /**
         * 參考連結
         * https://mariadb.com/kb/en/mariadb-error-codes/
         */
        switch($pdo->errorInfo()[1]){
            case 1064:
                $obj['info'] = 'SQL 語法錯誤';
            break;
        }
    }
}

//告訴前端，回傳格式為 JSON (前端接到，會是物件型態)
header('Content-Type: application/json');

//輸出 JSON 格式，供 ajax 取得 response
echo json_encode($obj, JSON_UNESCAPED_UNICODE);