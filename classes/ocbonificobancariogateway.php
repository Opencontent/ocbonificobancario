<?php

class OCBonificoBancarioGateway extends eZPaymentGateway
{
    const WORKFLOW_TYPE_STRING = 'ocbonificobancario';
    
    function execute( $process, $event )
    {
        $processParameters = $process->attribute( 'parameter_list' );
        $order = eZOrder::fetch( $processParameters['order_id'] );
        $order->setStatus( 1001 );
        $order->store();
        return eZWorkflowType::STATUS_ACCEPTED;
    }
    
}

eZPaymentGatewayType::registerGateway( OCBonificoBancarioGateway::WORKFLOW_TYPE_STRING,
                                       "OCBonificoBancarioGateway",
                                       "Bonifico bancario" );

?>