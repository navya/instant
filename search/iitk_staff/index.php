<?php include("functions/functions.php");?>
<?php $visitors_count = visitor(); ?>
<!DOCTYPE HTML>
<html lang='en'>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>IITK - Instant Faculty Search</title>
<script type="text/javascript" src="js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<?php if(valid_ip()){ ?>
<script type="text/javascript" src="js/request.js"></script>
<?php }?>
<link href="../style.css" rel="stylesheet" type="text/css" />
<link href="chosen.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href='../favicon.ico' /> 
</head>

<body>
<div align="center"><h2><a href='../'>Student Search</a> <a href='../iitk_staff' style='text-decoration:none;' class='active'>Faculty/Employee Search</a> <a href="../faq.php">FAQs</a> </h2></div>
<div id="container">
<form id="form" action="index.php" method="post" >
<label for="pf_no">PF No:</label><input type="text" id="pf_no" name="pf_no" value="" autocomplete="off" autofocus="autofocus" />
<label for="name">Name:</label><input type="text" name="name" value="" id="name" />
<label for="login">Login:</label><input type="text" name="email" value="" autocomplete="off" id="login" />
<label for="dept">Department</label>
<select name="dept" id="dept" class="dept">
<optgroup label="All">
<option selected value="all">All</option>
</optgroup>
<optgroup label="Engineering">
<option value="AE">Aerospace Engineering</option>
<option value="BSBE">Bio Sciences &amp; Bioengineering</option>
<option value="CHE">Chemical Engineering</option>
<option value="CE">Civil Engineering</option>
<option value="CSE">Computer Sci. &amp; Engineering</option>
<option value="EE">Electrical Engineering</option>
<option value="IME">Industrial &amp; Management Engg.</option>
<option value="MME/MSE">Materials &amp; Metallurgical Engg.</option>
<option value="MME/MSE">Material Science &amp; Engg.</option>
<option value="ME">Mechanical Engineering</option>
</optgroup>
<optgroup label="Humanities">
<option value="economics">Humanities &amp; Social Sciences</option>
</optgroup>
<optgroup label="Science">
<option value="chemistry">Chemistry</option>
<option value="Mathematics and Statistics">Mathematics & Statistics</option>
<option value="physics">Physics</option>
<option value="Statistics">Statistics</option>
</optgroup>
</select>
<label for="desig">Designation</label>
<select name="desig" id="desig" style="text-transform:capitalize" class="desig" >
<option value='all'> All               </option>
<option> Professor                     </option>
<option> Assistant professor           </option>
<option> Associate professor           </option>
<option> Asst. prof. on contract       </option>
<option> Visiting professor            </option>
<option> Deputy director               </option>
<option> Sr.p.t. instructor            </option>
<option> Apprentice trainee            </option>
<option> Assistant engineer            </option>
<option> Assistant librarian           </option>
<option> Assistant registrar           </option>
<option> Asst. security officer        </option>
<option> Asst.exe. engineer. (elec.)   </option>
<option> Asstt. care taker             </option>
<option> Attendant                     </option>
<option> Ayah                          </option>
<option> Bus conductor                 </option>
<option> Catering manager       	  </option>
<option> Chief scientific officer      </option>
<option> Cleaner                       </option>
<option> Computer engineer		       </option>
<option> Cook                          </option>
<option> Deputy registrar              </option>
<option> Director                      </option>
<option> Dresser                       </option>
<option> Driver                        </option>
<option> Engine driver (s.g.)          </option>
<option> Executive engineer (civil)    </option>
<option> Helper                        </option>
<option> Hemoeopathic consultant (pt)  </option>
<option> Instrumentation engineer      </option>
<option> Jr. superintedent             </option>
<option> Jr. tech. superintendent      </option>
<option> Jr.technician                 </option>
<option> Junior assistant              </option>
<option> Junior engineer               </option>
<option> Junior technician             </option>
<option> Labour advisor                </option>
<option> Legal advisor                 </option>
<option> Librarian                     </option>
<option> Library information officer   </option>
<option> Mali                          </option>
<option> Medical officer               </option>
<option> Peon                          </option>
<option> Pharmacist                    </option>
<option> Physical training instructor  </option>
<option> Post doctoral fellow          </option>
<option> Principal                     </option>
<option> Principal computer engg.      </option>
<option> Principal medical officer     </option>
<option> Principal research engineer   </option>
<option> Registrar                     </option>
<option> Research engg.		           </option>
<option> S.l.i.a                       </option>
<option> Sanitory inspector            </option>
<option> Scientific officer		       </option>
<option> Security guard                </option>
<option> Security officer              </option>
<option> Senior assistant              </option>
<option> Senior attendant              </option>
<option> Senior driver                 </option>
<option> Senior lab assistant          </option>
<option> Sr. aircraft maint. engineer  </option>
<option> Sr. assistant                 </option>
<option> Sr. computer engineer         </option>
<option> Sr. draftsman                 </option>
<option> Sr. driver (s.g.)             </option>
<option> Sr. research engineer         </option>
<option> Sr. scientific officer        </option>
<option> Sr. tech. superintendent      </option>
<option> Sr. technician                </option>
<option> Staff nurse                   </option>
<option> Superintendent                </option>
<option> Suprintending engineer        </option>
<option> Teacher                       </option>
<option> Technical officer (s.g.)      </option>
<option> Technical superintendent      </option>
</select>
<!--
<label for="gender"></label>
<input type="radio" name="gender" value="both" id='both' checked="checked"/>Both
<input type="radio" name="gender" value="M" id='male' />Male
<input type="radio" name="gender" value="F" id='female' />Female-->
</form>
<p style="margin:10px auto 0;width:690px;" class="count"><? echo $visitors_count; ?> visitors since 07 Nov 2011</p>
<div id="results"> 
	<div class="qA">
		<p>Disable Adobe Flash Extension to work it properly if you are using chrome.</p>
	</div>
</div>
</div>
<button class='back-top' style="margin-right:20px;margin-bottom:20px;float:right;position:fixed;bottom:10px;right:10px">Back to Top</button>
<div id='hidden' style='display:none'></div>
<div id="footer">
<p>&copy;search.junta.iitk.ac.in, 2010</p>
</div>
</body>
</html>