//Question class
class Question {
    constructor() {
        this.qstn;          //Question
        this.numAns = 2;    //Number of answers
        this.answers = [];  //Store answers
    }
};

//Survey class
class Survey {
    constructor() {
        this.title = "";
        this.desc = "";
        this.numQstn = 0;    //Number of questions
        this.qstnArr = [];   //Array of question objects     
    }
    //Get number of questions from survey
    getQ() {
        return this.numQstn;
    }
    //Set title
    setTitle(name) {
        this.title = name;
    }
    //Set description
    setDesc(detail) {
        this.desc = detail;
    }
    //Add question
    addQ() {
        this.numQstn++;                     //Increase question count
        this.qstnArr.push(new Question());  //Add new question to question array
    }
    //Remove question from survey
    delQ() {
        this.numQstn--;     //Decrease question count
        this.qstnArr.pop(); //Delete last question in array
    }
};

//Add question to survey form
function addQstn() {
    var numQ = srvy.getQ();     //Number of questions in survey
    numQ++;                     //Increase count

    //Add new question element to html page
    var q = qTemp.cloneNode(true);
    q.id = "qstn" + numQ;
    q.querySelector(".txt").innerHTML = "Question " + numQ;
    q.querySelector("#addBtn").setAttribute("onclick", "addAns(" + numQ + ")");
    q.querySelector("#delBtn").setAttribute("onclick", "delAns(" + numQ + ")");

    srvy.addQ();    //Add question to survey
    document.getElementById("survey").appendChild(q);
};

//Delete question from survey from
function delQstn() {
    //Only allowed if number of question on survey form exceeds 1
    if (srvy.numQstn > 1) {
        //Get node and destroy
        let node = document.getElementById("qstn" + srvy.numQstn);
        if (node.parentNode) {
            node.parentNode.removeChild(node);
        }
        srvy.delQ();    //Delete question from survey
    }
};

//Add an answer to a question (q = question number)
function addAns(q) {
    q = q-1;    //Convert to element value
    //Increase number of answers in the question
    srvy.qstnArr[q].numAns++;

    //Get answers class from specified question #
    var temp = document.querySelector("#qstn" + (q+1) + " > #answers");
    var ans = aTemp.cloneNode(true);
    ans.id = "ans" + srvy.qstnArr[q].numAns;
    ans.placeholder = "Answer " + srvy.qstnArr[q].numAns;

    temp.appendChild(ans);  //Add answer
};

//Delete an answer to a question
function delAns(q) {
    if (srvy.qstnArr[q-1].numAns > 2) {
        var cont = document.querySelector("#qstn" + q + " > #answers");
        var ans = document.querySelector("#qstn" + q + " #ans" + srvy.qstnArr[q-1].numAns);
        cont.removeChild(ans);
        srvy.qstnArr[q-1].numAns--;
    }
};

//Display error message when required fields are not shown
function showErr() {
    var err = document.getElementById("error");
    err.style.display = "block";                    //Toggle css display on 
    err.innerHTML = "*All fields required";         //Add & display error message
}

//When create survey button is clicked, save the survey values
function create() {
    var error = false;
    
    var sName = document.getElementById("surveyTitle").value.trim();    //Get survey title    
    var sDesc = document.getElementById("surveyDesc").value.trim();     //Get survey description
    
    //Check for empty input text
    if (sName === "" || sDesc === "") {
        error = true;
        if (sName === "") {
            var titleErr = document.getElementById("surveyTitle");
            titleErr.style.border = "2px solid red";
        }
        if (sDesc === "") {
            var descErr = document.getElementById("surveyDesc");
            descErr.style.border = "2px solid red";
        }
    }
    else {
        srvy.setTitle(sName);   //Set survey title
        srvy.setDesc(sDesc);    //Set survey description
    }
    
    //Loop every question
    for (var i=0; i<srvy.numQstn; i++) {
        //Get and set question text
        var q = document.querySelector("#qstn" + (i+1) + " > #qCtnt").value.trim();
        
        //Check for empty input text
        if (q === "") {
            error = true;
            var qErr = document.querySelector("#qstn" + (i+1) + " > #qCtnt");
            qErr.style.border = "2px solid red";
        }
        else {
            srvy.qstnArr[i].qstn = q;   //Set question text
        }
        
        //Loop every answer
        for (var j=0; j<srvy.qstnArr[i].numAns; j++) {
            //Get and set answer text
            var a = document.querySelector("#qstn" + (i+1) + " #ans" + (j+1)).value.trim();
            
            //Check for empty input text
            if (a === "") {
                error = true;
                var aErr = document.querySelector("#qstn" + (i+1) + " #ans" + (j+1));
                aErr.style.border = "2px solid red";
            }
            else {
                srvy.qstnArr[i].answers.push(a);    //Set answer text
            }
        }
    }
    
    //If there are no errors, save data
    if (!error) {
        var str = JSON.stringify(srvy);         //Convert object to JSON string
        //localStorage.setItem("Survey", str);  //Save to local storage
        document.cookie = "survey=" + str;      //Save to cookie
        window.location = "/SurveyEngine/admin/create.php"; //Redirect page
    }
    //Else show error message
    else {
        showErr();
        //Delete answer values that were successfuly pushed previously
        for (var i=0; i<srvy.numQstn; i++) {
            for (var j=0; j<srvy.qstnArr[i].numAns; j++) {
                srvy.qstnArr[i].answers.pop();
            }
        }
    }
    
    //Remove error markings if corrected by user
    if (sName !== "") { //Title
        document.getElementById("surveyTitle").style.border = "none";
    }
    if (sDesc !== "") { //Description
        document.getElementById("surveyDesc").style.border = "none";
    }
    for (var i=0; i<srvy.numQstn; i++) {    //Question
        var q = document.querySelector("#qstn" + (i+1) + " > #qCtnt");
        if (q.value !== "") {
            q.style.border = "none";
        }
        
        for (var j=0; j<srvy.qstnArr[i].numAns; j++) {    //Answer
            var a = document.querySelector("#qstn" + (i+1) + " #ans" + (j+1));
            if (a.value !== "") {
                a.style.border = "none";
            }
        }
    }
};

//Run when page loads            
window.onload = function () {
    //In order to add default questions/answers later
    qTemp = document.getElementById("qstn1");   //Default question
    aTemp = document.getElementById("ans1");    //Default answer
    qTemp.parentNode.removeChild(qTemp);        //Remove
    //Add first question
    addQstn();
};

var srvy = new Survey();  //Create survey
var qTemp;  //Store question
var aTemp;  //Store answer