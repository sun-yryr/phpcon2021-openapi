<?php

declare(strict_types=1);

namespace Tests\Feature;

class SampleControllerTest extends ControllerTestCase {
    public function test_index(): void
    {
        $response = $this->get('/sample');
        // assert
        $response
            ->assertOk()
            ->assertSee('ok');
    }

    public function test_assertion1(): void
    {
        $response = $this->get('/sample/assertion');
        // assert
        $response
            ->assertOk()
            ->assertExactJson([
                'name' => '太郎',
                'age' => 20,
                'birthday' => '1999-04-25',
            ]);
    }

    public function test_assertion2(): void
    {
        $this->getAndValidate('/sample/assertion');
        // assert済み
    }
}
