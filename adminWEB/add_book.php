<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['adminlog']) && $_SESSION['adminlog'] === true;

// 建立與資料庫的連接
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "phpfinal_library";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 初始化變數
$authors = [];
$categories = [];
$message = "";

// 從資料庫中獲取作者和類別
$author_query = "SELECT authorId, authorname FROM author";
$category_query = "SELECT catId, catname FROM category";

$author_result = $conn->query($author_query);
$category_result = $conn->query($category_query);

if ($author_result->num_rows > 0) {
    while ($row = $author_result->fetch_assoc()) {
        $authors[$row['authorId']] = $row['authorname'];
    }
}

if ($category_result->num_rows > 0) {
    while ($row = $category_result->fetch_assoc()) {
        $categories[$row['catId']] = $row['catname'];
    }
}

// 處理表單提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 檔案路徑
    $target_directory = "../uploads/";
    
    if (isset($_FILES["image"]) && $_FILES["image"]["size"] > 0) {
        $target_file = $target_directory . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // 檢查 $uploadOk 是否為 0，如果是，輸出錯誤訊息
        if ($uploadOk == 0) {
            $message = "抱歉，您的檔案未上傳。";
        } else {
            // 如果一切都正確，嘗試上傳檔案
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $message = "檔案 ". basename($_FILES["image"]["name"]). " 已經成功上傳。";

                // 獲取完整的檔案路徑
                $image_path = $target_file;

                $bookname = $_POST['bookname'];
                $authorId = $_POST['author'];
                $categoryId = $_POST['category'];

                // 插入新書籍到資料庫
                $stmt = $conn->prepare("INSERT INTO book (bookname, authorId, catId, image) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("siis", $bookname, $authorId, $categoryId, $image_path);

                if ($stmt->execute()) {
                    $message = "書籍新增成功";
                } else {
                    $message = "書籍新增失敗: " . $stmt->error;
                }

                $stmt->close();
            } else {
                $message = "抱歉，上傳檔案時發生錯誤。";
            }
        }
    } else {
        $message = " ";
    }
}

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

<!-- 主內容 -->
<div class="main-content">
    <div class="form-container">
        <h1>新增書籍</h1>
        <?php if (!empty($message)) { echo '<p>' . htmlspecialchars($message) . '</p>'; } ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
            <label for="bookname">書名:</label>
            <input type="text" id="bookname" name="bookname" required>

            <label for="author">作者:</label>
            <select id="author" name="author" required>
                <option value="">請選擇作者</option>
                <?php foreach ($authors as $authorId => $authorName): ?>
                    <option value="<?php echo htmlspecialchars($authorId); ?>"><?php echo htmlspecialchars($authorName); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="category">類別:</label>
            <select id="category" name="category" required>
                <option value="">請選擇類別</option>
                <?php foreach ($categories as $categoryId => $categoryName): ?>
                    <option value="<?php echo htmlspecialchars($categoryId); ?>"><?php echo htmlspecialchars($categoryName); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="image">上傳圖片:</label>
            <input type="file" id="image" name="image" accept="image/*" required>

            <input type="submit"  class="button primary" value="新增書籍">
        </form>
    </div>
</div>

<!-- JavaScript -->
<script>
    function toggleMemberOptions() {
        var options = document.getElementById("memberOptions");
        if (options.style.display === "block") {
            options.style.display = "none";
        } else {
            options.style.display = "block";
        }
    }
</script>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/browser.min.js"></script>
    <script src="../assets/js/breakpoints.min.js"></script>
    <script src="../assets/js/util.js"></script>
    <script src="../assets/js/main.js"></script>

</body>
</html>
