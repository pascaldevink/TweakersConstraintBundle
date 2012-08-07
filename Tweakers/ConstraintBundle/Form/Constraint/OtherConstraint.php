<?php

namespace Tweakers\ConstraintBundle\Form\Constraint;

use Symfony\Component\Validator\Constraint;

class OtherConstraint extends Constraint
{
	public $message = 'The other field "{{ field }}" does not exist';

	public $otherName;
	public $constraint;

	public function getRequiredOptions()
	{
		$options = parent::getRequiredOptions();

		$options[] = 'otherName';
		$options[] = 'constraint';

		return $options;
	}
}
