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
        $this->RegisterVariableString("BufferIN", "BufferIN", "", -7);
        $this->RegisterVariableString("Dataset", "Dataset", "", -6);
        $this->RegisterVariableInteger("Sensoren", "Sensoren", "", -5);
        IPS_SetHidden($this->GetIDForIdent('BufferIN'), true);
        IPS_SetHidden($this->GetIDForIdent('Dataset'), true);
        $this->RegisterVariableString("Sensor1_ROM", "Sensor1_ROM", "", 5);
        $Sensor1_ROMID = $this->GetIDForIdent("Sensor1_ROM");
        $this->RegisterVariableString("Sensor1_Typ", "Sensor1_Typ", "", 6);
        $Sensor1_TypID = $this->GetIDForIdent("Sensor1_Typ");
        $this->RegisterVariableFloat("Sensor1_Temp", "Sensor1_Temp", "~Temperature", 7);
        $Sensor1_TempID = $this->GetIDForIdent("Sensor1_Temp");
        $this->RegisterVariableString("Sensor2_ROM", "Sensor2_ROM", "", 8);
        $Sensor2_ROMID = $this->GetIDForIdent("Sensor2_ROM");
        $this->RegisterVariableString("Sensor2_Typ", "Sensor2_Typ", "", 9);
        $Sensor2_TypID = $this->GetIDForIdent("Sensor2_Typ");
        $this->RegisterVariableFloat("Sensor2_Temp", "Sensor2_Temp", "~Temperature", 10);
        $Sensor2_TempID = $this->GetIDForIdent("Sensor2_Temp");
        $this->RegisterVariableString("Sensor3_ROM", "Sensor3_ROM", "", 11);
        $Sensor3_ROMID = $this->GetIDForIdent("Sensor3_ROM");
        $this->RegisterVariableString("Sensor3_Typ", "Sensor3_Typ", "", 12);
        $Sensor3_TypID = $this->GetIDForIdent("Sensor3_Typ");
        $this->RegisterVariableFloat("Sensor3_Temp", "Sensor3_Temp", "~Temperature", 13);
        $Sensor3_TempID = $this->GetIDForIdent("Sensor3_Temp");
