#!/bin/bash
set -e

# Install npm dependencies if package.json exists and node_modules doesn't exist or is empty
if [ -f "package.json" ]; then
    if [ ! -d "node_modules" ] || [ -z "$(ls -A node_modules)" ]; then
        echo "Installing npm dependencies..."
        npm install
    else
        echo "npm dependencies already installed, skipping..."
    fi
fi

# Execute the main command (php-fpm by default)
exec "$@"
