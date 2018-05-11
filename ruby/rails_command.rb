rails c ActiveRecord::Migration.drop_table(:users) 删除数据表

ActiveRecord::Base.connection.execute 'show tables'

cap staging deploy
cap production deploy

RAILS_ENV=production bin/rake ...

rspec spec/models/users_spec.rb

bin/rake assets:precompile

bin/rake db:migrate --trace
