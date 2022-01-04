<?php
/**
 * Created by PhpStorm.
 * User: Antonio
 * Date: 15/10/2019
 * Time: 01:07 PM
 */

namespace  JoalmLibrary\PhpOpenpay;

require_once("../vendor/autoload.php");
use Openpay;
use OpenpayApiError;
use OpenpayApiAuthError;
use OpenpayApiRequestError;
use OpenpayApiConnectionError;
use OpenpayApiTransactionError;
use \Exception;

class PhpOpenpay
{   
    private $openpay = NULL;
    private $device_session_id = NULL;
    private $mechant_id = NULL;
    private $private_api_key = NULL;
    private $api_url = false;

    public function __construct(){
        $this->device_session_id = 'kR1MiQhz2otdIuUlQkbEyitIqVMiI16f';
        $this->mechant_id = env('OPEN_PAY_MERCHANT_ID');
        $this->private_api_key = env('OPEN_PAY_PRIVATE_API_KEY');
        $this->api_url = env('OPEN_PAY_SANDBOX_URL');
        Openpay::setId($this->mechant_id);
        Openpay::setApiKey($this->private_api_key);
        if(env('APP_ENV') == 'production'){
            Openpay::setProductionMode(true);
            $this->api_url = env('OPEN_PAY_PRODUCTION_URL');
        }
        $this->openpay = Openpay::getInstance($this->mechant_id, $this->private_api_key);
    }

