<?php

namespace Tests\Unit\Traits;


use Morris\Core\Enums\User\Role;
use PHPUnit\Framework\TestCase;

class HasLabelsTest extends TestCase
{
    public function test_label_path_correctly_decoded(): void
    {
        $testedEnum = Role::Attendee;
        $labelPath = $testedEnum->getRoot();

        $this->assertEquals("user.role", $labelPath);
    }
}
