# Weekly D3 Forum Software
I hope you enjoy it! I had fun developing it, and you can have fun chatting.
## Installation
1. Upload all files to the Web server.
2. Save the files (if needed).
3. Visit the Web site that you are uploading to. If you do not have a Web site, you can copy it onto your computer and run 
    `php -S 0.0.0.0:8000 -t filesareuploadedhere/`,
   where filesareuploadedhere/ is the directory where you copied the files. Then, visit `localhost:8000`.
4. Edit the following files to match your forum's policy:
  - DCP.php
  - tos_raw.php
  then, save those files.
5. Edit styles/main/main.css to match your favorite settings.
6. Click "Sign Up" at the top bar when visiting the site.
7. Register an account using your information.
8. \[Optional] If you wish to close account registration, comment out the code on account/signup.php and replace with a notice saying signups are closed.