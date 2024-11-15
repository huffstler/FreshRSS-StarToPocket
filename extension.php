<?php

class StarToPocketExtension extends Minz_Extension {
	public function init() {
		$this->registerTranslates();

		// New, watching for star activity
		$this->registerHook('entries_favorite', [$this, 'handleStar']);
		$this->registerController('starToPocket');
		$this->registerViews();
	}

	public function handleConfigureAction() {
		$this->registerTranslates();
		
		if (Minz_Request::isPost()) {
			$keyboard_shortcut = Minz_Request::param('keyboard_shortcut', '');
			FreshRSS_Context::$user_conf->pocket_keyboard_shortcut = $keyboard_shortcut;
			FreshRSS_Context::$user_conf->save();
		}
	}

	/**
	 * if isStarred, send each starredEntry to 
	 * addAction in the controller.
	 */
	public function handleStar(array $starredEntries, bool $isStarred): void {
		$this->registerTranslates();
		foreach ($starredEntries as $entry) {
			if ($isStarred){
				$url_array = [
					'c' => 'starToPocket',
					'a' => 'add',
					'params' => [
						'id' => $entry,
					]
				];
				Minz_Request::forward($url_array);
			}
		}
	}
}
