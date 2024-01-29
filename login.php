<?php
session_start();
mb_internal_encoding("utf8");

#ログイン状態であればマイページにリダイレクト
if (isset($_SESSION['id'])) {
    header("Location:mypage.php");
}

$errors = ""; //変数の初期化

#POST処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    #エスケープ処理
    $input["mail"] = htmlentities($_POST["mail"] ?? "", ENT_QUOTES);
    $input["password"] = htmlentities($_POST["password"] ?? "", ENT_QUOTES);

    #1.バリデーションチェック
    if (!filter_input(INPUT_POST, "mail", FILTER_VALIDATE_EMAIL)) { //メールのバリデーション
        $errors = "メールアドレスとパスワードを正しく入力してください";
    }
    if (strlen(trim($_POST["password"] ?? "")) == 0) { //パスワードのバリデーション
        $errors = "メールアドレスとパスワードを正しく入力してください";
    }

    #2.ログイン認証
    if (empty($errors)) {
        try {
            $pdo = new PDO("mysql:dbname=php_practice;host=localhost;", "root", ""); //DBに接続
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //エラーモードを例外に設定

            #ユーザー情報取得
            $stmt = $pdo->prepare("SELECT * FROM user WHERE mail = ?");
            $stmt->execute(array($input["mail"]));
            $user = $stmt->fetch(PDO::FETCH_ASSOC); //文字列キーによる配列としてテーブル取得
        } catch (PDOException $e) {
            echo mb_convert_encoding($e->getMessage(), 'utf-8', 'sjis'); //例外発生時にエラーメッセージを出力
        }

        $pdo = null; //BD切断

        #ユーザー情報が取り出せた かつ パスワードが一致すれば、セッションに値を代入し、マイページへ遷移
        if ($user && password_verify($input["password"], $user["password"])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['mail'] = $user['mail'];
            $_SESSION['age'] = $user['age'];
            $_SESSION['password'] = $input['password'];
            $_SESSION['comments'] = $user['comments'];

            #｢ログイン情報を保持する｣にチェックがあれば、セッションを保持する。
            if ($_POST['login_keep'] == 1) {
                $_SESSION['login_keep'] = $_POST['login_keep'];
            }

            #｢ログイン情報を保持する｣にチェックがあればクッキーをセット、なければ削除する。
            if (!empty($_SESSION['id']) && !empty($_SESSION['login_keep'])) {
                setcookie('mail', $_SESSION['mail'], time() + 60 * 60 * 24 * 7);
                setcookie('password', $_SESSION['password'], time() + 60 * 60 * 24 * 7);
                setcookie('login_keep', $_SESSION['login_keep'], time() + 60 * 60 * 24 * 7);
            } elseif (empty($_SESSION['login_keep'])) {
                setcookie('mail', '', time() - 1);
                setcookie('password', '', time() - 1);
                setcookie('login_keep', '', time() - 1);
            }
            header("Location:mypage.php");
        } else {
            $errors = "メールアドレスとパスワードを正しく入力してください";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログインページ</title>
    <link rel="stylesheet" type="text/css" href="./style.css">
</head>

<body>
    <h1 class="form_title">ログインページ</h1>
    <form method="POST" action="">
        <div class="item">
            <label>メールアドレス</label>
            <input type="text" class="text" size="35" name="mail" value="<?php
                                                                            if ($_COOKIE['login_keep'] ?? "") {
                                                                                echo $_COOKIE["mail"] ?? "";
                                                                            }
                                                                            ?>">
        </div>
        <div class="item">
            <label>パスワード</label>
            <input type="password" class="text" size="35" name="password" value="<?php if ($_COOKIE['login_keep'] ?? "") {
                                                                                        echo $_COOKIE["password"] ?? "";
                                                                                    } ?>">
            <?php if (!empty($errors)) : ?>
                <p class="err_message"><?php echo $errors; ?></p>
            <?php endif; ?>
        </div>
        <div class="item">
            <label>
                <input type="checkbox" name="login_keep" value="1" <?php
                                                                    if ($_COOKIE['login_keep'] ?? "") {
                                                                        echo "checked='checked'";
                                                                    }
                                                                    ?>>
                ログイン状態を保持する
            </label>
        </div>
        <div class="item">
            <input type="submit" class="submit" value="ログイン">
        </div>
</body>

</html>