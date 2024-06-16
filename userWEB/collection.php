<?php ob_start(); session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>高大圖書館</title>
    <?php include("head.inc"); ?>
    <style>
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

        .book-box {
            display: flex;
            align-items: center;
            margin: 20px auto;
            width: 80%;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
        }

        .book-image {
            max-width: 150px;
            margin-right: 20px;
        }

        .book-details {
            flex-grow: 1;
        }

        .book-details p {
            margin: 5px 0;
        }

        .results {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .main-content {
            padding-top: 50px;
            text-align: center;
            width: 70%; 
            margin: 0 auto;
        }

        .book-box {
            display: flex;
            flex-direction: column; 
            align-items: center;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
            width: calc(30% - 20px); 
            margin-right: 20px; 
            margin-left: 0;
            float: left;
        }

        .book-image {
            max-width: 80%;
            height: auto;
            margin-bottom: 10px;
        }

        .book-details p {
            margin: 3px 0;
        }

        #show-all-books {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #show-all-books:hover {
            background-color: #45a049;
        }
    </style>
</head>

<!-- Header -->
<?php include("list.inc"); ?>

<div class="main-content">
    <h1>館藏查詢</h1>
    <!-- 搜尋表單 -->
    <form method="POST" action="collection.php">
        <input type="text" id="query" name="query" placeholder="輸入書名或作者">
        <input type="submit" value="搜尋" class="primary">
    </form>
    <div id="recordTable">
    <?php
// 連接到資料庫
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "phpfinal_library";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}

// 查詢資料庫
$sql = "SELECT book.bookname, author.authorname, category.catname, book.image 
        FROM book 
        INNER JOIN author ON book.authorId = author.authorId 
        INNER JOIN category ON book.catId = category.catId";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = $_POST['query'];
    if($query != NULL)
        $sql .= " WHERE book.bookname LIKE '%$query%' OR author.authorname LIKE '%$query%'";
}

$result = $conn->query($sql);

echo "<div class='results'>";
if ($result->num_rows > 0) {
    // 輸出資料
    while($row = $result->fetch_assoc()) {
        echo "<div class='book-box'>";
        if (isset($_SESSION['userlog']) && $_SESSION['userlog']) {
            echo "<a href='./rentbook.php?search_keyword=".$row["bookname"]."&search_field=bookname'  title='123'><img src='" . $row["image"] . "' alt='Book Image' class='book-image'></a>";
        }
        else {
            echo "<a href='./login.php'  title='123'><img src='" . $row["image"] . "' alt='Book Image' class='book-image'></a>";
        }
        echo "<div class='book-details'>";
        echo "<p><label>書名:</label> " . $row["bookname"]. "</p>";
        echo "<p><label>作者:</label> " . $row["authorname"]. "</p>";
        echo "<p><label>類別:</label> " . $row["catname"]. "</p>";
        echo "</div></div>";
    }
} else {
    echo "没有找到相關結果";
}
echo "</div>";

$conn->close();
?>
    </div>
    </div>
    <div id="copyright" class="copyright">
    	連絡電話：07-5919000 <br/>
        聯絡地址：高雄市楠梓區大學南路700號
    </div>

    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/browser.min.js"></script>
    <script src="../assets/js/breakpoints.min.js"></script>
    <script src="../assets/js/util.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>