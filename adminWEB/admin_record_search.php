<?php
// 建立与数据库的连接
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phpfinal_library";

$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接是否成功
if ($conn->connect_error) {
    die("連接數據庫失敗：" . $conn->connect_error);
}

// 处理搜索表单提交
$search_keyword = isset($_GET['search_keyword']) ? $_GET['search_keyword'] : '';
$search_field = isset($_GET['search_field']) ? $_GET['search_field'] : 'bookname';

// SQL查询，联合查询相关表
$sql = "SELECT book.bookId, book.bookname, category.catname, author.authorname, user.username, user.id, book.status, borrow.borrowdate, borrow.returndate
        FROM book
        JOIN category ON book.catId = category.catId
        JOIN author ON book.authorId = author.authorId
        LEFT JOIN borrow ON book.bookId = borrow.bookId
        LEFT JOIN user ON borrow.id = user.id
        WHERE {$search_field} LIKE '%{$search_keyword}%'";
$result = $conn->query($sql);
// 检查查询是否成功
if (!$result) {
    die("查詢失敗：" . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<?php include("head.inc")?>
<head>

<style>
        /* CSS樣式 */
        .nav-link {
            text-decoration: none;
            color: white;
            margin-right: 20px;
        }

        .nav-link:hover {
            text-decoration: underline;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 120px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
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

        .main-content {
            text-align: center;
            margin: 50px auto;
            max-width: 1000px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
            background-color: #FF0000;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
        }

        .main-content form input[type="submit"]:hover {
            background-color: #CC0000;
        }

        input[type="text"],
        select {
            width: 300px;
            padding: 10px;
            margin: 10px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .action-buttons a {
            background-color: #7B7B7B;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            text-decoration: none;
        }

        .action-buttons a:hover {
            background-color: #CC0000;
        }
    </style>
</head>
<body>
<!-- 固定按钮 -->
<?php include "list.inc"; ?>

<!-- 主内容 -->
<div class="main-content">
    <h1>租借記錄查詢</h1>
    <!-- 搜索表单 -->
    <form action="admin_record_search.php" method="get">
        <input type="text" name="search_keyword" placeholder="搜尋">
        <select name="search_field">
            <option value="bookname">書名</option>
            <option value="username">會員名字</option>
            <option value="catname">類別</option>
            <option value="authorname">作者</option>
        </select>
        <button type="submit" class="button primary icon fa-search">搜尋</button>
    </form>
    <!-- 借阅记录表格 -->
    <div id="recordTable">
        <?php
        // SQL 查询
        $sql = "SELECT book.bookId, book.bookname, category.catname, author.authorname, user.username, user.id, book.status, borrow.borrowdate, borrow.returndate
                FROM borrow
                INNER JOIN book ON borrow.bookId = book.bookId
                JOIN category ON book.catId = category.catId
                JOIN author ON book.authorId = author.authorId
                LEFT JOIN user ON borrow.id = user.id
                WHERE {$search_field} LIKE '%{$search_keyword}%'";

        $result = $conn->query($sql);

                    // 检查查询是否成功
                    if (!$result) {
                        die("查詢失敗：" . mysqli_error($conn));
                    }
        
                    if ($result->num_rows > 0) {
                        echo "<table>
                                <tr>
                                    <th>書籍Id</th>
                                    <th>書名</th>
                                    <th>類別</th>
                                    <th>作者</th>
                                    <th>會員Id</th>
                                    <th>會員名字</th>
                                    <th>是否出租</th>
                                    <th>租借時間</th>
                                    <th>歸還時間</th>
                                    <th>最晚歸還時間</th>
                                    <th>是否超時</th> <!-- 新增是否超時列 -->
                                </tr>";
                        while ($row = $result->fetch_assoc()) {
                            // 計算最晚歸還時間
                            $borrowdate = strtotime($row['borrowdate']);
                            $returndate = strtotime($row['returndate']);
                            $latest_returndate = $borrowdate + (30 * 24 * 60 * 60); // 加 30 天
        
                            // 檢查是否超時
                            $is_overdue = ($returndate > $latest_returndate) ? '是' : '否';
                            $font_color = ($is_overdue == '是') ? 'red' : 'black';
        
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['bookId']) . "</td>
                                    <td>" . htmlspecialchars($row['bookname']) . "</td>
                                    <td>" . htmlspecialchars($row['catname']) . "</td>
                                    <td>" . htmlspecialchars($row['authorname']) . "</td>
                                    <td>" . htmlspecialchars($row['id']) . "</td>
                                    <td>" . htmlspecialchars($row['username']) . "</td>
                                    <td>" . ($row['status'] == 1 ? '是' : '否') . "</td>
                                    <td>" . htmlspecialchars($row['borrowdate']) . "</td>
                                    <td>" . htmlspecialchars($row['returndate']) . "</td>
                                    <td>" . date('Y-m-d', $latest_returndate) . "</td>
                                    <td style='color: $font_color;'>" . $is_overdue . "</td> <!-- 標註超時 -->
                                </tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>没有符合的租借記錄</p>";
                    }
                    ?>
                </div>
            </div>
        </body>
        </html>
            
