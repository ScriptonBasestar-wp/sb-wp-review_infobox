#!/bin/bash

## wp-config 파일 생성
# wp config create --dbname=1111111 --dbuser=root

## wp db 생성
# wp db create

## 계정 생성 및 초기화
wp core install --url=localhost --title="wp-test" --admin_user=testuser --admin_password=testpass --admin_email=test@test.org
# wp super-admin add testuser

## 플러그인 활성화
wp plugin activate sb-review_infobox

##설정정보 추가
# wp 