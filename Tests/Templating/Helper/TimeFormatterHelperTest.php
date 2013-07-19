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
     * @var TimeFormatterHelper
     */
    private $helper;

    /**
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
        $this->makeTest('now -1 year', "a year ago", true);
        $this->makeTest('now -1 month', "a month ago", true);
        $this->makeTest('now -1 day', "yesterday", true);
        $this->makeTest('now -2 day', "few days ago", true);
        $this->makeTest('now -2 hour', "few hours ago", true);
        $this->makeTest('now -1 hour', "an hour ago", true);
        $this->makeTest('now -30 minutes', "half hour ago", true);
        $this->makeTest('now -12 minutes', "15 minutes ago", true);
        $this->makeTest('now -1 minute', "a minute ago", true);
        $this->makeTest('now -1 second', "a minute ago", true);
    }

    /**
     * Testing simple next format.
     */
    public function testSimpleNext()
    {
        $this->makeTest('now +1 year', "next year", true);
        $this->makeTest('now +1 month', "next month", true);
        $this->makeTest('now +1 day', "tomorrow", true);
        $this->makeTest('now +1 hour', "next hour", true);
        $this->makeTest('now +30 minutes', "in next half hour", true);
        $this->makeTest('now +5 minute', "in next 15 minutes", true);
        $this->makeTest('now +2 minute', "in next 15 minutes", true);
        $this->makeTest('now +1 second', "in next minute", true);
    }

    /**
     * Testing normal ago format.
     */
    public function testNormalAgo()
    {
        $this->makeTest('now -1 year', "a year ago");
        $this->makeTest('now -1 month', "a month ago");
        $this->makeTest('now -1 day', "yesterday");
        $this->makeTest('now -1 hour', "an hour ago");
        $this->makeTest('now -30 minutes', "half hour ago");
        $this->makeTest('now -1 minute', "a minute ago");
        $this->makeTest('now -1 second', "a second ago");
    }

    /**
     * Testing normal next format.
     */
    public function testNormalNext()
    {
        $this->makeTest('now +1 year', "next year");
        $this->makeTest('now +1 month', "next month");
        $this->makeTest('now +1 day', "tomorrow");
        $this->makeTest('now +1 hour', "next hour");
        $this->makeTest('now +30 minutes', "in next half hour");
        $this->makeTest('now +5 minute', "in next 5 minutes");
        $this->makeTest('now +2 minute', "in next 2 minutes");
        $this->makeTest('now +2 second', "in next 2 seconds");
        $this->makeTest('now +1 second', "next second");
    }

    /**
     * Create test.
     *
     * @param string $date
     * @param string $expect
     * @param bool   $simple
     */
    private function makeTest($date, $expect, $simple = false)
    {
        $now = new \DateTime('now');
        $second = new \DateTime($date);

        $result = $this->helper->formatTime($second, $now, $simple ? "simple" : null);

        $this->assertEquals($result, $expect);
    }

}
