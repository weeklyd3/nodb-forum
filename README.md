# Weekly D3 Forum Software
**Note**: It is recommended to install on a 64 bit system. If you install on a 32-bit system, the [2038 problem](https://en.wikipedia.org/wiki/2038_Problem) will strike in, and all your dates tracked after 03:14:07 1-19-2038 will reset to 1901! So, if you want to last until 2486, use a 64-bit system!

**WARNING: DO NOT INSTALL WITH THE REPO CODE. DOWNLOAD A STABLE RELEASE INSTEAD. DEV VERSION IS VERY UNSTABLE.**
## Asking for Help

You can find documentation at the [wiki](https://github.com/weeklyd3/nodb-forum/wiki).

If you can't find answers to your questions at the wiki, please ask them by clicking below:   
[![Ask a Question!](https://gist.githubusercontent.com/weeklyd3/ac9072f166f619e67e733380af93308d/raw/400ef17931c381224f99f89fee4cd0b47e2c67a0/Ask%2520a%2520question%2520button.svg)](https://github.com/weeklyd3/nodb-forum/issues/new?assignees=&labels=question&template=support_request.yaml&title=%5BQuestion%5D%3A+%3Ctitle%3E)  
<sub><sup>([direct link](https://github.com/weeklyd3/nodb-forum/issues/new?assignees=&labels=question&template=support_request.yaml&title=%5BQuestion%5D%3A+%3Ctitle%3E))</sup></sub>
## Updates
**7/8/2021**: Relicensed as AGPL.

**7/8/2021**: E_ERROR fixed, repo code is usuable. (But that doesn't make us like downloading repo code more!)

I hope you enjoy it! I had fun developing it, and you can have fun chatting.
## Installation
**Note**: You need to be logged in as `admin` for administrator functions such as uninstallation. After your initial login, go to /app/tools/admins.php to add additional admins, and (maybe) delete `admin`.

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

## Features
 - Live refresh for chat
 - Accessibility
   - All form fields have labels
   - [Placeholders are not used in place of labels](https://www.smashingmagazine.com/2018/06/placeholder-attribute/)
 - Easy account setup 
 - Easy installation, only requires extensions that are enabled by default:
   - arrays
   - json
 - AGPL 3.0 license ([view license](https://www.gnu.org/licenses/agpl-3.0.txt))

## Screenshots
![private messages](https://user-images.githubusercontent.com/79176077/134054046-5c3dacd9-df87-4373-ade6-325e970400e4.png)  
![home page](https://user-images.githubusercontent.com/79176077/134054392-6b562c0c-a581-41d9-9455-d878982c3fe6.png)  
![tags](https://user-images.githubusercontent.com/79176077/134054532-1c8f5d6c-0ce4-4527-96d8-e0c512c0561a.png)
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
