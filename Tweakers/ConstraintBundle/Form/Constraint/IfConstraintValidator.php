<?php

namespace Tweakers\ConstraintBundle\Form\Constraint;

use Symfony\Component\Validator\ConstraintValidator;
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

		$ifOutcome = true;
		foreach($if as $condition)
		{
			$validator = $validatorFactory->getInstance($condition);
			$validator->initialize($this->context);

			if (!$validator->isValid($value, $condition))
				$ifOutcome = false;
		}

		if ($ifOutcome)
		{
			$thenOutcome = true;
			foreach($then as $condition)
			{
				$validator = $validatorFactory->getInstance($condition);
				$validator->initialize($this->context);

				if (!$validator->isValid($value, $condition))
				{
					$this->setMessage($validator->getMessageTemplate(), $validator->getMessageParameters());
					$thenOutcome = false;
				}
			}

			return $thenOutcome;
		}
		else
		{
			if ($else)
			{
				$elseOutcome = true;
				foreach($else as $condition)
				{
					$validator = $validatorFactory->getInstance($condition);
					$validator->initialize($this->context);

					if (!$validator->isValid($value, $condition))
					{
						$this->setMessage($validator->getMessageTemplate(), $validator->getMessageParameters());
						$elseOutcome = false;
					}
				}

				return $elseOutcome;
			}
		}

		return true;
	}
}
