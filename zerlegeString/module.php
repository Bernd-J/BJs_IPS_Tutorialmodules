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

        $Dummymodul_1_ID = IPS_CreateInstance("{485D0419-BE97-4548-AA9C-C083EB82E61E}");
        IPS_SetName($Dummymodul_1_ID, "Sensor_1"); // Instanz benennen
        IPS_SetParent($Dummymodul_1_ID, $this->InstanceID); // Instanz einsortieren unter dem Objekt klappt, aber:

        //      ** sobald ich die InstanceID verlasse findet er nix mehr, gibt´s ne Abhilfe?

        //      **************************** hier fehl am Platze ??

/*       was wollte ich erreichen?
         nachdem der erste Datensatz empfangen wurde ist die Anzahl an angeschlossenen Temperatursensoren bekannt,
         siehe Zeile 81.
         Nun sollte für jeden Temperatursensor ein Dummymodul erstellt werden und dort hinein sollten die Werte.
         Zusätzlich 2 Eingabefelder: Name und Standort
         könnte man die nachträglich in den "elements" Bereich des Konfigurationsformulares hinein bringen?
         BTW: die Variablen des applychanges Bereiches hier oben sind unten nicht mehr nutzbar;
         und über &this-> werden sie nur gefunden bei gleichem Parent, also nicht wenn sie unterhalb liegen.
         Noch ne Idee: Im "actions" Bereich des Konfigurationsformulares einen Button um die Verbindung zu testen und einmalig die Anzahl an Sensoren auszulesen.
         Weiteres Problem: mit jedem Neustart wird ein neues Dummymodul angelegt, gibt es sowas wie RegisterInstance ??
*/
        $this->RegisterVariableString("Sensor1_ROM", "Sensor1_ROM", "", -4);
        $Sensor1_ROMID = $this->GetIDForIdent("Sensor1_ROM");
        $this->RegisterVariableString("Sensor1_Temp", "Sensor1_Temp", "", -3); // ja, ich weiss, hier gehört später eine FloatVariable rein
        $Sensor1_TempID = $this->GetIDForIdent("Sensor1_Temp");
        $this->RegisterVariableString("Sensor2_Temp", "Sensor2_Temp", "", -3);
        $Sensor2_TempID = $this->GetIDForIdent("Sensor2_Temp");
        $this->RegisterVariableString("Sensor3_Temp", "Sensor3_Temp", "", -3);
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
//           IPS_LogMessage('Datasets: ',print_r($Datasets,1));
           $AnzahlDatasets = count ($Datasets);
           if ($AnzahlDatasets > 1)                                      // checkt ob ein vollständiger da ist
           {
//           IPS_LogMessage('If Datasets Weg oben',$AnzahlDatasets);
           SetValueString($BufferID, $Datasets[1]);                      // schreibt die Reste wieder zurück
           SetValueString($DatasetID, $Datasets[0]);                     // schreibt vollständigen Datensatz in Dataset, kann später wieder raus
           // ab hier zerlegen wir das Dataset 0                         // *************** Datensatz verarbeiten ************
           $AnzahlSensoren = substr_count($Datasets[0], 'ROM');          // hier zählen wir wieviele Sensoren vorhanden sind
//           IPS_LogMessage('Anzahl Sensoren:',$AnzahlSensoren);
           SetValueInteger($SensorenID, $AnzahlSensoren);                // und füllen damit die Variable

           $Sensoren = $Datasets[0];
           $Startsequenz1 = "ROM = ";                                    // damit fängt der Datensatz an
           $Endesequenz1 = chr(0x0D).chr(0x0A);                          // damit hört der Datensatz auf
           $Sensordaten[0] = explode ($Startsequenz1, $Sensoren);
           IPS_LogMessage('Sensor1 ROM ID unten: ',$this->GetIDForIdent("Sensor1_ROM"));
//           SetValueString($Sensor1_ROMID, $Sensordaten[0][1]);         // und füllen damit die Variable, hier ist alles Mist, wir brauchen ne Funktion zum Erstellen der Dummymodule
           SetValueString($this->GetIDForIdent("Sensor1_ROM"), $Sensordaten[0][1]);  // und füllen damit die Variable, und dann Befüllen aus dem Array
//         *********** hier wird gearbeitet, bzw. verzweifelt ***************************
           $Startsequenz2 = "Chip = ";                                   // damit fängt der Datensatz an
           $Endesequenz2 = chr(0x0D).chr(0x0A);                          // damit hört der Datensatz auf
           $Sensordaten[1] = explode ($Startsequenz2, $Sensoren);
           $Startsequenz3 = "Temperature = ";                            // damit fängt der Datensatz an
           $Endesequenz3 = chr(0x0D).chr(0x0A);                          // damit hört der Datensatz auf
           $Sensordaten[2] = explode ($Startsequenz3, $Sensoren);
           SetValueString($this->GetIDForIdent("Sensor1_Temp"), $Sensordaten[2][1]);  // und füllen damit die Variable, und dann Befüllen aus dem Array
           SetValueString($this->GetIDForIdent("Sensor2_Temp"), $Sensordaten[2][2]);  // und füllen damit die Variable, und dann Befüllen aus dem Array
           SetValueString($this->GetIDForIdent("Sensor3_Temp"), $Sensordaten[2][3]);  // und füllen damit die Variable, und dann Befüllen aus dem Array
                                                                         // Restinformationen abschneiden folgt demnächst
           IPS_LogMessage('Sensordaten: ',print_r($Sensordaten,1));
           }
           else
           {
//           IPS_LogMessage('If ELSE Weg Datasets',$AnzahlDatasets);
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