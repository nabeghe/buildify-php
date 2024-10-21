<?php

/**
 * @property int $a
 * @property int $b
 * @property int $c
 * @property int $d
 * @property int $e
 * @property int $f
 * @property int $g
 *
 * @method self|int a($value = false)
 * @method self|int b($value = false)
 * @method self|int c($value = false)
 * @method self|int d($value = false)
 * @method self|int e($value = false)
 * @method self|int f($value = false)
 * @method self|int g($value = false)
 */
class MyBuilder extends \Nabeghe\Buildify\Buildify
{
    public function defaults(): array
    {
        return [
            'a' => -1,
            'b' => -2,
            'c' => -3,
        ];
    }

    public function refresh($target = null): void
    {
        parent::refresh($target);
        $this->d = $this->b + $this->c;
        $this->e = $this->b * $this->c;

        if ($this->f === 100) {
            $this->g = 50;
        }
    }
}