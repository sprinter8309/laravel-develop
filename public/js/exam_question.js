var question_form = document.querySelector(".exam-question-form");
var button_begin = document.querySelector(".exam-question-button-begin");
var button_next = document.querySelector(".exam-question-button-next");
var button_previous = document.querySelector(".exam-question-button-previous");
var button_begin = document.querySelector(".exam-question-button-begin");
var button_finish = document.querySelector(".exam-question-button-finish");

var technic_info_question_id = document.querySelector(".exam-question-technic-info-quest-id");

function initialize() 
{
    question_form.onsubmit = function(event) {        
        event.preventDefault();
    }

    button_next.onclick = function() {     
        question_form.action = "/exam/question/next";
        question_form.submit();
    }
    
    button_previous.onclick = function() {     
        question_form.action = "/exam/question/previous";
        question_form.submit();
    }
 
    button_begin.onclick = function() {     
        question_form.action = "/exam/question/begin";
        question_form.submit();
    }    
 
    button_finish.onclick = function() {     
        question_form.action = "/exam/finish";
        question_form.submit();
    }    
}

document.addEventListener("DOMContentLoaded", initialize);
