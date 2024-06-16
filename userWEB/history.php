<?php
session_start();

// 檢查用戶是否登錄
if (!isset($_SESSION['userlog']) || $_SESSION['userlog'] !== true) {
    header("Location: login.php");
    exit();
}

// 建立與資料庫的連接
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phpfinal_library";

$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接是否成功
if ($conn->connect_error) {
    die("連接資料庫失敗：" . $conn->connect_error);
}

// 獲取當前用戶名
$username = $_SESSION['username'];

// 查詢用戶ID
$user_id_query = "SELECT id FROM user WHERE username = ?";
$stmt = $conn->prepare($user_id_query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $user_id = $row['id'];
} else {
    die("未找到用戶ID");
}

// 查詢當前用戶的借閱記錄
$sql = "SELECT book.bookId, book.bookname, category.catname, author.authorname, borrow.borrowdate, borrow.returndate
        FROM borrow
        INNER JOIN book ON borrow.bookId = book.bookId
        JOIN category ON book.catId = category.catId
        JOIN author ON book.authorId = author.authorId
        WHERE borrow.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE HTML>
<html>
<head>
    <?php include("head.inc"); ?>
    <style>
        /* 初始狀態下沒有下劃線 */
        .nav-link {
            text-decoration: none;
            color: white; /* 設置鏈接文字顏色為白色 */
            margin-right: 20px; /* 添加右邊距 */
        }

        /* 滑鼠懸停時顯示下劃線 */
        .nav-link:hover {
            text-decoration: underline;
        }

        /* 下拉菜單樣式 */
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
            max-width: 800px; /* 增加容器的最大寬度 */
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
            text-align: center;
        }

        .main-content form label,
        .main-content form input[type="text"],
        .main-content form input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
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

        table {
            width: 100%; /* 設置表格寬度為100% */
            border-collapse: collapse;
            margin: 20px 0;
            padding: 1px; /* 調整表格的內邊距 */
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body class="is-preload">

    <!-- Header -->
    <header id="header">
        <a class="logo" href="index.php">高大圖書館</a>
        <nav>
            <a href="#menu">Menu</a>
        </nav>
    </header>

    <!-- Nav -->    
    <?php include("list.inc"); ?>

    <div class="main-content">
        <h1>借閱記錄</h1>
        <div id="recordTable">
            <?php
            if ($result->num_rows > 0) {
                echo "<table>
                        <tr>
                            <th>書名</th>
                            <th>類別</th>
                            <th>作者</th>
                            <th>租借時間</th>
                            <th>歸還時間</th>
                        </tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['bookname']) . "</td>
                            <td>" . htmlspecialchars($row['catname']) . "</td>
                            <td>" . htmlspecialchars($row['authorname']) . "</td>
                            <td>" . htmlspecialchars($row['borrowdate']) . "</td>
                            <td>" . htmlspecialchars($row['returndate']) . "</td>
                        </tr>";
                }
                echo "</table>";
            } else {
                echo "<p>還沒有借閱記錄喔!</p>";
            }
            // 關閉資料庫連接
            $stmt->close();
            $conn->close();
            ?>
        </div>
    </div>

    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/browser.min.js"></script>
    <script src="../assets/js/breakpoints.min.js"></script>
    <script src="../assets/js/util.js"></script>
    <script src="../assets/js/main.js"></script>
    <!-- Footer -->
    <div id="copyright" class="copyright">
        連絡電話：07 5919000 <br/>
        聯絡地址：高雄市楠梓區大學南路700號
    </div>
</body>
</html>
