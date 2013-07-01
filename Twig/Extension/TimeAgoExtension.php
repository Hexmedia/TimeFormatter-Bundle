<?php

namespace Hexmedia\TimeAgoBundle\Twig\Extension;

use Hexmedia\TimeAgoBundle\Templating\Helper\TimeAgoHelper;

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
			new \Twig_SimpleFilter('time_ago', array($this, 'TimeAgoFilter')),
		);
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
	public function TimeAgoFilter($fromTime, $toTime = null, $format = null, $dateFormat = "Y-m-d H:i:s") {
		return $this->helper->TimeAgo($fromTime, $toTime, $format, $dateFormat);
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

