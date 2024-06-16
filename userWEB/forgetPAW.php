<?php
session_start();
// 建立與資料庫的連線
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phpfinal_library";

$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線是否成功
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 定義錯誤訊息變數
$error = "";

// 檢查表單是否提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 獲取表單數據
    $username = $_POST['username'];
    $email = $_POST['email'];

    // 檢查帳號和email是否匹配
    $check_query = "SELECT * FROM user WHERE username='$username' AND email='$email'";
    $result = $conn->query($check_query);
    if ($result->num_rows == 1) {
        // 如果帳號和email相匹配，將用戶帳號存入SESSION，以便在changePAW.php中使用
        $_SESSION['reset_username'] = $username;
        
        // 重定向到 changePAW.php
        header("Location: changePAW.php");
        exit();
    } else {
        // 如果帳號和email不匹配，顯示錯誤訊息
        $error = "帳號或Email錯誤";
    }
}

$conn->close();
?>

<!DOCTYPE HTML>
<html>
	<head>
        <?php include("head.inc"); ?>
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
        <?php include("list.inc"); ?>
        <section id="main" class="wrapper">
            <div class="inner">
                <div class="content" style="display: flex; justify-content: center; align-items: center; height: 80vh; text-align: center;">
                    <header style="text-align: center;">
                        <h2 >忘記密碼</h2> </br>
                        <h4>請輸入您的帳號以及email來重新設定密碼</h4>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                            <div class="row gtr-uniform" >
                                <div class="col-12 col-12-xsmall" style="text-align: center;">
                                    帳號　<input type="text" name="username" id="username" value="" placeholder="username" ><br/>
                                    Email　<input type="text" name="email" id="email" value="" placeholder="email">
                                </div>

                                <div class="col-12" style="display: flex; justify-content: center; align-items: center; height: 10vh; text-align: center;">
                                    <ul class="actions"><li><input type="submit" value="登入" class="primary"></li>
                                    </ul></div>
                            </div>
                        </form>
                        <?php if (isset($error)) echo "<p>$error</p>"; ?>
                    </header>
                </div>
            </div>
        </section>
        <div id="copyright " class="copyright">
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
