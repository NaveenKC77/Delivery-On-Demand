
[//]: # (php md)
./vendor/bin/phpmd ./src  xml ./app/build/phpmd/rulesets.xml --report-file ./app/build/phpmd/results.xml



to fix eol error
php vendor/bin/phpcbf --standard=PSR12 --extensions=php src/
