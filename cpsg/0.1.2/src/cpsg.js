/*
 * Copyright 2007 Yu-Jie Lin
 * 
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 3 of the License, or (at your option)
 * any later version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
 * more details.
 * 
 * You should have received a copy of the GNU General Public License along
 * with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * Author       : Yu-Jie Lin
 * Created Date : 2007-04-15 08:51:28
 * Website      : http://code.google.com/p/llbbsc/wiki/cpsg
 */

var LICENSES  = new Array();
var LANGUAGES = new Array();
var LANGUAGE_FIELDS = new Array();
var INFO_FIELDS = new Array();

/**
 * Initiates DOM elements when body loaded
 */
function OnLoad(){
	var ls
	var select
	var option
	var lf
	var infoFields
	// Licenses
	ls = document.getElementById("licenseSelector");
	if (ls==undefined){
		alert ("Can not find licenseSelector!");
		return;
		}

	select = document.createElement("select");
	select.id = "licenseSelect";
	select.onchange = OnLicenseSelected;
	for (var n=0;n<LICENSES.length;n++){
		option = document.createElement("option");
		option.value = n;
		option.appendChild(document.createTextNode(LICENSES[n].name));
		select.appendChild(option);
		}
	option = document.createElement("option");
	option.value = n;
	option.selected = true;
	option.appendChild(document.createTextNode("Customize"));
	select.appendChild(option);
	ls.appendChild(select);

	// Languages
	ls = document.getElementById("languagesSelector");
	if (ls==undefined){
		alert ("Can not find languageSelector!");
		return;
		}

	select = document.createElement("select");
	select.onchange = OnLanguageSelected;
	for (var n=0;n<LANGUAGES.length;n++){
		option = document.createElement("option");
		option.value = n;
		option.appendChild(document.createTextNode(LANGUAGES[n].name));
		select.appendChild(option);
		}
	option = document.createElement("option");
	option.value = n;
	option.selected = true;
	option.appendChild(document.createTextNode("Customize"));
	select.appendChild(option);
	ls.appendChild(select);

	// Language Fields
	lf = document.getElementById("languageFields");
	if (lf==undefined){
		alert ("Can not find languageFields!");
		return;
		}

	for (var n=0;n<LANGUAGE_FIELDS.length;n++){
		span = document.createElement("span");
		span.appendChild(document.createTextNode(LANGUAGE_FIELDS[n].description));
		span.className = "languageField";

		input = document.createElement("input");
		input.id = "lf_" + LANGUAGE_FIELDS[n].name;
		input.className = "languageField";

		lf.appendChild(span);
		lf.appendChild(input);

		br = document.createElement("br");
		br.className = "clear";
		lf.appendChild(br);
		}

	// Info Fields
	infoFields = document.getElementById("infoFields");
	if (infoFields==undefined){
		alert ("Can not find infoFields!");
		return;
		}

	for (var n=0;n<INFO_FIELDS.length;n++){
		span = document.createElement("span");
		span.appendChild(document.createTextNode(INFO_FIELDS[n].description));
		span.className = "infoField";

		input = document.createElement("input");
		input.id = "if_" + INFO_FIELDS[n].name;
		input.className = "infoField";
		input.value = (INFO_FIELDS[n].defaultValue) ? INFO_FIELDS[n].defaultValue
												    : INFO_FIELDS[n].description;

		infoFields.appendChild(span);
		infoFields.appendChild(input);

		br = document.createElement("br");
		br.className = "clear";
		infoFields.appendChild(br);
		}
	}

/**
 * Fired when one of license clicked
 */
function OnLicenseSelected(){
	if (this.value < LICENSES.length){
		lt = document.getElementById("licenseTemplate");
		lt.value = LICENSES[this.value].statement;
		}
	}

/**
 * Fired when download link clicked
 */
