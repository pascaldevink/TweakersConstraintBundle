<?php

namespace Tweakers\ConstraintBundle\Tests\Form\Constraint;

use Tweakers\ConstraintBundle\Form\Constraint\OtherConstraint;

class OtherConstraintTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @expectedException			\Symfony\Component\Validator\Exception\MissingOptionsException
	 * @expectedExceptionMessage    The options "otherName", "constraint" must be set for constraint Tweakers\ConstraintBundle\Form\Constraint\OtherConstraint
	 */
	public function testRequiredOptions()
	{
		$constraint = new OtherConstraint();
	}
}
