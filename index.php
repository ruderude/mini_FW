<?php

require 'common.php';

$db = DB::getInstance();

// $name = filter_input(INPUT_POST, 'name');
$type = '1';
$sql = 'SELECT * FROM users WHERE type = :type';
$params = [
	':type' => $type
];
$rows = $db->select($sql, $params, 0, 10);
?>
<!DOCTYPE HTML>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<title></title>
	</head>
	<body>
		<div>
			<?php if (0 < count($rows)) : ?>
				<table>
					<?php foreach ($rows as $row) : ?>
						<tr>
							<td><?= h($row['id']); ?></td>
							<td><?= h($row['name']); ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php else: ?>
				<div>検索しましたが見つかりませんでした。</div>
			<?php endif; ?>
		</div>
	</body>
</html>