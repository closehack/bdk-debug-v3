<?php

/**
 * PHPUnit tests for Debug class
 */
class MethodProfileTest extends DebugTestFramework
{

    public function testProfile()
    {

        $this->debug->profile();
        $this->a();
        $this->debug->profileEnd();

        $this->testMethod(
            null,
            array(),
            array(
                'custom' => function ($logEntry) {
                    $this->assertSame('profileEnd', $logEntry['method']);
                    $data = $logEntry['args'][0];
                    $a = $data['MethodProfileTest::a'];
                    $b = $data['MethodProfileTest::b'];
                    $c = $data['MethodProfileTest::c'];
                    // test a
                    $this->assertCount(3, $data);
                    $this->assertSame(1, $a['calls']);
                    $this->assertGreaterThanOrEqual(0.25 + 0.75*2, $a['totalTime']);
                    $this->assertLessThan(0.01, $a['ownTime']);
                    // test b
                    $this->assertSame(1, $b['calls']);
                    $this->assertGreaterThanOrEqual(0.25 + 0.75*2, $b['totalTime']);
                    $this->assertLessThan(0.25 + 0.01, $a['ownTime']);
                    // test c
                    $this->assertSame(2, $c['calls']);
                    $this->assertGreaterThanOrEqual(0.75*2, $c['totalTime']);
                    $this->assertLessThan(0.75*2 + 0.01, $a['ownTime']);
                    ksort($logEntry['meta']);
                    $this->assertSame(array(
                        'caption' => "Profile 'Profile 1' Results",
                        'columns' => array(),
                        'name' => 'Profile 1',
                        'sortable' => true,
                        'totalCols' => array('ownTime'),
                    ), $logEntry['meta']);
                },
                'chromeLogger' => '[[{"MethodProfileTest::a":{"calls":1,"totalTime":%f,"ownTime":%f},"MethodProfileTest::b":{"calls":1,"totalTime":%f,"ownTime":%f},"MethodProfileTest::c":{"calls":2,"totalTime":%f,"ownTime":%f}}],null,"table"]',
                'firephp' => 'X-Wf-1-1-1-2: %d|[{"Label":"Profile \'Profile 1\' Results","Type":"TABLE"},[["","calls","totalTime","ownTime"],["MethodProfileTest::a",1,%f,%f],["MethodProfileTest::b",1,%f,%f],["MethodProfileTest::c",2,%f,%f]]]|',
                'html' => '<li class="m_profileEnd">
                    <table class="sortable table-bordered">
                    <caption>Profile \'Profile 1\' Results</caption>
                    <thead>
                        <tr><th>&nbsp;</th><th>calls</th><th scope="col">totalTime</th><th scope="col">ownTime</th></tr>
                    </thead>
                    <tbody>
                        <tr><th class="t_callable t_key text-right" scope="row"><span class="classname">MethodProfileTest</span><span class="t_operator">::</span><span class="t_identifier">a</span></th><td class="t_int">1</td><td class="t_float">%f</td><td class="t_float">%f</td></tr>
                        <tr><th class="t_callable t_key text-right" scope="row"><span class="classname">MethodProfileTest</span><span class="t_operator">::</span><span class="t_identifier">b</span></th><td class="t_int">1</td><td class="t_float">%f</td><td class="t_float">%f</td></tr>
                        <tr><th class="t_callable t_key text-right" scope="row"><span class="classname">MethodProfileTest</span><span class="t_operator">::</span><span class="t_identifier">c</span></th><td class="t_int">2</td><td class="t_float">%f</td><td class="t_float">%f</td></tr>
                    </tbody>
                    <tfoot>
                        <tr><td>&nbsp;</td><td></td><td></td><td class="t_float">%f</td></tr>
                    </tfoot>
                    </table>
                    </li>',
                'script' => 'console.table({"MethodProfileTest::a":{"calls":1,"totalTime":%f,"ownTime":%f},"MethodProfileTest::b":{"calls":1,"totalTime":%f,"ownTime":%f},"MethodProfileTest::c":{"calls":2,"totalTime":%f,"ownTime":%f}});',
                'text' => 'Profile \'Profile 1\' Results = array(
                    [MethodProfileTest::a] => array(
                        [calls] => 1
                        [totalTime] => %f
                        [ownTime] => %f
                    )
                    [MethodProfileTest::b] => array(
                        [calls] => 1
                        [totalTime] => %f
                        [ownTime] => %f
                    )
                    [MethodProfileTest::c] => array(
                        [calls] => 2
                        [totalTime] => %f
                        [ownTime] => %f
                    )
                )',
            )
        );
    }


    private function a()
    {
        $this->b();
    }

    private function b()
    {
        $this->c();
        usleep(1000000 * .25);
        $this->c();
    }

    private function c()
    {
        usleep(1000000 * .75);
    }

    private function d()
    {
        usleep(1000000 * .33);
    }
}
