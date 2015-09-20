# Tutorialmodule f�r IPS

* String Receiver by Nall-chan

Das original Repro findet man
[HIER](https://github.com/Nall-chan/IPSTesting.git)

Als Ausgangsbasis diente ihm
[GitHub Nall.chan MS35](https://github.com/Nall-chan/IPSMS35).
Er reduzierte radikal, �brig blieb die Nutzung der Ein- und Ausgabefunktionen der hauseigenen IPS Module.

Beim Durchst�bern in seinem Original Code kam der gef�hlte Eindruck auf ich bef�nde mich nicht mehr unter lauter B�umen auf der Suche nach dem Wald, Nee....., das war der Dschungel pur.

Diverse Versuche es auf die wichtigsten Funktionen zusammen zu streichen scheiterten bei mir, netterweise hat uns Michael dann diese Version �berlassen.

Was f�llt auf:

Dadurch, dass in der module.json 2 intern genutzte ID�s angesprochen werden, welche von mehreren Modulen genutzt werden, erscheint als �bergeordnete Instanz alles, was diese ID�s nutzt.

Seht selbst:

   ![](../Doku/Doku_String_Receiver_modulejson.png)

Installieren des Modules (in der module.json steht als "vendor" Tutorials !)

   ![](../Doku/Doku_Instanz_hinzu.png)

wobei anzumerken ist dass dieses Modul selbst kein eigenes Interface erstellt, dazu kommen wir sp�ter.

Aus diesem Grunde bietet er im Pull-down als �bergeordnete Instanz die bereits vorhandenen Schnittstellen an, welche kompatibel sind:

   ![](../Doku/Doku_String_Receiver.png)

M�chten wir allerdings eine neue serielle Schnittstelle nutzen, so m�ssen wir sie selbst anlegen und verkn�pfen.

Als Beispiel f�r eingehenden Traffic schnappen wir uns mal ein beliebiges ESP8266 Modul, benutzen anhand [dieser] (https://www.symcon.de/forum/threads/27549-WiFi-Modul-ESP8266?p=255879#post255879) Anleitung und [jener](https://homec4u.wordpress.com/2015/06/24/1-wire-per-arduino-ide-direkt-auf-dem-esp8266/) die entsprechende Hardware und �ffnen die Arduino IDE:

   ![](../Doku/Doku_1W_Arduinosample.png)

Anhand des Quelltextes erkennen wir dass hier an GPIO2 angeschlossene DS18B20 ausgelesen werden und die Daten auf der seriellen Schnittstelle ausgegeben werden.

�ffnet man: Werkzeuge --> Serieller Monitor

sieht man sch�n die sprudelnden Datenpakete:

   ![](../Doku/Doku_1W_Datastream.png)

Zu diesem Zeitpunkt befanden sich 2 St�ck am Bus.

In IPS findet sich im Log des ComPortes dann folgendes:

   ![](../Doku/Doku_1W_Datastream_IPSComport.png)

Wie wir sehen h�ngen die Daten nicht zusammen, sondern trudeln alle per Zufallsl�nge rein.

Da wir in Zeile 29 der module.php s�mtlichen eingehenden Traffic ins log senden schaut das Meldungsfenster dementsprechend aus:

   ![](../Doku/Doku_1W_IPS_Meldungsfenster.png)

Hier ist das Ende des ersten Modules, im n�chsten geht es weiter mit dem Auswerten.