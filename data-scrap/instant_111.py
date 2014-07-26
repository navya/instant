#!/usr/bin/python2
from __future__ import print_function
import sys
import urllib
import urllib2
for i in range(11100001,11129999):
	# Sending a get request to the URL
	html=urllib2.urlopen('http://oa.cc.iitk.ac.in:8181/Oa/Jsp/OAServices/IITk_SrchRes.jsp?typ=stud&numtxt=%d&sbm=Y' %i)
	# Reading the response
	html=html.read()

    # Finding the name for a roll number
	beg=html.find('<b>Name: </b>')+len("<b>Name: </b>")
	# If no result found
	if(beg==len("<b>Name: </b>")-1):
		continue
	else:
		end=html.find('</p>',beg)
		if(beg+39 == end):
			continue
		print ("NULL;", end="")
		print (str(i) + ";", end="")
		print (html[beg:end].strip().lower() + ";", end="")

    # Finding the program for a roll number
	beg=html.find('<b>Program: </b>')+len("<b>Program: </b>")
	# If no result found
	if(beg==len("<b>Program: </b>")-1):
		print ("none;", end="")
	else:
		end=html.find('</p>',beg)
		print (html[beg:end].strip().lower() + ";", end="")

    # Finding the Department for a roll number
	beg=html.find('<b>Department: </b>')+len("<b>Department: </b>")
	# If no result found
	if(beg==len("<b>Department: </b>")-1):
		print ("none;", end="")
	else:
		end=html.find('</p>',beg)
		print (html[beg:end].strip().lower() + ";", end="")

    # Find the hostel info for the current roll number
	beg=html.find('<b>Hostel Info: </b>')+len("<b>Hostel Info: </b>")
	if(beg==len("<b>Hostel Info: </b>")-1):
		print ("none;", end="")
	else:
		end=html.find('<br>',beg)
		print (html[beg:end].strip().lower() + ";", end="")

    # Finding the Gender for a roll number
	beg=html.find('<b> Gender:</b>')+len("<b> Gender:</b>")
	# If no result found
	if(beg==-1):
		print ("none;", end="")
	else:
		end=html.find('<b>',beg)
		print (html[beg:end].strip().lower() + ";", end="")

    # Finding the Email for a roll number
	beg=html.find('<b>E-Mail: </b>')+len('<b>E-Mail: </b>')
	beg=html.find('mailto:', beg)+len('mailto:')
	# If no result found
	if(beg==len('mailto:')-1):
		print ("none;", end="")
	else:
		end=html.find('"',beg)
		print (html[beg:end].strip().lower() + ";", end="")
    
    # Finding the Blood Group for a roll number
	beg=html.find('<b> Blood Group:</b>')+len("<b> Blood Group:</b>")
	# If no result found
	if(beg==-1):
		print ("none;", end="")
	else:
		end=html.find('<!--',beg)
		print (html[beg:end].strip().lower() + ";", end="")
        print ("India")


