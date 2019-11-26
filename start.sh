./jwt.sh

bin/console doctrine:schema:update --force
bin/console printify:user:create
bin/console server:start