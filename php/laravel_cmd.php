php artisan make:controller UserController 
php artisan make:model User
php artisan make:model User --migration 生成模型时生成数据库迁移
php artisan make:migration create_users_table
php artisan migrate
php artisan migrate --force 在生产环境中强制运行迁移
php artisan migrate:rollback 回滚最新的一次迁移
php artisan migrate:reset 回滚所有的应用迁移
php artisan migrate:refresh 回滚所有数据库迁移 这个命令可以有效的重建整个数据库
