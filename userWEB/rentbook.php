<?php
session_start();
// 建立與資料庫的連接
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phpfinal_library";

$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接是否成功
if ($conn->connect_error) {
    die("連接數據庫失敗：" . $conn->connect_error);
}

// 處理提交搜尋
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search_keyword'])) {
    $search_keyword = $_GET['search_keyword'];
    $search_field = $_GET['search_field'];

    // 使用 prepared statements 和綁定參數來構建 SQL 查詢
    $sql = "SELECT book.bookId, book.bookname, book.image, category.catname, author.authorname, book.status, book.borrowdate, book.returndate
            FROM book
            JOIN category ON book.catId = category.catId
            JOIN author ON book.authorId = author.authorId
            WHERE {$search_field} LIKE ?";
    
    $stmt = $conn->prepare($sql);
    $search_keyword = "%{$search_keyword}%";
    $stmt->bind_param("s", $search_keyword);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // 如果未提交搜索表單，則顯示所有書籍信息
    $sql = "SELECT book.bookId, book.bookname, book.image, category.catname, author.authorname, book.status, book.borrowdate, book.returndate
            FROM book
            JOIN category ON book.catId = category.catId
            JOIN author ON book.authorId = author.authorId";
    $result = $conn->query($sql);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>高大圖書館</title>
    <?php include("head.inc"); ?>
    <style>
        /* 初始狀態下没有下划線 */
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

        .main-content {
            text-align: center;
            margin: 50px auto;
            max-width: 800px; 
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
            padding: 1px; 
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


<!-- Header -->
<?php include("list.inc"); ?>

<div class="main-content">
    <h1>租借系統</h1>
    <!-- 搜尋表單 -->
    <form action="rentbook.php" method="get">
        <input type="text" name="search_keyword" placeholder="搜尋">
            <select name="search_field">
                <option value="bookname">書名</option>
                <option value="catname">類別</option>
                <option value="authorname">作者</option>
            </select>
            <input type="submit" value="搜尋" class="primary">
    </form>
    <div id="recordTable">
        <!-- 搜尋結果將動態填充至此 -->
        <?php
if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>書名</th>
                <th>類別</th>
                <th>作者</th>
                <th>是否出租</th>
                <th>租借時間</th>
                <th>最晚歸還時間</th>
                <th>操作</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['bookname']) . "</td>
                <td>" . htmlspecialchars($row['catname']) . "</td>
                <td>" . htmlspecialchars($row['authorname']) . "</td>
                <td>" . ($row['status'] == 1 ? '是' : '否') . "</td>
                <td>" . htmlspecialchars($row['borrowdate']) . "</td>
                <td>" . htmlspecialchars($row['returndate']) . "</td>
                <td>";
        if ($row['status'] == 0) {
            echo "<form action='rentbook.php' method='post'>
                    <input type='hidden' name='bookId' value='" . $row['bookId'] . "'>
                    <button type='submit' name='rent'>借閱</button>
                  </form>";
        } else {
            // 檢查當前登入用戶是否是借閱者
            $bookId = $row['bookId'];
            $username = $_SESSION["username"];
            $check_borrower_query = "SELECT * FROM borrow 
                                     JOIN user ON borrow.id = user.id 
                                     WHERE borrow.bookId = '$bookId' AND user.username = '$username' AND borrow.returndate IS NULL";
            $check_borrower_result = $conn->query($check_borrower_query);
            if ($check_borrower_result->num_rows > 0) {
                echo "<form action='rentbook.php' method='post'>
                        <input type='hidden' name='bookId' value='" . $row['bookId'] . "'>
                        <button type='submit' name='return'>歸還</button>
                      </form>";
            } else {
                echo "已借出";
            }
        }
        echo "</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "<p>没有符合的租借記錄</p>";
    // 若沒有找到紀錄，則出現所有書籍資訊
    $sql_all_books = "SELECT book.bookId, book.bookname, book.image, category.catname, author.authorname, book.status, book.borrowdate, book.returndate
                      FROM book
                      JOIN category ON book.catId = category.catId
                      JOIN author ON book.authorId = author.authorId";
    $result_all_books = $conn->query($sql_all_books);
    if ($result_all_books->num_rows > 0) {
        echo "<table>
                <tr>
                    <th>書名</th>
                    <th>類別</th>
                    <th>作者</th>
                    <th>是否出租</th>
                    <th>租借時間</th>
                    <th>最晚歸還時間</th>
                    <th>操作</th>
                </tr>";
        while ($row = $result_all_books->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['bookname']) . "</td>
                    <td>" . htmlspecialchars($row['catname']) . "</td>
                    <td>" . htmlspecialchars($row['authorname']) . "</td>
                    <td>" . ($row['status'] == 1 ? '是' : '否') . "</td>
                    <td>" . htmlspecialchars($row['borrowdate']) . "</td>
                    <td>" . htmlspecialchars($row['returndate']) . "</td>
                    <td>";
            if ($row['status'] == 0) {
                echo "<form action='rentbook.php' method='post'>
                        <input type='hidden' name='bookId' value='" . $row['bookId'] . "'>
                        <button type='submit' name='rent'>借閱</button>
                      </form>";
            } else {
                //檢查當前登入用戶是否是借閱者
                $bookId = $row['bookId'];
                $username = $_SESSION["username"];
                $check_borrower_query = "SELECT * FROM borrow 
                                         JOIN user ON borrow.id = user.id 
                                         WHERE borrow.bookId = '$bookId' AND user.username = '$username' AND borrow.returndate IS NULL";
                $check_borrower_result = $conn->query($check_borrower_query);
                if ($check_borrower_result->num_rows > 0) {
                    echo "<form action='rentbook.php' method='post'>
                            <input type='hidden' name='bookId' value='" . $row['bookId'] . "'>
                            <button type='submit' name='return'>歸還</button>
                          </form>";
                } else {
                    echo "已借出";
                }
            }
            echo "</td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>没有書籍資訊</p>";
    }
}
?>

