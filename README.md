# HNGi13 Stage Zero Backend

[![Live](https://img.shields.io/badge/live-online-brightgreen)](https://baydre-stage-0-backend.pxxl.click/me)  
Link: https://baydre-stage-0-backend.pxxl.click/me

A Simple RESTful API endpoint that returns your profile information along with a dynamic cat fact fetched from an external API. This task validates your ability to consume third-party APIs, format JSON responses, and return dynamic data.

## Features

- Returns JSON with user info and a cat fact
- Uses cURL when available and falls back to `file_get_contents`
- Simple `.env` support for user metadata

## Requirements

- PHP 8+ (or PHP 7.4+)
- php-curl extension recommended (project will fall back to `file_get_contents` if cURL is not available)

Install php-curl on Debian/Ubuntu:
```bash
sudo apt update
sudo apt install php-curl
# restart webserver or php-fpm if needed, e.g.:
# sudo systemctl restart apache2
# sudo systemctl restart php8.1-fpm
```

## .env

Create a plain `.env` (no PHP tags). Example:
```env
USER_EMAIL="garba.chima@gmail.com"
USER_NAME="garba chima"
USER_STACK="your_stack_here"
```
Note: the repo's `.gitignore` already excludes `.env`.

If your current `.env` contains `<?php` at the top, remove that line so it is plain key=value format.

## Run (built-in PHP server)
From the project folder:
```bash
cd /home/baydre_africa/HNG-13/backend/stage-zero-backend
php -S localhost:8000
```
Open http://localhost:8000/me

## API
- GET /me
  - Returns JSON with:
    - user (email, name, stack)
    - fact (cat fact string)
    - createdAt (ISO 8601)

### Sample Response
```json
{
  "status": "success",
  "user": {
    "email": "garba.chima@example.com",
    "name": "Garba Chima",
    "stack": "TechStack"
  },
  "timestamp": "2025-10-16T09:55:42.123Z",
  "fact": "Life na japa, code na deploy!"
}
```

## Troubleshooting
- Error "Call to undefined function curl_init()": php-curl extension not installed/enabled. Install and restart PHP/webserver.
- If you prefer always using cURL but it's missing, enable it via your PHP package manager.

## License
See LICENSE file in the repository.

## Author
- Name: baydre
- Slack: @baydre
- Email: baydreafrica@gmail.com
- GitHub: https://github.com/baydre

## Deployment

PXXL (chosen platform)

- Connect your repository in the PXXL dashboard or push the repo following PXXL instructions.
- Set environment variables in the PXXL app settings: USER_EMAIL, USER_NAME, USER_STACK
- Start command (use the PORT PXXL provides):
  ```
  php -S 0.0.0.0:$PORT -t .
  ```
- If using Docker on PXXL, include a Dockerfile and let PXXL build the image. Ensure the container listens on the PORT provided by PXXL.