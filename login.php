<?php
require_once 'include/user.php';

if (isset($_POST['type'])) {
	if ($_POST['type'] == 'login') {
		login($_POST['username'], $_POST['passwd']);
	}
	if ($_POST['type'] == 'register') {
		register($_POST['username'], $_POST['email'], $_POST['passwd'], $_POST['nickname']);
	}
	if ($_POST['type'] == 'logout') {
		logout();
	}
}

if (hasLogin()) {
	header("location: ./");
} else {
	if (!(isset($_GET['t']) && $_GET['t'] == 'register')) {

		?>
		<!DOCTYPE html>
		<html>

		<head>
			<meta charset="utf-8" />
			<title>MarkNote</title>
			<meta http-equiv="X-UA-Compatible" content="IE=edge" />
			<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

			<link rel="stylesheet" type="text/css" href="include/css/login.css">
		</head>

		<body>
			<h1 class="title">MarkNote</h1>

			<form method="post" action="login.php" class="login-form">
				<p class="login-text">用户名</p>
				<input class="input-text" type="text" name="username" autofocus="autofocus" />
				<p class="login-text">密码</p>
				<input class="input-text" type="password" name="passwd" />

				<input class="input-btn" type="submit" name="submit" value="登录" />
				<input type="hidden" name="type" value="login" />

			</form>

			<p style="text-align: center;"><a href="login.php?t=register">还没有账户?在此注册</a></p>

		</body>

		</html>
	<?php } else { ?>
		<!DOCTYPE html>
		<html>

		<head>
			<meta charset="utf-8" />
			<title>MarkNote</title>
			<meta http-equiv="X-UA-Compatible" content="IE=edge" />
			<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

			<link rel="stylesheet" type="text/css" href="include/css/login.css">
		</head>

		<body>
			<h1 class="title">注册</h1>

			<form method="post" action="login.php">
				用户名
				<input class="input-text" type="text" name="username" autofocus="autofocus" />
				昵称
				<input class="input-text" type="text" name="nickname" />
				密码
				<input class="input-text" type="password" name="passwd" />
				电子邮箱
				<input class="input-text" type="text" name="email" />
				<input class="input-btn" type="submit" name="submit" value="注册" />

				<input type="hidden" name="type" value="register">
			</form>

			<p style="text-align: center;"><a href="./">已有账户?在此登录</a></p>

		</body>

		</html>
	<?php } ?>
<?php }
