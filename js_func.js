// JavaScript Document
window.onpageshow = function(event) {
	"use strict";
    if (event.persisted) {
        window.location.reload();
    }
};
