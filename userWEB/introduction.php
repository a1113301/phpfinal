<?php session_start();?>
<!DOCTYPE HTML>
<html>
<head>
    <?php include("head.inc"); ?>
    <style>
        .image img {
            width: 200%;
            height: auto;
            object-fit: cover;
            display: block;
        }
    </style>
</head>
<body class="is-preload">

    <!-- Header -->
    <?php include("list.inc"); ?>

    <!-- Heading -->
    <div id="heading">
        <h1>本館介紹</h1>
    </div>

    <!-- Main -->
    <section id="main" class="wrapper">
        <div class="inner">
            <div class="content">
                <header><h2>館史介紹</h2></header>
                <p>高大圖書館創立於2024年5月28日，致力於提供多元豐富的知識資源與舒適的閱讀環境。圖書館自成立以來，不僅收藏了大量的紙本與電子書籍，還引進了最新的數位科技，方便讀者輕鬆查詢和借閱資料。秉持著服務社群的宗旨，高大圖書館舉辦各類讀書會、講座及文化活動，成為了學術交流和社會互動的重要場所。短短時間內，圖書館已成為了市民們學習、交流與休閒的心靈港灣。</p>
                <hr><h3>創立初衷</h3>
                <p>想過必修，丁丁真帥，手下留情，好人有好報</p>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="wrapper">
        <div class="inner">
            <header class="special"><h2>圖書館負責人</h2></header>
            <div class="testimonials">
                <section>
                    <div class="content">
                        <blockquote>
                            <p>帥哥1號</p>
                        </blockquote>
                        <div class="author">
                            <div class="image">
                                <img height="256" width="256" src="../images/pic01.jpg" alt="">
                            </div>
                            <p class="credit">- <strong>林建宏</strong> <span> - 董事長</span></p>
                        </div>
                    </div>
                </section>
                <section>
                    <div class="content">
                        <blockquote>
                            <p>帥哥2號</p>
                        </blockquote>
                        <div class="author">
                            <div class="image">
                                <img height="256" width="256" src="../images/pic03.jpg" alt="">
                            </div>
                            <p class="credit">- <strong>馬寬宇</strong> <span> - 網站美編</span></p>
                        </div>
                    </div>
                </section>
                <section>
                    <div class="content">
                        <blockquote>
                            <p>帥哥3號</p>
                        </blockquote>
                        <div class="author">
                            <div class="image">
                                <img height="256" width="256" src="../images/pic02.jpg" alt="">
                            </div>
                            <p class="credit">- <strong>余俊恩</strong> <span> - 網站後台</span></p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>

    <!-- Copyright -->
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
