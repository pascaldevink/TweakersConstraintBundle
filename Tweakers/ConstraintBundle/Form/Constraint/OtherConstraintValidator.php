<?php

namespace Tweakers\ConstraintBundle\Form\Constraint;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\Constraint;

class OtherConstraintValidator extends ConstraintValidator
{

	/**
	 * Checks if the passed value is valid.
	 *
	 * @param mixed      $value      The value that should be validated
	 * @param Constraint $constraint The constrain for the validation
	 *
	 * @return Boolean Whether or not the value is valid
	 *
	 * @api
	 */
	public function isValid($value, Constraint $constraint)
	{
		$otherName = $constraint->otherName;
		$otherConstraint = $constraint->constraint;

		$root = $this->context->getRoot();

		if (!isset($root[$otherName]))
		{
			$this->setMessage($constraint->message, array('field' => $otherName));
			return false;
		}

		$otherValue = $root[$otherName];

		$validatorFactory = new ConstraintValidatorFactory();
		$validator = $validatorFactory->getInstance($otherConstraint);
		$validator->initialize($this->context);

		return $validator->isValid($otherValue, $otherConstraint);
	}
}
