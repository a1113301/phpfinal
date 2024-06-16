<?php
session_start();
$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true ? 'true' : 'false';

// 連接到資料庫
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "phpfinal_library";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}

// 查詢書籍類別和數量
$category_sql = "SELECT catname, COUNT(*) AS count FROM book INNER JOIN category ON book.catId = category.catId GROUP BY book.catId";
$category_result = $conn->query($category_sql);

$category_labels = [];
$category_counts = [];

if ($category_result->num_rows > 0) {
    while($row = $category_result->fetch_assoc()) {
        $category_labels[] = $row["catname"];
        $category_counts[] = $row["count"];
    }
}

// 查詢借閱狀態
$borrowed_sql = "SELECT COUNT(*) AS count FROM book WHERE status=1";
$borrowed_result = $conn->query($borrowed_sql);
$borrowed_count = 0;
if ($borrowed_result->num_rows > 0) {
    $borrowed_row = $borrowed_result->fetch_assoc();
    $borrowed_count = $borrowed_row["count"];
}

// 查詢書籍總數
$total_books_sql = "SELECT COUNT(*) AS total FROM book";
$total_books_result = $conn->query($total_books_sql);
$total_books_count = 0;
if ($total_books_result->num_rows > 0) {
    $total_books_row = $total_books_result->fetch_assoc();
    $total_books_count = $total_books_row["total"];
}

// 查詢熱門書籍
$popular_books_sql = "SELECT book.bookname, COUNT(borrow.bookId) AS borrow_count FROM borrow INNER JOIN book ON borrow.bookId = book.bookId GROUP BY borrow.bookId ORDER BY borrow_count DESC LIMIT 5";
$popular_books_result = $conn->query($popular_books_sql);

$popular_books = [];
$popular_counts = [];

if ($popular_books_result->num_rows > 0) {
    while($row = $popular_books_result->fetch_assoc()) {
        $popular_books[] = $row["bookname"];
        $popular_counts[] = $row["borrow_count"];
    }
}

// 關閉資料庫連接
$conn->close();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<?php include("head.inc"); ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>整體圖表分析</title>
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
            max-width: 90%;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .main-content h1 {
            margin-bottom: 20px;
        }

        .form-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .chart-container {
            width: 45%;
            margin-bottom: 20px;
        }

        .chart {
            width: 100%;
            height: 300px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="is-preload">

<!-- Header -->
<?php include("list.inc"); ?>
<!-- Highlights -->
<div class="main-content">
    <h1>整體圖表分析</h1>
    <div class="form-container">
        <div class="chart-container">
            <h3>書籍類別長條圖</h3>
            <div class="chart">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
        <div class="chart-container">
            <h3>書籍借閱狀況圓餅圖</h3>
            <div class="chart">
                <canvas id="borrowedChart"></canvas>
            </div>
        </div>
		<div class="chart-container">
            <h3>熱門書籍前五名</h3>
            <div class="chart">
                <canvas id="popularBooksChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    // 書籍類別長條圖
    var ctx1 = document.getElementById('categoryChart').getContext('2d');
    var categoryChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($category_labels); ?>,
            datasets: [{
                label: '書籍數量',
                data: <?php echo json_encode($category_counts); ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // 書籍借閱狀況圓餅圖
    var ctx2 = document.getElementById('borrowedChart').getContext('2d');
    var borrowedChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['可借閱', '借閱中'],
            datasets: [{
                label: '書籍借閱狀況',
                data: [<?php echo $total_books_count - $borrowed_count; ?>, <?php echo $borrowed_count; ?>],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 99, 132, 0.2)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

	// 熱門書籍前五名
	var ctx3 = document.getElementById('popularBooksChart').getContext('2d');
    var popularBooksChart = new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($popular_books); ?>,
            datasets: [{
                label: '借閱次數',
                data: <?php echo json_encode($popular_counts); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

</body>
</html>
