<?php
/**
 * @var OTS_DB_MySQL $db
 */

$up = function () use ($db) {
	$db->addColumn(TABLE_PREFIX . 'forum', 'post_html', 'TINYINT(1) NOT NULL DEFAULT 0 AFTER `post_smile`');

	$query = $db->query("SELECT `id` FROM `" . TABLE_PREFIX . "forum_boards` WHERE `name` LIKE " . $db->quote('News') . " LIMIT 1;");
	if ($query->rowCount() == 0) {
		return; // don't make anything
	}

	$query = $query->fetch();
	$id = $query['id'];

	// update all forum threads with is_html = 1
	$db->exec("UPDATE `" . TABLE_PREFIX . "forum` SET `post_html` = 1 WHERE `section` = " . $id . " AND `id` = `first_post`;");
};

$down = function () use ($db) {
	$db->dropColumn(TABLE_PREFIX . 'forum', 'post_html');
};
