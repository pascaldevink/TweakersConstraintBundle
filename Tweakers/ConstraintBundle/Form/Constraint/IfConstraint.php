<?php

namespace Tweakers\ConstraintBundle\Form\Constraint;

use Symfony\Component\Validator\Constraint;

class IfConstraint extends Constraint
{
	public $if;
	public $then;
	public $else;

	public function getRequiredOptions()
	{
		$options = parent::getRequiredOptions();

		$options[] = 'if';
		$options[] = 'then';

		return $options;
	}

	public function getDefaultOption()
	{
		return array(
			'if' => false,
			'then' => false,
			'else' => false
		);
	}

}
