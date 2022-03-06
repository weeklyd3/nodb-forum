<<<<<<< HEAD
function finishXHR(text, id, element) {
	const files = JSON.parse(text);
	if (files.length === 0) {
		return "No files match your search.";
	} else {
		const ul = document.createElement('ul');
		for (var i = 0; i < files.length; i++) {
			var optiondiv = document.createElement('div');
			var option = document.createElement('label');
			var button = document.createElement('input');
			button.type = 'radio';
			button.required = 'required';
			if (files[i].name === element.value) button.setAttribute('checked', 'checked');
			button.value = files[i].name;
			button.name = 'file-picker-' + id;
			button.id = 'file-picker-' + id + "-" + files[i].name;
			ul.appendChild(button);
			option.setAttribute('for', 'file-picker-' + id + "-" + files[i].name);
			var fileName = document.createElement('div');
			fileName.textContent = files[i].name;
			option.appendChild(fileName);
			var mod = document.createElement('div');
			mod.classList.add('smaller');
			mod.textContent = "(Modified " + files[i].mod + ")";
			var mime = document.createElement('div');
			mime.textContent = "MIME type " + files[i].mime;
			mime.classList.add('smaller');
			option.appendChild(mod);
			option.appendChild(mime);
			optiondiv.appendChild(option);
			ul.appendChild(optiondiv);
		}
		return ul.innerHTML;
	}
}
function loadFiles(search, files, id, element, mimetype = null) {
	files.innerHTML = "Please wait..."
	var oReq = new XMLHttpRequest();
	oReq.addEventListener("load", function() {
		files.innerHTML = finishXHR(this.responseText, id, element);
	});
	url = '/api/files';
	firstParamEntered = false;
	if (mimetype !== null) {
		url += "?mime=" + encodeURIComponent(mimetype);
		firstParamEntered = true;
	}
	if (search !== null) {
		url += firstParamEntered ? "&" : "?";
		url += "search=";
		url += encodeURIComponent(search);
	}
	oReq.open("GET", url);
	oReq.send();
}
function initFilePicker(element, mimetype = null) {
	element.size = 30;
	element.readOnly = true;
	element.placeholder = 'Select a file (no file selected)';
	element.classList.add('filepicker');
	var id = element.id + "-picker";
	var popup = document.createElement('div');
	popup.classList.add('overlay');
	popup.classList.add('filepicker-dialog');
	popup.setAttribute('id', id);
	popup.innerHTML = '<h3>Select a file</h3>';
	if (mimetype !== null) {
		popup.innerHTML += "<p>The file's mime type should start with <code>" + mimetype + "</code>.</p>";
	}
	var buttons = document.createElement('div');
	var files = document.createElement('form');
	files.classList.add('files');
	files.classList.add('box');
	files.id = id + "-filebox";
	files.action = 'javascript:;';
	files.innerHTML = 'Please wait...';
	popup.appendChild(files);
	var refreshButton = document.createElement('button');
	refreshButton.addEventListener('click', function() {
		loadFiles(null, files, id, element, mimetype);
	});
	refreshButton.textContent = 'Refresh';
	buttons.appendChild(refreshButton);
	var whitespace = document.createElement('span');
	whitespace.innerHTML = ' ';
	buttons.appendChild(whitespace);
	var cancelButton = document.createElement('button');
	cancelButton.textContent = 'Cancel';
	cancelButton.addEventListener('click', function() {
		popup.style.display = 'none';
		element.placeholder = 'Select a file (no file selected)';
	});
	var submitButton = document.createElement('button');
	var moreWhitespace = document.createElement('span');
	moreWhitespace.textContent = ' ';
	submitButton.setAttribute('form', id + "-filebox");
	submitButton.textContent = "Select file";
	submitButton.style.fontWeight = 700;
	buttons.appendChild(cancelButton);
	buttons.appendChild(moreWhitespace);
	buttons.appendChild(submitButton);
	files.addEventListener('submit', function() {
		const submitted = document.forms[id + '-filebox'];
		var file = submitted.elements['file-picker-' + id]['value'];
		element.value = file;
		popup.style.display = 'none';
		element.classList.add('fileselected');
		element.placeholder = 'Select a file (no file selected)';
	});
	popup.appendChild(buttons);
	document.body.appendChild(popup);
	element.addEventListener('focus', function() {
		popup.style.display = 'block';
		loadFiles(null, files, id, element, mimetype);
	});
	var closeButton = document.createElement('button');
	closeButton.type = 'button';
	closeButton.innerHTML = '(&times;)';
	var closeButtonWSpace = document.createElement('span');
	closeButtonWSpace.innerHTML = ' ';
	closeButtonWSpace.appendChild(closeButton);
	closeButton.addEventListener('click', function() {
		element.value = '';
		element.classList.remove('fileselected');
	});
	var openButton = document.createElement('button');
	openButton.type = 'button';
	openButton.innerHTML = "(open)";
	openButton.addEventListener('click', function() {
		window.open('/viewfile.php?filename=' + encodeURIComponent(element.value));
	});
	var openButtonWSpace = document.createElement('span');
	openButtonWSpace.innerHTML = ' ';
	openButtonWSpace.appendChild(openButton);
	if (!globalThis.noOpenButton) {closeButtonWSpace.appendChild(openButtonWSpace)};
	element.after(closeButtonWSpace);
	element.addEventListener('blur', function() {
	});
=======
function finishXHR(text, id, element) {
	const files = JSON.parse(text);
	if (files.length === 0) {
		return "No files match your search.";
	} else {
		const ul = document.createElement('ul');
		for (var i = 0; i < files.length; i++) {
			var optiondiv = document.createElement('div');
			var option = document.createElement('label');
			var button = document.createElement('input');
			button.type = 'radio';
			button.required = 'required';
			if (files[i].name === element.value) button.setAttribute('checked', 'checked');
			button.value = files[i].name;
			button.name = 'file-picker-' + id;
			button.id = 'file-picker-' + id + "-" + files[i].name;
			ul.appendChild(button);
			option.setAttribute('for', 'file-picker-' + id + "-" + files[i].name);
			var fileName = document.createElement('div');
			fileName.textContent = files[i].name;
			option.appendChild(fileName);
			var mod = document.createElement('div');
			mod.classList.add('smaller');
			mod.textContent = "(Modified " + files[i].mod + ")";
			var mime = document.createElement('div');
			mime.textContent = "MIME type " + files[i].mime;
			mime.classList.add('smaller');
			option.appendChild(mod);
			option.appendChild(mime);
			optiondiv.appendChild(option);
			ul.appendChild(optiondiv);
		}
		return ul.innerHTML;
	}
}
function loadFiles(files, id, element, mimetype = null) {
	files.innerHTML = "Please wait..."
	var oReq = new XMLHttpRequest();
	oReq.addEventListener("load", function() {
		files.innerHTML = finishXHR(this.responseText, id, element);
	});
	url = '/api/files';
	if (mimetype !== null) {
		url += "?mime=" + encodeURIComponent(mimetype);
	}
	oReq.open("GET", url);
	oReq.send();
}
function initFilePicker(element, mimetype = null) {
	element.readOnly = true;
	element.size = 30;
	element.placeholder = 'Select a file (no file selected)';
	element.classList.add('filepicker');
	var id = element.id + "-picker";
	var popup = document.createElement('div');
	popup.classList.add('overlay');
	popup.setAttribute('id', id);
	popup.innerHTML = '<h3>Select a file</h3>';
	if (mimetype !== null) {
		popup.innerHTML += "<p>The file's mime type should start with <code>" + mimetype + "</code>.</p>";
	}
	var buttons = document.createElement('div');
	var files = document.createElement('form');
	files.classList.add('files');
	files.classList.add('box');
	files.id = id + "-filebox";
	files.action = 'javascript:;';
	files.innerHTML = 'Please wait...';
	popup.appendChild(files);
	var refreshButton = document.createElement('button');
	refreshButton.addEventListener('click', function() {
		loadFiles(files, id, element, mimetype);
	});
	refreshButton.textContent = 'Refresh';
	buttons.appendChild(refreshButton);
	var whitespace = document.createElement('span');
	whitespace.innerHTML = ' ';
	buttons.appendChild(whitespace);
	var cancelButton = document.createElement('button');
	cancelButton.textContent = 'Cancel';
	cancelButton.addEventListener('click', function() {
		popup.style.display = 'none';
	});
	var submitButton = document.createElement('button');
	var moreWhitespace = document.createElement('span');
	moreWhitespace.textContent = ' ';
	submitButton.setAttribute('form', id + "-filebox");
	submitButton.textContent = "Select file";
	submitButton.style.fontWeight = 700;
	buttons.appendChild(cancelButton);
	buttons.appendChild(moreWhitespace);
	buttons.appendChild(submitButton);
	files.addEventListener('submit', function() {
		const submitted = document.forms[id + '-filebox'];
		var file = submitted.elements['file-picker-' + id]['value'];
		element.value = file;
		popup.style.display = 'none';
		element.classList.add('fileselected');
	});
	popup.appendChild(buttons);
	document.body.appendChild(popup);
	element.addEventListener('focus', function() {
		popup.style.display = 'block';
		loadFiles(files, id, element, mimetype);
	});
	var closeButton = document.createElement('button');
	closeButton.type = 'button';
	closeButton.innerHTML = '(&times;)';
	var closeButtonWSpace = document.createElement('span');
	closeButtonWSpace.innerHTML = ' ';
	closeButtonWSpace.appendChild(closeButton);
	closeButton.addEventListener('click', function() {
		element.value = '';
		element.readOnly = true;
		element.classList.remove('fileselected');
	});
	var openButton = document.createElement('button');
	openButton.type = 'button';
	openButton.innerHTML = "(open)";
	openButton.addEventListener('click', function() {
		window.open('/viewfile.php?filename=' + encodeURIComponent(element.value));
	});
	var openButtonWSpace = document.createElement('span');
	openButtonWSpace.innerHTML = ' ';
	openButtonWSpace.appendChild(openButton);
	if (!globalThis.noOpenButton) {closeButtonWSpace.appendChild(openButtonWSpace)};
	element.after(closeButtonWSpace);
>>>>>>> 0dd6ba65130b774d8e078ba9c410e6bb02f22f53
}