<?php
require_once('db_set.php');

if(check_time($id,$conn)){
    $back=$event['postback']['data'];
    switch($back){
        case 'A':
            $level="你選擇的等級為 (A)Beginner\nThe level you choose is (A)Beginner";
            break;
        case 'B':
            $level="你選擇的等級為 (B)Medium\nThe level you choose is (B)Medium";
            break;
        case 'C':
            $level="你選擇的等級為 (C)Advanced\nThe level you choose is (C)Advanced";
            break;
        default:
            break;
    }
    $client->replyMessage(array(
        'replyToken' => $event['replyToken'],
        'messages' => array(
            array(
                'type' => 'text',
                'text' => "$level"
            ),
            array(
                'type' => 'text',
                'text' => '請輸入語詞'."\n".'Please enter a word.'
            )
        )
    ));
    check_level($id,$back,$conn);
}
else{
    $client->replyMessage(array(
        'replyToken' => $event['replyToken'],
        'messages' => array(
            array(
                'type' => 'text',
                'text' => "此選單已超過有效期限，請重新選擇等級。"
            ),
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
}