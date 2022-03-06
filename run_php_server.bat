<<<<<<< HEAD
@echo off
echo "Please make sure PHP is installed."
set /p port="Enter port number: "
echo Press Ctrl-C twice at any time to quit.
=======
@echo off
echo "Please make sure PHP is installed."
set /p port="Enter port number: "
echo Press Ctrl-C twice at any time to quit.
>>>>>>> 0dd6ba65130b774d8e078ba9c410e6bb02f22f53
php -S 0.0.0.0:%port% -t .