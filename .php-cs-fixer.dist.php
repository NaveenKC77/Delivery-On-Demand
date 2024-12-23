<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('var')       // Exclude cache and logs
    ->exclude('vendor')    // Exclude third-party libraries
    ->exclude('public')    // Exclude compiled assets and entry point
    ->name('*.php')        // Only include PHP files
    ->notName('*.twig.php') // Exclude Twig compiled PHP files
    ->ignoreDotFiles(true) // Ignore hidden files
    ->ignoreVCS(true);     // Ignore VCS files like .git, .svn

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        // Symfony-specific rules
        'phpdoc_annotation_without_dot' => false, // Allow annotations without a period
        'phpdoc_align' => ['align' => 'left'], // Align phpdoc comments
        'concat_space' => ['spacing' => 'one'], // Ensure single space for concatenation
    ])
    ->setRiskyAllowed(true) // Allow risky rules
    ->setFinder($finder);
