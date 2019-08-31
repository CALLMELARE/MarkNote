<?php
require_once dirname(__FILE__) . '/include/user.php';
require_once dirname(__FILE__) . '/include/note.php';

if (!hasLogin()) {
	echo 'Please login';
	exit();
}

if (hasLogin() && isset($_POST['action']) && $_POST['action'] == 'getNotelist') {
	$theNotebooks = getUserNotebooks($USERNAME);
	// var_dump($theNotebooks);
	foreach ($theNotebooks as $value) {
		if (is_int($value)) {
			?>
			<div id="notelist-item-<?php echo $value ?>" class="notelist-item notelist-item-single" title="<?php echo getNoteTitle($value); ?>" onclick="loadNote(<?php echo $value ?>);"><i class="fa fa-file-text" aria-hidden="true"></i><?php echo getNoteTitle($value); ?></div>
		<?php
		}
		if (is_array($value)) {
			?>
			<div class="notelist-item-single" style="height: 1px;"></div>
			<div class="notelist-folder">
				<i class="fa fa-angle-down fa-lg i-notelist-folder-arrow" aria-hidden="true"></i>
				<div class="notelist-item notebook-opened notelist-item-notebook-title" onclick="toggleNotebook(this);"><i class="fa fa-book" aria-hidden="true"></i><?php echo $value[0]; ?></div>
				<?php
				foreach ($value as $note) {
					if (is_int($note)) {
						?>
						<div id="notelist-item-<?php echo $note ?>" class="notelist-item notelist-item-subnote" title="<?php echo getNoteTitle($note); ?>" onclick="loadNote(<?php echo $note ?>);"><i class="fa fa-file-text" aria-hidden="true"></i><?php echo getNoteTitle($note); ?></div>
					<?php
					}
				}
				?>
				<div class="notelist-item notelist-item-subnote2" onclick="newSubnote('<?php echo $value[0]; ?>');">
					<i class="fa fa-plus" aria-hidden="true"></i>
					新页面
				</div>
			</div><?php
				}
			}
			?>
	<div class="notelist-item-single" style="height: 1px;"></div>
	<div class="notelist-item" onclick="newNote();">
		<i class="fa fa-plus" aria-hidden="true"></i>
		新页面
	</div>
	<div class="notelist-item" onclick="newNotebook();">
		<i class="fa fa-plus" aria-hidden="true"></i>
		新笔记本
	</div>
	<?php
	exit();
}

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<title>MarkNote</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

	<link href="https://cdn.bootcss.com/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.bootcss.com/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>

	<script src="//cdn.bootcss.com/jquery/2.2.0/jquery.min.js"></script>
	<script src="//cdn.bootcss.com/marked/0.3.6/marked.min.js"></script>
	<script src="//cdn.bootcss.com/ace/1.2.5/ace.js"></script>
	<!-- <script src="//cdn.bootcss.com/prism/0.0.1/prism.min.js"></script> -->
	<!-- <script src="//cdn.bootcss.com/mathjax/2.5.3/MathJax.js"></script> -->
	<script src="//cdn.bootcss.com/mathjax/2.6.1/MathJax.js?config=TeX-MML-AM_CHTML"></script>
	<script src="//cdn.bootcss.com/Sortable/1.4.2/Sortable.min.js"></script>
	<script src="include/js/edit.js"></script>
	<script src="include/js/prism.js"></script>

	<link href="//cdn.bootcss.com/font-awesome/4.6.3/css/font-awesome.css" rel="stylesheet">
	<link href="//cdn.bootcss.com/octicons/3.5.0/octicons.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="include/css/edit.css">
	<link rel="stylesheet" type="text/css" href="include/css/prism.css">


	<script type="text/javascript" src="include/js/jquery.min.js"></script>
	<script type="text/javascript" src="include/js/message.js"></script>

</head>

