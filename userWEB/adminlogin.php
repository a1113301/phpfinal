<?php
session_start();

$_SESSION['userlog'] = false;
$_SESSION['username'] = "";
// 檢查用戶是否提交表單
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 獲取輸入的username和密碼
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 連結資料庫
    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $dbname = "phpfinal_library";

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    if ($conn->connect_error) {
        die("連接失敗: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM stuff WHERE stuffname = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // 檢查密碼是否匹配
        if ($row['password'] == $password) {
            // 匹配成功，保存到 session 中
            $_SESSION['adminlog'] = true; 
            header("Location: ../adminWEB/admin.php"); 
            exit();
        } else {
            // 匹配失敗，顯示密碼錯誤
            $error = "密碼錯誤";
        }
    } else {
        $error = "用戶名不存在";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>高大圖書館</title>
		<meta charset="utf-8" />
        <link rel="icon" href="../images/LOGO.png" type="image/x-icon">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link rel="stylesheet" href="../assets/css/main.css" />
	</head>
	<body class="is-preload">

			<header id="header">
				<a class="logo" href="index.php">高大圖書館</a>
				<nav>
					<a href="#menu">Menu</a>
				</nav>
			</header>

        <?php include("./list.inc"); ?>
        <section id="main" class="wrapper">
            <div class="inner">
                <div class="content" style="display: flex; justify-content: center; align-items: center; height: 80vh; text-align: center;">
                    <header style="text-align: center;">
                        <h2 >員工登入</h2>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                            <div class="row gtr-uniform" >
                                <div class="col-12 col-12-xsmall" style="text-align: center;">
                                    帳號　<input type="text" name="username" id="username" value="" placeholder="username" ><br/>
                                    密碼　<input type="password" name="password" id="password" value="" placeholder="password">
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

        <script src="../assets/js/jquery.min.js"></script>
        <script src="../assets/js/browser.min.js"></script>
        <script src="../assets/js/breakpoints.min.js"></script>
        <script src="../assets/js/util.js"></script>
        <script src="../assets/js/main.js"></script>
    </body></html>
		
	</body>
</html>