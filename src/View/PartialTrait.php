<?php

namespace Partial\View;

trait PartialTrait {
    public $partialCache = 'partial';

    public function partial($name, array $data = array(), array $options = array()) {
        $file = $plugin = null;

        if (!isset($options['callbacks'])) {
            $options['callbacks'] = false;
        }

        if (isset($options['cache'])) {
            $contents = $this->_elementCache($name, $data, $options);
            if ($contents !== false) {
                return $contents;
            }
        }

        $file = $this->_getPartialFileName($name);
        if ($file) {
            return $this->_renderElement($file, $data, $options);
        }

        if (empty($options['ignoreMissing'])) {
            list ($plugin, $name) = pluginSplit($name, true);
            $name = str_replace('/', DS, $name);
            $file = $plugin . $this->viewPath . DS . '_' . $name . $this->_ext;
            throw new \Cake\View\Exception\MissingElementException($file);
        }
    }

    /**
     * ファイル名を取得する
     *
     * @access private
     * @author sakuragawa
     */
    protected function _getPartialFileName($name) {
        list($plugin, $name) = $this->pluginSplit($name);

        $paths = $this->_paths($plugin);
        foreach ($paths as $path) {
            if (file_exists($path . $this->viewPath . DS . '_' . $name . $this->_ext)) {
                return $path . $this->viewPath . DS . '_' . $name . $this->_ext;
            }
        }
        return false;
    }
}
