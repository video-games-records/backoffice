# backoffice

## JWT
```
php bin/console lexik:jwt:generate-keypair
```

## Audit
```
php bin/console audit:schema:update --force
```

## Messenger
```
php bin/console messenger:consume player_chart_rank player_group_rank player_game_rank player_serie_rank player_platform_rank player_data player_rank
```