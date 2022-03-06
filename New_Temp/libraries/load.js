<<<<<<< HEAD
/* The code in this file will be executed
   on page load. */

var nodbForum = {};
nodbForum.insertIntoInput = function(myField, myValue) {
    if (document.selection) {
        myField.focus();
        sel = document.selection.createRange();
        sel.text = myValue;
    }
    else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        myField.value = myField.value.substring(0, startPos)
            + myValue
            + myField.value.substring(endPos, myField.value.length);
    } else {
        myField.value += myValue;
    }
};
=======
/* The code in this file will be executed
   on page load. */

var nodbForum = {};
nodbForum.insertIntoInput = function(myField, myValue) {
    if (document.selection) {
        myField.focus();
        sel = document.selection.createRange();
        sel.text = myValue;
    }
    else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        myField.value = myField.value.substring(0, startPos)
            + myValue
            + myField.value.substring(endPos, myField.value.length);
    } else {
        myField.value += myValue;
    }
};
>>>>>>> 0dd6ba65130b774d8e078ba9c410e6bb02f22f53
nodbForum.hostname = location.hostname;