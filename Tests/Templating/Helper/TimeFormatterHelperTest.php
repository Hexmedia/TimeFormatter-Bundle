<?php

namespace Hexmedia\TimeFormatterBundle\Tests\Templating\Helper;

use Hexmedia\TimeFormatterBundle\Templating\Helper\TimeFormatterHelper;
use Symfony\Component\Translation\Translator;

/**
 * Time formatter helper test class
 */
class TimeFormatterHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var TimeFormatterHelper 
     */
    private $helper;
    /**
     *
     * @var Translator
     */
    private $translator;

    /**
     * Test constructor
     */
    public function __construct()
    {
        $this->translator = new Translator("en");

        $this->helper = new TimeFormatterHelper($this->translator);
    }

    /**
     * Testing simple ago format.
     */
    public function testSimpleAgo()
    {
        $this->makeTest('now -1 year', "Year ago", true);
        $this->makeTest('now -1 month', "Month ago", true);
        $this->makeTest('now -1 day', "Few days ago", true);
        $this->makeTest('now -2 hour', "Few hours ago", true);
        $this->makeTest('now -1 hour', "Hour ago", true);
        $this->makeTest('now -30 minutes', "Half hour ago", true);
        $this->makeTest('now -1 minute', "5 minutes ago", true);
        $this->makeTest('now -1 second', "Minute ago", true);
    }

    /**
     * Testing simple next format.
     */
    public function testSimpleNext()
    {
        $this->makeTest('now +1 year', "Next year", true);
        $this->makeTest('now +1 month', "Next month", true);
        $this->makeTest('now +1 day', "Tomorrow", true);
        $this->makeTest('now +1 hour', "Next hour", true);
        $this->makeTest('now +30 minutes', "In next half hour", true);
        $this->makeTest('now +5 minute', "In next 5 minutes", true);
        $this->makeTest('now +2 minute', "In next 5 minutes", true);
        $this->makeTest('now +1 second', "In less than minute", true);
    }

    /**
     * Testing normal ago format.
     */
    public function testNormalAgo()
    {
        $this->makeTest('now -1 year', "Year ago");
        $this->makeTest('now -1 month', "Month ago");
        $this->makeTest('now -1 day', "Yesterday");
        $this->makeTest('now -1 hour', "Hour ago");
        $this->makeTest('now -30 minutes', "30 minutes ago");
        $this->makeTest('now -1 minute', "Minute ago");
        $this->makeTest('now -1 second', "Second ago");
    }

    /**
     * Testing normal next format.
     */
    public function testNormalNext()
    {
        $this->makeTest('now +1 year', "Next year");
        $this->makeTest('now +1 month', "Next month");
        $this->makeTest('now +1 day', "Tomorrow");
        $this->makeTest('now +1 hour', "Next hour");
        $this->makeTest('now +30 minutes', "In next 30 minutes");
        $this->makeTest('now +5 minute', "In next 5 minutes");
        $this->makeTest('now +2 minute', "In next 2 minutes");
        $this->makeTest('now +2 second', "In next 2 seconds");
        $this->makeTest('now +1 second', "Next second");
    }

    /**
     * Create test.
     * 
     * @param string $date
     * @param string $expect
     * @param bool $simple
     */
    private function makeTest($date, $expect, $simple = false)
    {
        $now = new \DateTime('now');
        $second = new \DateTime($date);

        $result = $this->helper->formatTime($second, $now, $simple ? "simple" : null);

        $this->assertEquals($result, $expect);
    }

}

?>
