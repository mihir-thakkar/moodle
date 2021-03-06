<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Basic authentication steps definitions.
 *
 * @package    core_auth
 * @category   test
 * @copyright  2012 David Monllaó
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// NOTE: no MOODLE_INTERNAL test here, this file may be required by behat before including /config.php.

require_once(__DIR__ . '/../../../lib/behat/behat_base.php');

use Moodle\BehatExtension\Context\Step\Given as Given;
use Moodle\BehatExtension\Context\Step\When as When;

/**
 * Log in log out steps definitions.
 *
 * @package    core_auth
 * @category   test
 * @copyright  2012 David Monllaó
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_auth extends behat_base {

    /**
     * Logs in the user. There should exist a user with the same value as username and password.
     *
     * @Given /^I log in as "(?P<username_string>(?:[^"]|\\")*)"$/
     */
    public function i_log_in_as($username) {
        // Visit login page.
        $this->getSession()->visit($this->locate_path('login/index.php'));

        // Enter username and password.
        $behatforms = behat_context_helper::get('behat_forms');
        $behatforms->i_set_the_field_to('Username', $this->escape($username));
        $behatforms->i_set_the_field_to('Password', $this->escape($username));

        // Press log in button.
        $behatforms->press_button(get_string('login'));
    }

    /**
     * Logs out of the system.
     *
     * @Given /^I log out$/
     */
    public function i_log_out() {

        $steps = array(new When('I follow "' . get_string('logout') . '"'));

        // No need to check anything else if we run without JS.
        if (!$this->running_javascript()) {
            return $steps;
        }

        // There is no longer any need to worry about whether the navigation
        // bar needs to be expanded; user_menu now lives outside the
        // hamburger.

        // However, the user menu *always* needs to be expanded.
        $xpath = "//div[@class='usermenu']//a[contains(concat(' ', @class, ' '), ' toggle-display ')]";
        array_unshift($steps, new When('I click on "'.$xpath.'" "xpath_element"'));

        return $steps;
    }
}
