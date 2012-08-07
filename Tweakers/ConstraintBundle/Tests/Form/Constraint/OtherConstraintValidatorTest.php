<?php

namespace Tweakers\ConstraintBundle\Tests\Form\Constraint;

use Tweakers\ConstraintBundle\Form\Constraint\OtherConstraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Tweakers\ConstraintBundle\Form\Constraint\OtherConstraintValidator;
use Symfony\Component\Validator\ExecutionContext;

class OtherConstraintValidatorTest extends \PHPUnit_Framework_TestCase
{
	protected $walker;
	protected $metadataFactory;

	protected function setUp()
	{
		$this->walker = $this->getMock('Symfony\Component\Validator\GraphWalker', array(), array(), '', false);
		$this->metadataFactory = $this->getMock('Symfony\Component\Validator\Mapping\ClassMetadataFactoryInterface');
	}

	protected function tearDown()
	{
		$this->walker = null;
		$this->metadataFactory = null;
	}

	public function testIsValid()
	{
		$context = new ExecutionContext(array('test' => 'foo'), $this->walker, $this->metadataFactory);

		$otherConstraint = new OtherConstraint(array(
			'otherName'		=> 'test',
			'constraint'	=> new StubConstraint()
		));

		$otherConstraintValidator = new OtherConstraintValidator();
		$otherConstraintValidator->initialize($context);

		$isValid = $otherConstraintValidator->isValid('bar', $otherConstraint);
		$this->assertFalse($isValid);

		StubConstraintValidator::$value = 'foo';
		$isValid = $otherConstraintValidator->isValid('bar', $otherConstraint);
		$this->assertTrue($isValid);
	}

	public function testIsValidWithoutOtherField()
	{
		$context = new ExecutionContext(array(), $this->walker, $this->metadataFactory);

		$otherConstraint = new OtherConstraint(array(
			'otherName'		=> 'test',
			'constraint'	=> new StubConstraint()
		));

		$otherConstraintValidator = new OtherConstraintValidator();
		$otherConstraintValidator->initialize($context);

		$isValid = $otherConstraintValidator->isValid('bar', $otherConstraint);
		$this->assertFalse($isValid);
		$this->assertEquals('The other field "{{ field }}" does not exist', $otherConstraintValidator->getMessageTemplate());
		$this->assertArrayHasKey('field', $otherConstraintValidator->getMessageParameters());
	}
}

class StubConstraint extends Constraint
{

}

class StubConstraintValidator extends ConstraintValidator
{
	public static $value = true;

	public function isValid($value, Constraint $constraint)
	{
		if (self::$value !== $value)
		{
			$this->setMessage('test message', array('foo' => 'bar'));
			return false;
		}

		return true;
	}
}