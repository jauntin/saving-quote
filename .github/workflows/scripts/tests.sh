cp .env.testing-ci .env.testing && cp .env.testing-ci .env && \
php artisan app:create-database && \
php artisan test --without-tty --parallel --processes 6 --coverage-text
