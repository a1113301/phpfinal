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

// 獲取作者ID
if (isset($_GET['authorId'])) {
    $authorId = intval($_GET['authorId']);
} else {
    die("無效的作者ID");
}

// 獲取作者信息
$sql = "SELECT authorname, authorId, authorBirthday FROM author WHERE authorId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $authorId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    die("未找到該作者");
}
$author = $result->fetch_assoc();

// 處理表單提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $authorname = $_POST['authorname'];
    $newAuthorId = $authorId; // 不改變作者ID

    $update_sql = "UPDATE author SET authorname = ?, authorBirthday = ? WHERE authorId = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssi", $authorname, $authorBirthday, $authorId);

    if ($stmt->execute()) {
        $message = "作者修改成功，3秒後跳回";
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'admin_manage_authors.php';
                }, 3000); // 3 秒
              </script>";
    } else {
        $message = "作者修改失敗: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="zh-TW">
    <?php include("head.inc"); ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>修改作者</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .main-content {
            padding: 50px;
            text-align: center;
        }
        input[type="text"], select {
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
        <h1>修改作者</h1>
        <?php if (isset($message)) { echo '<p>' . htmlspecialchars($message) . '</p>'; } ?>
        <form method="POST" action="">
            <label for="authorId">作者Id</label>
            <input type="text" id="authorId" name="authorId" value="<?php echo htmlspecialchars($author['authorId']); ?>" readonly>
            <br>
            <label for="authorname">作者姓名</label>
            <input type="text" id="authorname" name="authorname" value="<?php echo htmlspecialchars($author['authorname']); ?>" required>
            <br>
            <input type="submit" class="button primary fit" value="修改作者">
        </form>
    </div>
</body>
</html>
