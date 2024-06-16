<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("head.inc"); ?>
        <style>
        .main-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin: 0 auto;
            max-width: 600px; 
            padding: 20px; 
        }

        .main-content form {
            width: 100%;
        }

        .main-content textarea {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box; 
        }
    </style>
</head>
<body class="is-preload">

		<!-- Header -->
        <?php include("list.inc"); ?>
        <!-- 聯絡我們 -->
        <div class="main-content">
            <h1>聯絡我們</h1>
            <p>感謝您對我們的回饋，您的回饋是我們繼續努力的動力</p>

            <form action="process_feedback.php" method="post">
                <p>請輸入您想回饋意見:</p>
                <textarea name="sComment" rows="10" cols="50"></textarea>
                <br/>
                <input type="submit" value="送出">
             <input type="reset" value="清除">
            </form>
        </div>
        

        <script src="../assets/js/jquery.min.js"></script>
        <script src="../assets/js/browser.min.js"></script>
        <script src="../assets/js/breakpoints.min.js"></script>
        <script src="../assets/js/util.js"></script>
        <script src="../assets/js/main.js"></script>
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

    <div id="copyright" class="copyright">
        連絡電話：07-5919000 <br/>
        聯絡地址：高雄市楠梓區大學南路700號
    </div>
</body>
</html>