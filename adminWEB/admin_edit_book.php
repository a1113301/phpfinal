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

// 獲取書籍ID
if (isset($_GET['bookId'])) {
    $bookId = intval($_GET['bookId']);
} else {
    die("無效的書籍ID");
}

// 獲取書籍信息
$sql = "SELECT bookname, authorId, catId FROM book WHERE bookId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bookId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    die("未找到該書籍");
}
$book = $result->fetch_assoc();

// 獲取作者和分類列表
$author_query = "SELECT authorId, authorname FROM author";
$category_query = "SELECT catId, catname FROM category";
$authors = $conn->query($author_query)->fetch_all(MYSQLI_ASSOC);
$categories = $conn->query($category_query)->fetch_all(MYSQLI_ASSOC);

// 處理表單提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookname = $_POST['bookname'];
    $authorId = $_POST['author'];
    $categoryId = $_POST['category'];

    $update_sql = "UPDATE book SET bookname = ?, authorId = ?, catId = ? WHERE bookId = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("siii", $bookname, $authorId, $categoryId, $bookId);

    if ($stmt->execute()) {
        $message = "書籍修改成功";
    } else {
        $message = "書籍修改失敗: " . $stmt->error;
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
    <title>修改書籍</title>
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
        /* 初始状态下没有下划线 */
        .nav-link {
            text-decoration: none;
            color: white; /* 设置链接文字颜色为白色 */
            margin-right: 20px; /* 添加右边距 */
        }

        /* 鼠标悬停时显示下划线 */
        .nav-link:hover {
            text-decoration: underline;
        }

        /* 下拉菜单样式 */
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

        /* 中心对齐 */
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
            background-color: #CC0000; /* 悬停时的颜色 */
        }
    </style>
</head>
<body class="is-preload">

<?php include("list.inc");?>
    <div class="main-content">
        <h1>修改書籍</h1>
        <?php if (isset($message)) { echo '<p>' . htmlspecialchars($message) . '</p>'; } ?>
        <form method="POST" action="">
            <label for="bookname">書名</label>
            <input type="text" id="bookname" name="bookname" value="<?php echo htmlspecialchars($book['bookname']); ?>" required>
            <br>
            <label for="author">作者</label>
            <select id="author" name="author" required>
                <?php foreach ($authors as $author): ?>
                    <option value="<?php echo $author['authorId']; ?>" <?php if ($author['authorId'] == $book['authorId']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($author['authorname']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="category">類別</label>
            <select id="category" name="category" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['catId']; ?>" <?php if ($category['catId'] == $book['catId']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($category['catname']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br>
            <input type="submit"class="button primary fit" value="修改書籍">
        </form>
    </div>
</body>
</html>