    public function CreateCustomer($customer){
        try{
            $customer_data = array(
                'external_id' => $customer->id_user,
                'name' => $customer->nombre,
                'last_name' => $customer->apellidos,
                'email' => $customer->correo_electronico,
                'requires_account' => false,
                'phone_number' => $customer->telefono
              );
    
            $customer = $this->openpay->customers->add($customer_data);
            return $customer;
        }
        catch (OpenpayApiTransactionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (OpenpayApiRequestError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiConnectionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiAuthError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

    public function CreateCard($card, $customer_id){
        try{
            $card_data = array(
                'token_id' => $card->token_id,
                'device_session_id' => $card->device_session_id);
                
            $customer = $this->openpay->customers->get($customer_id);
            $card = $customer->cards->add($card_data);
            return $card;
        }
        catch (OpenpayApiTransactionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (OpenpayApiRequestError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiConnectionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiAuthError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function CreateCharge($openpay_card_id, $cdg_seg, $customer, $amount){
        $amount = floor($amount * 100) / 100;
        $customer = $this->openpay->customers->get($customer->openpay_customer_id);
        $charge_request = array(
            'method' => 'card',
            'source_id' => $openpay_card_id,
            'amount' => $amount,
            'currency' => 'MXN',
            'description' => 'Cargo de venta FARMAPP',
            'order_id' => 'oid-' . $cdg_seg,
            'device_session_id' => $this->device_session_id);
        
        $charge = $customer->charges->create($charge_request);
        return $charge;
    }

    public function DeleteCard($card_id, $customer_id){
        try{
            $customer = $this->openpay->customers->get($customer_id);
            $card = $customer->cards->get($card_id);
            $card->delete();
            return true;
        }
        catch (OpenpayApiTransactionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (OpenpayApiRequestError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiConnectionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiAuthError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

    public function Devolution($customer_id, $transaction_id){
        try{
            $refoun_data = array('description' => 'devolución');
            $customer = $this->openpay->customers->get($customer_id);
            $charge = $customer->charges->get($transaction_id);
            $charge->refund($refoun_data);
            return true;
        }
        catch (OpenpayApiTransactionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (OpenpayApiRequestError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiConnectionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiAuthError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function CreatePlan($name, $amount, $repeat_unit = 'month', $repeat_every = 1){
        try{

            $plan_data = array(
                'amount' => $amount,
                'status_after_retry' => 'unpaid',
                'retry_times' => 3,
                'name' => $name,
                'repeat_unit' => $repeat_unit,
                'trial_days' => '0',
                'repeat_every' => $repeat_every,
                'currency' => 'MXN'
            );
    
            $plan = $this->openpay->plans->add($plan_data);
            return $plan->id;
        }
        catch (OpenpayApiTransactionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (OpenpayApiRequestError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiConnectionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiAuthError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

    public function UpdatePlan($id, $amount, $repeat_unit = 'month', $repeat_every = 1){
        try{

            $plan = $this->openpay->plans->get($id);
            if(!$plan)
                throw new Exception("El plan no fue encontrado.");
                
            $plan->amount = $amount;
            $plan->repeat_unit = $repeat_unit;
            $plan->repeat_every = $repeat_every;
            $plan->save();

            return $plan;
        }
        catch (OpenpayApiTransactionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (OpenpayApiRequestError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiConnectionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiAuthError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

    public function DeletePlan($id, $customer_id){
        try{

            $customer = $this->openpay->customers->get($customer_id);
            if(!$customer)
                throw new Exception("El cliente openpay no fue encontrado.");
            $plan = $this->openpay->plans->get($id);
            if(!$plan)
                throw new Exception("El plan openpay no fue encontrado.");
            $plan->delete();

            return true;
        }
        catch (OpenpayApiTransactionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (OpenpayApiRequestError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiConnectionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiAuthError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

    public function CreateSubscription($customer_id, $plan_id, $card_id, $trial_end_date){
        try{
            $subscription_data = array(
                'plan_id' => $plan_id,
                'source_id' => $card_id,
                'trial_end_date' => $trial_end_date
            );

            $customer = $this->openpay->customers->get($customer_id);
            $subscription = $customer->subscriptions->add($subscription_data);
    
            return $subscription->id;
        }
        catch (OpenpayApiTransactionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (OpenpayApiRequestError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiConnectionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiAuthError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

    public function UpdateSubscription($id, $customer_id, $card_id){
        try{

            $customer = $this->openpay->customers->get($customer_id);
            if(!$customer)
                throw new Exception("El cliente openpay no fue encontrado.");
            $subscription = $customer->subscriptions->get($id);
            if(!$subscription)
                throw new Exception("La subscripción no fue encontrada.");
            $subscription->source_id = $card_id;
            $subscription->save();
            return $subscription;
        }
        catch (OpenpayApiTransactionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (OpenpayApiRequestError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiConnectionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiAuthError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (Exception $e) {
            if($e->getMessage() != "Undefined index: source_id")
                throw new Exception($e->getMessage());
        }

    }

    public function CancelSubscription($id, $customer_id){
        try{
      
            $customer = $this->openpay->customers->get($customer_id);
            $subscription = $customer->subscriptions->get($id);
            if(!$subscription)
                throw new Exception("La subscripción no fue encontrada.");
            $subscription->delete();
            return true;
        }
        catch (OpenpayApiTransactionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (OpenpayApiRequestError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiConnectionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiAuthError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

    public function CreateWebhook($url, $user, $password, $event_types){
        try{
            $webhook = array(
                'url' => $url,
                'user' => $user,
                'password' => $password,
                'event_types' => $event_types
            );
            $webhook = $this->openpay->webhooks->add($webhook);
            return $webhook->id;
        }
        catch (OpenpayApiTransactionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (OpenpayApiRequestError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiConnectionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiAuthError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

    public function GetWebhooks(){
        try{
            return $this->openpay->webhooks->getList();
        }
        catch (OpenpayApiTransactionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (OpenpayApiRequestError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiConnectionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiAuthError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

    public function DeleteWebhooks($id){
        try{
      
            $webhook = $this->openpay->webhooks->get($id);
            $webhook->delete();
            return true;
        }
        catch (OpenpayApiTransactionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (OpenpayApiRequestError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiConnectionError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiAuthError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        } 
        catch (OpenpayApiError $e) {
            $error_code = $e->getErrorCode();
            $message = OpenpayService::GetRedableErrorByCode($e->getErrorCode(), $e->getDescription());
            throw new Exception($message,$error_code);
        }
        catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

    public static function GetRedableErrorByCode($code, $description){

        $map = array(
            1000=>'Ocurrió un error interno en el servidor de Openpay.',
            1001=>'Los datos de la tarjeta son invalidos favor de verificarlos.',
            1002=>'La llamada no esta autenticada o la autenticación es incorrecta.',
            1003=>'La operación no se pudo completar por que el valor de uno o más de los parámetros no es correcto.',
            1004=>'Un servicio necesario para el procesamiento de la transacción no se encuentra disponible.',
            1005=>'Uno de los recursos requeridos no existe.',
            1006=>'Ya existe una transacción con el mismo ID de orden.',
            1007=>'La transferencia de fondos entre una cuenta de banco o tarjeta y la cuenta de Openpay no fue aceptada.',
            1008=>'Una de las cuentas requeridas en la petición se encuentra desactivada.',
            1009=>'El cuerpo de la petición es demasiado grande.',
            1010=>'Se esta utilizando la llave pública para hacer una llamada que requiere la llave privada, o bien, se esta usando la llave privada desde JavaScript.',
            1011=>'Se solicita un recurso que esta marcado como eliminado.',
            1012=>'El monto transacción esta fuera de los limites permitidos.',
            1013=>'La operación no esta permitida para el recurso.',
            1014=>'La cuenta esta inactiva.',
            1015=>'No se ha obtenido respuesta de la solicitud realizada al servicio.',
            1016=>'El mail del comercio ya ha sido procesada.',
            1017=>'El gateway no se encuentra disponible en ese momento.',
            1018=>'El número de intentos de cargo es mayor al permitido.',
            1020=>'El número de dígitos decimales es inválido para esta moneda.',
            2001=>'La cuenta de banco con esta CLABE ya se encuentra registrada en el cliente..',
            2002=>'La tarjeta con este número ya se encuentra registrada en el cliente.',
            2003=>'El cliente con este identificador externo (External ID) ya existe.',
            2004=>'El número de la tarjeta es invalido.',
            2005=>'La fecha de expiración de la tarjeta es anterior a la fecha actual.',
            2006=>'El código de seguridad de la tarjeta (CVV2) no fue proporcionado.',
            2007=>'El número de tarjeta es de prueba, solamente puede usarse en Sandbox.',
            2008=>'La tarjeta no es válida para puntos Santander.',
            2009=>'El código de seguridad de la tarjeta (CVV2) es inválido.',
            2010=>'Autenticación 3D Secure fallida.',
            2011=>'Tipo de tarjeta no soportada.',
            3001=>'La tarjeta fue rechazada.',
            3002=>'La tarjeta ha expirado.',
            3003=>'Tarjeta declinada.',
            3004=>'Tarjeta declinada.',
            3005=>'La tarjeta ha sido rechazada por el sistema antifraudes.',
            3006=>'La operación no esta permitida para este cliente o esta transacción.',
            3007=>'La tarjeta ha sido declinada.',
            3008=>'La tarjeta no es soportada en transacciones en línea.',
            3009=>'La tarjeta fue reportada como perdida.',
            3010=>'El banco ha restringido la tarjeta.',
            3011=>'El banco ha solicitado que la tarjeta sea retenida. Contacte al banco.',
            3012=>'Se requiere solicitar al banco autorización para realizar este pago.',
            3201=>'Comercio no autorizado para procesar pago a meses sin intereses.',
            3203=>'Promoción no valida para este tipo de tarjetas.',
            3204=>'El monto de la transacción es menor al mínimo permitido para la promoción.',
            3205=>'Promoción no permitida.',
            4001=>'La cuenta de Openpay no tiene fondos suficientes.',
            4002=>'La operación no puede ser completada hasta que sean pagadas las comisiones pendientes.',
            5001=>'La orden con este identificador externo (external_order_id) ya existe.',
            6001=>'El webhook ya ha sido procesado.',
            6002=>'No se ha podido conectar con el servicio de webhook.',
            6003=>'El servicio respondio con errores.',
        );
        if(isset($map[$code]))
            return $map[$code];
        else
            return $description;
    }
}