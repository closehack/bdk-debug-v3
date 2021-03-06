<?php

/**
 * PHPUnit tests for Debug class
 */
class TypeResourceTest extends DebugTestFramework
{

    public function providerTestMethod()
    {
        $fh = fopen(__FILE__, 'r');
        // val, html, text script
        $value = print_r($fh, true).': stream';
        return array(
            array(
                'log',
                array( $fh ),
                array(
                    'custom' => function () use ($fh) {
                        fclose($fh);
                    },
                    'entry' => array(
                        'log',
                        array(
                            new \bdk\Debug\Abstraction\Abstraction(array(
                                'type' => 'resource',
                                'value' => $value,
                            )),
                        ),
                        array(),
                    ),
                    'chromeLogger' => '[["Resource id #'.(int) $fh.': stream"],null,""]',
                    'html' => '<li class="m_log"><span class="t_resource">Resource id #'.(int) $fh.': stream</span></li>',
                    'text' => 'Resource id #'.(int) $fh.': stream',
                    'script' => 'console.log("Resource id #'.(int) $fh.': stream");',
                )
            ),
        );
    }
}
