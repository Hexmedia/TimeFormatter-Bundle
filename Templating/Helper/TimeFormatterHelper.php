<?php

namespace Hexmedia\TimeFormatterBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToTimestampTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;

class TimeFormatterHelper extends Helper {

	protected $translator;

	public function __construct($translator) {
		$this->translator = $translator;
	}

	public function getName() {
		return 'time_formatter';
	}

	/**
	 * As $fromTime and $toTime we can use timestamp as int, @\DateTime or string with format
	 * from $dateFormat
	 *
	 * @param \DateTime|string|int $fromTime
	 * @param \DateTime|string|int $toTime
	 * @param string $format simple - currently there is only idea to use this var
	 * @param string $dateFormat
	 *
	 * @return string
	 */
	public function formatTime($fromTime, $toTime = null, $format = null, $dateFormat = "Y-m-d H:i:s") {
		if ($toTime == null) {
			$toTime = new \DateTime('now');
		}

		$fromTime = $this->convertFormat($fromTime, $dateFormat);
		$toTime = $this->convertFormat($toTime, $dateFormat);

		$diff = $toTime->diff($fromTime);

		if (strtolower($format) == "simple") {
			return $this->formatSimple($diff);
		} else {
			return $this->formatNormal($diff);
		}
	}

	private function formatNormal($diff) {
		if ($diff->invert) {
			if ($diff->y > 0) {
				return $this->translator->transChoice('one: Next year|some: In next %year% years', $diff->y, array("%year%", $diff->y));
			} else if ($diff->m > 0) {
				return $this->translator->transChoice('one: Next month|some: In next %month% months', $diff->m, array("%month%", $diff->m));
			} else if ($diff->d > 0) {
				return $this->translator->transChoice('one: Tomorrow|some: In next %days% days', $diff->d, array("%days%", $diff->d));
			} else if ($diff->h > 0) {
				return $this->translator->transChoice('one: Next hour|some: In next %hours% hours', $diff->h, array("%hours%", $diff->h));
			} else if ($diff->i > 0) {
				if ($diff->i == 30) {
					$this->translator->trans("In next half hour");
				}
				return $this->translator->transChoice('one: Next minute|some: In next %minutes% minutes', $diff->i, array("%minutes%", $diff->i));
			} else if ($diff->s > 0) {
				return $this->translator->transChoice('one: Next second|some: In next %seconds% seconds', $diff->s, array("%seconds%", $diff->s));
			}
		} else {
			if ($diff->y > 0) {
				return $this->translator->transChoice('one: Year ago|some: %year% years ago', $diff->y, array("%year%", $diff->y));
			} else if ($diff->m > 0) {
				return $this->translator->transChoice('one: Month ago|some: %month% months ago', $diff->m, array("%month%", $diff->m));
			} else if ($diff->d > 0) {
				return $this->translator->transChoice('one: Yesterday|some: %days% days ago', $diff->d, array("%days%", $diff->d));
			} else if ($diff->h > 0) {
				return $this->translator->transChoice('one: Hour ago|some: %hours% hours ago', $diff->h, array("%hours%", $diff->h));
			} else if ($diff->i > 0) {
				if ($diff->i == 30) {
					$this->translator->trans("Half hour ago");
				}
				return $this->translator->transChoice('one: Minute ago|some: %minutes% minutes ago', $diff->i, array("%minutes%", $diff->i));
			} else if ($diff->s > 0) {
				return $this->translator->transChoice('one: Second ago|some: %seconds% seconds ago', $diff->s, array("%seconds%", $diff->s));
			}
		}
	}

	private function formatSimple($diff) {
		if ($diff->invert) {
			if ($diff->y > 0 || $diff->m > 0 || $diff->d > 0 || $diff->h > 0) {
				return $this->formatNormal($diff);
			} else {
				if ($diff->i < 1) {
					return $this->translator->trans("In less than minute");
				} else if ($diff->i < 5) {
					return $this->translator->trans("In less than 5 minutes");
				} else if ($diff->i < 7) {
					return $this->translator->trans("In next 5 minutes");
				} else if ($diff->i < 18) {
					return $this->translator->trans("In next 15 minutes");
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
					return $this->translator->trans("Less than minute ago");
				} else if ($diff->i < 7) {
					return $this->translator->trans("About 5 minutes ago");
				} else if ($diff->i < 18) {
					return $this->translator->trans("About 15 minutes ago");
				} else if ($diff->i < 40) {
					return $this->translator->trans("About half hour ago");
				} else {
					return $this->translator->trans("About hour ago");
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
	private
		function convertFormat($time, $dateFormat) {
		if (!($time instanceof \DateTime)) {
			if (is_number($time)) {
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

