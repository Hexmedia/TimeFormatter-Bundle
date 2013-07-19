<?php

namespace Hexmedia\TimeFormatterBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToTimestampTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use \Symfony\Component\Translation\Translator;

/**
 * TimeFormatterHelper
 */
class TimeFormatterHelper extends Helper
{

    /**
     * Translator 
     * 
     * @var Translator
     */
    protected $translator;

    /**
     * Constructor
     * 
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Get name
     * 
     * @return string
     */
    public function getName()
    {
        return 'time_formatter';
    }

    /**
     * As $fromTime and $toTime we can use timestamp as int, @\DateTime or string with format
     * from $dateFormat
     *
     * @param \DateTime|string|int $fromTime
     * @param \DateTime|string|int $toTime
     * @param string               $format
     * @param string               $dateFormat
     * 
     * @TODO: Format to be rewritten to use more specific input
     *
     * @return string
     */
    public function formatTime($fromTime, $toTime = null, $format = null, $dateFormat = "Y-m-d H:i:s")
    {
        if ($toTime == null) {
            $toTime = new \DateTime('now');
        }

        $fromTime = $this->convertFormat($fromTime, $dateFormat);
        $toTime = $this->convertFormat($toTime, $dateFormat);

        $diff = $fromTime->diff($toTime);

        if (strtolower($format) == "simple") {
            return $this->formatSimple($diff);
        } else {
            return $this->formatNormal($diff);
        }
    }

    /**
     * Formating using default format.
     * 
     * @param \DateInterval $diff
     * 
     * @return string
     */
    private function formatNormal(\DateInterval $diff)
    {
        $str = '';
        $first = null;
        $second = null;
        $val = 0;
        if ($diff->y > 0) {
            $str = "year";
            $val = $diff->y;
        } else if ($diff->m > 0) {
            $str = "month";
            $val = $diff->m;
        } else if ($diff->d > 0) {
            $str = "day";
            $first = array('after' => "tomorrow", 'before' => "yesterday");
            $val = $diff->d;
        } else if ($diff->h > 0) {
            $str = "hour";
            $val = $diff->h;
        } else if ($diff->i == 30) {
            return $diff->invert ? $this->translator->trans("in next half hour") : $this->translator->trans("half hour ago");
        } else if ($diff->i > 0) {
            $str = "minute";
            $val = $diff->i;
        } else if ($diff->s > 0) {
            $str = "second";
            $val = $diff->s;
        }
        if ($diff->invert) {
            $id = "%" . $str . "%";

            return $this->translator->transChoice(
                    ($first && isset($first['after']) ? $first['after'] : "next " . $str) . "|" .
                    ($second && isset($second['after']) ? $second['after'] : "in next " . $id . " " . $str . "s"), $val, array(
                    $id => $val
            ));
        } else {
            $id = "%" . $str . "%";

            return $this->translator->transChoice(
                    ($first && isset($first['before']) ? $first['before'] : ($str == "hour" ? 'an' : 'a') . ' ' . $str . ' ago') . "|" .
                    ($second && isset($second['before']) ? $second['before'] : $id . " " . $str . "s ago"), $val, array(
                    $id => $val
            ));
        }
    }

    /**
     * Formating using simple format.
     * 
     * @param \DateInterval $diff
     * 
     * @return string
     */
    private function formatSimple(\DateInterval $diff)
    {
        $str = '';
        $pre = '';
        if ($diff->y > 0 || $diff->m > 0 || $diff->d == 1 || $diff->h == 1) {
            return $this->formatNormal($diff);
        } else if ($diff->d > 0) {
            $str = "few days";
        } else if ($diff->h > 0) {
            $str = "few hours";
        } else if ($diff->i < 2) {
            $str = "minute";
            $pre = "a ";
        } else if ($diff->i < 20) {
            $str = "15 minutes";
        } else if ($diff->i < 40) {
            $str = "half hour";
        } else if ($diff->i < 60) {
            $str = "hour";
            $pre = "an";
        }

        return $this->translator->trans($diff->invert ? "in next " . $str : $pre . $str . " ago");
    }

    /**
     * Converting date format to expected by methods
     * 
     * @param \DateTime|string|int $time
     * @param string               $dateFormat
     * 
     * @return \DateTime
     */
    private function convertFormat($time, $dateFormat)
    {
        if (!($time instanceof \DateTime)) {
            if (is_numeric($time)) {
                $transformer = new DateTimeToTimestampTransformer();
                $time = $transformer->reverseTransform($time);
            } else {
                $transformer = new DateTimeToStringTransformer(null, null, $dateFormat);
                $time = $transformer->reverseTransport($time);
            }
        }

        return $time;
    }

}

