# Tutorialmodule f�r IPS

* zerlegeString

basierend auf dem Modul StringRecv basteln wir uns ein neues:

Vorgehensweise:

neues Modulverzeichnis anlegen

alle 4 Dateien des alten Modules in das neue kopieren:

form.json: kann so bleiben

module.json:

Zeile 2, neue id per [guidgenerator.com](https://www.guidgenerator.com/) erzeugen und hier tauschen.

Zeile 6, aussagekr�ftigen Namen einsetzen

Zeile 10 ersetzen, dient sp�ter in IP-Symcon als Prefix f�r die Befehle

Zeile 3 ist die wichtigste, der hier eingestellte Name definiert die neu zu schaffende Klasse welche sp�ter in IPS benutzt wird.

Man findet sie wieder in

module.php

in Zeile 3, dort muss sie entsprechend angepasst werden.

Unsere Erg�nzungen halten Einzug ab Zeile 29.

Was ist zu tun?

* als erstes zusammenfassen der einzelnen Datenpakete zu einem gesamten.

* separieren des Gesamtpaketes: Startsequenz "CR/LF/CR/LF", Endekennung "No more adresses."

* daraus separieren der einzelnen f�r uns interessanten Daten:
* One-Wire Hardware ID: Startsequenz " ROM  = ", Endekennung "CR/LF"
* One-Wire Chip: Startsequenz " Chip  = ", Endekennung "CR/LF"
* Temperatur: Startsequenz " Temperature  = ", Endekennung " Celsius,"

* folgt jetzt eine weitere HardwareID das obige Schema wiederholen.

Alle Daten in ein Array und daraus dann Variablen f�ttern mit zusammenfassendem Dummymodul f�r jeden Temperatursensor.
Jedem Sensor ein zus�tzliches Namensfeld verpassen welches ausgef�llt werden muss.