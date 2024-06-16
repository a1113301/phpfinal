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

// 確認表單是否被提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 從表單獲取資料
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // 查詢資料庫確保帳號或電話號碼未被使用
    $check_query = "SELECT * FROM user WHERE username='$username' OR phone='$phone'";
    $result = $conn->query($check_query);
    if ($result->num_rows > 0) {
        echo "帳號或電話號碼已被使用";
    } else {
        // 加入新使用者資料
        $insert_query = "INSERT INTO user (name, username, password, email, phone) VALUES ('$name', '$username', '$password', '$email', '$phone')";
        if ($conn->query($insert_query) === TRUE) {
            echo "註冊成功";
            header("Location: login.php");
        } else {
            echo "Error: " . $insert_query . "<br>" . $conn->error;
        }
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
                <div class="content" style="display: flex; justify-content: center; align-items: center; height: 120vh; text-align: center;">
                    <header style="text-align: center;">
                        <h2 >會員註冊</h2>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                            <div class="row gtr-uniform" >
                                <div class="col-12 col-12-xsmall" style="text-align: center;">
                                    姓名　<input type="text" name="name" id="name" value="" placeholder="請輸入本人姓名" ><br/>
                                    帳號　<input type="text" name="username" id="username" value="" placeholder="username" ><br/>
                                    密碼　<input type="password" name="password" id="password" value="" placeholder="password"><br/>
                                    Email　<input type="text" name="email" id="email" value="" placeholder="請使用合法信箱" ><br/>
                                    電話　<input type="text" name="phone" id="phone" value="" placeholder="電話號碼" >
                                </div>
                                
                                <!-- Break -->
                                <div class="col-12" style="display: flex; justify-content: center; align-items: center; height: 10vh; text-align: center;">
                                    <ul class="actions"><li><input type="submit" value="註冊" class="primary"></li>
                                    </ul></div>
                            </div>
                        </form>
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