<body>

	<div id="header">
		<h1 id="header-title">MarkNote</h1>
		<div id="header-user">
			<div id="header-user-head">
				<i class="fa fa-user fa-2x" aria-hiddem="true" style="margin: 7px 0px 0px 5px;"></i>
			</div>
			<span id="header-user-name"><?php echo $USERNAME; ?></span>
			<span id="header-user-emailandlogout"><?php echo getUserEmail($USERNAME); ?> | <a style="cursor: pointer;" onclick="$('#header-user-logoutform').submit();">登出</a></span>
			<a id="mobile-logout" style="cursor: pointer;" onclick="$('#header-user-logoutform').submit();">登出</a>
			<form id="header-user-logoutform" method="post" action="login.php">
				<input type="hidden" name="type" value="logout">
			</form>

		</div>
	</div>


	<div id="content">
		<div id="toolbar">
			<div id="fold-bar" class="toolbar-icon" onclick="foldSidebar()" style="padding-left: 17px;"><span class="octicon octicon-three-bars" style="font-size: 19px;"></span></span></div>
			<div class="toolbar-icon" title="新页面" onclick="newNoteBelow()" style="padding-left: 17px;"><span class="octicon octicon-file-text" style="font-size: 19px;"></span></span></div>
			<div class="toolbar-icon" title="新笔记本" onclick="newNotebook()"><span class="octicon octicon-file-directory"></span></div>
			<div class="toolbar-icon" title="保存" onclick="saveNote()"><i class="fa fa-lg fa-floppy-o" aria-hidden="true"></i></div>
			<!-- <div class="toolbar-icon" title="Search" onclick="EditorAce.config.loadModule('ace/ext/searchbox');" style="padding-left: 14px;"><span class="octicon octicon-search"></span></div>
			<div class="toolbar-icon" title="Settings" onclick=""><span class="octicon octicon-gear"></span></div> -->
			<div class="toolbar-icon" title="关于" onclick="infoCard()" style="padding-left: 17px;"><span class="octicon octicon-info" style="font-size: 19px;"></span></span></div>
		
		</div>
		<div id="sidebar">
			<div id="sidebar-status">当前状态: <span id="sidebar-status-icon">●</span> <span id="sidebar-status-text">页面加载中...</span></div>
			<div id="sidebar-notelist">载入</div>
		</div>
		<div id="editor">
			<div id="editor-ace"></div>
			<div id="editor-move"></div>
			<div id="editor-show"></div>
			<div id="editor-show-preprocess"></div>
		</div>
	</div>

	<div id="contextmenu-1" class="contextmenu">
		<div class="contextmenu-item" onclick="noteContextClick('open');"> <i class="fa fa-file" aria-hidden="true"></i> 打开</div>
		<div class="contextmenu-item" onclick="noteContextClick('rename');"> <i class="fa fa-edit" aria-hidden="true"></i> 重命名</div>
		<div class="contextmenu-item" onclick="noteContextClick('clone');"> <i class="fa fa-clone" aria-hidden="true"></i> 复制</div>
		<!-- <div class="contextmenu-item" onclick="noteContextClick('share');"> <i class="fa fa-share-alt" aria-hidden="true"></i> 分享</div>
		<div class="contextmenu-item" onclick="noteContextClick('export');"> <i class="fa fa-external-link " aria-hidden="true"></i> 导出</div> -->
		<div class="contextmenu-item" onclick="noteContextClick('delete');"> <i class="fa fa-trash" aria-hidden="true"></i> 删除</div>
		<div class="contextmenu-item" onclick="noteContextClick('properties');"> <i class="fa fa-info-circle" aria-hidden="true"></i> 属性</div>
	</div>

	<div id="contextmenu-2" class="contextmenu">
		<div class="contextmenu-item"><i class="fa fa-file" aria-hidden="true"></i> 打开</div>
		<div class="contextmenu-item"><i class="fa fa-edit" aria-hidden="true"></i> 重命名</div>
		<div class="contextmenu-item"><i class="fa fa-clone" aria-hidden="true"></i> 复制</div>
		<!-- <div class="contextmenu-item"><i class="fa fa-share-alt" aria-hidden="true"></i> 分享</div> -->
		<div class="contextmenu-item"><i class="fa fa-trash" aria-hidden="true"></i> 删除</div>
	</div>

	<div id="page-glass" onclick="hideProperties();"></div>
	<div id="sidebar-properties">
		<div id="sidebar-properties-header">
			<i class="fa fa-file-text fa-3x" aria-hidden="true"></i>
			<span id="sidebar-properties-header-notename">页面名称</span>
			<span id="sidebar-properties-header-notetype">页面类型</span>
		</div>
		<div id="sidebar-properties-body">
			<span class="sidebar-properties-body-lable">名称 </span><span id="sidebar-properties-body-name"></span><br>
			<span class="sidebar-properties-body-lable">最后修改于 </span><span id="sidebar-properties-body-lastmodify"></span><br>
			<span class="sidebar-properties-body-lable">最后访问于 </span><span id="sidebar-properties-body-lastaccess"></span><br>

		</div>
	</div>

	<input id="float-input" type="text">

	<div id="float-notsaved-lable">●</div>


</body>

</html>