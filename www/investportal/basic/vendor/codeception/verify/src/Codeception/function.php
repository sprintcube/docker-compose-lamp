<?php
if (!function_exists('verify')) {
    /**
     * @param $description
     * @param null $actual
     * @return \Codeception\Verify
     */
    function verify($description) {
        $descriptionGiven = (func_num_args() == 2);
        $class = \Codeception\Verify::$override
            ? \Codeception\Verify::$override
            : \Codeception\Verify::class;

        if ($descriptionGiven) {
            $args = func_get_args();
            return new $class($args[0], $args[1]);
        }
        return new $class($description);
    }

    function verify_that($truth) {
        verify($truth)->notEmpty();
    }

    function verify_not($fallacy) {
        verify($fallacy)->isEmpty();
    }
}

if (!function_exists('expect')) {

    /**
     * @param $description
     * @param null $actual
     * @return \Codeception\Verify
     */
    function expect() {
        return call_user_func_array('verify', func_get_args());
     }

    function expect_that($truth) {
        expect($truth)->notEmpty();
    }

    function expect_not($fallacy) {
        expect($fallacy)->isEmpty();
    }

}

if (!function_exists('verify_file')) {

    /**
     * @param $description
     * @param null $actual
     * @return \Codeception\Verify
     */
    function verify_file() {
        $verify = call_user_func_array('verify', func_get_args());
        $verify->setIsFileExpectation(true);
        return $verify;
    }
}

if (!function_exists('expect_file')) {
    /**
     * @param $description
     * @param null $actual
     * @return \Codeception\Verify
     */
    function expect_file() {
        return call_user_func_array('verify_file', func_get_args());
    }
}
