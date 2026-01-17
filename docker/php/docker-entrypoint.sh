#!/bin/bash
set -e

# Run npm install and build if package.json exists
if [ -f "package.json" ]; then
    echo "Installing npm dependencies..."
    npm install
    
    echo "Building assets..."
    npm run build
fi

# Execute the main command passed to the container
exec "$@"
