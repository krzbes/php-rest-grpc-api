<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# NO CHECKED-IN PROTOBUF GENCODE
# source: song.proto

namespace Schema;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\GPBUtil;
use Google\Protobuf\RepeatedField;

/**
 * Generated from protobuf message <code>Schema.UpdateSongRequest</code>
 */
class UpdateSongRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>int32 id = 1 [json_name = "id"];</code>
     */
    protected $id = 0;
    /**
     * Generated from protobuf field <code>string title = 2 [json_name = "title"];</code>
     */
    protected $title = '';
    /**
     * Generated from protobuf field <code>string release_year = 3 [json_name = "releaseYear"];</code>
     */
    protected $release_year = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int $id
     *     @type string $title
     *     @type string $release_year
     * }
     */
    public function __construct($data = NULL) {
        \Schema\GPBMetadata\Song::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>int32 id = 1 [json_name = "id"];</code>
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Generated from protobuf field <code>int32 id = 1 [json_name = "id"];</code>
     * @param int $var
     * @return $this
     */
    public function setId($var)
    {
        GPBUtil::checkInt32($var);
        $this->id = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string title = 2 [json_name = "title"];</code>
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Generated from protobuf field <code>string title = 2 [json_name = "title"];</code>
     * @param string $var
     * @return $this
     */
    public function setTitle($var)
    {
        GPBUtil::checkString($var, True);
        $this->title = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string release_year = 3 [json_name = "releaseYear"];</code>
     * @return string
     */
    public function getReleaseYear()
    {
        return $this->release_year;
    }

    /**
     * Generated from protobuf field <code>string release_year = 3 [json_name = "releaseYear"];</code>
     * @param string $var
     * @return $this
     */
    public function setReleaseYear($var)
    {
        GPBUtil::checkString($var, True);
        $this->release_year = $var;

        return $this;
    }

}

