<?php
/**
 * @author: Xiao Nian
 * @contact: xiaonian030@163.com
 * @datetime: 2020-12-01 14:00
 */

namespace HP\Command;
class Utility
{

    /**
     * logo
     * @return string
     */
    public static function serverLogo(): string {
        return <<<LOGO
    _____                              _
   / ____|                            | |
  | (___   __      __   ___     ___   | |   ___
   \___ \  \ \ /\ / /  / _ \   / _ \  | |  / _ \
   ____) |  \ V  V /  | (_) | | (_) | | | |  __/
  |_____/    \_/\_/    \___/   \___/  |_|  \___|
LOGO;
    }

    /**
     * 显示项目
     * @param $name
     * @param $value
     * @return string
     */
    static function displayItem($name, $value): string {
        if ($value === true) {
            $value = 'true';
        } else if ($value === false) {
            $value = 'false';
        } else if ($value === null) {
            $value = 'null';
        } else if (is_array($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        }
        return "\e[32m" . str_pad($name, 30, ' ', STR_PAD_RIGHT) . "\e[34m" . $value . "\e[0m";
    }

    /**
     * 清除缓存
     */
    public static function opCacheClear() {
        if (function_exists('apc_clear_cache')) {
            apc_clear_cache();
        }
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }
    }
}
