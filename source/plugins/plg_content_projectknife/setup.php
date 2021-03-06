<?php
/**
 * @package      pkg_projectknife
 * @subpackage   plg_content_projectknife
 *
 * @author       Tobias Kuhn (eaxs)
 * @copyright    Copyright (C) 2015-2017 Tobias Kuhn. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
 */

defined('_JEXEC') or die;


class plgContentProjectknifeInstallerScript
{
    public function postflight($type, JAdapterInstance $adapter)
    {
        if ($type == 'install')
        {
            $app = JFactory::getApplication();

            // Get the extension id
            $db    = JFactory::getDbo();
            $query = $db->getQuery(true);

            $query->select('extension_id')
                  ->from('#__extensions')
                  ->where('element = ' . $db->quote('projectknife'))
                  ->where($db->quoteName('type') . ' = ' . $db->quote('plugin'))
                  ->where($db->quoteName('folder') . ' = ' . $db->quote('content'));

            $db->setQuery($query);
            $id = (int) $db->loadResult();

            // Publish the plugin
            $query->clear()
                  ->update('#__extensions')
                  ->set('enabled = 1')
                  ->where('extension_id = ' . $id);

            $db->setQuery($query);
            $db->execute();
        }

        return true;
    }
}