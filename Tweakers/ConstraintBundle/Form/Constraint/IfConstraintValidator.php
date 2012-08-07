<?php

namespace Tweakers\ConstraintBundle\Form\Constraint;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\Constraint;

class IfConstraintValidator extends ConstraintValidator
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
		$if = $constraint->if;
		$then = $constraint->then;
		$else = $constraint->else;

		$validatorFactory = new ConstraintValidatorFactory();

		$ifOutcome = $this->runValidators($validatorFactory, $if, $value);

		if ($ifOutcome['outcome'] === true)
		{
			$outcome = $this->runValidators($validatorFactory, $then, $value);
			if ($outcome['messageTemplate'] && $outcome['messageParameters'])
				$this->setMessage($outcome['messageTemplate'], $outcome['messageParameters']);
			return $outcome['outcome'];
		}
		else
		{
			if ($else)
			{
				$outcome = $this->runValidators($validatorFactory, $else, $value);
				if ($outcome['messageTemplate'] && $outcome['messageParameters'])
					$this->setMessage($outcome['messageTemplate'], $outcome['messageParameters']);
				return $outcome['outcome'];
			}
		}

		return true;
	}

	protected function runValidators(ConstraintValidatorFactoryInterface $validatorFactory, $validators, $value)
	{
		$outcome = true;
		$messageTemplate = null;
		$messageParameters = null;

		foreach($validators as $condition)
		{
			$validator = $validatorFactory->getInstance($condition);
			$validator->initialize($this->context);

			if (!$validator->isValid($value, $condition))
			{
				$messageTemplate = $validator->getMessageTemplate();
				$messageParameters = $validator->getMessageParameters();
				$outcome = false;
			}
		}

		return array(
			'outcome'			=> $outcome,
			'messageTemplate'	=> $messageTemplate,
			'messageParameters'	=> $messageParameters
		);
	}
}
