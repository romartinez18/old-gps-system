#!/usr/bin/python
import serial, time
import os, string
import MySQLdb

# Conexion a la BD
db = MySQLdb.connect(host="127.0.0.1", user="root", passwd="", db="localiza_gps")

ser = serial.Serial()
ser.port = "COM4"
ser.baudrate = 9600
ser.bytesize = serial.EIGHTBITS #number of bits per bytes
ser.parity = serial.PARITY_NONE #set parity check: no parity
ser.stopbits = serial.STOPBITS_ONE #number of stop bits
ser.timeout = 1            #non-block read
ser.xonxoff = False     #disable software flow control
ser.rtscts = False     #disable hardware (RTS/CTS) flow control
ser.dsrdtr = False       #disable hardware (DSR/DTR) flow control
ser.writeTimeout = 2     #timeout for write
listadoSMS = "AT+CMGL"
eliminarSMS = ""
#ruta = "C:\\xampp\\htdocs\\mensajeria\\sms.txt"
#archivo = open(ruta, "a")

try: 
	ser.open()
except Exception, e:
	print "error open serial port: " + str(e)
	exit()
#if ser.isOpen():
print "Ejecutando el Servidor SMS"
while True:
	try:
		ser.flushInput() #flush input buffer
		ser.flushOutput()#flush output buffer
		
		# MODO TEXTO
		ser.write("AT+CMGF=1\r")
		time.sleep(0.8)
		ser.flushInput() 
		ser.flushOutput()
		
		# Listado de Mensajes no leidos
		ser.write(listadoSMS+"\r")
		#print("comando: "+listadoSMS)
		time.sleep(0.5)  
		
		Iniciar = True
		codigo = []
		sms = ""
		Ced = ""
		Cel = ""
		Cod = ""
		cmdLeido = False
		fila = 0
		datosMensaje = {}
		comando = ""
		x = 0
		Sql = ""
		consuSql = False
		cursor = db.cursor() # Cursor de la Base de datos
		cursorSMS = db.cursor() # Cursor de la Base de datos de SMS
		
		while True:
			response = ser.readline().rstrip('\n\r')
			response.strip(' ')
			if (response == "ERROR"):
				break
			if (response == "OK"):
				break
							
			if consuSql:
				print("Insertando Entrada")
				Sql = "INSERT IGNORE INTO entrada(codigo,celular,mensaje) VALUES ("+Cod+",'"+str(Cel)+"','"
				Sql = Sql + response + "')"
				print(Sql)
				consuSql = False
				cursor.execute(Sql)
				db.commit()
				
			if (response[:6] == "+CMGL:"):
				cmdLeido = False
				codigo.append(response[7:11])
				Cod = response[7:11]
				x = x + 1
				Cel = response[-12:-1]
				consuSql = True
				
			# info o saldo
			if (response[:4].upper() == "INFO"):
				cmdLeido = True
				Ced = response[5:]
				sms = "Info"
				
			if (response[:5].upper() == "SALDO"):
				cmdLeido = True
				Ced = response[6:]
				sms = "Saldo"
				
			# deuda
			if (response[:5].upper() == "DEUDA"):
				cmdLeido = True
				Ced = response[6:]
				sms = "Deuda"
				
			# ahorro
			if (response[:6].upper() == "AHORRO"):
				cmdLeido = True
				Ced = response[7:]
				sms = "Ahorro"
				
			# voto
			if (response[:4].upper() == "VOTO"):
				cmdLeido = True
				Ced = response[5:]
				sms = "Voto"
			
			if (cmdLeido):
				datosMensaje[fila,0] = codigo[(x - 1)]
				datosMensaje[fila,1] = Ced
				datosMensaje[fila,2] = Cel
				datosMensaje[fila,3] = sms
				fila = fila + 1
			
		ser.flushInput() #flush input buffer
		ser.flushOutput()#flush output buffer
		
		j = 0
		while j < x:
			# Eliminar SMS
			eliminarSMS = "AT+CMGD="+codigo[j]
			ser.write(eliminarSMS+"\r")
			time.sleep(0.4)
			ser.flushInput() #flush input buffer
			ser.flushOutput()#flush output buffer		
			j = j + 1
		
		fila = fila - 1
		f = 0
		envioMensaje = ""
		sendSMS = False
		leer = ""
		SqlSMS = ""
						
		Sql = "SELECT * FROM salida WHERE estatus = 0 ORDER BY id ASC"
		cursor.execute(Sql)
		db.commit()
		salidas = cursor.fetchall()
		FechaActual = ""
		#print(cursor.rowcount)
		for salida in salidas:
			cod = salida[0]
			Cel = salida[1]
			Mensaje = salida[2]
			FechaActual = time.strftime("%Y-%m-%d %X")
			
			# Enviar Mensaje
			enviarSMS = "AT+CMGS=\""+Cel+"\""
			ser.write(enviarSMS+"\r")
			time.sleep(1.0)
			ser.flushInput() 
			ser.flushOutput()
			ser.write(Mensaje+"\r")
			time.sleep(1.2) 
			ser.flushInput() 
			ser.flushOutput()
			ser.write(chr(26))
			time.sleep(1.5) 
			leer = ser.readline().rstrip('\n\r')
			print(Mensaje+"\n")
			print(leer)
			#print(FechaActual)
						
			if (leer != "ERROR"):
				SqlSMS = "UPDATE salida SET tiemporegistro='"+str(FechaActual)+"',estatus=1 WHERE id = "+str(cod)
				print(SqlSMS)
				cursorSMS.execute(SqlSMS)
				db.commit()
			time.sleep(1.0)
		
		#cursorSMS.close()
		#cursor.close()
		
		#ser.close()
		#archivo.close()
		#os.system("php-cgi.exe C:\\xampp\\htdocs\\mensajeria\\sms.php")
	except Exception, e1:
		print "Error de Comunicacion...: " + str(e1)
		db = MySQLdb.connect(host="127.0.0.1", user="root", passwd="", db="localiza_gps")
#else:
#	print "No se pudo Abrir el Puerto Serial "