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
        $this->RegisterVariableBoolean("ReplyEvent", "ReplyEvent", "", -5);
        $this->RegisterVariableBoolean("Connected", "Connected", "", -3);
        IPS_SetHidden($this->GetIDForIdent('BufferIN'), false);
        IPS_SetHidden($this->GetIDForIdent('ReplyEvent'), false);
        IPS_SetHidden($this->GetIDForIdent('Connected'), false);
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
          // Datenstream zusammenfügen
          $Head = GetValueString($BufferID); */
// Stream zusammenfügen
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

    private function GetErrorState()
    {
        return !GetValueBoolean($this->GetIDForIdent('Connected'));
    }

    private function SetErrorState($Value)
    {
        SetValueBoolean($this->GetIDForIdent('Connected'), !$Value);
    }

    private function SetReplyEvent($Value)
    {
        $EventID = $this->GetIDForIdent('ReplyEvent');
        if ($this->lock('ReplyEvent'))
        {
            SetValueBoolean($EventID, $Value);
            $this->unlock('ReplyEvent');
            return true;
        }
        return false;
    }

    private function WaitForResponse($Timeout)
    {
        $Event = $this->GetIDForIdent('ReplyEvent');
        for ($i = 0; $i < $Timeout / 5; $i++)
        {
            if (!GetValueBoolean($Event))
                IPS_Sleep(5);
            else
            {
                return true;
            }
        }
        return false;
    }

    protected function SendDataToParent($Data)
    {
        $DataArray = Array(
            "DataID" => "{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}",
            "Buffer" => utf8_encode($Data)    // Rohdaten mÃ¼ssen noch mit utf8_encode kodiert werden.
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