<?php

namespace Hexmedia\TimeFormatterBundle\Tests\Templating\Helper;

use Hexmedia\TimeFormatterBundle\Templating\Helper\TimeFormatterHelper;
use Symfony\Component\Translation\Translator;

class TimeFormatterHelperTest extends \PHPUnit_Framework_TestCase {

	private $helper;
	private $translator;

	public function __construct() {
		$this->translator = new Translator("en");

		$this->helper = new TimeFormatterHelper($this->translator);
	}

	public function testSimple1Ago() {
		$this->makeTest('now -1 year', "Year ago", true);
		$this->makeTest('now -1 month', "Month ago", true);
		$this->makeTest('now -1 day', "Few days ago", true);
		$this->makeTest('now -1 hour', "Hour ago", true);
		$this->makeTest('now -2 hour', "Few hours ago", true);
		$this->makeTest('now -1 minute', "About 5 minutes ago", true);
		$this->makeTest('now -1 second', "Less than minute ago", true);
	}

	public function testSimple1Next() {
		$this->makeTest('now +1 year', "Next year", true);
		$this->makeTest('now +1 month', "Next month", true);
		$this->makeTest('now +1 day', "Tomorrow", true);
		$this->makeTest('now +1 hour', "Next hour", true);
		$this->makeTest('now +5 minute', "In next 5 minutes", true);
		$this->makeTest('now +2 minute', "In less than 5 minutes", true);
		$this->makeTest('now +1 second', "In less than minute"
			, true);
	}

	public function testNormal1Ago() {
		$this->makeTest('now -1 year', "Year ago");
		$this->makeTest('now -1 month', "Month ago");
		$this->makeTest('now -1 day', "Yesterday");
		$this->makeTest('now -1 hour', "Hour ago");
		$this->makeTest('now -1 minute', "Minute ago");
		$this->makeTest('now -1 second', "Second ago");
	}

	public function testNormal1Next() {
		$this->makeTest('now +1 year', "Next year");
		$this->makeTest('now +1 month', "Next month");
		$this->makeTest('now +1 day', "Tomorrow");
		$this->makeTest('now +1 hour', "Next hour");
		$this->makeTest('now +5 minute', "In next %minutes% minutes");
		$this->makeTest('now +2 minute', "In next %minutes% minutes");
		$this->makeTest('now +2 second', "In next %seconds% seconds");
		$this->makeTest('now +1 second', "Next second");
	}

	private function makeTest($date, $expect, $simple = false) {
		$now = new \DateTime('now');
		$second = new \DateTime($date);

		$result = $this->helper->formatTime($now, $second, $simple ? "simple" : null);

		$this->assertEquals($result, $expect);
	}

}

?>
