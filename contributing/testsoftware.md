< [codechoice.md](codechoice.md) | [Start over](welcome.md)
### Don't worry, you can still help!
You can set up a testing server and use the software normally. You can then report any bugs or file feature requests.

To set up a testing server with minimal code, [follow these steps](testingserver.md).

Once you're on the site, use it like any user would. If you notice a table starting with **[Debug info] Error:**, feel free to [report it](createissue.md). Moreover, if the page fails to load completely or is blank, look in the console and provide the red text in a bug report. Red text looks something like this, but possibly with a different IP, stack trace, filename, and error text:

	172.18.0.1:51684 [500]: /index.php - Uncaught Error: Call to undefined function nonexistentFunc() in C:\clone\index.php:2
	Stack trace:
	#0 {main}
	  thrown in C:\clone\index.php on line 2

If something fails to work correctly, please also report that in a bug.

If you want to request a new feature, see the Requesting New Features section of [this document](createissue.md).