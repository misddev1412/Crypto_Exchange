<?php

if ( ! function_exists('isActive'))
{
    /**
     * Set the active class to the current opened menu.
     *
     * @param  string|array $route
     * @param  string       $className
     * @return string
     */
    function isActive($route, $className = 'active')
    {
        if (is_array($route)) {
            return in_array(Route::currentRouteName(), $route) ? $className : '';
        }
        if (Route::currentRouteName() == $route) {
            return $className;
        }
        if (strpos(URL::current(), $route)) return $className;
    }
}

if ( ! function_exists('testDatabaseConnection'))
{
    /**
     * Check database connection
     *
     * @param string | hostname
     * @param string | username
     * @param string | password
     * @param string | databaseName
     * @return bool
     */
    function testDatabaseConnection($hostname, $username, $password, $name){
        try {
            $connection = mysqli_connect($hostname, $username, $password, $name);
            if (mysqli_connect_errno()){
                return false;
            } else {
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}