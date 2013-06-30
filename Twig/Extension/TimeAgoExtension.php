<?php

namespace Salavert\TimeAgoInWordsBundle\Twig\Extension;

use Salavert\TimeAgoInWordsBundle\Templating\Helper\TimeAgoHelper;

class TimeAgoExtension extends \Twig_Extension {

	protected $translator;

	/**
	 * @var TimeAgoHelper
	 */
	protected $helper;

	/**
	 * Constructor method
	 *
	 * @param IdentityTranslator $translator
	 * @param TimeAgoHelper $helper
	 */
	public function __construct($translator, TimeAgoHelper $helper) {
		$this->translator = $translator;
		$this->helper = $helper;
	}

	public function getFilters() {
		return array(
			new \Twig_SimpleFilter('distance_of_time_in_words', array($this, 'distanceOfTimeInWordsFilter')),
			new \Twig_SimpleFilter('time_ago_in_words', array($this, 'timeAgoInWordsFilter')),
		);
	}

	/**
	 * Like distance_of_time_in_words, but where to_time is fixed to timestamp()
	 *
	 * @param $from_time String or DateTime
	 * @param bool $include_seconds
	 *
	 * @return mixed
	 */
	public function timeAgoInWordsFilter($fromTime, $includeSeconds = false) {
		return $this->helper->timeAgoInWords($fromTime, $includeSeconds);
	}

	/**
	 * Reports the approximate distance in time between two times given in seconds
	 * or in a valid ISO string like.
	 * For example, if the distance is 47 minutes, it'll return
	 * "about 1 hour". See the source for the complete wording list.
	 *
	 * Integers are interpreted as seconds. So, by example to check the distance of time between
	 * a created user an it's last login:
	 * {{ user.createdAt|distance_of_time_in_words(user.lastLoginAt) }} returns "less than a minute".
	 *
	 * Set include_seconds to true if you want more detailed approximations if distance < 1 minute
	 *
	 * @param $from_time String or DateTime
	 * @param $to_time String or DateTime
	 * @param bool $include_seconds
	 *
	 * @return mixed
	 */
	public function distanceOfTimeInWordsFilter($fromTime, $toTime = null, $includeSeconds = false) {
		return $this->helper->distanceOfTimeInWordsFilter($fromTime, $toTime, $includeSeconds);
	}

	/**
	 * Returns the name of the extension.
	 *
	 * @return string The extension name
	 */
	public function getName() {
		return 'time_ago_extension';
	}

}

