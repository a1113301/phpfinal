<header id="header">
    <a class="logo" href="../index.php">高大圖書館</a>
    <nav>
        <a href="#menu">Menu</a>
    </nav>
</header>

<!-- Nav -->	
<nav id="menu">
    <ul class="links">
        <?php
        if (isset($_SESSION['userlog']) && $_SESSION['userlog']) {
            $username = $_SESSION["username"];
            echo '<li>'.$username.' 您好'.'</li>';
        }
        ?>
        <li><a href="../index.php">首頁</a></li>
        <?php
        if (isset($_SESSION['userlog']) && $_SESSION['userlog']) {
            echo '<li><a href="./logout.php">登出</a></li>
                <li><a href="./rentbook.php">借書系統</a></li>
                <li><a href="./history.php">歷史紀錄</a></li>
                <li><a href="./callus.php">聯絡我們</a></li>';
        } else {
            echo '<li><a href="./login.php">登入</a></li>
                <li><a href="./signup.php">註冊</a></li>
                <li><a href="./adminlogin.php">員工</a></li>
                <li><a href="./login.php">聯絡我們</a></li>';
        }
        ?>
        <li><a href="./introduction.php">圖書館介紹</a></li>
        <li><a href="./news.php">最新消息</a></li>
        <li><a href="./collection.php">館藏查詢</a></li>
        
    </ul>
</nav>
