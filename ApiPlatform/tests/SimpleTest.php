<?php
namespace App\Test;

use PHPUnit\Framework\TestCase;

class SimpleTest extends TestCase {
  public function testAdition(){
    $value = true;
    $array = ['key' => 'value'];

    $this->assertEquals(3, 1+2, 'Three was extected 1+2');
    $this->assertTrue($value);

    $this->assertArrayHasKey('key', $array);

    $this->assertEquals('value', $array['key']);

    $this->assertCount(1, $array);
  }
}