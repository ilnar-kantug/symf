#! /bin/bash

# Commonly Used Aliases
alias ll="ls -alF"
alias ..="cd .."
alias ...="cd ../.."
alias ....="cd ../../.."
alias .....="cd ../../../.."
alias ~="cd ~" # `cd` is probably faster to type though
alias -- -="cd -"
alias home="cd ~"

alias art="php artisan"
alias artisan="php artisan"
alias cdump="composer dump-autoload -o"
alias composer:dump="composer dump-autoload -o"
alias db:reset="php artisan migrate:reset && php artisan migrate --seed"
alias dusk="php artisan dusk"
alias fresh="php artisan migrate:fresh"
alias migrate="php artisan migrate"
alias refresh="php artisan migrate:refresh"
alias rollback="php artisan migrate:rollback"
alias seed="php artisan db:seed"
alias serve="php artisan serve --quiet &"

alias phpunit="./vendor/bin/phpunit"
alias p="phpunit "
alias pf="phpunit --filter "
alias pd='phpunit --debug'

alias scheduler="bash -c 'while true; do php /var/www/html/artisan schedule:run --verbose --no-interaction & sleep 60; done'"
alias queue="php /var/www/html/artisan queue:work --verbose --tries=3 --timeout=90"
