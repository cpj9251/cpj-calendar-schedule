<?php

namespace CPJ\CalendarSchedule;

// use CPJ\CalendarSchedule\Admin\AdminPage;

use src\Admin\AdminPage;

class Plugin {

	/**
	 * Constructor
	 */
	public function __construct() {
		AdminPage::init();
	}

}
