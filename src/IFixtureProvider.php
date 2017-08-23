<?php declare(strict_types=1);

namespace blitzik\Fixtures;

interface IFixtureProvider
{
    public function getDataFixtures(): array;
}