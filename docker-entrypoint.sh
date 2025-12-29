#!/bin/bash
set -e

echo "=========================================="
echo "🚀 Starting Laravel Application"
echo "=========================================="

# Show current directory
echo "📁 Working directory: $(pwd)"
echo "📄 Files in directory:"
ls -la

# Step 1: Create .env file if it doesn't exist
if [ ! -f .env ]; then
    echo "📝 Creating .env file from .env.example..."
    if [ -f .env.example ]; then
        cp .env.example .env
        echo "✅ .env file created successfully"
    else
        echo "❌ .env.example not found! Creating empty .env"
        touch .env
        echo "APP_NAME=Laravel" >> .env
        echo "APP_ENV=production" >> .env
        echo "APP_KEY=" >> .env
        echo "APP_DEBUG=false" >> .env
        echo "APP_URL=http://localhost" >> .env
    fi
else
    echo "✅ .env file already exists"
fi

# Step 2: Set or generate APP_KEY
if [ -z "${APP_KEY}" ] || [ "${APP_KEY}" = "" ] || [ "${APP_KEY}" = "base64:" ]; then
    echo "🔑 Generating new APP_KEY..."
    php artisan key:generate --force
    echo "✅ APP_KEY generated successfully"
else
    echo "🔑 Setting APP_KEY from environment..."
    # Update .env with the provided APP_KEY
    sed -i "s|^APP_KEY=.*|APP_KEY=${APP_KEY}|" .env
    echo "✅ APP_KEY set from environment variable"
fi

# Step 3: Update other environment variables
echo "⚙️  Updating environment variables..."

# Function to update env variable
update_env() {
    local key="$1"
    local value="$2"
    
    if [ -n "${value}" ]; then
        # Check if key exists in .env
        if grep -q "^${key}=" .env; then
            # Escape special characters for sed
            ESCAPED_VALUE=$(echo "${value}" | sed 's/[\/&]/\\&/g')
            sed -i "s|^${key}=.*|${key}=${ESCAPED_VALUE}|" .env
        else
            echo "${key}=${value}" >> .env
        fi
        echo "   ✅ ${key}=${value}"
    fi
}

# Update common variables
update_env "APP_ENV" "${APP_ENV}"
update_env "APP_DEBUG" "${APP_DEBUG}"
update_env "APP_URL" "${APP_URL}"
update_env "LOG_CHANNEL" "${LOG_CHANNEL}"
update_env "DB_CONNECTION" "${DB_CONNECTION:-pgsql}"
update_env "DB_HOST" "${DB_HOST}"
update_env "DB_PORT" "${DB_PORT:-5432}"
update_env "DB_DATABASE" "${DB_DATABASE}"
update_env "DB_USERNAME" "${DB_USERNAME}"
update_env "DB_PASSWORD" "${DB_PASSWORD}"

# Step 4: Show current .env config
echo ""
echo "=========================================="
echo "📋 Current .env configuration:"
echo "=========================================="
grep -E "^(APP_|DB_|LOG_)" .env || cat .env
echo ""

# Step 5: Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
echo "✅ Caches cleared"

# Step 6: Run migrations (if not in testing)
if [ "${APP_ENV}" != "testing" ] && [ -n "${DB_HOST}" ]; then
    echo "🔄 Running database migrations..."
    php artisan migrate --force || echo "⚠️  Migrations may have failed, continuing..."
else
    echo "⏭️  Skipping migrations"
fi

# Step 7: Cache for production
if [ "${APP_ENV}" = "production" ]; then
    echo "⚡ Caching for production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    echo "✅ Production caching completed"
fi

# Step 8: Start Apache
echo ""
echo "=========================================="
echo "🌐 Starting Apache Web Server..."
echo "=========================================="
echo "✅ Server is ready at: ${APP_URL}"
echo ""

exec apache2-foreground