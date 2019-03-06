<?php

namespace ImgCropper;

use Gumlet\ImageResize;
use Nette\Forms\Controls\HiddenField;
use Nette\Forms\Controls\TextArea;
use Nette\Forms\Controls\TextInput;
use Nette\Forms\Form;
use Nette\InvalidStateException;

class ImageControl extends TextInput {
	private $ignoreAspectRatioWhileValidate;
	private $ignoreAspectRatio;
	private $scaleX;
	private $scaleY;

	public function __construct($persistentValue = null, $tooltip = '') {
		parent::__construct('');
		$this->setAttribute('data-label', $tooltip);
		$this->setAttribute('data-thumbnail', $persistentValue ? $persistentValue : 'https://avatars0.githubusercontent.com/u/3456749?s=160');
		$this->setAttribute('data-width', '180');
		$this->setAttribute('data-height', '180');
		$this->setAttribute('data-aspect-ratio', '3');
		$this->setAttribute('data-ignore-aspect-ratio', '1');
		$this->setAttribute('accept', 'image/*');
		$this->setAttribute('class', 'image-control sr-only');
		$this->setHtmlId($this->getName());
		$this->scaleX = 1;
		$this->scaleY = 1;
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
		$this->scaleY = $y;
		$this->scaleX = $x;
		return $this;
	}

	public function setThumbnail(string $url) {
		$this->setAttribute('data-thumbnail', $url);
		return $this;
	}

	public function setSize($height, $width) {
		$this->setAttribute('data-width', $width);
		$this->setAttribute('data-height', $height);
		return $this;
	}

	public function setThumbnailRatio($ratio) {
		$this->setAttribute('data-thumbnail-ratio', $ratio);
		return $this;
	}

	public function ignoreAspectRatio($ignor = true) {
		$this->setAttribute('data-ignore-aspect-ratio', (int)$ignor);
		$this->ignoreAspectRatio = $ignor;
		return $this;
	}

	public function ignoreAscpectRatioWhileValidate($ignor = true) {
		$this->ignoreAspectRatioWhileValidate = $ignor;
		return $this;
	}

	protected function attached($form) {
		if ($form instanceof Form) {
			if (!$form->isMethod('post')) {
				throw new InvalidStateException('File upload requires method POST.');
			}
		}
		parent::attached($form);
	}

	public function validate() {
		if ($this->isDisabled()) {
			return;
		}
		if(empty($_POST[$this->getName()])) {
			return;
		}
		$this->cleanErrors();
		try {
			$img = base64_decode(explode(',',$_POST[$this->getName()])[1]);
			$image = ImageResize::createFromString($img);
			if ($image->getSourceHeight() % $this->scaleY == 0 && $image->getSourceWidth() % $this->scaleX == 0 || $this->ignoreAspectRatioWhileValidate || $this->ignoreAspectRatio) {
				$this->setValue($image);
				$this->value = $image;
				return true;
			}
		} catch (\Exception $e) {
			return false;
		}
	}

	public function isOk() {
		return $this->value instanceof ImageResize
			? true
			: false;
	}

}