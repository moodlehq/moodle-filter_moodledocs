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
 * This filter allows you to create links to Moodle Docs easily
 *
 * Syntax for links is:
 *     [[docs:pagename|title|language]]
 * where:
 *     docs:      acronym of "MoodleDocs", optional, both if
 *                "docs" or nothing is present, the filter
 *                will process the link. Used by other
 *                filters to have a suitable namespace
 *                for them (like multimovie = "mm")
 *                Valid prefixes are 2-4 cc lowercase alpha chars
 *     pagename:  name of the page to link to
 *     title:     optional text to be linked, if no present
 *                pagename will be used
 *     language:  optional language code (en, es, it...) determining
 *                the target Docs (if no present, "en" will be used)
 *
 *
 * @package    filter
 * @subpackage moodledocs
 * @copyright  2001-3001 Eloy Lafuente (stronk7) http://contiento.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function moodledocs_filter($courseid, $text) {

    global $CFG;

    $u = empty($CFG->unicodedb) ? '' : 'u'; //Unicode modifier

    preg_match_all('/\[\[(([a-z]{2,4}):)?(.*?)(\|(.*?))?(\|(.*?))?\]\]/s'.$u, $text, $list_of_links);

/// No moodledocs links found. Return original text
    if (empty($list_of_links[0])) {
        return $text;
    }

    foreach ($list_of_links[0] as $key=>$item) {
    /// If prefix has been detected and it's not "docs", skip link
        if (!empty($list_of_links[2][$key]) && $list_of_links[2][$key] != 'docs') {
            continue;
        }
        $replace = '';
    /// Extract info from the multimovie link
        $link = new stdClass;
        $link->pagename = $list_of_links[3][$key];
        $link->title = $list_of_links[5][$key];
        $link->language = $list_of_links[7][$key];
        $link->target = '';
    /// Apply defaults
        if (empty($link->title)) {
            $link->title = $link->pagename;
        }
        if (empty($link->language)) {
            $link->language = 'en';
        }
    /// Process pagename, replacing spaces by hypens and
    /// urlencoding everything but colons, slashes and hashes
        $link->pagename = str_replace(' ', '_', $link->pagename);
        $link->pagename = urlencode($link->pagename);
        $link->pagename = str_replace(array('%3A', '%2F', '%23'), array(':', '/', '#'), $link->pagename);
    /// Calculate destination url
        $link->url = $CFG->docroot . '/' . $link->language . '/' . $link->pagename;
    /// Calculate the target (TODO: not XHTML, we should use some class + js to do this instead)
        if (!empty($CFG->doctonewwindow)) {
            $link->target = '" target="_blank';
        }
    /// Calculate the replacement
        $replace = '<a href="' . $link->url . $link->target . '" title="Moodle Docs: ' . $link->title . '">' . $link->title . '</a>';
    /// If replace found, do it
        if ($replace) {
            $text = str_replace($list_of_links[0][$key], $replace, $text);
        }
    }

/// Finally, return the text
    return $text;
}
