<?php

namespace Tweakers\ConstraintBundle\Tests\Form\Constraint;

use Tweakers\ConstraintBundle\Form\Constraint\IfConstraint;
use Symfony\Component\Validator\GraphWalker;
use Symfony\Component\Validator\ExecutionContext;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Tweakers\ConstraintBundle\Form\Constraint\IfConstraintValidator;

class IfConstraintValidatorTest extends \PHPUnit_Framework_TestCase
{
	protected $walker;
	protected $metadataFactory;
	protected $context;

	protected $ifConstraint;
	protected $thenConstraint;
	protected $elseConstraint;

	protected function setUp()
	{
		$this->walker = $this->getMock('Symfony\Component\Validator\GraphWalker', array(), array(), '', false);
		$this->metadataFactory = $this->getMock('Symfony\Component\Validator\Mapping\ClassMetadataFactoryInterface');
		$this->context = new ExecutionContext('Root', $this->walker, $this->metadataFactory);

		$this->ifConstraint = new StubIfConstraint();
		$this->thenConstraint = new StubThenConstraint();
		$this->elseConstraint = new StubElseConstraint();
	}

	protected function tearDown()
	{
		$this->walker = null;
		$this->metadataFactory = null;
		$this->context = null;

		$this->ifConstraint = null;
		$this->thenConstraint = null;
		$this->elseConstraint = null;

		StubIfConstraintValidator::$value = true;
		StubThenConstraintValidator::$value = true;
		StubElseConstraintValidator::$value = true;
	}

	public function testIfCondition()
	{
		$value = true;

		$ifConstraint = new IfConstraint(array(
			'if'		=> array($this->ifConstraint),
			'then'		=> array($this->thenConstraint)
		));
		$ifConstraintValidator = new IfConstraintValidator();
		$ifConstraintValidator->initialize($this->context);
		$isValid = $ifConstraintValidator->isValid($value, $ifConstraint);

		$this->assertTrue($isValid);
	}

	public function testElseCondition()
	{
		$value = true;

		StubIfConstraintValidator::$value = false;

		$ifConstraint = new IfConstraint(array(
			'if'		=> array($this->ifConstraint),
			'then'		=> array($this->thenConstraint),
			'else'		=> array($this->elseConstraint)
		));
		$ifConstraintValidator = new IfConstraintValidator();
		$ifConstraintValidator->initialize($this->context);
		$isValid = $ifConstraintValidator->isValid($value, $ifConstraint);

		$this->assertTrue($isValid);
	}

	public function testIfConditionMessage()
	{
		$value = true;

		StubThenConstraintValidator::$value = false;

		$ifConstraint = new IfConstraint(array(
			'if'		=> array($this->ifConstraint),
			'then'		=> array($this->thenConstraint)
		));
		$ifConstraintValidator = new IfConstraintValidator();
		$ifConstraintValidator->initialize($this->context);
		$isValid = $ifConstraintValidator->isValid($value, $ifConstraint);
		$messageTemplate = $ifConstraintValidator->getMessageTemplate();
		$messageParameters = $ifConstraintValidator->getMessageParameters();

		$this->assertFalse($isValid);
		$this->assertEquals('test message', $messageTemplate);
		$this->assertTrue(is_array($messageParameters));
		$this->assertArrayHasKey('foo', $messageParameters);
	}

}

class StubIfConstraint extends Constraint
{

}

class StubIfConstraintValidator extends ConstraintValidator
{
	public static $value = true;

	public function isValid($value, Constraint $constraint)
	{
		if (!self::$value)
			$this->setMessage('test message', array('foo' => 'bar'));

		return self::$value;
	}
}

class StubThenConstraint extends Constraint
{

}

class StubThenConstraintValidator extends ConstraintValidator
{
	public static $value = true;

	public function isValid($value, Constraint $constraint)
	{
		if (!self::$value)
			$this->setMessage('test message', array('foo' => 'bar'));

		return self::$value;
	}
}

class StubElseConstraint extends Constraint
{

}

class StubElseConstraintValidator extends ConstraintValidator
{
	public static $value = true;

	public function isValid($value, Constraint $constraint)
	{
		if (!self::$value)
			$this->setMessage('test message', array('foo' => 'bar'));

		return self::$value;
	}
}
