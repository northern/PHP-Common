<?php

namespace Northern\Test\Common\Util;

use Northern\Common\Util\ArrayUtil as Arr;
use Northern\Common\Util\ObjectUtil as Obj;
use Symfony\Component\Validator\Constraints as Assert;

class SimpleValidationTestObject
{
    protected $email;

    public function setEmail($email)
    {
        $this->email = $value;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getValidationConstraints()
    {
        $constraints = array(
            'email' => array(
                new Assert\NotBlank(
                    array('message' => "The email field cannot be left blank.")
                ),
                new Assert\Email(
                    array('message' => "The email field must contain a valid email address.")
                ),
                new Assert\Length(
                    array(
                        'min' => 7,
                        'minMessage' => "The email field cannot be less than 7 characters long.",
                        'max' => 64,
                        'maxMessage' => "The email field cannot be longer that 64 characters long.",
                    )
                ),
            ),
        );

        return $constraints;
    }
}

class ObjectUtilValidateTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleValidate()
    {
        $simpleObject = new SimpleValidationTestObject();

        // A test that should succeed.
        $values = array(
            'email' => 'test@example.com',
        );

        $errors = Obj::validate($simpleObject, $values, $simpleObject->getValidationConstraints());

        $this->assertEmpty($errors);

        // A test that should fail with a single message.
        $values = array(
            'email' => '',
        );

        $errors = Obj::validate($simpleObject, $values, $simpleObject->getValidationConstraints());

        $this->assertNotEmpty($errors);
        $this->assertNotEmpty(Arr::get($errors, 'email.0'));

        // A test that should fail with multiple messages.
        $values = array(
            'email' => 't#e.co',
        );

        $errors = Obj::validate($simpleObject, $values, $simpleObject->getValidationConstraints());

        $this->assertNotEmpty($errors);
        $this->assertNotEmpty(Arr::get($errors, 'email.0'));
        $this->assertNotEmpty(Arr::get($errors, 'email.1'));
    }
}