<?php
// 借閱按钮提交
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rent'])) {
    // 獲取要借閱的書籍ID
    $bookId = $_POST['bookId'];

    // 獲取當前日期
    $borrowdate = date('Y-m-d');

    // 計算歸還日期（我們設定為借閱日期30天後）
    $returndate = date('Y-m-d', strtotime($borrowdate . ' +30 days'));

    // 獲取當前搜尋的用戶名
    $username = $_SESSION["username"];

    // 查詢與當前搜尋中的用戶名匹配的用戶ID
    $SELECT_userid = "SELECT id FROM user WHERE username = '$username'";
    $result_userid = $conn->query($SELECT_userid);

    // 檢查查尋結果是否有效
    if ($result_userid->num_rows > 0) {
        // 提取用戶ID
        $row = $result_userid->fetch_assoc();
        $userid = $row['id'];

        // 插入借書紀錄到借書表
        $sql_insert_borrow = "INSERT INTO borrow (bookId, id, borrowdate) VALUES ($bookId, $userid, '$borrowdate')";

        // 執行插入查詢
        if ($conn->query($sql_insert_borrow) === TRUE) {
            // 更新書籍狀態成已借出
            $sql_update_status = "UPDATE book SET status = 1, borrowdate = '$borrowdate', returndate = '$returndate' WHERE bookId = $bookId";

            // 執行更新查詢
            if ($conn->query($sql_update_status) === TRUE) {
                echo "<script>alert('借閱成功！');</script>";
                // 刷新頁面
                echo "<meta http-equiv='refresh' content='0'>";
            } else {
                echo "Error updating record: " . $conn->error;
            }
        } else {
            echo "Error inserting record: " . $conn->error;
        }
    } else {
        echo "No user found with username: $username";
    }
}

// 歸還按鈕設定
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['return'])) {
    // 獲取要歸還的書籍ID
    $bookId = $_POST['bookId'];

    // 獲取當前日期
    $returnDate = date('Y-m-d');

    // 更新書籍狀態為未借出
    $sql_update_status = "UPDATE book SET status = 0, borrowdate = NULL, returndate = NULL WHERE bookId = $bookId";

    // 更新 borrow 表中的 returndate 為當前日期，但只更新該書籍ID的紀錄，並且只更新尚未歸還的紀錄
    $sql_update_borrow = "UPDATE borrow SET returndate = '$returnDate' WHERE bookId = $bookId AND returndate IS NULL LIMIT 1";

    // 執行更新查询
    if ($conn->query($sql_update_status) === TRUE && $conn->query($sql_update_borrow) === TRUE) {
        echo "<script>alert('歸還成功！');</script>";
        // 刷新頁面
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "Error: " . $conn->error;
    }
}

?>
    </div>
    </div>
    <div id="copyright" class="copyright">
    	連絡電話：07-5919000 <br/>
        聯絡地址：高雄市楠梓區大學南路700號
    </div>

	
    <!-- Scripts -->
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/browser.min.js"></script>
    <script src="../assets/js/breakpoints.min.js"></script>
    <script src="../assets/js/util.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>