<?php
mb_internal_encoding("utf8");

if (!isset($_COOKIE['visitsNum'])) {
    setcookie('visitsNum', 1, time() + 60 * 60 * 24 * 31);
} else {
    $afterNum = $_COOKIE['visitsNum'] + 1;
    setcookie('visitsNum', $afterNum, time() + 60 * 60 * 24 * 31);
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>閲覧した回数</title>
    <style>
        body {
            font-family: "Hirafino Kaku Gorhic RroN", "メイリオ", sans-serif;
        }

        .title {
            width: 500px;
            margin: 0 auto;
            margin-top: 30px;
            margin-bottom: 20px;
            border-left: 4px solid red;
            background-color: #f1f1f1;
            padding-top: 5px;
            padding-bottom: 5px;
            padding-left: 15px;
            font-size: 20px;
        }

        .part {
            width: 500px;
            margin: 0 auto;
            padding: 24px 0;
            border: 3px solid #ebebeb;
            border-radius: 10px;
            background-color: #fbfbfb;
        }

        .item {
            width: 80%;
            display: flex;
            flex-direction: column;
            margin: 0 auto;
            padding: 15px;
        }

        .text,
        textarea {
            padding: 5px;
            border: 1px solid #dfdfdf;
            color: #999;
            background: #fff;
        }
    </style>
</head>

<body>
    <h1 class="title">閲覧した回数</h1>
    <div class="part">
        <div class="item">
            <?php
            if (!isset($_COOKIE['visitsNum'])) {
                echo '<p>初めての訪問です</p>';
            } elseif ($afterNum >= 20) {
                echo '<p>' . $afterNum . '回目の訪問です。訪問履歴をリセットします。</p>';
                setcookie('visitsNum', "", time() - 1);
            } else {
                if ($afterNum % 5 == 0) {
                    echo '<p>' . $afterNum . '回目の訪問です。キリ番おめでとうございます！</p>';
                } else {
                    echo '<p>' . $afterNum . '回目の訪問です。なお、訪問履歴は、最終訪問日から1か月が経過するとリセットされます</p>';
                }
            }
            ?>
        </div>
    </div>
</body>

</html>