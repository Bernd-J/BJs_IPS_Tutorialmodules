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
        $this->RegisterVariableString("Dataset", "Dataset", "", -3);
        $this->RegisterVariableInteger("Sensoren", "Sensoren", "", -1);
        IPS_SetHidden($this->GetIDForIdent('BufferIN'), true);
        IPS_SetHidden($this->GetIDForIdent('Dataset'), true);

    }
################## PUBLIC

    public function SendText(string $String)
    {
        $this->SendDataToParent($String);
    }

################## DATAPOINTS

    public function ReceiveData($JSONString)
    {                                                                    // *************** Daten zusammen tragen ************
           $data = json_decode($JSONString);
           $BufferID = $this->GetIDForIdent("BufferIN");
           $DatasetID = $this->GetIDForIdent("Dataset");
           $SensorenID = $this->GetIDForIdent("Sensoren");
           $Head = GetValueString($BufferID);                            // holt sich die gespeicherten Restdaten aus der Variablen
           $dazu = utf8_decode($data->Buffer);                           // holen des neuen
           $all = $Head.$dazu;                                           // setzt alle zusammen
                                                                         // *************** Datensatz separieren *************
           $Startsequenz = chr(0x0D).chr(0x0A).chr(0x0D).chr(0x0A);      // damit f�ngt der Datensatz an
           $Datasets = explode ($Startsequenz, $all);                    // Nun zerlegen wir den Senf und basteln ein Array
//           IPS_LogMessage('Datasets: ',print_r($Datasets,1));
           $AnzahlDatasets = count ($Datasets);
           if ($AnzahlDatasets > 1)                                      // checkt ob ein vollst�ndiger da ist
           {
//           IPS_LogMessage('If Datasets Weg oben',$AnzahlDatasets);
           SetValueString($BufferID, $Datasets[1]);                      // schreibt die Reste wieder zur�ck
           SetValueString($DatasetID, $Datasets[0]);                     // schreibt vollst�ndigen Datensatz in Dataset, kann sp�ter wieder raus
           // ab hier zerlegen wir das Dataset 0                         // *************** Datensatz verarbeiten ************
           $AnzahlSensoren = substr_count($Datasets[0], 'ROM');          // hier z�hlen wir wieviele Sensoren vorhanden sind
//           IPS_LogMessage('Anzahl Sensoren:',$AnzahlSensoren);
           SetValueInteger($SensorenID, $AnzahlSensoren);                // und f�llen damit die Variable
           $Sensoren = $Datasets[0];
           $Startsequenz1 = "ROM = ";                                    // damit f�ngt der Datensatz an
           $Endesequenz1 = chr(0x0D).chr(0x0A);                          // damit h�rt der Datensatz auf
           $Sensordaten[0] = explode ($Startsequenz1, $Sensoren);
           $Startsequenz2 = "Chip = ";                                   // damit f�ngt der Datensatz an
           $Endesequenz2 = chr(0x0D).chr(0x0A);                          // damit h�rt der Datensatz auf
           $Sensordaten[1] = explode ($Startsequenz2, $Sensoren);
           $Startsequenz3 = "Temperature = ";                            // damit f�ngt der Datensatz an
           $Endesequenz3 = chr(0x0D).chr(0x0A);                          // damit h�rt der Datensatz auf
           $Sensordaten[2] = explode ($Startsequenz3, $Sensoren);
                                                                         // Restinformationen abschneiden
           IPS_LogMessage('Sensordaten: ',print_r($Sensordaten,1));
           }
           else
           {
//           IPS_LogMessage('If ELSE Weg Datasets',$AnzahlDatasets);
           SetValueString($BufferID, $all);                              // schreibt alles wieder zur�ck, weil es noch nicht vollst�ndig war
           }
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

}

?>