<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Utilities\Substitution;
use Tests\TestCase;

class SubstitutionMatchingTest extends TestCase
{
    public function testSubstitution(): void
    {
        $substitutionsData = require __DIR__.'/../data/substitute/substitute.php';

        $substitutionKeys = [
            'nested',
            'multiple_nested',
        ];

        foreach ($substitutionKeys as $key) {
            foreach ($substitutionsData[$key] as $substitution) {
                $substitutions = new Substitution();

                $substitutions->setSubstitutions($substitution['data']);

                $this->assertEquals(
                    $substitution['expected'],
                    $substitutions->substitute($substitution['given']),
                );
            }
        }
    }
}
