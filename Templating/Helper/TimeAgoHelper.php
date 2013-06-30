<?php

namespace Salavert\TimeAgoInWordsBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;

class TimeAgoHelper extends Helper {

	protected $translator;

	public function __construct($translator) {
		$this->translator = $translator;
	}

	public function getName() {
		return 'time_ago_in_words';
	}

	public function timeAgoInWords($from_time, $include_seconds = null) {
		return $this->distanceOfTimeInWordsFilter($from_time, new \DateTime('now'), $include_seconds);
	}

	public function distanceOfTimeInWordsFilter($from_time, $to_time = null, $include_seconds = null) {
		$datetime_transformer = new \Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer(null, null, 'Y-m-d H:i:s');
		$timestamp_transformer = new \Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToTimestampTransformer();

		# Transforming to Timestamp
		if (!($from_time instanceof \DateTime) && !is_numeric($from_time)) {
			$from_time = $datetime_transformer->reverseTransform($from_time);
			$from_time = $timestamp_transformer->transform($from_time);
		} elseif ($from_time instanceof \DateTime) {
			$from_time = $timestamp_transformer->transform($from_time);
		}

		$to_time = empty($to_time) ? new \DateTime('now') : $to_time;

		# Transforming to Timestamp
		if (!($to_time instanceof \DateTime) && !is_numeric($to_time)) {
			$to_time = $datetime_transformer->reverseTransform($to_time);
			$to_time = $timestamp_transformer->transform($to_time);
		} elseif ($to_time instanceof \DateTime) {
			$to_time = $timestamp_transformer->transform($to_time);
		}

		$distance_in_seconds = round(abs($to_time - $from_time));
		$distance_in_minutes = round($distance_in_seconds / 60);
		$distance_in_days = round($distance_in_minutes / 24);

		if ($distance_in_minutes <= 1) {
			if ($include_seconds) {
				if ($distance_in_seconds < 5) {
					return $this->translator->trans('less than %seconds seconds ago', array('%seconds' => 5));
				} elseif ($distance_in_seconds < 10) {
					return $this->translator->trans('less than %seconds seconds ago', array('%seconds' => 10));
				} elseif ($distance_in_seconds < 20) {
					return $this->translator->trans('less than %seconds seconds ago', array('%seconds' => 20));
				} elseif ($distance_in_seconds < 40) {
					return $this->translator->trans('half a minute ago');
				} elseif ($distance_in_seconds < 60) {
					return $this->translator->trans('less than a minute ago');
				} else {
					return $this->translator->trans('1 minute ago');
				}
			}
			return ($distance_in_minutes === 0) ? $this->translator->trans('less than a minute ago', array()) : $this->translator->trans('1 minute ago', array());
		} elseif ($distance_in_minutes <= 45) {
			return $this->translator->trans('%minutes minutes ago', array('%minutes' => $distance_in_minutes));
		} elseif ($distance_in_minutes <= 90) {
			return $this->translator->trans('about 1 hour ago');
		} elseif ($distance_in_minutes <= 1440) {
			return $this->translator->trans('about %hours hours ago', array('%hours' => round($distance_in_minutes / 60)));
		} elseif ($distance_in_minutes <= 2880) {
			return $this->translator->trans('1 day ago');
		} else {
			return $this->translator->trans('%days days ago', array('%days' => round($distance_in_minutes / 1440)));
		}
	}

}
