<?php

namespace App\Twig\Extension;

use Carbon\Carbon;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AgoExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('ago', [$this, 'formatAgo']),
        ];
    }

    public function formatAgo(string $dateTime)
    {
        try {
            $date = Carbon::parse($dateTime);

            return $date->diffForHumans();
        } catch (\Exception $e) {
            return $dateTime;
        }
    }
}
