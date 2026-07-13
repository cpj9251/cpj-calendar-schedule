<?php

namespace CPJ\ApptScheduler;

use CPJ\ApptScheduler\Blocks\SchedulerBlock;
use CPJ\ApptScheduler\CustomPostTypes\Appointments;
use CPJ\ApptScheduler\Settings\Settings;

class Plugin {

	/**
	 * Constructor
	 */
	public function __construct() {
		Settings::init();
		Appointments::register();
		SchedulerBlock::init();
	}

}
