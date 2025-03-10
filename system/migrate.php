<?php
/**
 * Database migrations
 *
 * @package   MyAAC
 * @author    Slawkens <slawkens@gmail.com>
 * @copyright 2019 MyAAC
 * @link      https://my-aac.org
 */
defined('MYAAC') or die('Direct access not allowed!');

// database migrations
$tmp = '';
if(fetchDatabaseConfig('database_version', $tmp)) { // we got version
	$tmp = (int)$tmp;
	if($tmp < DATABASE_VERSION) { // import if older
		$db->revalidateCache();
		for($i = $tmp + 1; $i <= DATABASE_VERSION; $i++) {
			require SYSTEM . 'migrations/' . $i . '.php';

			if (isset($up)) {
				$up();
				unset($up);
			}

			updateDatabaseConfig('database_version', $i);
		}
	}
}
else { // register first version
	registerDatabaseConfig('database_version', 0);
	$db->revalidateCache();
	for($i = 1; $i <= DATABASE_VERSION; $i++) {
		require SYSTEM . 'migrations/' . $i . '.php';

		if (isset($up)) {
			$up();
			unset($up);
		}

		updateDatabaseConfig('database_version', $i);
	}
}
