<?php
// vim: foldmethod=marker
/**
 *  AppSQL.php
 *
 *  @author     Masaki Fujimoto <fujimoto@php.net>
 *  @license    http://www.opensource.org/licenses/bsd-license.php The BSD License
 *  @package    Ethna
 *  @version    $Id$
 */

// {{{ Ethna_AppSQL
/**
 *  アプリケーションSQLベースクラス
 *
 *  @author     Masaki Fujimoto <fujimoto@php.net>
 *  @access     public
 *  @package    Ethna
 */
class Ethna_AppSQL
{
    /**#@+
     *  @access private
     */

    /** @protected    object  Ethna_Controller    controllerオブジェクト */
    protected $controller;

    /**#@-*/

    /**
     *  Ethna_AppSQLのコンストラクタ
     *
     *  @access public
     *  @param  object  Ethna_Controller    &$controller    controllerオブジェクト
     */
    public function __construct($controller)
    {
        $this->controller = $controller;
    }

    /**
     *  適切にエスケープされたSQL文を返す
     *
     *  @access public
     *  @param  string  $sqlfunc    SQL文種別名
     *  @param  array   $args       引数一覧
     *  @return string  エスケープされたSQL文
     */
    public function get($sqlid, $args)
    {
        Ethna_AppSQL::escapeSQL($args);

        return call_user_func_array(array($this, $sqlid), $args);
    }

    /**
     *  SQL引数をエスケープする
     *
     *  @access public
     *  @param  mixed   &$var   エスケープする値
     *  @static
     */
    public static function escapeSQL(&$var, $type = null)
    {
        if (!is_array($var)) {
            if (is_null($var)) {
                $var = 'NULL';
            } else {
                if ($type === 'sqlite') {
                    $var = "'" . sqlite_escape_string($var) . "'";
                } else {
                    $var = "'" . addslashes($var) . "'";
                }
            }
            return;
        }
        foreach (array_keys($var) as $key) {
            Ethna_AppSQL::escapeSQL($var[$key], $type);
        }
    }

    /**
     *  escapeSQLでエスケープされた文字列をunescapeする
     *
     *  @access public
     *  @param  mixed   &$var   エスケープを復帰する値
     *  @static
     */
    public static function unescapeSQL(&$var, $type = null)
    {
        if (!is_array($var)) {
            if ($var == 'NULL') {
                return;
            }
            $var = substr($var, 1, strlen($var)-2);
            $var = stripslashes($var);
            return;
        }
        foreach (array_keys($var) as $key) {
            Ethna_AppSQL::unescapeSQL($var[$key], $type);
        }
    }

    /**
     *  WHERE条件文を生成する
     *
     *  @access public
     *  @param  string  $field      検索対象のフィールド
     *  @param  mixed   $value      検索値
     *  @param  int     $condition  検索条件(Ethna_Const::_NE,...)
     *  @return string  検索条件文
     *  @static
     */
    public static function getCondition($field, $value, $condition = Ethna_Const::_EQ)
    {
        switch ($condition) {
        case Ethna_Const::_EQ:
            $op = "="; break;
        case Ethna_Const::_NE:
            $op = "!="; break;
        case Ethna_Const::_LIKE:
            $op = "LIKE"; break;
        case Ethna_Const::_GT:
            $op = ">"; break;
        case Ethna_Const::_LT:
            $op = "<"; break;
        case Ethna_Const::_GE:
            $op = ">="; break;
        case Ethna_Const::_LE:
            $op = "<="; break;
        }

        // default operand
        $operand = $value;

        if (is_array($value)) {
            if (count($value) > 0) {
                switch ($condition) {
                case Ethna_Const::_EQ:
                    $op = "IN"; break;
                case Ethna_Const::_NE:
                    $op = "NOT IN"; break;
                }
                $operand = sprintf("(%s)", implode(',', $value));
            } else {
                // always be false
                $op = "=";
                $operand = "NULL";
            }
        } else {
            if ($value == 'NULL') {
                switch ($condition) {
                case Ethna_Const::_EQ:
                    $op = "IS"; break;
                case Ethna_Const::_NE:
                    $op = "IS NOT"; break;
                }
            }
            if ($condition == Ethna_Const::_LIKE) {
                Ethna_AppSQL::unescapeSQL($value);
                $value = '%' . str_replace('%', '\\%', $value) . '%';
                Ethna_AppSQL::escapeSQL($value);
                $operand = $value;
            }
        }
        return "$field $op $operand";
    }
}
// }}}
