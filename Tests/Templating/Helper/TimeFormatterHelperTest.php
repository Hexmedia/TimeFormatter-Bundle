<?php

namespace Hexmedia\TimeFormatterBundle\Tests\Templating\Helper;

use Hexmedia\TimeFormatterBundle\Templating\Helper\TimeFormatterHelper;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;

/**
 * Time formatter helper test class
 */
class TimeFormatterHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    private $languages = array();

    /**
     * Test constructor
     */
    public function __construct()
    {
        $languages = array('en');//, 'pl');

        $yamlFileLoader = new YamlFileLoader();

        $dir = dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'translations';


        foreach ($languages as $lang) {
            $translator = new Translator($lang);
            $translator->addLoader("yml", $yamlFileLoader);

            if ($lang != "en") {
                $translator->addResource("yml", $dir . DIRECTORY_SEPARATOR . "messages.$lang.yml", "$lang", "messages", $lang);
            }

            $helper = new TimeFormatterHelper($translator);

            $this->languages[$lang] = array(
                'translator' => $translator,
                'helper' => $helper
            );
        }
    }

    /**
     * Testing simple ago format year.
     */
    public function testSimpleAgoYear()
    {
        $this->makeTest('year', true, "a year ago|%years% years ago", array(1 => 1, 2 => 2, 5 => 5, 10 => 10), true);
    }

    /**
     * Testing simple ago format month.
     */
    public function testSimpleAgoMonth()
    {
        $this->makeTest('month', true, 'a month ago|%months% months ago', array(1 => 1, 2 => 2, 5 => 5, 10 => 10), true);
    }

    /**
     * Testing simple ago format day.
     */
    public function testSimpleAgoDay()
    {
        $this->makeTest('day', true, "yesterday|%days% days ago", array(1 => 1), true);
        $this->makeSimpleTest("day", true, "few days ago", array(10, 15, 20), true);
    }

    /**
     * Testing simple ago format hour.
     */
    public function testSimpleAgoHours()
    {
        $this->makeTest('hour', true, 'an hour ago|%hours% hours ago', array(1 => 1), true);
        $this->makeSimpleTest("hour", true, "few hours ago", array(10, 15, 20), true);

    }

    /**
     * Testing simple ago format minute.
     */
    public function testSimpleAgoMinutes()
    {
        $this->makeTest('minute', true, "a minute ago|%minutes% minutes ago", array(1 => 1, 12 => 15, 10 => 15), true);
        $this->makeSimpleTest("minute", true, "half hour ago", array(30, 34, 28), true);
        $this->makeSimpleTest("minute", true, "an hour ago", array(46), true);
    }

    /**
     * Testing normal ago format year.
     */
    public function testAgoYear()
    {
        $this->makeTest('year', true, "a year ago|%years% years ago", array(1 => 1, 2 => 2, 5 => 5, 10 => 10));
    }

    /**
     * Testing normal ago format month.
     */
    public function testAgoMonth()
    {
        $this->makeTest('month', true, 'a month ago|%months% months ago', array(1 => 1, 2 => 2, 5 => 5, 10 => 10));
    }

    /**
     * Testing normal ago format day.
     */
    public function testAgoDay()
    {
        $this->makeTest('day', true, "yesterday|%days% days ago", array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 10 => 10, 20 => 20));
    }

    /**
     * Testing normal ago format hour.
     */
    public function testAgoHours()
    {
        $this->makeTest('hour', true, 'an hour ago|%hours% hours ago', array(1 => 1));

    }

    /**
     * Testing normal ago format minute.
     */
    public function testAgoMinutes()
    {
        $this->makeTest('minute', true, "a minute ago|%minutes% minutes ago", array(1 => 1, 12 => 12, 10 => 10, 15 => 15));
        $this->makeSimpleTest("minute", true, "half hour ago", array(30, 34, 28));
    }

    /**
     * Testing normal ago format seconds.
     */
    public function testAgoSeconds()
    {
        $this->makeTest('second', true, 'a second ago|%seconds% seconds ago', array(1 => 1, 12 => 12, 10 => 10, 15 => 15));
    }

    /**
     * Testing simple next format year.
     */
    public function testSimpleNextYear()
    {
        $this->makeTest('year', false, "next year|next %years% years", array(1 => 1, 2 => 2, 5 => 5, 10 => 10), true);
    }

    /**
     * Testing simple next format month.
     */
    public function testSimpleNextMonth()
    {
        $this->makeTest('month', false, 'next month|next %months% months', array(1 => 1, 2 => 2, 5 => 5, 10 => 10), true);
    }

    /**
     * Testing simple next format day.
     */
    public function testSimpleNextDay()
    {
        $this->makeTest('day', false, "tomorrow|next %days% days", array(1 => 1), true);
        $this->makeSimpleTest("day", false, "next few days", array(10 => 10, 15 => 15, 20 => 20));
    }

    /**
     * Testing simple next format hour.
     */
    public function testSimpleNextHours()
    {
        $this->makeTest('hour', false, 'next hour|next %hours% hours', array(1 => 1), true);
        $this->makeSimpleTest("hour", false, "next few hours", array(10, 15, 20), true);

    }

    /**
     * Testing simple next format minute.
     */
    public function testSimpleNextMinutes()
    {
        $this->makeTest('minute', false, "next minute|next %minutes% minutes", array(1 => 1, 12 => 15, 10 => 15), true);
        $this->makeSimpleTest("minute", false, "next half hour", array(30, 34, 28), true);
        $this->makeSimpleTest("minute", false, "next hour", array(46), true);
    }

    /**
     * Testing normal next format year.
     */
    public function testNextYear()
    {
        $this->makeTest('year', false, "next year|next %years% years", array(1 => 1, 2 => 2, 5 => 5, 10 => 10));
    }

    /**
     * Testing normal next format month.
     */
    public function testNextMonth()
    {
        $this->makeTest('month', false, 'next month|next %months% months', array(1 => 1, 2 => 2, 5 => 5, 10 => 10));
    }

    /**
     * Testing normal next format day.
     */
    public function testNextDay()
    {
        $this->makeTest('day', false, 'tomorrow|next %days% days', array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 10 => 10, 20 => 20));
    }

    /**
     * Testing normal next format hour.
     */
    public function testNextHours()
    {
        $this->makeTest('hour', false, 'next hour|next %hours% hours', array(1 => 1));

    }

    /**
     * Testing normal next format minute.
     */
    public function testNextMinutes()
    {
        $this->makeTest('minute', false, 'next minute|next %minutes% minutes', array(1 => 1, 12 => 12, 10 => 10, 15 => 15));
        $this->makeSimpleTest("minute", false, 'next half hour', array(30, 34, 28));
    }

    /**
     * Testing normal next format seconds.
     */
    public function testNextSeconds()
    {
        $this->makeTest('second', false, 'next second|next %seconds% seconds', array(1 => 1, 12 => 12, 10 => 10, 15 => 15));
    }

    /**
     * Create test.
     *
     * @param $type
     * @param $back
     * @param string $expect
     * @param $params
     * @param bool $simple
     * @internal param string $date
     */
    private function makeTest($type, $back, $expect, $params, $simple = false)
    {
        $now = new \DateTime('now');

        foreach ($this->languages as $lang => $data) {
            foreach ($params as $given => $should) {
                $second = clone $now;
                $second->modify(($back ? "-" : "+") . $given . " " . $type);

                $result = $data['helper']->formatTime($second, $now, $simple ? "simple" : null);

                $p = array('%' . $type . 's%' => $should);

                $expected = $data['translator']->transChoice($expect, $should, $p);

                $this->assertEquals($expected, $result);
            }
        }
    }

    private function makeSimpleTest($type, $back, $expect, $params)
    {
        $now = new \DateTime('now');

        foreach ($this->languages as $lang => $data) {
            foreach ($params as $given) {
                $second = clone $now;
                $second->modify(($back ? "-" : "+") . $given . " " . $type);

                $result = $data['helper']->formatTime($second, $now, "simple");

                $expected = $data['translator']->trans($expect);

                $this->assertEquals($expected, $result);
            }
        }
    }
}
