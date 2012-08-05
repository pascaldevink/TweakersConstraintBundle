TweakersConstraintBundle
========================

A set of validators used at Tweakers.net

Use it like:

    $validation = new \Symfony\Component\Validator\Constraints\Collection(array(
    'fields' => array(
      'name'		=> new IfConstraint(array(
        'if'	=> array(new \Tweakers\ConstraintBundle\Form\Constraint\OtherConstraint(array(
          'otherName'		=> 'longname',
          'constraint'	=> new \Symfony\Component\Validator\Constraints\True()
        ))),
        'then'	=> array(new \Symfony\Component\Validator\Constraints\MaxLength(array('limit'=>10))),
        'else'	=> array(new \Symfony\Component\Validator\Constraints\MaxLength(array('limit'=>6)))
      )),
      'email'		=> new \Symfony\Component\Validator\Constraints\Email()
    ),
    'allowExtraFields' => true
    ));