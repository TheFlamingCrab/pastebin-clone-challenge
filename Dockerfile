FROM php:8.2-cli

# Install PHP dependencies
RUN apt-get update && apt-get install -y \
    sqlite3 libsqlite3-dev \
    curl gnupg ca-certificates wget \
    firefox-esr \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install geckodriver (check latest version https://github.com/mozilla/geckodriver/releases)
ENV GECKODRIVER_VERSION=0.33.0
RUN wget -q https://github.com/mozilla/geckodriver/releases/download/v${GECKODRIVER_VERSION}/geckodriver-v${GECKODRIVER_VERSION}-linux64.tar.gz \
    && tar -xzf geckodriver-v${GECKODRIVER_VERSION}-linux64.tar.gz -C /usr/local/bin \
    && rm geckodriver-v${GECKODRIVER_VERSION}-linux64.tar.gz

# Install Node.js (Debian Buster backports nodejs 18 by default, so install from NodeSource for 18)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*


# Copy app files (PHP app + bot.js + package.json etc)
WORKDIR /app
COPY . .

# Install Node.js dependencies (selenium-webdriver)
RUN npm install selenium-webdriver

# Expose PHP server port
EXPOSE 8000

# Create start.sh to run PHP server and bot.js concurrently
RUN echo '#!/bin/bash\n\
php -S 0.0.0.0:8000 -t /app &\n\
node bot.js &\n\
wait\n' > /start.sh && chmod +x /start.sh

# Expose port 8000 for PHP server
EXPOSE 8000

# Run start.sh when container starts
CMD ["/start.sh"]
