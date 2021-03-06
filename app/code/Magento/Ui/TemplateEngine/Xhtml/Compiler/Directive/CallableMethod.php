<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Ui\TemplateEngine\Xhtml\Compiler\Directive;

use Magento\Framework\View\Element\UiComponentInterface;

/**
 * Class CallableMethod
 */
class CallableMethod implements DirectiveInterface
{
    /**
     * Execute directive
     *
     * @param array $directive
     * @param UiComponentInterface $component
     * @return string
     */
    public function execute($directive, UiComponentInterface $component)
    {
        $object = $component;
        $result = '';
        foreach (explode('.', $directive[1]) as $method) {
            $methodName = substr($method, 0, strpos($method, '('));
            if (is_callable([$object, $methodName])) {
                $result = $object->$methodName();
                if (is_scalar($result)) {
                    break;
                }
                $object = $result;
                continue;
            }
            break;
        }

        return $result;
    }

    /**
     * Get regexp search pattern
     *
     * @return string
     */
    public function getPattern()
    {
        return '#\{\{((?:[\w_0-9]+\(\)){1}(?:(?:\.[\w_0-9]+\(\))+)?)\}\}#';
    }
}
