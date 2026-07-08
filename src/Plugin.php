<?php

namespace CPJ\ApptScheduler;

use CPJ\ApptScheduler\Admin\AdminPage;

class Plugin {

	/**
	 * Constructor
	 */
	public function __construct() {
		AdminPage::init();
	}

}
