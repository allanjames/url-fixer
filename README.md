# Brafton Redirect Exporter
simple plugin to create htaccess code for redirects

Install and activate the plugin.  Plugin settings can then be found by navigating to the settings option in the left hand navigation after activation.  Main intent of the plugin is to set redirects for posts or pages after the url structure has been modified via the permalinks settings.  So as a common example, if post urls were updated to add the word "blog" in the url structure directly after the root url and before the post title, this plugin helps to set redirects from the old url to the new one in bulk.

Example:

www.rootdomain.com/post-title/ ---------------->  www.rootdomain.com/blog/post-title

There also exists the option to include the post category in the redirect where it did not exist in the previous post structure.

This plugin connects to and updates the Wordpress root htaccess file provided it is writable.  There is also an option to export the old and new urls to a .csv file.

This is a disposable plugin, intended to be used once and discarded.  There is no need to keep this plugin active within the WP dashboard.
