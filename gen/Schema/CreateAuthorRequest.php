<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# NO CHECKED-IN PROTOBUF GENCODE
# source: author.proto

namespace Schema;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\GPBUtil;
use Google\Protobuf\RepeatedField;

/**
 * Generated from protobuf message <code>Schema.CreateAuthorRequest</code>
 */
class CreateAuthorRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string name = 2 [json_name = "name"];</code>
     */
    protected $name = '';
    /**
     * Generated from protobuf field <code>string surname = 3 [json_name = "surname"];</code>
     */
    protected $surname = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $name
     *     @type string $surname
     * }
     */
    public function __construct($data = NULL) {
        \Schema\GPBMetadata\Author::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string name = 2 [json_name = "name"];</code>
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Generated from protobuf field <code>string name = 2 [json_name = "name"];</code>
     * @param string $var
     * @return $this
     */
    public function setName($var)
    {
        GPBUtil::checkString($var, True);
        $this->name = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string surname = 3 [json_name = "surname"];</code>
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Generated from protobuf field <code>string surname = 3 [json_name = "surname"];</code>
     * @param string $var
     * @return $this
     */
    public function setSurname($var)
    {
        GPBUtil::checkString($var, True);
        $this->surname = $var;

        return $this;
    }

}

