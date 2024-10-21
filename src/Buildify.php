<?php namespace Nabeghe\Buildify;

#[\AllowDynamicProperties]
abstract class Buildify extends \stdClass implements \ArrayAccess, \JsonSerializable
{
    use BuildifyTrait;
}