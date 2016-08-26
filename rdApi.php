<?php
header("content-type: text/html; charset=utf-8");
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/')); //取得並拆解路徑
$input = json_decode(file_get_contents('php://input'),true); //取得原始POST資料
$action = preg_replace('/[^a-z0-9_]+/i','',array_shift($request));

$actRequest = 'true';
$user = $_GET['username'];
$type = $_GET['type'];
$amount = $_GET['amount'];
$number = $_GET['number'];
$lastNumber = date('is').rand(10,20);

if ($action == 'addAccount') {
    if (is_numeric($user) || $user == "") {
        echo '未輸入帳號';
        exit();
    }
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
        echo '參數不完整';
        exit();
    }
    $act = new action();
    $callTransfer = $act->transfer($user,$type,$amount,$actRequest,$lastNumber);
    print_r($callTransfer);
}

if ($action == 'checkTransfer') {
    $act = new action();
    $callBalance = $act->checkTransfer($user,$actRequest,$number);
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

class Action {
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

    function transfer($user,$type,$amount,$actRequest,$lastNumber) {
        $pdo = new Pdosql();
        $db = $pdo->getConnect();
        $searchNumber = $db->query('SELECT * FROM `Detail` ORDER BY `number` desc limit 1');
        $row = $searchNumber->fetch();
        if ($type == 'IN') {
            $deposit = $db->prepare('UPDATE `PlayStation` SET `balance` = `balance` + :amount, `lastNumber` = :lastNumber WHERE `account` = :user');
            $deposit->bindParam('amount', $amount,PDO::PARAM_INT,50);
            $deposit->bindParam('user', $user);
            $deposit->bindParam('lastNumber', $lastNumber);
            $depositResult = $deposit->execute();
            if (!$depositResult) {
                $actRequest = 'false';
                $sistuation = 'OPERAT FAILURE';
            } else {
                $sistuation = 'OPERAT SUCCESS';
            }

            $changeSistuation = $db->prepare('INSERT INTO `Detail` (`account`, `amount`, `act`, `sistuation`, `number`) VALUES (:user, :amount, "IN", :sistuation, :number)');
            $changeSistuation->bindParam('user', $user);
            $changeSistuation->bindParam('amount', $amount);
            $changeSistuation->bindParam('sistuation', $sistuation);
            $changeSistuation->bindParam('number', $lastNumber);
            $changeSistuation->execute();
        }

        if ($type == 'OUT') {
            $checkBalance = $db->prepare('SELECT * FROM `PlayStation` WHERE `account` = :user');
            $checkBalance->bindParam('user', $user);
            $checkBalance->execute();
            $checkBalanceResult = $checkBalance->fetch();
            if ($checkBalanceResult['balance'] < $amount) {
                echo '餘額不足，轉帳失敗';
                exit();
            }

            $turnOut = $db->prepare('UPDATE `PlayStation` SET `balance` = `balance` - :amount, `lastNumber` = :lastNumber WHERE `account` = :user');
            $turnOut->bindParam('amount', $amount,PDO::PARAM_INT,50);
            $turnOut->bindParam('user', $user);
            $turnOut->bindParam('lastNumber', $lastNumber);
            $turnOutResult = $turnOut->execute();
            if (!$turnOutResult) {
                $actRequest = 'false';
                $sistuation = 'OPERAT FAILURE';
            } else {
                $sistuation = 'OPERAT SUCCESS';
            }

            $changeSistuation = $db->prepare('INSERT INTO `Detail` (`account`, `amount`, `act`, `sistuation`, `number`) VALUES (:user, :amount, "OUT", :sistuation, :number)');
            $changeSistuation->bindParam('user', $user);
            $changeSistuation->bindParam('amount', $amount);
            $changeSistuation->bindParam('sistuation', $sistuation);
            $changeSistuation->bindParam('number', $lastNumber);
            $changeSistuation->execute();
        }

            $check = $db->prepare('SELECT * FROM `PlayStation` WHERE `account` = :user');
            $check->bindParam('user', $user);
            $check->execute();
            $checkResult = $check->fetch();
            $array['Result'] = $actRequest;
            $array['account'] = $checkResult['account'];
            $array['balance'] = $checkResult['balance'];
            $array['number'] = $checkResult['lastNumber'];

            return json_encode($array);
    }

    function checkTransfer($user,$actRequest,$number) {
        $pdo = new Pdosql();
        $db = $pdo->getConnect();
        $checkSistuation = $db->prepare('SELECT * FROM `Detail` WHERE `account` = :user && `number` = :number');
        $checkSistuation->bindParam('number', $number);
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