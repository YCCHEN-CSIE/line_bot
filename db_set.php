<?php
function add_user($id,$conn){
    $sql = "INSERT INTO `user_state`(`UID`) VALUES ('$id')";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}

function del_user($id,$conn){
    $sql = "DELETE FROM `user_state` WHERE `UID`='$id'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}

function last_use($id,$conn){
    $now=date("Y-m-d H:i:s");
    $sql = "UPDATE `user_state` SET `last_user_time`='$now' WHERE `UID`='$id'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}

function check_level($id,$user_level,$conn){
    $sql = "UPDATE `user_state` SET `user_level`='$user_level',`state`=1 WHERE `UID`='$id'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}

function check_time($id,$conn){
    $sql = "SELECT `last_user_time` FROM `user_state` WHERE `UID`='$id'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
    $data=$data[0]['last_user_time'];
    $time_gap=strtotime("now")-strtotime($data);
    if($time_gap<300)return true;
    else return false;
}

function check_state($id,$conn){
    $sql = "SELECT `state` FROM `user_state` WHERE `UID`='$id'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $data=$stmt->fetchAll(PDO::FETCH_ASSOC);
    $data=$data[0]['state'];
    return $data;
}

function get_user_level($id,$conn){
    $sql = "SELECT `user_level` FROM `user_state` WHERE `UID`='$id'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $level=$stmt->fetchAll(PDO::FETCH_ASSOC);
    $level=$level[0]['user_level'];
    return $level;
}

function get_sentence($id,$sen_level,$num_of_sen,$keyword,$conn){
    $sql = "SELECT `sentence` FROM `sentence` WHERE `level`='$sen_level' AND `sentence` LIKE '%$keyword%' ORDER BY RAND() LIMIT $num_of_sen";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $sentence=$stmt->fetchAll(PDO::FETCH_ASSOC);
    return $sentence;
}