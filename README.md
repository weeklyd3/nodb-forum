# Weekly D3 Forum Software

**WARNING: DO NOT INSTALL WITH THE REPO CODE. DOWNLOAD A STABLE RELEASE INSTEAD. DEV VERSION IS VERY UNSTABLE.**

## Updates
**7/8/2021**: Relicensed as AGPL.

**7/8/2021**: E_ERROR fixed, repo code is usuable. (But that doesn't make us like downloading repo code more!)

I hope you enjoy it! I had fun developing it, and you can have fun chatting.
## Installation
**Note**: You need to be logged in as `admin` for administrator functions such as uninstallation.

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

## Licensing
### nodb-forum
nodb-forum Forum Software.  
Copyright (C) 2021 nodb-forum contributors

This program is free software: you can redistribute it and/or modify  
it under the terms of the GNU Affero General Public License as  
published by the Free Software Foundation, either version 3 of the  
License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,  
but WITHOUT ANY WARRANTY; without even the implied warranty of  
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the  
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License  
along with this program.  If not, see https://www.gnu.org/licenses/.

### Parsedown
This software includes Parsedown.

The MIT License (MIT)

Copyright (c) 2013-2018 Emanuil Rusev, erusev.com

Permission is hereby granted, free of charge, to any person obtaining a copy of  
this software and associated documentation files (the "Software"), to deal in  
the Software without restriction, including without limitation the rights to  
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of  
the Software, and to permit persons to whom the Software is furnished to do so,  
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all  
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR  
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS  
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR  
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER  
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN  
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.  
