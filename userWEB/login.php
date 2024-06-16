<?php
session_start();
$_SESSION['adminlog'] = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $servername = "localhost";
        $db_username = "root";
        $db_password = "";
        $dbname = "phpfinal_library";

        $conn = new mysqli($servername, $db_username, $db_password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if ($row['password'] == $password) {
                $_SESSION['username'] = $username;
                $_SESSION['userlog'] = true; // 設定登入狀態
                header("Location: ../index.php");
                exit();
            } else {
                $error = "密碼錯誤";
            }
        } else {
            $error = "帳號不存在";
        }

        $stmt->close();
        $conn->close();
    } else {
        $error = "請輸入帳號和密碼";
    }
}
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

    <!-- Main -->
    <section id="main" class="wrapper">
        <div class="inner">
            <div class="content" style="display: flex; justify-content: center; align-items: center; height: 80vh; text-align: center;">
                <header style="text-align: center;">
                    <h2>會員登入</h2>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <div class="row gtr-uniform">
                            <div class="col-12 col-12-xsmall" style="text-align: center;">
                                帳號　<input type="text" name="username" id="username" value="" placeholder="username"><br/>
                                密碼　<input type="password" name="password" id="password" value="" placeholder="password">
                            </div>
                            
                            <!-- Break -->
                            <div class="col-12" style="display: flex; justify-content: center; align-items: center; height: 10vh; text-align: center;">
                                <ul class="actions">
                                    <li><input type="submit" value="登入" class="primary"></li>
                                </ul>
                            </div>
                        </div>
                    </form>
                    <?php if (isset($error)) echo "<p>$error</p>"; ?>
                    <div class="col-6 col-12-medium" style="display: flex; justify-content: center; align-items: center; height: 10vh; text-align: center;">
                        <ul class="actions">
                            <li><a href="forgetPAW.php" class="button">忘記密碼</a></li>
                            <li><a href="signup.php" class="button">尚未註冊</a></li>
                        </ul>
                    </div>
                </header>
            </div>
        </div>
    </section>

    <!-- Footer -->
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
