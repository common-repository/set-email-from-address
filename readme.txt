=== Set Email "From" Address ===
Contributors: justinsamuel
Donate link: http://www.justinsamuel.com/
Tags: email, admin
Requires at least: 2.0
Tested up to: 2.3.2
Stable tag: 1.0

This plugin allows you to set the "From" address used in emails sent by WordPress, thus overriding the default of wordpress@yourdomain.com.

== Description ==

This plugin allows you to set the "From" address used in emails sent by WordPress, 
thus overriding the default of wordpress@yourdomain.com.

The resulting "From" in emails sent by WordPress will still display a name that 
depends on the context in which the email was sent (sometimes the blog name, 
sometimes the comment author’s name, sometimes nothing). Only the address itself 
will be affected.

== Installation ==

1. Unzip the plugin's zip file. A single directory called `set-email-from-address` will be extracted.
2. Upload the extracted `set-email-from-address` directory to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. go to Options > Email "From" Address to change the the "From" address of emails WordPress sends.

== Frequently Asked Questions ==

= Can I change the name portion of the "From" in sent emails? =

The "From" line of an email header can look look this:

`From: "Some Name" <something@yourdomain.com>`

Where `Some Name` is what an email client will show you is the name of the person who sent the email.

Using this plugin, the resulting "From" in emails sent by WordPress will still display a name that 
depends on the context in which the email was sent. Sometimes that is the blog's name, 
sometimes it's the comment author's name, and sometimes it's nothing at all. Using this plugin, the
name that is displayed in the email's "From" line is still decided by WordPress.
Only the address itself (`something@yourdomain.com`) will be affected by using this plugin.

If you want to change how your blog's name shows up as the name in the "From" part of the email
for some emails WordPress sends, for now you have to change your blog's name in WordPress. If being 
able to change this in the "From" line of emails without changing the name of your blog across the board
important to you, let me know and I'll consider that for a future release.

== Screenshots ==

1. The only change in the admin the Set Email "From" Address plugin makes is the addition of this Options subpage. 

== Change log ==

**1.0** (*2008-01-15*)

* Initial release
