# this python script copy al 
# default car prewiews
# tracks outlines
# from the tgiven svn repo to a specified folder in the webserver

import os
import sys
import shutil
import xml.etree.ElementTree as ET


#=====================
#get a list of the folders in the cars directory
sdRepoFolder = '/home/madbad/Development/svn/speed-dream/speed-dreams/trunk/'

carMainFolder = sdRepoFolder+'data/cars/models/'
carCatMainFolder = sdRepoFolder+'data/cars/categories/'

trackMainFolder = sdRepoFolder+'data/tracks/'
trackCatMainFolder = sdRepoFolder+'data/tracks/'


##============================
## EXTRACT CARS CATEGORY DATA
##============================
carCategories = {}

catFiles =  os.listdir(carCatMainFolder)

for catFile in catFiles:
	xmlCatFile = carCatMainFolder+catFile
	
	fileName, fileExtension = os.path.splitext(xmlCatFile)

	if fileExtension=='.xml':
		if os.path.isfile(xmlCatFile):
			xmlFileUrl = xmlCatFile
			parser = ET.XMLParser()
			parser._parser.UseForeignDTD(True)
			#parser.entity['default-surfaces'] = u'\u00A0'
			#parser.entity['default-objects'] = u'\u00A0'
			tree = ET.parse(xmlFileUrl, parser=parser)

			#and get the root of the xml tree
			root = tree.getroot()

			catName=root.attrib['name']
			catId= root.findall("./section[@name='Car']/attstr[@name='category']")[0].attrib['val']
		
			carCategories[catId]={}
			carCategories[catId]['cars']=[]
			carCategories[catId]['name']=catName
		
			print 'Processed: '+catId+' : '+catName


##============================
## EXTRACT CARS DATA
##============================
cars = {}
carFolders =  os.listdir(carMainFolder)

for folder in carFolders:
	dirName=carMainFolder+folder+'/'

	xmlFileUrl=dirName+folder+'.xml'
	imgFileUrl=dirName+folder+'-preview.jpg'


	if os.path.isfile(xmlFileUrl):

		if os.path.isfile(imgFileUrl):
			newImgUrl= './../img/cars/'+folder+'-preview.jpg'
			carImg= './img/cars/'+folder+'-preview.jpg'
			shutil.copyfile(imgFileUrl, newImgUrl)

		tree = ET.parse(xmlFileUrl)

		#and get the root of the xml tree
		root = tree.getroot()

		#car name
		carName=root.attrib['name']
		carId=folder

		#car category
		carCategory= root.findall("./section[@name='Car']/attstr[@name='category']")[0].attrib['val']
		
		#assign the car to a car categorie
		carCategories[carCategory]['cars'].append(carId)
		
		#populate the car object with all the infos of the car
		cars[carId]={}
		cars[carId]['id']=carId
		cars[carId]['name']=carName
		cars[carId]['img']=carImg
		cars[carId]['category']=carCategory

		print 'Processed: '+carId+' : '+carName

##============================
## EXTRACT TRACKS CATEGORY DATA
##============================
trackCategories = {}

##============================
## EXTRACT TRACKS DATA
##============================
tracks = {}
trackCategoryFolders =  os.listdir(trackMainFolder)

for category in trackCategoryFolders:
	categoryFolder=trackMainFolder+category+'/'
	
	if not os.path.isfile(trackMainFolder+category):
			
		categoryFolders = os.listdir(categoryFolder)

		#log car categories info
		trackCategories[category]={}
		trackCategories[category]['id']=category
		trackCategories[category]['name']=category
		trackCategories[category]['tracks']=[];
		

		for track in categoryFolders:
			#print categoryFolder+'\n'
			#print track+'\n\n'
			
			if not os.path.isfile(categoryFolder+track):
			
				trackFolder=categoryFolder+track+'/'
				xmlFileUrl=trackFolder+track+'.xml'

				#print categoryFolder+track
			
				if not os.path.isfile(categoryFolder+track):
					if os.path.isfile(xmlFileUrl):
						#print xmlFileUrl
						
						parser = ET.XMLParser()
						parser._parser.UseForeignDTD(True)
						parser.entity['default-surfaces'] = u'\u00A0'
						parser.entity['default-objects'] = u'\u00A0'
						tree = ET.parse(xmlFileUrl, parser=parser)

						#and get the root of the xml tree
						root = tree.getroot()

						#trackId=root.attrib['name']
						trackId=track
						trackName= root.findall("./section[@name='Header']/attstr[@name='name']")[0].attrib['val']
						trackCategory= root.findall("./section[@name='Header']/attstr[@name='category']")[0].attrib['val']
						imgFileUrl= trackFolder+'outline.png'
						
						if os.path.isfile(imgFileUrl):
							newImgUrl= './../img/tracks/'+track+'-outline.png'
							trackImg= './img/tracks/'+track+'-outline.png'
							shutil.copyfile(imgFileUrl, newImgUrl)
							
						#populate the car object with all the infos of the car
						tracks[trackId]={}
						tracks[trackId]['id']=trackId
						tracks[trackId]['name']=trackName
						tracks[trackId]['img']=trackImg
						tracks[trackId]['category']=trackCategory
						#
						trackCategories[category]['tracks'].append(trackId)

						print 'Processed: '+trackId+' : '+trackName


#save he carCategory info into a file
out_file = open("./../data/carCategories.txt","w")
out_file.write(str(carCategories))
out_file.close()

#save he cars info into a file
out_file = open("./../data/cars.txt","w")
out_file.write(str(cars))
out_file.close()

#save he carTrack info into a file
out_file = open("./../data/trackCategories.txt","w")
out_file.write(str(trackCategories))
out_file.close()

#save he tracks info into a file
out_file = open("./../data/tracks.txt","w")
out_file.write(str(tracks))
out_file.close()
