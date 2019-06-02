function validForm() {
    //Flag to check if all questions have been answered
    var valid = true;

    //For each question
    var questions = document.querySelectorAll(".qstn");
    for (var i=0; i<questions.length; i++) {
        //Flag to check if answer has been checked
        var answered = false;
        
        //For each answer
        var ans = questions[i].querySelectorAll("input[type='radio']");
        for (var j=0; j<ans.length; j++) {   
            //Check if radio box is checked
            if (ans[j].checked === true) {
                console.log(ans[j]);
                answered = true;
            }
        }
        
        //If a question is not answered, output error sign for that question
        if (!answered) {
            var qErr = document.getElementById("q"+(i+1));
            qErr.style.border = "2px solid red";
            //Survey is incomplete
            valid = false;
        }
        
        if (answered) {
            var q = document.getElementById("q"+(i+1));
            q.style.border = "none";
        }
    }

    //Submit form if valid
    if (valid) {
        var form = document.getElementById("survey");
        form.submit();
    }
    else {
        //Display an error
        var err = document.getElementById("error");
        err.style.display = "block";                    //Toggle css display on 
        err.innerHTML = "*Please answer all questions";
    }
}