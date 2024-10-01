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
 * @todo MDL-82708 delete this file as part of Moodle 6.0 development.
 * @deprecated This file is no longer required in Moodle 4.5+.
 * @package    filter_moodledocs
 * @copyright  2001-3001 Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

debugging('This file is no longer required in Moodle 4.5+. Please do not include/require it.', DEBUG_DEVELOPER);

