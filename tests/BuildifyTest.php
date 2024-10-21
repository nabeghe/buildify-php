<?php declare(strict_types=1);

require __DIR__.'/MyBuilder.php';

class BuildifyTest extends \PHPUnit\Framework\TestCase
{
    public function testRefreshedCount()
    {
        $my = MyBuilder::new();
        $this->assertSame(1, $my->refreshedCount());
    }

    public function testProps()
    {
        $my = MyBuilder::new([
            'b' => 13,
            'c' => 14,
        ]);
        $this->assertSame(-1, $my->a);
        $this->assertSame(13, $my->b());
        $this->assertSame(14, $my->c);
        $this->assertSame(27, $my->d);
        $this->assertSame(182, $my->e);

        $my->f = 100;
        $this->assertSame(100, $my->f());
        $this->assertSame(50, $my->g);
    }
}