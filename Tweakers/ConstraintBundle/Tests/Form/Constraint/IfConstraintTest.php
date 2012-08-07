<?php

namespace Tweakers\ConstraintBundle\Tests\Form\Constraint;

use Tweakers\ConstraintBundle\Form\Constraint\IfConstraint;

class IfConstraintTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @expectedException			\Symfony\Component\Validator\Exception\MissingOptionsException
	 * @expectedExceptionMessage    The options "if", "then" must be set for constraint Tweakers\ConstraintBundle\Form\Constraint\IfConstraint
	 */
	public function testRequiredOptions()
	{
		$constraint = new IfConstraint();
	}

	public function testDefaultOptions()
	{
		$constraint = new IfConstraint(array(
			'if'	=> true,
			'then'	=> true
		));

		$defaultOptions = $constraint->getDefaultOption();
		$this->assertFalse($defaultOptions['if']);
		$this->assertFalse($defaultOptions['then']);
		$this->assertFalse($defaultOptions['else']);
	}
}
