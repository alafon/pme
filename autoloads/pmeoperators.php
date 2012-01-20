<?php

/*
 *
 * @TODO copyright & co
 *
 */

namespace PME;

class Operators {

    public function __construct() {

    }

    public function operatorList() {

        return array( 'pme' );
    }

    public function namedParameterPerOperator() {

        return true;
    }

    public function namedParameterList() {

        return array( 'pme' => array(
                           'module' => array( 'type' => 'string',
                                                'required' => true,
                                                'default' => '' ),
                           'function' => array( 'type' => 'string',
                                                'required' => true,
                                                'default' => '' ),
                           'params' =>   array( 'type' => 'array',
                                                'required' => false,
                                                'default' => array() ) ),
                );
    }

    public function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters ) {

        $module = $namedParameters['module'];
        $function = $namedParameters['function'];
        $params = $namedParameters['params'];

        // @todo : add some tests
        $operatorValue = call_user_func( array( '\\PME\\' . ucfirst( $module ). '\\Operators', "_$function" ), $params );
    }


}

?>