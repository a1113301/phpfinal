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

        <?php include("list.inc"); ?>
        <section id="main" class="wrapper">
            <div class="inner">
                <div class="content" style="display: flex; justify-content: center; align-items: center; height: 80vh; text-align: center;">
                    <header style="text-align: center;">
                        <h2 >重新設定密碼</h2> </br>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                            <div class="row gtr-uniform" >
                                <div class="col-12 col-12-xsmall" style="text-align: center;">
                                    新密碼　<input type="password" name="new_password" id="username" value="" placeholder="username" ><br/>
                                    再次輸入新密碼　<input type="password" name="new_password" id="email" value="" placeholder="email">
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
        <script src="../assets/js/main.js"></script></body></html>
		
	</body>
</html>