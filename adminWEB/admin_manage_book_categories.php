<?php
// 建立与数据库的连接
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phpfinal_library";

// 建立连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接是否成功
if ($conn->connect_error) {
    die("连接数据库失败：" . $conn->connect_error);
}

// 处理删除操作
if (isset($_GET['delete'])) {
    $catId = intval($_GET['delete']);
    
    // 删除相关的书籍
    $delete_books_sql = "DELETE FROM book WHERE catId = ?";
    $stmt_books = $conn->prepare($delete_books_sql);
    $stmt_books->bind_param("i", $catId);
    if ($stmt_books->execute()) {
        // 删除类别
        $delete_category_sql = "DELETE FROM category WHERE catId = ?";
        $stmt_category = $conn->prepare($delete_category_sql);
        $stmt_category->bind_param("i", $catId);
        if ($stmt_category->execute()) {
            $message = "类别及其相关书籍已成功删除";
        } else {
            $message = "删除类别失败：" . $stmt_category->error;
        }
        $stmt_category->close();
    } else {
        $message = "删除相关书籍失败：" . $stmt_books->error;
    }
    $stmt_books->close();
}

// 设置每页显示的记录数
$records_per_page = 10;

// 获取当前页码
$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// 获取搜索关键字和字段
$search_keyword = isset($_GET['search_keyword']) ? $_GET['search_keyword'] : '';
$search_field = isset($_GET['search_field']) ? $_GET['search_field'] : 'catname';

// 计算偏移量
$offset = ($current_page - 1) * $records_per_page;

// 准备查询语句
$sql = "SELECT SQL_CALC_FOUND_ROWS category.catId, category.catname, GROUP_CONCAT(book.bookname SEPARATOR ', ') AS booknames
        FROM category 
        LEFT JOIN book ON book.catId = category.catId
        WHERE {$search_field} LIKE ?
        GROUP BY category.catId
        LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$search_keyword = '%' . $search_keyword . '%'; // 添加通配符以实现模糊搜索
$stmt->bind_param("sii", $search_keyword, $offset, $records_per_page);
$stmt->execute();
$result = $stmt->get_result();

// 获取总记录数
$total_records_sql = "SELECT FOUND_ROWS() as total_records";
$total_records_result = $conn->query($total_records_sql);
$total_records_row = $total_records_result->fetch_assoc();
$total_records = $total_records_row['total_records'];

// 计算总页数
$total_pages = ceil($total_records / $records_per_page); 

// 关闭连接
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
            max-width: 1000px; /* 增加主容器的最大寬度 */
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
            text-align: center; /* 使表單內容居中 */
            width: 100%;
        }

        .main-content form input[type="text"],
        .main-content form button {
            display: block;
            width: 100%;
            max-width: 400px;
            margin: 0 auto 10px auto; /* 使輸入框和按鈕居中 */
        }

        .main-content form input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .main-content form button {
            background-color: 	#BEBEBE; /* 修改為藍色 */
            color: white;
            border: none;
            border-radius: 10px;
            padding: 1px 5px; /* 調整按鈕大小 */
            cursor: pointer;
            font-size: 15px;
            width: 80px; /* 設置按鈕寬度 */
            height: 45px; /* 設置按鈕高度 */
        }

        .main-content form button:hover {
            background-color: 	#6C6C6C; /* 滑鼠懸停時的顏色 */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .action-buttons a:hover {
            text-decoration: underline;
        }
    </style>
    <style>
    /* 修改樣式，使其與搜尋按鈕相似 */
    .action-buttons a {
        background-color: 	#7B7B7B; /* 修改為紅色 */
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

<!-- 主内容 -->
<div class="main-content">
    <h1>管理類別</h1>
    <!-- 搜索表單 -->
    <form action="admin_manage_book_categories.php" method="get">
        <input type="text" name="search_keyword" placeholder="輸入搜尋關鍵字">
        <button type="submit"  class="button primary icon fa-search">搜尋</button>
    </form>

    <!-- 類別列表 -->
    <div id="categoryTable">
        <!-- 搜尋結果將動態填入此 -->
        <?php
        if ($result && $result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>類別ID</th>
                        <th>類別名稱</th>
                        <th>書籍名稱</th>
                        <th>操作</th>
                    </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['catId']) . "</td>
                        <td>" . htmlspecialchars($row['catname']) . "</td>
                        <td>" . htmlspecialchars($row['booknames']) . "</td>
                        <td class='action-buttons'>
                            <a href='admin_edit_category.php?catId=" .htmlspecialchars($row['catId']) . "'\">修改</a>
                            <a href='admin_manage_book_categories.php?delete=" . htmlspecialchars($row['catId']) . "' onclick=\"return confirm('確定要刪除這個類別嗎嗎？')\">刪除</a>
                            
                        </td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>沒有符合條件的類別。</p>";
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
