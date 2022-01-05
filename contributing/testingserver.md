### Running a testing server
It just requires the PHP server, and (as a bonus) requires no internet connection. However, the site will only be accessible from your own computer.
1. If you haven't already, [download PHP](https://www.php.net/). PHP 7.4.3 is used for development, so any version newer than that is okay.
2. Open up the command line by holding <kbd>Shift</kbd> while right-clicking in your clone folder and selecting "Open command window here" (Windows) or opening Terminal and `cd`ing to the directory on Mac/Linux.
3. Choose a port number. 8000 will be used here, although if another application is using it, it will not be accessible.
   
   To see if a port is taken, visit `localhost:PORTNUM`, where `PORTNUM` is the port number. If you see a `Localhost refused to connect` error, that means that the port is not taken.
4. Run the command `php -S 0.0.0.0:8000 -t .`. There is only one period at the end. Replace 8000 with the port number.
5. Visit `localhost:8000` where 8000 is replaced with your port number in a browser.
6. Congratulations, you have set up the site!