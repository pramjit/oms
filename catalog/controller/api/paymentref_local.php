<?php

class ControllerApiPaymentref extends Controller {
	public function index() {
            $log=new Log("MO_SUB_PAY_".date('Ymd').".log");
		//$this->load->language('api/jeepcampaign');
            $keys = array( 
                    'Emp_id',
                    'Cust_id',
                    'Amnt_Bank',
                    'Amnt_Date',
                    'Amnt_Type',
                    'Amnt_Ref',
                    'Amnt_Rs',
                    'Pay_type'
            );
            $json = array();
            $mcrypt = new MCrypt();
            $log->write($this->request->post);
            
            foreach ($keys as $key) {
                $this->request->post[$key] =$mcrypt->decrypt($this->request->post[$key]) ;
            }
            $log->write($this->request->post);
            $this->load->model('account/activity');
            $activity_data = $this->request->post;
            $this->model_account_activity->addActivity('plancreate', $activity_data);
            $this->load->model('payment/paymentref');


            if (isset($this->request->post["username"])  && $this->request->post["username"]!="0"){

                $api_info = $this->model_payment_paymentref->addpaymentref($this->request->post);
                $log->write($api_info);
                if ($api_info>0) {	
                    //======================= Msg Sending Start ==================//
                    $MoId=$this->request->post["username"];
                    $StoId=$this->request->post['Cust_id'];
                    $MsgDtls=$this->model_payment_paymentref->MsgDtls($MoId,$StoId);
                    $DealerName=$MsgDtls['DL_NAME'];
                    $TotalRs=$this->request->post['Amnt_Rs'];
                    $SubDate=date('dM, Y');
                    $MoName=$MsgDtls['MO_NAME'];
                            $AM_MOB=$MsgDtls['AM_MOB'];
                            $InMob_I='918392913500'; // for fixed
                            $InMob_II='917830800900'; // for fixed
                            $InMob_III='919971813600'; // for test    
                            //$InMob_IV='919205540431'; // for test  
                            $numbers = array($InMob_III);
                            //$numbers = array($InMob_III,$InMob_IV);
                            
                        
                            $textmsg='Payment of M/s DealerName of Rs. TotalRs /- on SubDate submitted by MoName .';
                            $textmsg=str_replace('DealerName',$DealerName,$textmsg);
                            $textmsg=str_replace('TotalRs',$TotalRs,$textmsg);
                            $textmsg=str_replace('SubDate',$SubDate,$textmsg);
                            $textmsg=str_replace('MoName',$MoName,$textmsg);
                            $log->write("Message Sent:".$textmsg);
                            $message = rawurlencode($textmsg);
                            $numbers = implode(',', $numbers);
                            //$numbers2 = implode(',', $numbers2);
                            //Authorization
                            $apiKey = urlencode('bDDRD5CawD8-vZaDs0E2AFnEOi1BBmyE90KujZbjTc');
                            $sender = urlencode('KAIBLY');
                        
                            $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
                        
                            // Send the POST request with cURL
                            $ch = curl_init('https://api.textlocal.in/send/');
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            $responseBody = curl_exec($ch);
                            if($responseBody === false) {
                                $responseBody=curl_error($ch);
                            }
                            curl_close($ch);
                            $log->write("Message Sent:".$responseBody);	
                            $type='MO_SUB_MT';
                            $OrderId=100;// Payment Submit
                            $msgMaster = $this->model_payment_paymentref->msgMaster($OrderId,$type,$MoId,$StoId,$textmsg,$numbers,$responseBody);
                            $log->write("Message2DB:".$msgMaster);
                    
                    //======================= Msg Sending Start ==================//
                    $j=1;
                } else {
                    $j=0;
                }
            }else {
			
                $j=0;
            }
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($j));
	}
}