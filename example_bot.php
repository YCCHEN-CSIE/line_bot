<?php
/**
 * Copyright 2017 GoneTone
 *
 * Line Bot
 * 範例 Example Bot 執行主文件
 *
 * 此範例 GitHub 專案：https://github.com/GoneTone/line-example-bot-php
 * 官方文檔：https://developers.line.biz/en/reference/messaging-api/
 */
date_default_timezone_set("Asia/Taipei"); // 設定時區為台北時區

require_once('LINEBotTiny.php');
require_once('conn.php');
require_once('db_set.php');

if (file_exists(__DIR__ . '/config.ini')) {
    $config = parse_ini_file("config.ini", true); // 解析配置檔
    if ($config['Channel']['Token'] == Null || $config['Channel']['Secret'] == Null) {
        error_log("config.ini 配置檔未設定完全！", 0); // 輸出錯誤
    } else {
        $channelAccessToken = $config['Channel']['Token'];
        $channelSecret = $config['Channel']['Secret'];
    }
} else {
    $configFile = fopen("config.ini", "w") or die("Unable to open file!");
    $configFileContent = '; Copyright 2019 GoneTone
;
; Line Bot
; 範例 Example Bot 配置文件
;
; 此範例 GitHub 專案：https://github.com/GoneTone/line-example-bot-php
; 官方文檔：https://developers.line.biz/en/reference/messaging-api/

[Channel]
; 請在雙引號內輸入您的 Line Bot "Channel access token"
Token = ""

; 請在雙引號內輸入您的 Line Bot "Channel secret"
Secret = ""
';
    fwrite($configFile, $configFileContent); // 建立文件並寫入
    fclose($configFile); // 關閉文件
    error_log("config.ini 配置檔建立成功，請編輯檔案填入資料！", 0); // 輸出錯誤
}

$message = null;
$event = null;

$client = new LINEBotTiny($channelAccessToken, $channelSecret);
foreach ($client->parseEvents() as $event) {
    $id=$event['source']['userId'];
    last_use($id,$conn);
    switch ($event['type']) {
        case 'message':
            $message = $event['message'];
            switch ($message['type']) {
                case 'text':
                    require_once('includes/text.php'); // Type: Text
                    break;
                default:
                    //error_log("Unsupporeted message type: " . $message['type']);
                    break;
            }
            break;
        case 'postback':
            require_once('postback.php'); // postback
            break;
        case 'follow': // 加為好友觸發
            add_user($id,$conn);
            $client->replyMessage(array(
                'replyToken' => $event['replyToken'],
                'messages' => array(
                    array(
                        'type' => 'template', // 訊息類型 (模板)
                        'altText' => '確認等級', // 替代文字
                        'template' => array(
                            'type' => 'buttons', // 類型 (按鈕)
                            'text' => '請輸入等級'."\n".'Please choose your Chinese language level.', // 文字
                            'actions' => array(
                                array(
                                    'type' => 'postback', // 類型 (回傳)
                                    'label' => '(A)Beginner', // 標籤 1
                                    'data' => 'A' // 資料
                                ),
                                array(
                                    'type' => 'postback',
                                    'label' => '(B)Medium',
                                    'data' => 'B'
                                ),
                                array(
                                    'type' => 'postback',
                                    'label' => '(C)Advanced',
                                    'data' => 'C'
                                )
                            )
                        )
                    )
                )
            ));
            break;
        case 'unfollow': // 封鎖
            del_user($id,$conn);
            break;
        case 'join': // 加入群組觸發
            /*$client->replyMessage(array(
                'replyToken' => $event['replyToken'],
                'messages' => array(
                    array(
                        'type' => 'text',
                        'text' => '大家好，這是一個範例 Bot OuO

範例程式開源至 GitHub (包含教學)：
https://github.com/GoneTone/line-example-bot-php'
                    )
                )
            ));*/
            break;
        default:
            //error_log("Unsupporeted event type: " . $event['type']);
            break;
    }
}