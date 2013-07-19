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
     * @TODO: Format to be rewritten to ys ms ds hs is ss where first letter means time unit and the second one means format.
     *
     * @param \DateTime|string|int $fromTime
     * @param \DateTime|string|int $toTime
     * @param string $format simple - currently there is only idea to use this var
     * @param string $dateFormat
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
     * @param \Dateinterval $diff
     * @return string
     */
    private function formatNormal(\Dateinterval $diff)
    {
        $str = '';
        $first = null;
        $second = null;
        $full = null;
        $val = 0;
        
        if ($diff->y > 0) {
            $str = "year";
            $val = $diff->y;
        } else if ($diff->m > 0) {
            $str = "month";
            $val = $diff->m;
        } else if ($diff->d > 0) {
            $str = "day";
            $first = array(
                'after' => "tomorrow", 
                'before' => "yesterday"
            );
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
                ($second && isset($second['after']) ? $second['after'] : "in next " . $id . " " . $str . "s"),
                $val, array(
                    $id => $val
                ));
        } else {
            $id = "%" . $str . "%";
            
            return $this->translator->transChoice(
                ($first && isset($first['before']) ? $first['before'] : ($str == "hour" ? 'an' : 'a') . ' ' . $str . ' ago') . "|" .
                ($second && isset($second['before']) ? $second['before'] : $id . " " . $str . "s ago"),
                $val, array(
                    $id => $val
                ));
        }
    }

    /**
     * Formating using simple format.
     * 
     * @param \DateInterval $diff
     * @return string
     */
    private function formatSimple(\DateInterval $diff)
    {
        $v = '';
        $pre = '';
        
        if ($diff->y > 0 || $diff->m > 0 || $diff->d == 1 || $diff->h == 1) {
            return $this->formatNormal($diff);
        } else if ($diff->d > 0) {
            $v = "few days";
        } else if ($diff->h > 0) {
            $v = "few hours";
        } else if ($diff->i < 2) {
            $v = "minute";
            $pre = "a ";
        } else if ($diff->i < 20) {
            $v = "15 minutes";
        } else if ($diff->i < 40) {
            $v = "half hour";
        } else if ($diff->i < 60) {
            $v = "hour";
            $pre = "an";
        }
        
        return $this->translator->trans($diff->invert ? "in next " . $v : $pre . $v . " ago");
        
//        
//        
//        if ($diff->invert) {
//            if ($diff->y > 0 || $diff->m > 0 || ) {
//                return $this->formatNormal($diff);
//            } else {
//                if ($diff->i < 1) {
//                    return $this->translator->trans('in less than minute');
//                } else if ($diff->i < 7) {
//                    return $this->translator->transChoice('', 5, array('%minutes%' => 5));
//                } else if ($diff->i < 18) {
//                    return $this->translator->transChoice('next minute|in next %minutes% minutes', 15, array('%minutes%' => 15));
//                } else if ($diff->i < 40) {
//                    return $this->translator->trans("in next half hour");
//                } else {
//                    return $this->translator->trans("in next hour");
//                }
//            }
//        } else {
//            if ($diff->y > 0 || $diff->m > 0) {
//                return $this->formatNormal($diff);
//            } else if ($diff->d > 0) {
//                return $this->translator->trans("few days ago");
//            } else if ($diff->h > 1) {
//                return $this->translator->trans("few hours ago");
//            } else if ($diff->h > 0) {
//                return $this->translator->trans("an hour ago");
//            } else {
//                if ($diff->i < 1) {
//                    return $this->translator->transChoice('a minute ago|%minutes% minutes ago', 1, array("%minutes%" => 1));
//                } else if ($diff->i < 7) {
//                    return $this->translator->transChoice('a minute ago|%minutes% minutes ago', 5, array("%minutes%" => 5));
//                } else if ($diff->i < 18) {
//                    return $this->translator->transChoice('a minute ago|%minutes% minutes ago', 15, array("%minutes%" => 15));
//                } else if ($diff->i < 40) {
//                    return $this->translator->trans("half hour ago");
//                } else {
//                    return $this->translator->trans("an hour ago");
//                }
//            }
//        }
    }

    /**
     * Converting date format to expected by methods
     * 
     * @param \DateTime|string|int $time
     * @param strin  $dateFormat
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

