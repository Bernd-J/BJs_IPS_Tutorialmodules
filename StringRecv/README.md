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

wobei anzumerken ist dass dieses Modul selbst kein eigenes Interface erstellt, dazu kommen wir sp�ter.

