<?php

namespace CPJ\CalendarScheduler;

use CPJ\CalendarScheduler\Admin\AdminPage;

class Plugin {

	/**
	 * Constructor
	 */
	public function __construct() {
		AdminPage::init();
	}

}
