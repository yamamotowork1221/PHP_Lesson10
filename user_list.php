<?php
mb_internal_encoding("utf8");

try {
    $pdo = new PDO("mysql:dbname=php_practice;host=localhost;", "root", ""); //DBに接続
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //エラーモードを例外に設定

    $stmt = $pdo->query("SELECT * FROM user");
} catch (PDOException $e) {
    $e->getMessage(); //例外発生時にエラーメッセージを出力
}

$pdo = null;
//文字列キーによる配列としてテーブルを全行取得
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザーリスト</title>
    <style>
        td {
            padding: 7px 40px;
        }
    </style>
</head>

<body>
    <h1>ユーザーリスト</h1>
    <div class="confirm">
        <table>
            <tr>
                <th>ID</th>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>年齢</th>
                <th>コメント</th>
            </tr>
            <?php
            foreach ($users as $user) {
                echo "<tr>\n";
                echo "<td>{$user['id']}</td>\n";
                echo "<td>{$user['name']}</td>\n";
                echo "<td>{$user['mail']}</td>\n";
                echo "<td>{$user['age']}</td>\n";
                echo "<td>{$user['comments']}</td>\n";
                echo "</tr>\n";
            }
            ?>
        </table>
    </div>
</body>

</html>