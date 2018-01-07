=== WP Term Order ===
Contributors: johnjamesjacoby, stuttter
Tags: taxonomy, term, order
Requires at least: 4.3
Tested up to: 4.4
Stable tag: 0.1.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9Q4F4EL5YJ62J

== Description ==

Sort taxonomy terms, your way.

WP Term Order allows users to order any visible category, tag, or taxonomy term numerically, providing a customized order for their taxonomies.

= Also checkout =

* [WP Chosen](https://wordpress.org/plugins/wp-chosen/ "Make long, unwieldy select boxes much more user-friendly.")
* [WP Event Calendar](https://wordpress.org/plugins/wp-event-calendar/ "The best way to manage events in WordPress.")
* [WP Term Meta](https://wordpress.org/plugins/wp-term-meta/ "Metadata, for taxonomy terms.")
* [WP Term Authors](https://wordpress.org/plugins/wp-term-authors/ "Authors for categories, tags, and other taxonomy terms.")
* [WP Term Colors](https://wordpress.org/plugins/wp-term-colors/ "Pretty colors for categories, tags, and other taxonomy terms.")
* [WP Term Icons](https://wordpress.org/plugins/wp-term-icons/ "Pretty icons for categories, tags, and other taxonomy terms.")
* [WP Term Visibility](https://wordpress.org/plugins/wp-term-visibility/ "Visibilities for categories, tags, and other taxonomy terms.")
* [WP User Groups](https://wordpress.org/plugins/wp-user-groups/ "Group users together with taxonomies & terms.")
* [WP User Activity](https://wordpress.org/plugins/wp-user-activity/ "The best way to log activity in WordPress.")
* [WP User Avatars](https://wordpress.org/plugins/wp-user-avatars/ "Allow users to upload avatars or choose them from your media library.")
* [WP User Profiles](https://wordpress.org/plugins/wp-user-profiles/ "A sophisticated way to edit users in WordPress.")

== Screenshots ==

1. Drag and drop your categories, tags, and custom taxonomy terms

== Installation ==

Download and install using the built in WordPress plugin installer.

Activate in the "Plugins" area of your admin by clicking the "Activate" link.

No further setup or configuration is necessary.

== Frequently Asked Questions ==

= Does this create new database tables? =

No. There are no new database tables with this plugin.

= Does this modify existing database tables? =

Yes. The `wp_term_taxonomy` table is altered, and an `order` column is added.

= Where can I get support? =

The WordPress support forums: https://wordpress.org/support/plugin/wp-term-order/

= Where can I find documentation? =

http://github.com/stuttter/wp-term-order/

== Changelog ==

= 0.1.4 =
* Fix order saving in non-fancy mode

= 0.1.3 =
* Add filter to target specific taxonomies

= 0.1.2 =
* Normalize textdomains

= 0.1.1 =
* Prevent move on "No items" table row

= 0.1.0 =
* Initial release
