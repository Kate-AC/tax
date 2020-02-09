<?php

require('./Tax.php');

class TestTax
{
    private $count = 0;

    public function run()
    {
        $this->eq(Tax::now(), 1.10);
        $this->eq(Tax::now('1989-03-31'), 1.00);
        $this->eq(Tax::now('1989-04-01'), 1.03);
        $this->eq(Tax::now('1997-04-01'), 1.05);
        $this->eq(Tax::now('2014-03-31'), 1.05);
        $this->eq(Tax::now('2014-04-01'), 1.08);
        $this->eq(Tax::now('2019-09-30'), 1.08);
        $this->eq(Tax::now('2019-10-01'), 1.10);
        $this->eq(Tax::now(new \DateTime('1989-04-01')), 1.03);
        $this->eq(Tax::now(new \DateTime('1997-04-01 00:00:00')), 1.05);

        $this->eq(Tax::now(new \DateTime('1997-04-01 00:00:00')), 1.05);

        Tax::setCurrentTime('2014-04-01');
        $this->eq(Tax::now(), 1.08);
        $this->eq(Tax::calc(100), 108);

        Tax::resetCurrentTime();
        $this->eq(Tax::now(), 1.1);
        $this->eq(Tax::calc(100), 110);

        Tax::setCurrentTime('2014-03-31');
        $this->eq(Tax::now(), 1.05);
        $this->eq(Tax::calc(100), 105);

        try {
            Tax::now('hoge');
            $this->error();
        } catch (\RuntimeException $e) {
            $this->ok();
        }

        try {
            Tax::setCurrentTime('hoge');
            $this->error();
        } catch (\RuntimeException $e) {
            $this->ok();
        }

        try {
            Tax::calc('hoge');
            $this->error();
        } catch (\RuntimeException $e) {
            $this->ok();
        }
    }

    private function eq($a, $b)
    {
        $this->count++;
        if ((string)$a === (string)$b) {
            echo sprintf("\033[0;32m [%s] OK %s %s\033[0m", $this->count, $a, $b) . PHP_EOL;
            return;
        }
        echo sprintf("\033[0;31m [%s] ERROR %s %s\033[0m", $this->count, $a, $b) . PHP_EOL;
    }

    private function ok()
    {
        $this->count++;
        echo sprintf("\033[0;32m [%s] OK\033[0m", $this->count) . PHP_EOL;
    }

    private function error()
    {
        $this->count++;
        echo sprintf("\033[0;31m [%s] ERROR\033[0m", $this->count) . PHP_EOL;
    }
}

(new TestTax())->run();

