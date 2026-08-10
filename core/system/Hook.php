<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2014-2015 by Future Group.         # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
class Hook {
    // Mảng chứa tất cả các listener đã đăng ký
    protected static $hooks = [];

    /**
     * Đăng ký một callback cho một sự kiện
     *
     * @param string $eventName
     * @param callable $callback
     */
    public static function listen($hook_name, $callback){
        if (!isset(self::$hooks[$hook_name])) {
            self::$hooks[$hook_name] = [];
        }
        self::$hooks[$hook_name][] = $callback;
    }
    /**
     * Gọi các callback đã đăng ký cho sự kiện
     *
     * @param string $hook_name
     * @param mixed $payload
     */
    public static function dispatch($hook_name, $payload = null) {
        if (!empty(self::$hooks[$hook_name])) {
            foreach (self::$hooks[$hook_name] as $callback) {
                call_user_func($callback, $payload);
            }
        }
    }
    /**
     * Gọi các callback theo dạng filter – mỗi callback nhận giá trị và trả lại giá trị
     *
     * @param string $hook_name
     * @param mixed $value
     * @return mixed
     */
    public static function filter($hook_name, $value) {
        if (!empty(self::$hooks[$hook_name])) {
            foreach (self::$hooks[$hook_name] as $callback) {
                $value = call_user_func($callback, $value);
            }
        }
        return $value;
    }
}
?>