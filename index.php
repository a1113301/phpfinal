<?php
session_start();

// 連接到數據庫
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phpfinal_library";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}

// 查詢最新新增的三本書
$new_books_sql = "SELECT bookname, image FROM book ORDER BY bookid DESC LIMIT 3";
$new_books_result = $conn->query($new_books_sql);

$new_books = [];
if ($new_books_result->num_rows > 0) {
    while($row = $new_books_result->fetch_assoc()) {
        $new_books[] = $row;
    }
}

// 查詢最熱門的三本書
$popular_books_sql = "SELECT book.bookname, book.image, COUNT(borrow.bookid) AS borrow_count 
                      FROM borrow 
                      JOIN book ON borrow.bookid = book.bookid 
                      GROUP BY borrow.bookid 
                      ORDER BY borrow_count DESC 
                      LIMIT 3";
$popular_books_result = $conn->query($popular_books_sql);

$popular_books = [];
if ($popular_books_result->num_rows > 0) {
    while ($row = $popular_books_result->fetch_assoc()) {
        $popular_books[] = $row;
    }
}

// 關閉數據庫連接
$conn->close();
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>高大圖書館</title>
    <link rel="icon" href="./images/LOGO.png" type="image/x-icon">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <style>
        .book-image {
            width: 100%;
            height: auto;
            max-height: 200px; /* 可以根據需要調整這個值 */
            object-fit: contain;
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
    <nav id="menu">
        <ul class="links">
            <?php
            if (isset($_SESSION['userlog']) && $_SESSION['userlog'] == true) {
                $username = $_SESSION["username"];
                echo '<li>' . $username . ' 您好' . '</li>';
            }
            ?>
            <li><a href="./index.php">首頁</a></li>
            <?php
            if (isset($_SESSION['userlog']) && $_SESSION['userlog']) {
                echo '<li><a href="./userWEB/logout.php">登出</a></li>
                      <li><a href="./userWEB/rentbook.php">借書系統</a></li>
                      <li><a href="./userWEB/history.php">歷史紀錄</a></li>
                      <li><a href="./userWEB/callus.php">聯絡我們</a></li>';
            } else {
                echo '<li><a href="./userWEB/login.php">登入</a></li>
                      <li><a href="./userWEB/signup.php">註冊</a></li>
                      <li><a href="./userWEB/adminlogin.php">員工</a></li>
                      <li><a href="./userWEB/login.php">聯絡我們</a></li>';
            }
            ?>
            <li><a href="./userWEB/introduction.php">圖書館介紹</a></li>
            <li><a href="./userWEB/news.php">最新消息</a></li>
            <li><a href="./userWEB/collection.php">館藏查詢</a></li>
        </ul>
    </nav>

    <!-- Banner -->
    <section id="banner">
        <div class="inner">
            <h1>高大圖書館</h1>
            <p>選擇一本屬於自己的好書<br /></p>
        </div>
        <video autoplay loop muted playsinline src="images/高大1.mp4"></video>
    </section>

    <!-- Highlights -->
    <section class="wrapper">
        <div class="inner">
            <header class="special">
                <h2>新書推薦</h2>
                <p>新上架好書推薦</p>
            </header>
            <div class="highlights">

                <?php foreach ($new_books as $book): ?>
                <section>
                    <div class="content">
                        <header>
                            <?php
                                if (isset($_SESSION['userlog']) && $_SESSION['userlog']) {
                                    echo "<a href='./userWEB/rentbook.php?search_keyword=".$book["bookname"]."&search_field=bookname'  title='123'><img src='./userWEB/" . $book["image"] . "' alt='Book Image' class='book-image'></a>";
                                }
                                else {
                                    echo "<a href='./userWEB/login.php'  title='123'><img src='./userWEB/" . $book["image"] . "' alt='Book Image' class='book-image'></a>";
                                }
                            ?>
                            <h3><?php echo htmlspecialchars($book['bookname']); ?></h3>
                        </header>
                    </div>
                </section>
                <?php endforeach; ?>
            </div>

        </div>
    </section>

    <!-- CTA -->

    <!-- Testimonials -->
    <!-- Popular Books -->
    <section class="wrapper">
        <div class="inner">
            <header class="special">
                <h2>熱門書籍</h2>
                <p>暢銷好書推薦</p>
            </header>
            <div class="highlights">
                <?php foreach ($popular_books as $book): ?>
                <section>
                    <div class="content">
                        <header>
                            <?php
                            if (isset($_SESSION['userlog']) && $_SESSION['userlog']) {
                                echo "<a href='./userWEB/rentbook.php?search_keyword=".$book["bookname"]."&search_field=bookname'  title='123'><img src='./userWEB/" . $book["image"] . "' alt='Book Image' class='book-image'></a>";
                            } else {
                                echo "<a href='./userWEB/login.php'  title='123'><img src='./userWEB/" . $book["image"] . "' alt='Book Image' class='book-image'></a>";
                            }
                            ?>
                            <h3><?php echo htmlspecialchars($book['bookname']); ?></h3>
                        </header>
                    </div>
                </section>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/browser.min.js"></script>
    <script src="assets/js/breakpoints.min.js"></script>
    <script src="assets/js/util.js"></script>
    <script src="assets/js/main.js"></script>
    <div id="copyright" class="copyright">
        連絡電話：07 5919000 <br/>
        聯絡地址：高雄市楠梓區大學南路700號
    </div>
</body>
</html>