//        IPS_SetParent($Sensor1_ROMID, $Dummymodul_1_ID); // Instanz einsortieren unter dem Objekt

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
           $Startsequenz = chr(0x0D).chr(0x0A).chr(0x0D).chr(0x0A);      // damit fängt der Datensatz an
           $Datasets = explode ($Startsequenz, $all);                    // Nun zerlegen wir den Senf und basteln ein Array
           $AnzahlDatasets = count ($Datasets);
           if ($AnzahlDatasets > 1)                                      // checkt ob ein vollständiger da ist
           {
           SetValueString($BufferID, $Datasets[1]);                      // schreibt die Reste wieder zurück
           SetValueString($DatasetID, $Datasets[0]);                     // schreibt vollständigen Datensatz in Dataset, kann später wieder raus
           // ab hier zerlegen wir das Dataset 0                         // *************** Datensatz verarbeiten ************
           $AnzahlSensoren = substr_count($Datasets[0], 'ROM');          // hier zählen wir wieviele Sensoren vorhanden sind
           SetValueInteger($SensorenID, $AnzahlSensoren);                // und füllen damit die Variable

           $Sensoren = $Datasets[0];

           $Startsequenz1 = "ROM = ";                                    // damit fängt der Datensatz an
           $Ende1nachZeichen = 21;                                       // und ist xx Zeichen lang
           $Sensordaten[0] = explode ($Startsequenz1, $Sensoren);
            $SensorkorrROM = "Hardware ID des 1-W Bausteines";
            $Sensordaten[0][0] = $SensorkorrROM;
            $SensorkorrROM = $Sensordaten[0][1];
            $SensorkorrROM = substr($SensorkorrROM, 0, $Ende1nachZeichen);
            $Sensordaten[0][1] = $SensorkorrROM;
            $SensorkorrROM = $Sensordaten[0][2];
            $SensorkorrROM = substr($SensorkorrROM, 0, $Ende1nachZeichen);
            $Sensordaten[0][2] = $SensorkorrROM;
            $SensorkorrROM = $Sensordaten[0][3];
            $SensorkorrROM = substr($SensorkorrROM, 0, $Ende1nachZeichen);
            $Sensordaten[0][3] = $SensorkorrROM;
           SetValueString($this->GetIDForIdent("Sensor1_ROM"), $Sensordaten[0][1]);  // und füllen damit die Variable, und dann Befüllen aus dem Array
           SetValueString($this->GetIDForIdent("Sensor2_ROM"), $Sensordaten[0][2]);  // und füllen damit die Variable, und dann Befüllen aus dem Array
           SetValueString($this->GetIDForIdent("Sensor3_ROM"), $Sensordaten[0][3]);  // und füllen damit die Variable, und dann Befüllen aus dem Array

           $Startsequenz2 = "Chip = ";                                    // damit fängt der Datensatz an
           $Ende2nachZeichen = 8;                                         // und ist xx Zeichen lang
           $Sensordaten[1] = explode ($Startsequenz2, $Sensoren);
            $SensorkorrROM = "Typ des 1-W Bausteines";
            $Sensordaten[1][0] = $SensorkorrROM;
            $SensorkorrROM = $Sensordaten[1][1];
            $SensorkorrROM = substr($SensorkorrROM, 0, $Ende2nachZeichen);
            $Sensordaten[1][1] = $SensorkorrROM;
            $SensorkorrROM = $Sensordaten[1][2];
            $SensorkorrROM = substr($SensorkorrROM, 0, $Ende2nachZeichen);
            $Sensordaten[1][2] = $SensorkorrROM;
            $SensorkorrROM = $Sensordaten[1][3];
            $SensorkorrROM = substr($SensorkorrROM, 0, $Ende2nachZeichen);
            $Sensordaten[1][3] = $SensorkorrROM;
           SetValueString($this->GetIDForIdent("Sensor1_Typ"), $Sensordaten[1][1]);  // und füllen damit die Variable, und dann Befüllen aus dem Array
           SetValueString($this->GetIDForIdent("Sensor2_Typ"), $Sensordaten[1][2]);  // und füllen damit die Variable, und dann Befüllen aus dem Array
           SetValueString($this->GetIDForIdent("Sensor3_Typ"), $Sensordaten[1][3]);  // und füllen damit die Variable, und dann Befüllen aus dem Array

           $Startsequenz3 = "Temperature = ";                              // damit fängt der Datensatz an
           $Ende3nachZeichen = 5;                                          // und ist xx Zeichen lang
           $Sensordaten[2] = explode ($Startsequenz3, $Sensoren);
            $SensorkorrROM = "Temperatur des 1-W Bausteines in Celsius";
            $Sensordaten[2][0] = $SensorkorrROM;
            $SensorkorrROM = $Sensordaten[2][1];
            $SensorkorrROM = substr($SensorkorrROM, 0, $Ende3nachZeichen);
            $Sensordaten[2][1] = $SensorkorrROM;
            $SensorkorrROM = $Sensordaten[2][2];
            $SensorkorrROM = substr($SensorkorrROM, 0, $Ende3nachZeichen);
            $Sensordaten[2][2] = $SensorkorrROM;
            $SensorkorrROM = $Sensordaten[2][3];
            $SensorkorrROM = substr($SensorkorrROM, 0, $Ende3nachZeichen);
            $Sensordaten[2][3] = $SensorkorrROM;
           SetValueFloat($this->GetIDForIdent("Sensor1_Temp"), $Sensordaten[2][1]);  // und füllen damit die Variable, und dann Befüllen aus dem Array
           SetValueFloat($this->GetIDForIdent("Sensor2_Temp"), $Sensordaten[2][2]);  // und füllen damit die Variable, und dann Befüllen aus dem Array
           SetValueFloat($this->GetIDForIdent("Sensor3_Temp"), $Sensordaten[2][3]);  // und füllen damit die Variable, und dann Befüllen aus dem Array
           }
           else
           {
           SetValueString($BufferID, $all);                              // schreibt alles wieder zurück, weil es noch nicht vollständig war
           }
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

}

?>