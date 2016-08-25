<?php
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$input = json_decode(file_get_contents('php://input'),true); //取得原始POST資料
$action = preg_replace('/[^a-z0-9_]+/i','',array_shift($request));
echo $input;
$actRequest = 'true';
$user = $_GET['username'];
$type = $_GET['type'];
$amount = $_GET['amount'];
if ($action == 'addAccount') {
    $act = new action();
    $create = $act->creatAccount($user);
    print_r($create);
}

if ($action == 'balance') {
    $act = new action();
    $callBalance = $act->getBlance($user,$actRequest);
    print_r($callBalance);
}

if ($action == 'transfer') {
    if ($user == "" || $type == "" || $amount == ""){
        exit();
    }
    $act = new action();
    $callTransfer = $act->transfer($user,$type,$amount,$actRequest);
    print_r($callTransfer);
}

if ($action == 'checkTransfer') {
    $act = new action();
    $callBalance = $act->checkTransfer($user,$actRequest);
    print_r($callBalance);
}

class Pdosql{
    public static $connect;
    function __construct(){
        self::$connect = new PDO('mysql:host=localhost;dbname=BankApi;port=443','root','');
        self::$connect->exec('set names utf8');
    }

    function getConnect() {
        return self::$connect;
    }
}

class action {
    function creatAccount($user) {
        $pdo = new Pdosql();
        $db = $pdo->getConnect();
        try {
            $checkAccount = $db->prepare('SELECT * FROM `PlayStation` WHERE `account` = :user');
            $checkAccount->bindParam('user', $user);
            $checkAccount->execute();
            $checkAccountResult = $checkAccount->fetch();
            if ($checkAccountResult) {
                throw new Exception('Account exist');
            }

            $adduser = $db->prepare('INSERT INTO `PlayStation` (`account`) VALUES (:user)');
            $adduser->bindParam("user", $user);
            $result = $adduser->execute();
            if (!$result) {
                throw new Exception('Not create account');
            }
            return 'Create account success';
        } catch(Exception $e) {
            echo ' '.$e->getMessage();
        }
    }

    function getBlance($user,$actRequest) {
        $pdo = new Pdosql();
        $db = $pdo->getConnect();
        $getBalance = $db->prepare('SELECT * FROM `PlayStation` WHERE `account` = :user');
        $getBalance->bindParam('user', $user);
        $getBalance->execute();
        $getBalanceResult = $getBalance->fetch();
        if (!$getBalanceResult) {
            $actRequest = 'false';
        }

        $array['Result'] = $actRequest;
        $array['account'] = $getBalanceResult['account'];
        $array['balance'] = $getBalanceResult['balance'];
        return json_encode($array);
    }

    function transfer($user,$type,$amount,$actRequest) {
        $pdo = new Pdosql();
        $db = $pdo->getConnect();
        if ($type == 'IN') {
            $deposit = $db->prepare('UPDATE `PlayStation` SET `balance` = `balance` + :amount, `act` = "IN" WHERE `account` = :user');
            $deposit->bindParam('amount', $amount,PDO::PARAM_INT,50);
            $deposit->bindParam('user', $user);
            $depositResult = $deposit->execute();
            if (!$depositResult) {
                $actRequest = 'false';
                $changeSistuation = $db->prepare('UPDATE `PlayStation` SET `sistuation` = "OPERAT FAILURE" WHERE `account` = :user');
                $changeSistuation->bindParam('user', $user);
                $changeSistuation->execute();

            } else {
                $changeSistuation = $db->prepare('UPDATE `PlayStation` SET `sistuation` = "OPERAT SUCCESS" WHERE `account` = :user');
                $changeSistuation->bindParam('user', $user);
                $changeSistuation->execute();
            }
            $check = $db->prepare('SELECT * FROM `PlayStation` WHERE `account` = :user');
            $check->bindParam('user', $user);
            $check->execute();
            $checkResult = $check->fetch();
            $array['Result'] = $actRequest;
            $array['account'] = $checkResult['account'];
            $array['balance'] = $checkResult['balance'];
            return json_encode($array);
        }

        if ($type == 'OUT') {
            $turnOut = $db->prepare('UPDATE `PlayStation` SET `balance` = `balance` - :amount,`act` = "OUT" WHERE `account` = :user');
            $turnOut->bindParam('amount', $amount,PDO::PARAM_INT,50);
            $turnOut->bindParam('user', $user);
            $turnOutResult = $turnOut->execute();
            if (!$turnOutResult) {
                $actRequest = 'false';
                $changeSistuation = $db->prepare('UPDATE `PlayStation` SET `sistuation` = "OPERAT FAILURE" WHERE `account` = :user');
                $changeSistuation->bindParam('user', $user);
                $changeSistuation->execute();
            } else {
                $changeSistuation = $db->prepare('UPDATE `PlayStation` SET `sistuation` = "OPERAT SUCCESS" WHERE `account` = :user');
                $changeSistuation->bindParam('user', $user);
                $changeSistuation->execute();
            }
            $check = $db->prepare('SELECT * FROM `PlayStation` WHERE `account` = :user');
            $check->bindParam('user', $user);
            $check->execute();
            $checkResult = $check->fetch();
            $array['Result'] = $actRequest;
            $array['account'] = $checkResult['account'];
            $array['balance'] = $checkResult['balance'];
            return json_encode($array);
        }

    }

    function checkTransfer($user,$actRequest) {
        $pdo = new Pdosql();
        $db = $pdo->getConnect();
        $checkSistuation = $db->prepare('SELECT * FROM `PlayStation` WHERE `account` = :user');
        $checkSistuation->bindParam('user', $user);
        $checkSistuation->execute();
        $checkSistuationResult = $checkSistuation->fetch();
        if ($checkSistuationResult['sistuation'] == 'OPERAT FAILURE' || empty($checkSistuationResult) ) {
            $actRequest = 'false';
        }
        $array['Result'] = $actRequest;
        $array['account'] = $checkSistuationResult['account'];
        $array['act'] = $checkSistuationResult['act'];
        $array['sistuation'] = $checkSistuationResult['sistuation'];
        return json_encode($array);

    }




}