function OnDownload(){
	licenseNo = document.getElementById("licenseSelect").value;
	if (licenseNo < LICENSES.length){
		window.open(LICENSES[licenseNo].link, "_blank");
		}
	}

/**
 * Fired when one of language clicked
 */
function OnLanguageSelected(){
	if (this.value < LANGUAGES.length){
		for (var n=0;n<LANGUAGE_FIELDS.length;n++){
			field = document.getElementById("lf_" + LANGUAGE_FIELDS[n].name);
			field.value = LANGUAGES[this.value][LANGUAGE_FIELDS[n].name];
			}
		}
	}

/**
 * Substitutes
 */
function SubstituteInfos(licText){
	for (var n=0;n<INFO_FIELDS.length;n++){
		withText = document.getElementById("if_" + INFO_FIELDS[n].name).value;
		regexp = new RegExp("\\[" + INFO_FIELDS[n].name + "\\]", "g");
		licText = licText.replace(regexp, withText);
		}
	return licText;
	}

/**
 * Wraps
 * Wraps text, and keeps indent
 */
function Wraps(text, textWidth){
	var newText = "";
	lines = text.split(/\n/);
	for (lineNo in lines){
		var line = lines[lineNo];
		if (line == ""){
			if (lineNo < lines.length - 1)
				newText += "\n";
			continue;
			}
		// " * " will be treated as a list.
		var indent = line.match(/^[\t ]*([\*] *)?/)[0];
		line = line.substring(indent.length, line.length);
		var width = textWidth - indent.length;

		while(line.length>0){
			if (line.length > width){
				// find space within width
				var regexp = new RegExp("^(.{0," + width + "})[\\t ]");
				ret = regexp.exec(line);
				if (ret){
					newText += indent + ret[1] + "\n"; //[0] includes \t or " "
					line = line.substring(ret[0].length, line.length);
					}
				else{
					// The first word of line is too long, find this word in complete
					regexp = new RegExp("^(.{" + width + ",}?)[\\t ]");
					ret = regexp.exec(line);
					if (ret){
						newText += indent + ret[1] + "\n"; //[0] includes \t or " "
						line = line.substring(ret[0].length, line.length);
						}
					else{
						// The rest is only one word
						if (lineNo == lines.length - 1)
							// Last line doesn't need return
							newText += indent + line;
						else
							newText += indent + line + "\n";
						break;
						}
					}
				}
			else{
				// Last part of current line
				if (lineNo == lines.length - 1)
					// Last line doesn't need return
					newText += indent + line;
				else
					newText += indent + line + "\n";
				break;
				}
			// Only first line of wrapped line can have "*"
			indent = indent.replace("*", " ");
			}
		}
	return newText;
	}

/**
 * Prefix
 */
function Prefix(text, prefixStr){
	return prefixStr + text.replace(/\n/g, "\n" + prefixStr);
	}
/**
 * Generate Result
 */
function Generate(){
/*
	text = "A\nB\nC";
	alert(text.replace(/\n/g, "\n * "));
	return*/
	var licText = document.getElementById("licenseTemplate").value;
	// Substitutes with Infos
	licText = SubstituteInfos(licText);
	// Wraps lines
	textWidth = parseInt(document.getElementById("if_WRAP_WIDTH").value) -
				document.getElementById("lf_beforeLine").value.length;
	licText = Wraps(licText, textWidth);
	// Prefix comment 
	prefix = document.getElementById("lf_beforeLine").value;
	licText = Prefix(licText, prefix);
	//alert(licText);
	// Before and After
	before = document.getElementById("lf_before").value + "\n";
	after = "\n" + document.getElementById("lf_after").value;
	licText = before + licText + after;

	// Update
	var licResult = document.getElementById("licenseResult");
	licResult.value = licText;
	}

/**
 * License class
 * @param name
 * @param statement
 */
function License(name, statement, link){
	this.name = name;
	this.statement = statement;
	this.link = link;
	}

