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
    $bookId = intval($_GET['delete']);
    $delete_sql = "DELETE FROM book WHERE bookId = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $bookId);
    if ($stmt->execute()) {
        $message = "書籍已成功刪除";
    } else {
        $message = "刪除書籍失敗：" . $stmt->error;
    }
    $stmt->close();
}

// 設定每頁顯示的記錄數
$records_per_page = 20;

// 獲取當前頁碼
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $current_page = intval($_GET['page']);
} else {
    $current_page = 1;
}

// 獲取搜尋關鍵字和字段
$search_keyword = isset($_GET['search_keyword']) ? $conn->real_escape_string($_GET['search_keyword']) : '';
$search_field = isset($_GET['search_field']) ? $conn->real_escape_string($_GET['search_field']) : 'bookname';

// 計算偏移量
$offset = ($current_page - 1) * $records_per_page;

// 執行查詢來獲取符合條件的書籍數據
$sql = "SELECT SQL_CALC_FOUND_ROWS book.bookId, book.bookname, category.catname, author.authorname 
        FROM book 
        JOIN category ON book.catId = category.catId
        JOIN author ON book.authorId = author.authorId
        WHERE {$search_field} LIKE '%{$search_keyword}%'
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
<?php include("head.inc"); ?>
<head>
    
<style>
        /* 初始狀態下沒有下劃線 */
        .nav-link {
            text-decoration: none;
            color: white; /* 設置連結文字顏色為白色 */
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
            max-width: 1000px;
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
            background-color: #CC0000; /* 滑鼠懸停時的顏色 */
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

    <style>
    /* 修改樣式，使其與搜尋按鈕相似 */
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
    <h1>管理書籍</h1>
    <!-- 搜尋表單 -->
    <form action="admin_manage_book.php" method="get">
        <input type="text" name="search_keyword" placeholder="輸入搜尋關鍵字">
        <select name="search_field">
            <option value="bookname">書名</option>
            <option value="bookId">書籍ID</option>
            <option value="catname">類別</option>
            <option value="authorname">作者</option>
        </select>
        <button type="submit"  class="button primary icon fa-search">搜尋</button>
    </form>

    <!-- 書籍列表 -->
    <div id="bookTable">
        <!-- 搜尋結果將動態填充至此 -->
        <?php
        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>書籍ID</th>
                        <th>書名</th>
                        <th>類別</th>
                        <th>作者</th>
                        <th>操作</th>
                    </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['bookId']) . "</td>
                        <td>" . htmlspecialchars($row['bookname']) . "</td>
                        <td>" . htmlspecialchars($row['catname']) . "</td>
                        <td>" . htmlspecialchars($row['authorname']) . "</td>
                        <td class='action-buttons'>
                            <a href='admin_edit_book.php?bookId=" . htmlspecialchars($row['bookId']) . "'>修改</a>
                            <a href='admin_manage_book.php?delete=" . htmlspecialchars($row['bookId']) . "' onclick=\"return confirm('確定要刪除這本書嗎？')\">刪除</a>
                        </td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>沒有找到符合條件的書籍。</p>";
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
</script>


</body>
</html>
