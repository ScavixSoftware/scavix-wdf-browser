<?php
/**
 * Scavix Web Development Framework
 *
 * Copyright (c) since 2025 Scavix Software GmbH & Co. KG
 *
 * This library is free software; you can redistribute it
 * and/or modify it under the terms of the GNU Lesser General
 * Public License as published by the Free Software Foundation;
 * either version 3 of the License, or (at your option) any
 * later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>
 *
 * @copyright since 2025 Scavix Software GmbH & Co. KG
 * @license http://www.opensource.org/licenses/lgpl-license.php LGPL
 */

use ScavixWDF\Wdf;

Wdf::RegisterPackage('browser');

/**
 * Checks the remote browser.
 *
 * If $version is given will check the major version too, if $gt_match is true, greater versions will match too.
 * Samples:
 * <code php>
 * browser_is('msie',7,false); // true for InternetExplorer 7
 * browser_is('msie',7,true); // true for InternetExplorer 7, 8, 9, ...
 * browser_is('msie'); // true for every InternetExplorer
 * </code>
 * @param string $id Browser id (msie, firefox,...)
 * @param int $version Major version to check
 * @param bool $gt_match If true greater versions match too
 * @return bool true or false
 */
function browser_is($id, $version = 0, $gt_match = true)
{
    $bd = browserDetails();
    if ($bd['browser_id'] != strtoupper($id))
        return false;
    return !$version || (
        ($gt_match && $bd['major_version'] >= $version) ||
        (!$gt_match && $bd['major_version'] == $version));
}

/**
 * @shortcut <browser_is>('MSIE')
 */
function isIE()
{
    return browser_is('MSIE');
}

/**
 * @shortcut <browser_is>('MSIE',6,false)
 */
function isIE6()
{
    return browser_is('MSIE', 6, false);
}

/**
 * @shortcut <browser_is>('MSIE',7,false)
 */
function isIE7()
{
    return browser_is('MSIE', 7, false);
}

/**
 * @shortcut <browser_is>('MSIE',8,false)
 */
function isIE8()
{
    return browser_is('MSIE', 8, false);
}

/**
 * @shortcut <browser_is>('MSIE',9,false)
 */
function isIE9()
{
    return browser_is('MSIE', 9, false);
}

/**
 * @shortcut <browser_is>('MSIE',10,false)
 */
function isIE10()
{
    return browser_is('MSIE', 10, false);
}

/**
 * @shortcut <browser_is>('MSIE',7,true)
 */
function isMinIE7()
{
    return browser_is('MSIE', 7, true);
}

/**
 * @shortcut <browser_is>('MSIE',8,true)
 */
function isMinIE8()
{
    return browser_is('MSIE', 8, true);
}

/**
 * @shortcut <browser_is>('FIREFOX')
 */
function isFirefox()
{
    return browser_is('FIREFOX');
}

/**
 * @shortcut <browser_is>('FIREFOX',3,true)
 */
function isMinFirefox3()
{
    return browser_is('FIREFOX', 3, true);
}

/**
 * @internal Fetches all browser information from `$_SERVER['HTTP_USER_AGENT']`
 */
function browserDetails($user_agent = null, $key = null)
{
    $caps = Wdf::GetBuffer(__FUNCTION__)->get('caps', function ()
    {
        $caps = new \phpbrowscap\Browscap(system_app_temp_dir('', false));
        $caps->remoteIniUrl = "https://browscap.org/stream?q=Lite_PHP_BrowsCapINI";
        return $caps;
    });
    $bd = array_change_key_case($caps->getBrowser($user_agent, true), CASE_LOWER);

    if (($bd['browser'] ?? '') == 'Default Browser')
        $bd = [];

    $bd['name'] = implode(' ', array_filter(
        [
            ($bd['browser'] ?? ''),
            str_replace('0.0', '', $bd['version'] ?? ''),
            ($bd['platform'] ?? '')
        ]));

    if (!isset($bd['browser_name']))
        $bd['browser_name'] = "Unknown";
    if (!isset($bd['platform']))
        $bd['platform'] = "Unknown";
    if (!isset($bd['browser']))
        $bd['browser'] = "Unknown";
    if (!isset($bd['version']))
        $bd['version'] = '0.0';
    $bd['agent'] = $bd['browser_name'];
    $bd['browser_id'] = strtoupper($bd['browser']);
    $bd['major_version'] = intval($bd['version']);

    return $key ? ($bd[$key] ?? '') : $bd;
}