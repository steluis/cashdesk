# -*- encoding: utf-8 -*-
import usb.core
import usb.util
import sys
import MySQLdb
import codecs 
import logging
# Open database connection
db = MySQLdb.connect("localhost","cashdesksql","cashdesksql","cashdesk" )

# prepare a cursor object using cursor() method
cursor = db.cursor()
id_stampante = sys.argv[9]

sql = "SELECT * FROM parametri WHERE descrizione LIKE 'logo'"
# Execute the SQL command
cursor.execute(sql)
# Fetch all the rows in a list of lists.
results = cursor.fetchall()
for row in results:
 logo = row[2]

sql = "SELECT * FROM stampanti WHERE id LIKE '%s'" % id_stampante
# Execute the SQL command
cursor.execute(sql)
# Fetch all the rows in a list of lists.
results = cursor.fetchall()
for row in results:
 codec = row[9]
 ip_stampante = row[5]
 connessione = row[4]

sql = "SELECT * FROM caratteri WHERE charset LIKE '%s'" % codec
# Execute the SQL command
cursor.execute(sql)
# Fetch all the rows in a list of lists.
results = cursor.fetchall()
for row in results:
 euro = row[2]
 egrave = row[3]
 eacuto = row[4]
 agrave = row[5]
 igrave = row[6]
 ograve = row[7]
 ugrave = row[8]
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

if connessione == 'USB':
   Epson = Usb(prodid,vendid)
if connessione == 'Rete':
   Epson = Network(ip_stampante,9100)
   
euro = codecs.escape_decode(euro)[0]
egrave = codecs.escape_decode(egrave)[0]
agrave = codecs.escape_decode(agrave)[0]
eacuto = codecs.escape_decode(eacuto)[0]
igrave = codecs.escape_decode(igrave)[0]
ograve = codecs.escape_decode(ograve)[0]
ugrave = codecs.escape_decode(ugrave)[0]
stringa=stringa.replace("è",egrave)
stringa=stringa.replace("à",agrave)
stringa=stringa.replace("é",eacuto)
stringa=stringa.replace("ì",igrave)
stringa=stringa.replace("ò",ograve)
stringa=stringa.replace("ù",ugrave)
stringa=stringa.replace("€",euro)
filelogo = open ("./logos/"+logo+".hex", "r")
data=filelogo.read()
data = codecs.escape_decode(data)[0]
filelogo.close()
Epson._raw(data)  # Stampa logo
Epson.charcode("LATIN2")
Epson.set("LEFT","A","normal",1,1,9)
Epson.set("LEFT","A","B",1,1,9)
Epson.text("\n"+intestazione+"\n\n")
Epson.set("LEFT","A","B",2,2,9)
Epson.text("N."+sco+"   ")
Epson.set("LEFT","B","B",1,2,9)
Epson.text("COPERTI "+cop+"   "+asp+"\n")
Epson.set("LEFT","A","normal",1,1,9)
Epson._raw(stringa+"\n")
Epson.text("\n")
Epson.text("\n")
Epson.text("\n")
Epson.text("\n")
Epson.text("\n")
Epson.cutriga()
Epson.close()
