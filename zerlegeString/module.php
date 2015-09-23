<?

class zerlegeString extends IPSModule
{

    public function Create()
    {
        //Never delete this line!
        parent::Create();
    }

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();
        $this->RegisterVariableString("BufferIN", "BufferIN", "", -4);
        IPS_SetHidden($this->GetIDForIdent('BufferIN'), false);
    }
################## PUBLIC

    public function SendText(string $String)
    {
        $this->SendDataToParent($String);
    }

################## DATAPOINTS

    public function ReceiveData($JSONString)
    {
// alles geklaut aus MS35 Zeile 592 - 620
//        IPS_LogMessage('RecData', utf8_decode($JSONString));
//        IPS_LogMessage(__CLASS__, __FUNCTION__); //
//FIXME Bei Status inaktiv abbrechen
        $data = json_decode($JSONString);
        $BufferID = $this->GetIDForIdent("BufferIN");
// Empfangs Lock setzen
        if (!$this->lock("ReplyLock"))
        {
            throw new Exception("ReceiveBuffer is locked");
        }
        /*
          // Datenstream zusammenf�gen
          $Head = GetValueString($BufferID); */
// Stream zusammenf�gen
        SetValueString($BufferID, utf8_decode($data->Buffer));
// Empfangs Event setzen
        /*        if (!$this->SetReplyEvent(TRUE))
          {
          // Empfangs Lock aufheben
          $this->unlock("ReplyLock");
          throw new Exception("Can not send to ParentLMS");
          } */
        $this->SetReplyEvent(TRUE);
// Empfangs Lock aufheben
        $this->unlock("ReplyLock");
        return true;
    }

    protected function SendDataToParent($Data)
    {
        $DataArray = Array(
            "DataID" => "{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}",
            "Buffer" => utf8_encode($Data)    // Rohdaten müssen noch mit utf8_encode kodiert werden.
            );
        $JSONString = json_encode($DataArray);
        IPS_SendDataToParent($this->InstanceID, $JSONString);
    }
################## SEMAPHOREN Helper  - private

    private function lock($ident)
    {
        for ($i = 0; $i < 100; $i++)
        {
            if (IPS_SemaphoreEnter("LMS_" . (string) $this->InstanceID . (string) $ident, 1))
            {
//                IPS_LogMessage((string)$this->InstanceID,"Lock:LMS_" . (string) $this->InstanceID . (string) $ident);
                return true;
            }
            else
            {
                IPS_Sleep(mt_rand(1, 5));
            }
        }
        return false;
    }

    private function unlock($ident)
    {
//                IPS_LogMessage((string)$this->InstanceID,"Unlock:LMS_" . (string) $this->InstanceID . (string) $ident);

        IPS_SemaphoreLeave("LMS_" . (string) $this->InstanceID . (string) $ident);
    }

}

?>