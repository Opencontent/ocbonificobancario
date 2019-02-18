<?php

class OCBonificoBancarioGateway extends eZPaymentGateway
{
    const WORKFLOW_TYPE_STRING = 'ocbonificobancario';
    
    function execute( $process, $event )
    {
        $processParameters = $process->attribute( 'parameter_list' );
        $order = eZOrder::fetch( $processParameters['order_id'] );

        $orderStatus = (int)eZINI::instance('bonificobancario.ini')->variable('Settings', 'OrderStatus');
        if ($orderStatus == 0){
            $orderStatus = 1001;
        }
        $order->setStatus( $orderStatus );

        $order->store();
        return eZWorkflowType::STATUS_ACCEPTED;
    }
    
}

eZPaymentGatewayType::registerGateway( OCBonificoBancarioGateway::WORKFLOW_TYPE_STRING,
                                       "OCBonificoBancarioGateway",
                                       "Bonifico bancario" );

?>