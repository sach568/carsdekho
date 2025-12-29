#!/bin/bash
set -e

echo "=========================================="
echo "🚀 Starting Laravel Application with PostgreSQL"
echo "=========================================="

# Display info
echo "📁 Working directory: $(pwd)"
echo "🌐 Database Host: ${DB_HOST}"

# Step 1: Create .env file
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    if [ -f .env.example ]; then
        cp .env.example .env
    else
        cat > .env << EOF
APP_NAME=CarsDekho
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://carsdekho.onrender.com
LOG_CHANNEL=stderr

DB_CONNECTION=pgsql
DB_HOST=${DB_HOST}
DB_PORT=5432
DB_DATABASE=car_website
DB_USERNAME=car_user
DB_PASSWORD=${DB_PASSWORD}
EOF
    fi
    echo "✅ .env file created"
fi

# Step 2: Set APP_KEY
if [ -z "${APP_KEY}" ] || [ "${APP_KEY}" = "base64:" ]; then
    echo "🔑 Generating new APP_KEY..."
    php artisan key:generate --force
else
    echo "🔑 Setting APP_KEY from environment..."
    sed -i "s|^APP_KEY=.*|APP_KEY=${APP_KEY}|" .env
fi

# Step 3: Update database credentials in .env
echo "⚙️  Updating database configuration..."

# Function to safely update env vars
update_env() {
    local key="$1"
    local value="$2"
    
    if [ -n "${value}" ]; then
        # Escape special characters
        ESCAPED_VALUE=$(echo "${value}" | sed 's/[\/&]/\\&/g')
        if grep -q "^${key}=" .env; then
            sed -i "s|^${key}=.*|${key}=${ESCAPED_VALUE}|" .env
        else
            echo "${key}=${ESCAPED_VALUE}" >> .env
        fi
        echo "   ✅ ${key}=[hidden]"
    fi
}

# Update variables
update_env "DB_CONNECTION" "${DB_CONNECTION:-pgsql}"
update_env "DB_HOST" "${DB_HOST}"
update_env "DB_PORT" "${DB_PORT:-5432}"
update_env "DB_DATABASE" "${DB_DATABASE:-car_website}"
update_env "DB_USERNAME" "${DB_USERNAME:-car_user}"
update_env "DB_PASSWORD" "${DB_PASSWORD}"

# Also update app variables
sed -i "s|^APP_ENV=.*|APP_ENV=${APP_ENV:-production}|" .env
sed -i "s|^APP_DEBUG=.*|APP_DEBUG=${APP_DEBUG:-false}|" .env
sed -i "s|^APP_URL=.*|APP_URL=${APP_URL:-https://carsdekho.onrender.com}|" .env

# Step 4: Test database connection
echo ""
echo "🔍 Testing database connection..."
timeout 10 bash -c "
if pg_isready -h ${DB_HOST} -p ${DB_PORT:-5432} 2>/dev/null; then
    echo '✅ PostgreSQL is reachable'
else
    echo '❌ Cannot connect to PostgreSQL'
    echo '   Host: ${DB_HOST}'
    echo '   Port: ${DB_PORT:-5432}'
fi
" || echo "⚠️  Connection test timeout"

# Step 5: Wait for database (optional)
echo "⏳ Waiting for database to be ready..."
for i in {1..10}; do
    if php -r "
    try {
        \$pdo = new PDO('pgsql:host=${DB_HOST};port=${DB_PORT:-5432}', '${DB_USERNAME}', '${DB_PASSWORD}');
        echo '✅ Database connection successful';
        exit(0);
    } catch (Exception \$e) {
        echo 'Attempt $i failed: ' . \$e->getMessage();
        exit(1);
    }
    " 2>/dev/null; then
        echo "✅ Database is ready!"
        break
    fi
    if [ $i -eq 10 ]; then
        echo "⚠️  Database not ready after 10 attempts, continuing anyway..."
    fi
    sleep 2
done

# Step 6: Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true

# Step 7: Run migrations
echo "🔄 Running database migrations..."
php artisan migrate --force || {
    echo "⚠️  Migration failed, trying to create database..."
    # Try to create database if it doesn't exist
    php artisan tinker --execute="
    try {
        DB::statement('CREATE DATABASE IF NOT EXISTS ${DB_DATABASE}');
        echo 'Database created or already exists';
    } catch(Exception \$e) {
        echo 'Cannot create database: ' . \$e->getMessage();
    }
    " 2>/dev/null || true
    
    # Try migration again
    php artisan migrate --force || echo "⚠️  Migrations failed, but continuing..."
}

# Step 8: Cache for production
if [ "${APP_ENV}" = "production" ]; then
    echo "⚡ Caching for production..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# Step 9: Start Apache
echo ""
echo "=========================================="
echo "🌐 Starting Apache Web Server..."
echo "=========================================="
echo "✅ Server: ${APP_URL}"
echo "✅ Database: ${DB_HOST}"
echo ""

exec apache2-foreground