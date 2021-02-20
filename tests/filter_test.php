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
 * Moodle Docs filter phpunit tests
 *
 * @package    filter_moodledocs
 * @category   test
 * @copyright  2013 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/filter/moodledocs/filter.php'); // Include the code to test

/**
 * Moodle Docs filter testcase
 */
class filter_moodledocs_testcase extends basic_testcase {

    /**
     * Test some simple replaces, some case-sensitive, others no...
     */
    public function test_filter_simple() {

        $texts = array(
            // Some non-matching cases.
            '[[]]' => '!^\[\[\]\]$!',
            '[[Not matching' => '!^\[\[Not matching$!',
            'Not matching]]' => '!^Not matching\]\]$!',
            '[Not matching]' => '!^\[Not matching\]$!',
            '[[docs:]]' => '!^\[\[docs:\]\]$!',
            '[[mm:Not matching]]' => '!^\[\[mm:Not matching\]\]$!',

            // Some matching cases.
            '[[Example]]'=> '!^<a href=".*/en/Example" title="Moodle Docs - Example">Example</a>$!',
            '[[docs:Example]]'=> '!^<a href=".*/en/Example" title="Moodle Docs - Example">Example</a>$!',
            '[[Two words]]' => '!^<a href=".*/en/Two_words" title="Moodle Docs - Two words">Two words</a>$!',
            '[[docs:Two words]]' => '!^<a href=".*/en/Two_words" title="Moodle Docs - Two words">Two words</a>$!',
            '[[docs:Page :/#]]' => '!^<a href=".*/en/Page_:/#" title="Moodle Docs - Page :/#">Page :/#</a>$!',
            '[[docs:Page <>]]' => '!^<a href=".*/en/Page_%3C%3E" title="Moodle Docs - Page <>">Page <></a>$!',
            '[[docs:Page &lt;&gt;]]' => '!^<a href=".*/en/Page_%26lt%3B%26gt%3B" title="Moodle Docs - Page &lt;&gt;">Page &lt;&gt;</a>$!',
            "[[docs:Page '\"]]" => "!^<a href=\".*/en/Page_%27%22\" title=\"Moodle Docs - Page '&quot;\">Page '\"</a>$!",
            '[[docs:Page áé]]' => '!^<a href=".*/en/Page_%C3%A1%C3%A9" title="Moodle Docs - Page áé">Page áé</a>$!',
            '[[Page p|title t|it]]' => '!^<a href=".*/it/Page_p" title="Moodle Docs - Page p">title t</a>$!',
            '[[Page p|title t|24/it]]' => '!^<a href=".*/24/it/Page_p" title="Moodle Docs - Page p">title t</a>$!',
            'A[[P1]]&[[P2]]Z' => '!^A<a href=".*/en/P1" title=".*P1">P1</a>&<a href=".*/en/P2" title=".*P2">P2</a>Z$!',

            // Some known limitations (nested ocurrences). TODO: Fix them someday.
            '[[Outer[[Inner]]]]' => '!^<a href=".*Outer%5B%5BInner" title="Moodle Docs - Outer\[\[Inner">Outer\[\[Inner</a>\]\]$!',
        );

        $filter = new testable_filter_moodledocs();

        foreach ($texts as $text => $expected) {
            $msg = "Testing text '$text':";
            $result = $filter->filter($text);

            // TODO: Remove once out lower PHPUnit version is 9.5.
            if (!method_exists($this, 'assertMatchesRegularExpression')) {
                $this->assertRegExp($expected, $result, $msg); // Pre PHPUnit 9.5.
            } else {
                $this->assertMatchesRegularExpression($expected, $result, $msg);
            }
        }
    }
}


/**
 * Subclass of filter_moodledocs, for easier testing.
 */
class testable_filter_moodledocs extends filter_moodledocs {

    public function __construct() {
        $this->context = context_system::instance();
    }
}
