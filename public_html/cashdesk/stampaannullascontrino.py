# -*- encoding: utf-8 -*-
import usb.core
import usb.util
import sys
import MySQLdb
import codecs 
import logging
logging.basicConfig(filename='logpython.log',level=logging.DEBUG)
# Open database connection
db = MySQLdb.connect("localhost","cashdesksql","cashdesksql","cashdesk" )

# prepare a cursor object using cursor() method
cursor = db.cursor()
id_stampante = sys.argv[9]
logging.info('ID Stampante '+id_stampante)

sql = "SELECT * FROM parametri WHERE descrizione LIKE 'logo'"
# Execute the SQL command
cursor.execute(sql)
# Fetch all the rows in a list of lists.
results = cursor.fetchall()
for row in results:
 logo = row[2]
logging.info('Logo: '+logo)

sql = "SELECT * FROM stampanti WHERE id LIKE '%s'" % id_stampante
# Execute the SQL command
cursor.execute(sql)
# Fetch all the rows in a list of lists.
results = cursor.fetchall()
for row in results:
 ip_stampante = row[5]
 connessione = row[4]
logging.info('IP Stampante '+ip_stampante)
# disconnect from server
db.close()

from escpos.printer import Usb
from escpos.printer import Network
sco = sys.argv[1]
asp = sys.argv[2]
cop = sys.argv[3]
intestazione = sys.argv[6]
prodid = int(sys.argv[7],0)
vendid = int(sys.argv[8],0)
stringa = sys.argv[4]

logging.info('Prima di apertura Epson scontrino '+sco)
if connessione == 'USB':
   Epson = Usb(prodid,vendid)
if connessione == 'Rete':
   Epson = Network(ip_stampante,9100)
   
logging.info('Connessione: '+connessione)
logging.info('Dopo apertura Epson scontrino '+sco)
logging.info('Stampante '+sys.argv[7])
Epson.charcode("LATIN2")
Epson.set("LEFT","A","B",2,2,9)
Epson.text("MESSAGGIO PER LA\n")
Epson.text("CUCINA ANNULLARE\n")
Epson.text("LA COMANDA N."+sco)
Epson.text("\n")
Epson.text("\n")
Epson.text("\n")
Epson.text("\n")
Epson.text("\n")
Epson.text("\n")
Epson.text("\n")
Epson.text("\n")
Epson.text("\n")
Epson.text("\n")
Epson.text("\n")
Epson.text("\n")
Epson.text("\n")
Epson.cutriga()
Epson.close()
