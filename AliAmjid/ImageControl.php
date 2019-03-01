<?php

namespace AliAmjid\imgCropper;

use Gumlet\ImageResize;
use http\Exception\InvalidArgumentException;
use Nette\Forms\Controls\HiddenField;
use Nette\Forms\Form;
use Nette\InvalidStateException;

class ImageControl extends HiddenField {
	private $ignoreAspectRatioWhileValidate;
	private $ignoreAspectRatio;
	private $scaleX;
	private $scaleY;

	public function __construct($persistentValue = null, $tooltip = '') {
		parent::__construct($persistentValue);
		$this->setAttribute('data-label', $tooltip);
		$this->setAttribute('data-thumbnail', $persistentValue ? $persistentValue : 'https://avatars0.githubusercontent.com/u/3456749?s=160');
		$this->setAttribute('data-thumbnail-width', '180');
		$this->setAttribute('data-thumbnail-height', '180');
		$this->setAttribute('data-aspect-ratio', '3');
		$this->setAttribute('data-ignore-aspect-ratio', '1');
		$this->setAttribute('accept', 'image/*');
		$this->setAttribute('class', 'image-control sr-only');

		/**
		 * thodi wala dil.. thodi wala dil ... kari menu kill..
		 */
	}

	/**
	 * @return static
	 * @internal
	 */
	public function setValue($value) {
		return $this;
	}

	public function setLabel(string $label) {
		$this->setAttribute('data-label', $label);
		return $this;
	}

	public function setScaleMode(int $x, int $y) {
		if ($y == 0) throw new \Exception('y cant be 0');
		$this->setAttribute('data-scale', $x / $y);
	}

	public function setThumbnail(string $url) {
		$this->setAttribute('data-thumbnail', $url);
	}

	public function setThumnailSize($height, $width) {
		$this->setAttribute('data-thumbnail-width', $width);
		$this->setAttribute('data-thumbnail-height', $height);
	}

	public function ignoreAspectRatio($ignor = true) {
		$this->setAttribute('data-ignore-aspect-ratio', (int)$ignor);
		$this->ignoreAspectRatio = $ignor;
	}

	public function ignoreAscpectRatioWhileValidate($ignor = true) {
		$this->ignoreAspectRatioWhileValidate = $ignor;
	}

	protected function attached($form) {
		if ($form instanceof Form) {
			if (!$form->isMethod('post')) {
				throw new InvalidStateException('File upload requires method POST.');
			}
			$form->getElementPrototype()->enctype = 'multipart/form-data';
		}
		parent::attached($form);
	}

	public function validate() {
		if ($this->isDisabled()) {
			return;
		}
		$this->cleanErrors();
		if ($this->rules->validate()) {
			try {
				$image = ImageResize::createFromString(base64_decode($this->getHttpData()));
				if ($image->getSourceHeight() % $this->scaleY == 0 && $image->getSourceWidth() % $this->scaleX == 0 || $this->ignoreAspectRatioWhileValidate || $this->ignoreAspectRatio) {
					$this->setValue($image);
					$this->value = $image;
					return true;
				}
			} catch (\Exception $e) {
				return false;
			}
		} else {
			return false;
		}
	}

	public function isOk() {
		return $this->value instanceof ImageResize
			? true
			: false;
	}

}