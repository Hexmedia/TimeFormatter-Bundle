<?php

namespace Hexmedia\TimeFormatterBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToTimestampTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;

/**
 * TimeFormatterHelper
 */
class TimeFormatterHelper extends Helper
{

    protected $translator;

    public function __construct($translator)
    {
        $this->translator = $translator;
    }

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

    private function formatNormal($diff)
    {
        if ($diff->invert) {
            if ($diff->y > 0) {
                return $this->translator->transChoice('Next year|In next %year% years', $diff->y, array("%year%" => $diff->y));
            } else if ($diff->m > 0) {
                return $this->translator->transChoice('Next month|In next %month% months', $diff->m, array("%month%" => $diff->m));
            } else if ($diff->d > 0) {
                return $this->translator->transChoice('Tomorrow|In next %days% days', $diff->d, array("%days%" => $diff->d));
            } else if ($diff->h > 0) {
                return $this->translator->transChoice('Next hour|In next %hours% hours', $diff->h, array("%hours%" => $diff->h));
            } else if ($diff->i > 0) {
                if ($diff->i == 30) {
                    $this->translator->trans('In next half hour');
                }
                return $this->translator->transChoice('Next minute|In next %minutes% minutes', $diff->i, array("%minutes%" => $diff->i));
            } else if ($diff->s > 0) {
                return $this->translator->transChoice('Next second|In next %seconds% seconds', $diff->s, array("%seconds%" => $diff->s));
            }
        } else {
            if ($diff->y > 0) {
                return $this->translator->transChoice('Year ago|%year% years ago', $diff->y, array("%year%" => $diff->y));
            } else if ($diff->m > 0) {
                return $this->translator->transChoice('Month ago|%month% months ago', $diff->m, array("%month%" => $diff->m));
            } else if ($diff->d > 0) {
                return $this->translator->transChoice('Yesterday|%days% days ago', $diff->d, array("%days%" => $diff->d));
            } else if ($diff->h > 0) {
                return $this->translator->transChoice('Hour ago|%hours% hours ago', $diff->h, array("%hours%" => $diff->h));
            } else if ($diff->i > 0) {
                if ($diff->i == 30) {
                    $this->translator->trans("Half hour ago");
                }
                return $this->translator->transChoice('Minute ago|%minutes% minutes ago', $diff->i, array("%minutes%" => $diff->i));
            } else if ($diff->s > 0) {
                return $this->translator->transChoice('Second ago|%seconds% seconds ago', $diff->s, array("%seconds%" => $diff->s));
            }
        }
    }

    private function formatSimple($diff)
    {
        if ($diff->invert) {
            if ($diff->y > 0 || $diff->m > 0 || $diff->d > 0 || $diff->h > 0) {
                return $this->formatNormal($diff);
            } else {
                if ($diff->i < 1) {
                    return $this->translator->trans('In less than minute');
                } else if ($diff->i < 7) {
                    return $this->translator->transChoice('Next minute|In next %minutes% minutes', 5, array('%minutes%' => 5));
                } else if ($diff->i < 18) {
                    return $this->translator->transChoice('Next minute|In next %minutes% minutes', 15, array('%minutes%' => 15));
                } else if ($diff->i < 40) {
                    return $this->translator->trans("In next half hour");
                } else {
                    return $this->translator->trans("In next hour");
                }
            }
        } else {
            if ($diff->y > 0 || $diff->m > 0) {
                return $this->formatNormal($diff);
            } else if ($diff->d > 0) {
                return $this->translator->trans("Few days ago");
            } else if ($diff->h > 1) {
                return $this->translator->trans("Few hours ago");
            } else if ($diff->h > 0) {
                return $this->translator->trans("Hour ago");
            } else {
                if ($diff->i < 1) {
                    return $this->translator->transChoice('Minute ago|%minutes% minutes ago', 1, array("%minutes%" => 1));
                } else if ($diff->i < 7) {
                    return $this->translator->transChoice('Minute ago|%minutes% minutes ago', 5, array("%minutes%" => 5));
                } else if ($diff->i < 18) {
                    return $this->translator->transChoice('Minute ago|%minutes% minutes ago', 15, array("%minutes%" => 15));
                } else if ($diff->i < 40) {
                    return $this->translator->trans("Half hour ago");
                } else {
                    return $this->translator->trans("Hour ago");
                }
            }
        }
    }

    /**
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

