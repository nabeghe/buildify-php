<?php namespace Nabeghe\Buildify;

trait BuildifyTrait
{
    public const DEFAULT_NULL = true;

    public const REFRESHABLE = false;

    public const GET_PREFIX = 'get';

    public const SET_PREFIX = 'set';

    public const FLUENT = true;

    /**
     * Properties are stored inside this.
     *
     * @var array
     */
    protected array $_props = [];

    /**
     * If a property does not exist, should null be returned?
     *
     * @var bool
     */
    protected bool $_defaultNull;

    /**
     * Is the init method running?
     *
     * @var bool|null
     */
    protected ?bool $_initializing = null;

    /**
     * Is it refreshable or not?
     *
     * @var bool
     */
    protected bool $_refreshable;

    protected string $_getPrefix;

    protected string $_setPrefix;

    protected bool $_fluent;

    /**
     * Number of refreshes.
     *
     * @var int
     */
    protected int $_refreshedCount = 0;

    public function props(): array
    {
        return $this->_props;
    }

    public function refreshedCount(): int
    {
        return $this->_refreshedCount;
    }

    public function __construct(?array $props = null)
    {
        $this->setup($props);
    }

    /**
     * When the constructor is overridden in the class, this can be called to setup Buildify.
     *
     * @param  array|null  $props
     * @return void
     */
    protected function setup(?array $props = null): void
    {
        $this->_defaultNull = static::DEFAULT_NULL;
        $this->_refreshable = static::REFRESHABLE;
        $this->_getPrefix = static::GET_PREFIX;
        $this->_setPrefix = static::SET_PREFIX;
        $this->_fluent = static::FLUENT;

        $this->_props = array_merge($this->defaults(), $props ?? []);
        unset($props);

        $this->_initializing = true;
        $this->init();
        $this->_initializing = false;
    }

    /**
     * Returns the default values of the properties.<br>
     * If the assign mode is used for setup, default values can be defined within the class by prefixing the field name with an underscore (_).
     *
     * @return array
     */
    public function defaults(): array
    {
        return [];
    }

    /**
     * Executes after the props have been assigned in the setup.
     */
    protected function init()
    {
    }

    /**
     * Creates a new object of the current class and returns it.
     *
     * @param  array|null  $props
     * @return static
     */
    public static function new(?array $props = null): static
    {
        return new static($props);
    }

    /**
     * Returns a callable that, when executed, returns an object of the current class.
     *
     * @param  array|null  $props
     * @return callable
     */
    public static function newCallable(?array $props = null): callable
    {
        return function () use (&$props) {
            return new static($props);
        };
    }

    /**
     * Executes on every change of a property value, except during initial setup.
     *
     * @param  string  $name
     * @param  mixed  $newValue
     * @param  mixed  $oldValue
     */
    public function refresh($name, $newValue, $oldValue): void
    {
        $this->_refreshedCount++;
    }

    protected function &prop($name, $value = null)
    {
        // get:

        if (func_num_args() == 1) {
            $get_method = $this->_getPrefix.ucfirst($name);
            if (method_exists($this, $get_method)) {
                $result = $this->$get_method();
                return $result;
            }
            if ($this->_defaultNull && !isset($this->_props[$name])) {
                $this->_props[$name] = null;
            }
            return $this->_props[$name];
        }

        // set:

        $old_value = $this->_prop($name);
        $updated = true;

        $set_method = $this->_setPrefix.ucfirst($name);
        if (method_exists($this, $set_method)) {
            if ($this->$set_method($value)) {
                $this->_props[$name] = $value;
            } else {
                $updated = false;
            }
        } else {
            $this->_props[$name] = $value;
        }

        if ($updated && $this->_refreshable && !$this->_initializing) {
            $this->refresh($name, $value, $old_value);
        }

        return $this;
    }

    protected function _prop($name, $value = null)
    {
        if (func_num_args() == 1) {
            return $this->prop($name);
        }

        return $this->prop($name, $value);
    }

    public function proped($name, $value = null)
    {
        return array_key_exists($this->_props, $name);
    }

    public function unprop($name)
    {
        unset($this->_props[$name]);
        return $this;
    }

    public function __set($name, $value)
    {
        $this->prop($name, $value);
    }

    public function __get($name)
    {
        return $this->prop($name);
    }

    public function __call($name, $arguments)
    {
        if ($this->_fluent) {
            if ($arguments) {
                $this->_prop($name, $arguments[0]);
                return $this;
            }

            return $this->_prop($name);
        }
    }

    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return $this->prop($offset);
    }

    #[\ReturnTypeWillChange]
    public function &offsetGet($offset)
    {
        return $this->prop($offset);
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        return $this->prop($offset, $value);
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        return $this->unprop($offset);
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->_props;
    }
}