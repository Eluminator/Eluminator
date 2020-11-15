<?php

use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function testAdd(): void
    {
        $router = new Core\Router();
        $router->add('/test', ['testController' => 'testAction']);
        $routes = $router->getRoutes();
        $expected = [
            '/^\/test$/i' =>
                [
                    'testController' => "testAction"
                ]
        ];

        $this->assertEquals($expected , $routes);
        $this->assertEquals(array_key_first($expected['/^\/test$/i']) , 'testController');
        $this->assertEquals($expected['/^\/test$/i']['testController'] , 'testAction');
    }

}