/**
 * Help to add a license to LICENSES array
 * @param name
 * @param statement
 */
function AddLicense(name, statement, link){
	LICENSES.push(new License(name, statement, link));
	}

/* ***************************************************************************
 * LICENSES
 * ***************************************************************************/

AddLicense(
"Apache License, Version 2.0",
"\
Copyright [COPY_YEARS], [COPY_HOLDER]\n\
\n\
Licensed under the Apache License, Version 2.0 (the \"License\"); you may not use this file except in compliance with the License. You may obtain a copy of the License at\n\
\n\
   http://www.apache.org/licenses/LICENSE-2.0\n\
\n\
Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an \"AS IS\" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.\
",
"http://www.apache.org/licenses/LICENSE-2.0"
);

AddLicense(
"BSD License, New",
"\
Copyright (c) [COPY_YEARS], [COPY_HOLDER]\n\
\n\
All rights reserved.\n\
\n\
Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:\n\
\n\
	* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.\n\
	* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.\n\
	* Neither the name of the [ORGANIZATION] nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.\n\
\n\
THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS \"AS IS\" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.\
",
"http://www.opensource.org/licenses/bsd-license.php"
);

AddLicense(
"GNU General Public License version 2",
"\
Copyright [COPY_YEARS] [COPY_HOLDER]\n\
\n\
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.\n\
\n\
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.\n\
\n\
You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA\
",
"http://www.gnu.org/licenses/old-licenses/gpl-2.0.html"
);

AddLicense(
"GNU General Public License version 2 (Part of)",
"\
Copyright [COPY_YEARS] [COPY_HOLDER]\n\
\n\
This file is part of [PROGRAM].\n\
\n\
[PROGRAM] is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.\n\
\n\
[PROGRAM] is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.\n\
\n\
You should have received a copy of the GNU General Public License along with [PROGRAM]; if not, write to the Free Software\n\
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA\
",
"http://www.gnu.org/licenses/old-licenses/gpl-2.0.html"
);

AddLicense(
"GNU Lesser General Public License version 2.1",
"\
Copyright [COPY_YEARS] [COPY_HOLDER]\n\
\n\
This program is free software; you can redistribute it and/or modify it under the terms of the GNU Lesser General Public License as published by the Free Software Foundation; either version 2.1 of the License, or (at your option) any later version.\n\
\n\
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Lesser General Public License for more details.\n\
\n\
You should have received a copy of the GNU Lesser General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA\
",
"http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html"
);

AddLicense(
"GNU Lesser General Public License version 2.1 (Part of)",
"\
Copyright [COPY_YEARS] [COPY_HOLDER]\n\
\n\
This file is part of [PROGRAM].\n\
\n\
[PROGRAM] is free software; you can redistribute it and/or modify it under the terms of the Lesser GNU General Public License as published by the Free Software Foundation; either version 2.1 of the License, or (at your option) any later version.\n\
\n\
[PROGRAM] is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Lesser General Public License for more details.\n\
\n\
You should have received a copy of the GNU Lesser General Public License along with [PROGRAM]; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA\
",
"http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html"
);

AddLicense(
"GNU General Public License version 3",
"\
Copyright [COPY_YEARS] [COPY_HOLDER]\n\
\n\
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.\n\
\n\
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.\n\
\n\
You should have received a copy of the GNU General Public License along with this program.  If not, see <http://www.gnu.org/licenses/>.\
",
"http://www.gnu.org/licenses/gpl.html"
);

AddLicense(
"GNU General Public License version 3 (Part of)",
"\
Copyright [COPY_YEARS] [COPY_HOLDER]\n\
\n\
This file is part of [PROGRAM].\n\
\n\
[PROGRAM] is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.\n\
\n\
[PROGRAM] is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.\n\
\n\
You should have received a copy of the GNU General Public License along with this program.  If not, see <http://www.gnu.org/licenses/>.\
",
"http://www.gnu.org/licenses/gpl.html"
);

