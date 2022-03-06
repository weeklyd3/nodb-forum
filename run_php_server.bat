@echo off
echo "Please make sure PHP is installed."
set /p port="Enter port number: "
echo Press Ctrl-C twice at any time to quit.
php -S 0.0.0.0:%port% -t .