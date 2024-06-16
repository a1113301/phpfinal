<?php
// 將 PHPMailer 引入到全局命名空間中
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// 引入 PHPMailer 類文件
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// 創建一個 PHPMailer 實例；傳遞 `true` 啟用異常
$mail = new PHPMailer(true);

// 從表單中獲取內容並將換行符轉換為 <br> 標籤
$content = $_POST["sComment"];
$content = nl2br($content);

// 連接到你的資料庫
$link = mysqli_connect('localhost', 'root', '', 'phpfinal_library');

// 如果連接失敗，則輸出錯誤信息並終止
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

// 查詢資料庫獲取使用著的電子郵件
$query = "SELECT email FROM user";
$result = mysqli_query($link, $query);

// 如果查詢失敗，則輸出錯誤信息並終止
if (!$result) {
    die("Error: " . $query . "<br>" . mysqli_error($link));
}

// 從查詢結果中提取用戶電子郵件
$row = mysqli_fetch_assoc($result);
$fromEmail = $row['email'];

// 關閉資料庫連接
mysqli_close($link);

try {
    // 服務器設置
    $mail->SMTPDebug = SMTP::DEBUG_OFF;                       
    $mail->isSMTP();                                           
    $mail->Host       = 'smtp.gmail.com';                       
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = 'a1113344@mail.nuk.edu.tw';             
    $mail->Password   = 'xqoj ajzw iyxg xvej';                  
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
    $mail->Port       = 465;                                    

    // 設置收件人和發件人
    $mail->setFrom($fromEmail, 'Mailer');
    $mail->addAddress('a1113344@mail.nuk.edu.tw', 'Joe User'); 
    $mail->addReplyTo($fromEmail, 'Information');

    // 內容設置
    $mail->isHTML(true);                                      
    $mail->Subject = '來自聯絡我們的回饋';
    $mail->Body    = $content;
    $mail->AltBody = '這是非 HTML 郵件客戶端的純文本內容';
    
    // 設置郵件的字符編碼
    $mail->CharSet = 'UTF-8';

    // 發送郵件
    $mail->send();
    
    // 顯示發送成功消息並在5秒後重定向到首頁
    echo '<p>已發送您的建議，感謝您寶貴的回饋，將於2秒後跳回首頁。</p>';
    echo '<script>setTimeout(function() {window.location.href = "../index.php";}, 2000);</script>';
} catch (Exception $e) {
    // 如果發送失敗，則輸出錯誤信息並終止
    echo "發送失敗，請稍後再試。" . $mail->ErrorInfo;
}
?>
