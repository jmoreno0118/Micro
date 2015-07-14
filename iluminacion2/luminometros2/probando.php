<?php
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<script type="text/javascript" src="../../includes/jquery-validation-1.13.1/lib/jquery.js"></script>
<script type="text/javascript" src="../../includes/jquery-validation-1.13.1/lib/jquery-1.11.1.js"></script>
<script type="text/javascript" src="../../includes/jquery-validation-1.13.1/dist/jquery.validate.js"></script>
<script type="text/javascript" src="../../includes/jquery-validation-1.13.1/dist/additional-methods.js"></script>
<script type="text/javascript">
$("#work_form").validate({
    rules: {
        "work_emp_name[0]": {
            required: true
        }
    }
});




var tpl = $("#form_tpl").html();

var counter = 1;

$("form").on("click", ".add_employer", function (e) {
    e.preventDefault();
    var tplData = {
        i: counter
    };
    $("#word_exp_area").append(tpl(tplData));
    counter += 1;
    $('.work_emp_name').each(function () {
        $(this).rules("add", {
            required: true
        });
    });
});</script>
</head>
<body>
<form enctype="multipart/form-data" action="" method="post" id="work_form" class="form-horizontal">
    <div id="word_exp_area">
        <div class="control-group">
            <label class="control-label" for='emp_name'>Employer Name</label>
            <div class="controls">
                <input type="text" name="work_emp_name[0]" class="work_emp_name" value="" />
            </div>
        </div>
        <div class="control-group">
            <label></label>
            <div class="controls">
                <div class="btn-toolbar pull-right">
                    <div class="btn-group"> <a class="btn add_employer" href="#"><i class="icon-plus"></i>Add Employer</a>
                    </div>
                </div>
            </div>
        </div>
        <hr>
            <input type="submit" value="Save" class="btn btn-primary"/>
</form>
<script type="text/html" id="form_tpl">
    <div class = "control-group" > <label class = "control-label"
    for = 'emp_name' > Employer Name </label>
        <div class="controls">
            <input type="text" name="work_emp_name[<%= element.i %>]" class="work_emp_name"
                   value=""/ > </div>
    </div>
</script>
</body>
</html>