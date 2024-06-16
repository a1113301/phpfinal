<?php
session_start();
$isLoggedIn = isset($_SESSION['adminlog']) && $_SESSION['adminlog'] === true;

// 建立與資料庫的連接
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "phpfinal_library";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}

$message = "";

// 處理表單提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $author = $_POST['author'];

    // 檢查資料庫中是否已經存在相同的作者
    $check_query = "SELECT * FROM author WHERE authorname = 'author'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        $message = "已經有此作者";
    } else {
        // 插入新作者到資料庫
        $insert_query = "INSERT INTO author (authorname) VALUES ('$author')";
        if ($conn->query($insert_query) === TRUE) {
            $message = "作者新增成功";
        } else {
            $message = "作者新增失敗: " . $conn->error;
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<?php include("head.inc");?>
<head>

<style>
    /* 初始狀態下沒有下劃線 */
    .nav-link {
        text-decoration: none;
        color: white; /* 設定連結文字顏色為白色 */
        margin-right: 20px; /* 添加右邊距 */
    }

    /* 滑鼠懸停時顯示下劃線 */
    .nav-link:hover {
        text-decoration: underline;
    }

    /* 下拉選單樣式 */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 120px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #f1f1f1;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    /* 中心對齊 */
    .main-content {
        text-align: center;
        margin: 50px auto;
        max-width: 600px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .main-content h1 {
        margin-bottom: 20px;
    }

    .main-content form {
        display: inline-block;
        text-align: left;
    }

    .main-content form label,
    .main-content form input[type="text"],
    .main-content form input[type="submit"] {
        display: block;
        width: 100%;
        margin-bottom: 10px;
    }

    .main-content form input[type="text"] {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .main-content form input[type="submit"]:hover {
        background-color: #CC0000; /* 懸停時的顏色 */
    }

    input[type="text"], select {
        width: 300px; /* 設置相同的寬度 */
        padding: 10px;
        margin: 10px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
</style>


</head>
<body class="is-preload">
<?php include("list.inc");?>

<!-- 主要內容 -->
<div class="main-content">
    <h1>新增書籍作者</h1>
    <?php if (!empty($message)) { echo '<p style="color: #ce1b28;">' . htmlspecialchars($message) . '</p>'; } ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="author">作者姓名:</label>
        <input type="text" id="author" name="author" required>
        <input type="submit" class="button primary" value="新增作者">
    </form>
</div>


</body>
</html>
