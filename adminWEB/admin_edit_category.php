<?php
// 建立與數據庫的連接
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phpfinal_library";

$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接是否成功
if ($conn->connect_error) {
    die("連接數據庫失敗：" . $conn->connect_error);
}

// 獲取類別ID
if (isset($_GET['catId'])) {
    $catId = intval($_GET['catId']);
} else {
    die("無效的類別ID");
}

// 獲取類別信息
$sql = "SELECT catId, catname FROM category WHERE catId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $catId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    die("未找到該類別");
}
$category = $result->fetch_assoc();

// 處理表單提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newCatname = $_POST['newCatname'];

    $update_sql = "UPDATE category SET catname = ? WHERE catId = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $newCatname, $catId);
    if ($stmt->execute()) {
        $message = "修改完成，3秒後跳回";
        echo '<script>setTimeout(function() {window.location.href = "./admin_manage_book_categories.php";}, 3000);</script>';
        // 更新成功後刷新頁面以顯示更新後的信息
    } else {
        $message = "類別修改失敗: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>  

<!DOCTYPE html>
<html lang="zh-TW">
<?php include("head.inc");?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>修改類別</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .main-content {
            padding: 50px;
            text-align: center;
        }
        input[type="text"] {
            width: 300px;
            padding: 10px;
            margin: 10px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
    </style>
    <style>
        /* 初始狀態下沒有下劃線 */
        .nav-link {
            text-decoration: none;
            color: white; /* 設定鏈結文字顏色為白色 */
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
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .main-content form label,
        .main-content form input[type="text"],
        .main-content form select,
        .main-content form input[type="submit"],
        .main-content form input[type="file"] {
            width: 100%;
            max-width: 300px; /* 設置最大寬度 */
            margin-bottom: 10px;
        }

        .main-content form input[type="text"],
        .main-content form select,
        .main-content form input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .main-content form input[type="submit"]:hover {
            background-color: #CC0000; /* 懸停時的顏色 */
        }
    </style>
</head>
<body class="is-preload">

<?php include("list.inc");?>
    <div class="main-content">
        <h1>修改類別</h1>
        <?php if (isset($message)) { echo '<p>' . htmlspecialchars($message) . '</p>'; } ?>
        <form method="POST" action="">
            <label for="catId">類別ID</label>
            <input type="text" id="catId" name="catId" value="<?php echo htmlspecialchars($category['catId']); ?>" readonly>
            <br>
            <label for="catname">現在類別名稱</label>
            <input type="text" id="catname" name="catname" value="<?php echo htmlspecialchars($category['catname']); ?>" readonly>
            <br>
            <label for="newCatname">修改後的類別名稱</label>
            <input type="text" id="newCatname" name="newCatname" required>
            <br>
            <input type="submit" class="button primary fit" value="修改類別">
        </form>
    </div>
</body>
</html>