AddLicense(
"GNU Lesser General Public License version 3",
"\
Copyright [COPY_YEARS] [COPY_HOLDER]\n\
\n\
This program is free software; you can redistribute it and/or modify it under the terms of the GNU Lesser General Public License as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.\n\
\n\
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Lesser General Public License for more details.\n\
\n\
You should have received a copy of the GNU Lesser General Public License along with this program.  If not, see <http://www.gnu.org/licenses/>.\
",
"http://www.gnu.org/licenses/lgpl.html"
);

AddLicense(
"GNU Lesser General Public License version 3 (Part of)",
"\
Copyright [COPY_YEARS] [COPY_HOLDER]\n\
\n\
This file is part of [PROGRAM].\n\
\n\
[PROGRAM] is free software; you can redistribute it and/or modify it under the terms of the Lesser GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.\n\
\n\
[PROGRAM] is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Lesser General Public License for more details.\n\
\n\
You should have received a copy of the GNU Lesser General Public License along with this program.  If not, see <http://www.gnu.org/licenses/>.\
",
"http://www.gnu.org/licenses/lgpl.html"
);

AddLicense(
"MIT License",
"\
Copyright (c) [COPY_YEARS] [COPY_HOLDER]\n\
\n\
Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the \"Software\"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:\n\
\n\
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.\n\
\n\
THE SOFTWARE IS PROVIDED \"AS IS\", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.\
",
"http://www.opensource.org/licenses/mit-license.html"
);

/* ***************************************************************************
 * LANGUAGE_FIELD
 * ***************************************************************************/

/**
 * LanguageField class
 * @param name
 * @param description
 */
function LanguageField(name, description){
	this.name = name;
	this.description = description;
	}

/**
 * Help to add a language field to LANGUAGE_FIELD array
 * @param name
 * @param statement
 */
function AddLanguageField(name, description){
	LANGUAGE_FIELDS.push(new LanguageField(name, description));
	}

AddLanguageField("name", "Name of Language");
AddLanguageField("before", "Before Statement");
AddLanguageField("beforeLine", "Before Each Line");
AddLanguageField("after", "After Statement");

/* ***************************************************************************
 * LANGUAGES
 * ***************************************************************************/

/**
 * Language class
 * @param name
 */
function Language(argv){
	for (var n=0;n<argv.length;n++)
		this[LANGUAGE_FIELDS[n].name] = argv[n];
	}

/**
 * Help to add a language to LANGUAGES array
 * @param name
 * @param statement
 */
function AddLanguage(argv){
	LANGUAGES.push(new Language(argv));
	}

AddLanguage([
"C++ Style 1",
"/*\n",
" * ",
" */"
]);

AddLanguage([
"C++ Style 2",
"/*******************************************************************************\n",
" * ",
" ******************************************************************************/"
]);

AddLanguage([
"Python Style 1",
"",
"## ",
""
]);

AddLanguage([
"Python Style 2",
"################################################################################\n",
"## ",
"################################################################################"
]);

/* ***************************************************************************
 * INFO_FIELDS
 * ***************************************************************************/

/**
 * LanguageField class
 * @param name
 * @param description
 */
function InfoField(name, description, defaultValue){
	this.name = name;
	this.description = description;
	this.defaultValue = defaultValue;
	}

/**
 * Help to add a language field to LANGUAGE_FIELD array
 * @param name
 * @param statement
 */
function AddInfoField(name, description, defaultValue){
	INFO_FIELDS.push(new InfoField(name, description, defaultValue));
	}

AddInfoField("COPY_YEARS", "Copyright Years", new Date().getFullYear());
AddInfoField("COPY_HOLDER", "Copyright Holder");
AddInfoField("ORGANIZATION", "Organization Name");
AddInfoField("PROGRAM", "Program Name");
AddInfoField("WRAP_WIDTH", "Word Wrap Width", 78);
