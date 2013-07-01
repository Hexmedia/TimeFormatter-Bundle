<?php

namespace Hexmedia\TimeAgoBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToTimestampTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;

class TimeAgoHelper extends Helper {

	protected $translator;

	public function __construct($translator) {
		$this->translator = $translator;
	}

	public function getName() {
		return 'time_ago';
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
	public function TimeAgo($fromTime, $toTime = null, $format = null, $dateFormat = "Y-m-d H:i:s") {
		if ($toTime == null) {
			$toTime = new \DateTime('now');
		}

		$fromTime = $this->convertFormat($fromTime, $dateFormat);
		$toTime = $this->convertFormat($toTime, $dateFormat);

		$diff = $toTime->diff($fromTime);

		if ($diff->y > 0) {
			return $this->translator->transChoice('one: Year Ago|some: %year% years ago', $diff->y, array("%year%", $diff->y));
		} else if ($diff->m > 0) {
			return $this->translator->transChoice('one: Month ago|some: %month% months ago', $diff->m, array("%month%", $diff->m));
		} else if ($diff->d > 0) {
			return $this->translator->transChoice('one: Day ago|some: %days% days ago', $diff->d, array("%days%", $diff->d));
		} else if ($diff->h > 0) {
			return $this->translator->transChoice('one: Hour ago|some: %hours% hours ago', $diff->h, array("%hours%", $diff->h));
		} else if ($diff->i > 0) {
			return $this->translator->transChoice('one: Minute ago|some: %minutes% minutes ago', $diff->i, array("%minutes%", $diff->i));
		} else if ($diff->s > 0) {
			return $this->translator->transChoice('one: Second ago|some: %seconds% seconds ago', $diff->s, array("%seconds%", $diff->s));
		}
	}

	/**
	 *
	 * @param \DateTime|string|int $time
	 * @param strin  $dateFormat
	 * @return \DateTime
	 */
	private function convertFormat($time, $dateFormat) {
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

