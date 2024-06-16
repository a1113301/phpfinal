<?php
session_start();

// 連接到資料庫
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

// 關閉資料庫連接
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>高大圖書館</title>
    <?php include("head.inc"); ?>
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
<?php include("list.inc"); ?>

<!-- Heading -->
<div id="heading">
    <h1>最新消息</h1>
</div>

<section class="wrapper">
    <div class="inner">
        <header class="special">
            <h2>新書上架</h2>
            <p>我們最新新增了下面的書，對這些題材有興趣的會員們，歡迎去借閱喔!</p>
        </header>
        <div class="highlights">
            <?php foreach ($new_books as $book): ?>
                <section>
                    <div class="content">
                        <header>
                            <?php
                                if (isset($_SESSION['userlog']) && $_SESSION['userlog']) {
                                    echo "<a href='./rentbook.php?search_keyword=".$book["bookname"]."&search_field=bookname'  title='123'><img src='./" . $book["image"] . "' alt='Book Image' class='book-image'></a>";
                                }
                                else {
                                    echo "<a href='./login.php'  title='123'><img src='./" . $book["image"] . "' alt='Book Image' class='book-image'></a>";
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

<section class="wrapper">
    <div class="inner">
        <header class="special">
            <h2>特殊活動</h2>
            <p>特殊的活動</p>
        </header>
        <div class="highlights">
            <section>
                <div class="content">
                    <header>
                        <a href="https://lic.nuk.edu.tw/p/406-1012-62231,r5.php?Lang=zh-tw">
                            <img src="../images/active1.png" alt="Icon" style="width: 250px; height: 125px;px;"></a>
                    </header>
                    <p>圖資高等教育深耕計畫專任人員徵才</p>
                </div>
            </section>
            <section>
                <div class="content">
                    <header>
                        <a href="https://lic.nuk.edu.tw/p/412-1012-127.php?Lang=zh-tw">
                            <img src="../images/active2.jpg" alt="Icon" style="width: 250px; height: 125px;px;"></a>
                    </header>
                    <p>視訊會議軟體使用說明</p>
                </div>
            </section>
            <section>
                <div class="content">
                    <header>
                        <a href="https://lic.nuk.edu.tw/p/406-1012-7734,r4.php?Lang=zh-tw">
                            <img src="../images/active3.jpg" alt="Icon" style="width: 250px; height: 125px;px;"></a>
                    </header>
                    <p>近期勒索軟體 Locky 活動頻繁，請提高警覺</p>
                </div>
            </section>
        </div>
    </div>
</section>

<!-- CTA -->
<section id="cta" class="wrapper">
    <div class="inner">
        <h2>特別推薦</h2>
        <p>很特別的推薦</p>
    </div>
</section>

<section class="wrapper">
    <div class="inner" style="text-align: center;">
        <header class="special">
            <h2>電影原型-沙丘</h2>
            <p>新上架電影好書推薦</p>
        </header>
        <div class="highlights" style="display: flex; justify-content: center; align-items: center;">
            <!-- 電影介紹 -->
            <section style="margin: 10px;">
                <div class="content">
                    <header>
                    <a href="https://www.youtube.com/watch?v=HiAK5IRCITc&ab_channel=%E8%8F%AF%E7%B4%8D%E5%85%84%E5%BC%9F%E5%8F%B0%E7%81%A3">
                    <img src="../images/desert.png" alt="Icon" style="width: 200px; height: 200px;"></a>
                        <h3>沙丘</h3>
                    </header>
                    <p>一個非常棒的電影</p>
                </div>
            </section>
            <!-- 其他電影介紹 -->
            <!-- ... -->
        </div>
    </div>
</section>

	<!-- 版權資訊 -->
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
		</body></html>

</body>
</html>