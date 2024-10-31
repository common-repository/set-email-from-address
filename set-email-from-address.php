<?php
/*
Plugin Name: Set Email &quot;From&quot; Address
Plugin URI: http://www.justinsamuel.com/wordpress-plugins/set-email-from-address/
Description: Allows setting the "From" address used in emails sent by WordPress. After activating this plugin, go to Options &gt; Email &quot;From&quot; Address.
Version: 1.0
Author: Justin Samuel
Author URI: http://www.justinsamuel.com/


Copyright 2008  Justin Samuel  (justin at justinsamuel dot com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

add_filter('wp_mail_from', array('setEmailFromAddress', 'filter_wp_mail_from'));
add_filter('wp_mail', array('setEmailFromAddress', 'filter_wp_mail'));

add_action('admin_menu', array('setEmailFromAddress', 'addToOptionsMenu'));

add_option('email_from_address', '', 'Used as the address in email "From" headers.');

class setEmailFromAddress {
    
    function loadTextDomain() {
        static $loaded;
        if (!isset($loaded)) {
            load_plugin_textdomain(__CLASS__, 'wp-content/plugins/set-email-from-address');
            $loaded = true;
        }
    }
    
    function getDefaultAddress() {
        static $address;
        if (!isset($address)) {
            $address = 'wordpress@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
        }
        return $address;
    }
    
    function getCustomAddress() {
        static $address;
        if (!isset($address)) {
            $address = get_option('email_from_address');
        }
        return $address;
    }
    
    function filter_wp_mail_from($originalFrom) {
        return setEmailFromAddress::getCustomAddress() ? setEmailFromAddress::getCustomAddress() : $originalFrom;
    }
    
    function filter_wp_mail($mail) {
        if (setEmailFromAddress::getCustomAddress()) {
	        $pattern = '/^From:(.*?)' . str_replace('.', '\.', setEmailFromAddress::getDefaultAddress()) . '(.*?)$/m';
	        $replacement = 'From:${1}' . setEmailFromAddress::getCustomAddress() . '${2}';
	        $mail['headers'] = preg_replace($pattern, $replacement, $mail['headers']);
        }
        return $mail;
    }

    function addToOptionsMenu() {
        setEmailFromAddress::loadTextDomain();
        add_options_page(
	        __('Set&nbsp;Email&nbsp;&quot;From&quot;&nbsp;Address'),
	        __('Email&nbsp;&quot;From&quot;&nbsp;Address'),
	        8,
	        str_replace("\\", "/", __FILE__),
	        array('setEmailFromAddress', 'displayOptionsPage')
        );
    }

    function isValidAddress($address) {
        $validRegex = '/^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/iD';
        return preg_match($validRegex, $address) || strlen($address) == 0 ? true : false;
    }

    function handleSubmission() {
        if (!empty($_POST)) {
            if (!session_id()) {
                session_start();
            }
            $fromAddress = trim($_POST['email_from_address']);
            if (empty($_SESSION['set-email-from-address-nonce'])) {
                $message = __('Form submission with nonce never having been set (CSRF attack prevention). Possible session problem.');
                $class = 'error';
            } else if ($_POST['nonce'] != $_SESSION['set-email-from-address-nonce']) {
                $message = __('Form submission with incorrect nonce (CSRF attack prevention).  Possible session problem.');
                $class = 'error';
            } else if (!setEmailFromAddress::isValidAddress($fromAddress)) {
                $message = __('Invalid "From Address". No settings updated.');
                $class = 'error';
            } else {
                update_option('email_from_address', $fromAddress);
                $message = __('Email From setting updated.');
                $class = 'updated';
            }
            ?>
            <div class="<?php echo $class ?>">
                <p><strong><?php echo $message ?></strong></p>
            </div>
            <?php
        }
    }

    function displayOptionsPage() {
        setEmailFromAddress::loadTextDomain();
        setEmailFromAddress::handleSubmission();
        if (!session_id()) {
            session_start();
        }
        if (empty($_SESSION['set-email-from-address-nonce'])) {
            $_SESSION['set-email-from-address-nonce'] = md5(rand() . microtime());
        }
        ?>
        <div class="wrap">
        <form method="post" action="">
        
        <h2><?php _e('Set Email &quot;From&quot; Address') ?></h2>
        <p class="submit"><input type="submit" value="<?php _e('Update Settings &raquo;') ?>" /></p>
        
        <p><?php _e('Set the &quot;From&quot; address used in emails sent by WordPress.') ?></p>
        
        <p><?php _e("The resulting &quot;From&quot; in emails sent by WordPress will still display a name that depends on the context in which the email was sent.") ?>
		<?php _e("Sometimes this is the blog's name, sometimes it's the comment author's name, and sometimes it's nothing.") ?>
		<?php _e("No matter what the name is, only the email address will be affected.") ?></p>
        
        <fieldset class="options"><legend><?php _e('Settings') ?></legend>
        <table class="editform optiontable">
            <tbody>
                <tr valign="top">
                    <th scope="row"><?php _e('From Address:') ?></th>
                    <td><input name="email_from_address" id="email_from_address"
                        class="code" value="<?php echo htmlspecialchars(get_option('email_from_address'), ENT_QUOTES) ?>"
                        size="60" type="text" /><br />
                    <?php _e('Default is') ?> <code><?php echo htmlspecialchars(setEmailFromAddress::getDefaultAddress(), ENT_QUOTES) ?></code></td>
                </tr>
            </tbody>
        </table>
        </fieldset>
        
        <p class="submit"><input type="submit" value="<?php _e('Update Settings &raquo;') ?>" /></p>
        <input type="hidden" name="nonce" value="<?php echo $_SESSION['set-email-from-address-nonce'] ?>" />
        
        </form>
        </div>
        <?php
    }

}
