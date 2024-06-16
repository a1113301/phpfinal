<?php
// 建立與數據庫的連接
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phpfinal_library";

// 建立連接
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接是否成功
if ($conn->connect_error) {
    die("連接數據庫失敗：" . $conn->connect_error);
}

// 處理刪除操作
if (isset($_GET['delete'])) {
    $authorId = intval($_GET['delete']);
    
    // 刪除相關的書籍
    $delete_books_sql = "DELETE FROM book WHERE authorId = ?";
    $stmt_books = $conn->prepare($delete_books_sql);
    $stmt_books->bind_param("i", $authorId);
    if ($stmt_books->execute()) {
        // 刪除作者
        $delete_author_sql = "DELETE FROM author WHERE authorId = ?";
        $stmt_author = $conn->prepare($delete_author_sql);
        $stmt_author->bind_param("i", $authorId);
        if ($stmt_author->execute()) {
            $message = "作者及其相關書籍已成功刪除";
        } else {
            $message = "刪除作者失敗：" . $stmt_author->error;
        }
        $stmt_author->close();
    } else {
        $message = "刪除相關書籍失敗：" . $stmt_books->error;
    }
    $stmt_books->close();
}

// 設定每頁顯示的記錄數
$records_per_page = 10;

// 獲取當前頁碼
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $current_page = intval($_GET['page']);
} else {
    $current_page = 1;
}

// 獲取搜尋關鍵字和字段
$search_keyword = isset($_GET['search_keyword']) ? $_GET['search_keyword'] : '';
$search_field = isset($_GET['search_field']) ? $_GET['search_field'] : 'authorname';

// 計算偏移量
$offset = ($current_page - 1) * $records_per_page;

// 執行查詢來獲取符合條件的作者數據
$sql = "SELECT SQL_CALC_FOUND_ROWS author.authorId, author.authorname, GROUP_CONCAT(book.bookname SEPARATOR ', ') AS booknames
        FROM author 
        LEFT JOIN book ON book.authorId = author.authorId
        WHERE {$search_field} LIKE '%{$search_keyword}%'
        GROUP BY author.authorId
        LIMIT {$offset}, {$records_per_page}";
$result = $conn->query($sql);

// 獲取總記錄數
$total_records_sql = "SELECT FOUND_ROWS() as total_records";
$total_records_result = $conn->query($total_records_sql);
$total_records_row = $total_records_result->fetch_assoc();
$total_records = $total_records_row['total_records'];

// 計算總頁數
$total_pages = ceil($total_records / $records_per_page); 

// 關閉連接
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
            max-width: 1000px; /* 增加容器的最大寬度 */
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

        .main-content form input[type="submit"] {
            background-color: #FF0000; /* 修改為紅色 */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
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

        table {
            width: 100%; /* 設置表格寬度為100% */
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .action-buttons a {
            background-color: #7B7B7B; /* 修改為紅色 */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px; /* 調整內邊距 */
            cursor: pointer;
            text-decoration: none; /* 刪除下劃線 */
        }

        .action-buttons a:hover {
            background-color: #CC0000; /* 滑鼠懸停時的顏色 */
        }
    </style>
</head>
<body class="is-preload">
<?php include("list.inc");?>
 <!-- 主內容 -->
 <div class="main-content">
    <h1>管理作者</h1>
    <!-- 搜尋表單 -->
    <form action="admin_manage_authors.php" method="get">
        <input type="text" name="search_keyword" placeholder="輸入搜尋關鍵字">
        <select name="search_field">
            <option value="authorId">作者ID</option>
            <option value="authorname">作者</option>
        </select>
        <a href="#" class="button primary icon fa-search">搜尋</a>
    </form>

    <!-- 作者列表 -->
    <div id="authorTable">
        <!-- 搜尋結果將動態填充至此 -->
        <?php
        if ($result && $result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>作者ID</th>
                        <th>作者</th>
                        <th>書籍</th>
                        <th>操作</th>
                    </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['authorId']) . "</td>
                        <td>" . htmlspecialchars($row['authorname']) . "</td>
                        <td>" . htmlspecialchars($row['booknames']) . "</td>
                        <td class='action-buttons'>
                    
                            <a href='admin_edit_author.php?authorId=" . htmlspecialchars($row['authorId']) . "'>修改</a>
                            <a href='admin_manage_authors.php?delete=" . htmlspecialchars($row['authorId']) . "' onclick=\"return confirm('確定要刪除這位作者嗎？')\">刪除</a>
                        </td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>沒有符合條件的作者。</p>";
        }
        ?>
    </div>
</div>
<script>
        // 實時顯示搜尋結果
        function showBooks(str) {
            var xhttp;
            if (str == "") {
                document.getElementById("bookTable").innerHTML = "";
                return;
            }
            xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("bookTable").innerHTML = this.responseText;
                }
            };
            // 使用 GET 方法將搜尋關鍵字發送到伺服器端
            xhttp.open("GET", "admin_manage_book.php?search_keyword=" + str, true);
            xhttp.send();
        }
<body>
</script>


</body>
</html>
