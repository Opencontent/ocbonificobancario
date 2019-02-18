<?php

class BonificoBancarioOperator
{
    private $Operators = array();

    function __construct()
    {
        $this->Operators = array(
            'bonificobancario_iban',
            'bonificobancario_intestatario_iban',
            'bonificobancario_note_node_id',
        );
    }

    function operatorList()
    {
        return $this->Operators;
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array(
            'bonificobancario_iban' => array(
                'order' => array(
                    'type' => 'object',
                    'required' => true)),

            'bonificobancario_intestatario_iban' => array(
                'order' => array(
                    'type' => 'object',
                    'required' => true)),

            'bonificobancario_note_node_id' => array(
                'order' => array(
                    'type' => 'object',
                    'required' => true)),
        );
    }

    function modify(&$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters)
    {
        switch ($operatorName) {

            case 'bonificobancario_iban':
            case 'bonificobancario_intestatario_iban':
            case 'bonificobancario_note_node_id':
                {
                    if ($operatorName == 'bonificobancario_iban'){
                        $parameterKey = 'IBAN';
                    }elseif ($operatorName == 'bonificobancario_intestatario_iban'){
                        $parameterKey = 'IntestatarioIBAN';
                    }else{
                        $parameterKey = 'NoteNodeID';
                    }
                    $operatorValue = eZINI::instance('bonificobancario.ini')->variable('Settings', $parameterKey);
                    if (class_exists('OCPaymentRecipient') && in_array('ocpaymentrecipient', eZExtension::activeExtensions())){
                        /** @var eZOrder $order */
                        $order = $namedParameters['order'];
                        foreach ($order->productItems() as $product){
                            /** @var eZContentObject $productObject */
                            $productObject = $product['item_object']->attribute('contentobject');
                            $productPaymentRecipient = eZPaymentRecipientType::getPaymentRecipientFromContentObject($productObject);
                            if ($productPaymentRecipient instanceof OCPaymentRecipient){
                                if ($productPaymentRecipient->hasParameter($parameterKey)){
                                    $operatorValue = $productPaymentRecipient->getParameter($parameterKey);
                                    break;
                                }
                            }
                        }
                    }
                }
                break;
        }
    }

}
