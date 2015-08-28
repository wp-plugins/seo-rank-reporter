=== SEO Rank Reporter ===

Contributors: davidscoville
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=PQJ3EP32NVC5J
Tags: seo, rank, google, rankings, SERP, kwista
Requires at least: 2.9
Tested up to: 4.3
Stable tag: 2.2.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Track your Google rankings every 3 days and see reports of your progress in a graph. 

== Description ==

Based on keywords you choose, the SEO Rank Reporter plugin will track your Google rankings every 3 days and report the data in an easy-to-read graph. 

Learn [How to use the SEO Rank Reporter Plugin](http://www.youtube.com/watch?v=0gXyUyq1FcQ)

http://www.youtube.com/watch?v=0gXyUyq1FcQ

You will also be able to visualize your traffic flow in response to ranking changes and receive emails notifying you of major rank changes. 

**Features:**

* Add keywords to the reporter and let the reporter track your website's (or other websites') ranking changes. 
* View a historical graph of your rankings. 
* Visually compare how your website is ranking compared to competitors.
* Watch how your traffic fluctuates based on ranking changes.
* Get email notifications when any one of your rankings changes in X (you specify X) positions.
* Download a full CSV file containing all of your ranking data.
* View a list of keywords that are currently driving traffic to your website and add them to the rank reporter.
* Search using other Google Country URLs (e.g., google.co.uk)

**Translators:**

* French (fr_FR) - [Aurélien Baron](http://www.linkedin.com/pub/aurélien-baron/21/4b4/1b5)
* Chinese (zh_CN) - [Annie Chen](http://www.linkedin.com/pub/annie-chen/3a/b0b/968)
* Spanish (es_ES) - [Kelly Kremko Alegría](http://www.linkedin.com/pub/kelly-kremko-alegría/a/117/677)

Many thanks to translators!

**History:**

While traffic is a good measurement of SEO, online marketers who take their SEO seriously, will also use rankings as a major KPI (key performance indicator) of their work. Many SEO tools exist to track Google rankings. However, these tools can be pricey and bulky. 

The SEO Rank Reporter tool was built to offer a simple ranking measurement tool for Wordpress websites. Users can now view how their website ranks for a number of different keywords right within their Wordpress admin panel. 

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the entire directory `/seo-rank-reporter/` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Click on the new Admin Section, `Rank Reporter,` and then the menu item, `Add Keywords`
1. Add a new keyword/url set to track by entering your desired keyword and url and then clicking `Add to Reporter`

== Frequently Asked Questions ==

= How often are my keyword/url sets checked? =

The SEO Rank Reporter checks your Google rankings every three days using Wordpress's built-in cron function

== Screenshots ==

1. The SEO Rank Reporter displays a graph of keywords you've selected and how they've changed in rankings over time.
2. Along with the graph, the Rank Reporter also displays a table of all the keywords you've added. Columns include the original rank of the keyword/url and its current rank. 
3. On another page, you can juxtapose a keyword's rank with it's traffic. This graph shows the number of hits your website has received for a particular keyword. This is helpful to see if your traffic significantly increases with a boost in rankings. 
4. Receive email notifications if a keyword/url set suddenly jumps or drops in rankings. 

== Changelog ==

= 2.2.3 = 

* Fixed security issue

= 2.2.2 = 

* Rank checking is more efficient
* Adding input now includes website URL as default value

= 2.2.1 = 

* Fixed datepicker and made minor text modifications

= 2.2 = 

* Added French, Chinese, and Spanish Languages

= 2.1.7 = 

* Prepared plugin for i18n

= 2.1.6 = 

* Prepared plugin for i18n
* Modified the capture of the country URL

= 2.1.5 = 

* Added new manual update rankings button
* Improved notifications to send both HTML and plain email messages

= 2.1.4 = 

* Fixed bug where all rankings were reported as Not in Top 100

= 2.1.3 = 

* Fixed bug where all rankings were reported as Not in Top 100

= 2.1.2 = 

* Fixed error where Reporter scans multiple brand URLs

= 2.1.1 =

* Fixed break error when adding keywords

= 2.1 = 

* Added validation to the Add Keywords page
* Added feedback widget to the Settings page

= 2.0 =

* Fixed jQuery errors causing graph not to show
* Modified the interface of the visits page to be more similar to the ranking graph page
* Added the ability to filter the visits graph
* Added other Google country URLs to search
* Changed the cron function to work faster--searches Google SERP of 100 results rather than 10 pages of 10 results
* Improved the Add Keywords functionality

= 1.1 =

* Fixed conflict with Automatic SEO Links Plugin
* Added ability to remove database table
* Added settings page

= 1.0.1 =

* Fixed jQuery issue for graph
* Removed the possibility of deleting gathered data when an upgrade occurs

= 1.0 =

* First Release

== Upgrade Notice ==

= 2.2.3 = 

* Fixed security issue

= 2.2.2 = 

* Improvements to the way the plugin checks

= 2.2.1 = 

* Minor improvements

= 2.2 = 

* Added French, Chinese, and Spanish Languages

= 2.1.7 =

Prepared plugin for localization

= 2.1.6 =

Prepared plugin for localization and improved code

= 2.1.5 =

Added manual update rankings button and improved notifications

= 2.1.4 =

Fixed another issue from previous bug

= 2.1.3 =

Fixed bug with reporter not getting proper rankings

= 2.1.2 =

Fixed xpath issues and set to capture country URL

= 2.1.1 =

Worked on break error when adding keywords 

= 2.1 = 

Provided validation for when adding keywords

= 2.0 =

Made major underlying code changes - few visually noticeable changes

= 1.1 =

Fixed a few bugs, added settings page

= 1.0.1 = 

jQuery issue fixed, data won't be deleted on upgrade

= 1.0 =

No upgrades needed yet