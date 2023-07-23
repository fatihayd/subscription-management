## Ön koşullar

Sistem gereklilikleri:
- PHP 8.x
- Composer
- MySQL

## Kurulum Adımları

1. Gerekli paketleri indirmek için aşağıdaki komutu çalıştırın:

    ```bash
    composer install
    ```

2. MySQL üzerinden bir veritabanı oluşturun.
3. Ortam yapılandırmaları için `.env.example` dosyasını kopyalayarak `.env` dosyası oluşturun:

    ```bash
    cp .env.example .env
    ```

4. Oluşturulan `.env` dosyası içerisindeki `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` alanlarına oluşturulan veritabanı bilgilerini girin.

5. Uygulama anahtarı oluşturmak için aşağıdaki komutu çalıştırın:

    ```bash
    php artisan key:generate
    ```

6. Tabloları oluşturmak için aşağıdaki komutu çalıştırın:

    ```bash
    php artisan migrate
    ```

7. Sistemin çalışması için gereken ayarları yüklemek için aşağıdaki komutu çalıştırın:

    ```bash
    php artisan db:seed
    ```

8. Sistemi çalıştırmak için aşağıdaki komutu kullanın:

    ```bash
    php artisan serve
    ```

Sistem, "http://127.0.0.1:8000" adresinde çalışacaktır.

# Cli-commands

1. Abonelikleri yenilemek için aşağıdaki komutu kullanın:

    ```bash 
    php artisan renew:subscription
   ```
2. Yeni kullanıcı kaydı için:
   
    ```bash  
    php artisan make:register --name= --email= --password=
    ```
3. Kullanıcıya abonelik eklemek için aşağıdaki komutu kullanın:
   ```bash  
   php artisan add:subscription --user_id= --renewed_at= --expired_at=
   ```
4. Kullanıcıya ait aboneliği güncellemek için aşağıdaki komutu kullanın: 
    ```bash  
   php artisan update:subscription --user_id= --subscription_id= --renewed_at= --expired_at=
   ```
5. Kullanıcıya ait aboneliği silmek için aşağıdaki komutu kullanın: 
   ```bash  
   php artisan delete:subscription --user_id= --subscription_id=
   ```
6. Kullanıcıya ait tüm abonelikleri silmek için aşağıdaki komutu kullanın:
   ```bash  
   php artisan delete:all_subscription --user_id=
   ```
7. Kullanıcıya ait aboneliğe ödeme eklemek için aşağıdaki komutu kullanın:
   ```bash  
   php artisan add:transaction --user_id= --subscription_id=
   ```

# Enpointler


1. Kayıt olmak için aşağıdaki endpoint'i kullanın: \
   `POST /api/register`
2. Oturum açmak için aşağıdaki endpoint'i kullanın: \
   `POST /api/login`
3. Kullanıcıya abonelik eklemek için aşağıdaki endpoint'i kullanın: \
   `POST /api/user/{id}/subscription`
4. Kullanıcıya ait aboneliği güncellemek için aşağıdaki endpoint'i kullanın: \
   `PUT /api/user/{id}/subscription/{subscription_id}`
5. Kullanıcıya ait aboneliği silmek için aşağıdaki endpoint'i kullanın: \
   `DELETE /api/user/{id}/subscription/{subscription_id}`
6. Kullanıcıya ait tüm abonelikleri silmek için aşağıdaki endpoint'i kullanın: \
   `DELETE /api/user/{id}/subscriptions`
7. Kullanıcıya ait aboneliğe ödeme eklemek için aşağıdaki endpoint'i kullanın: \
   `POST /api/user/{id}/transaction`
8. Kullanıcıya ait abonelik ve ödemeleri almak için aşağıdaki endpoint'i kullanın: \
   `GET /api/user/{id}`
