
    <!-- BEGIN Page Title -->
    <div class="page-title">
        <div>
            <h1><i class="icon-file-alt"></i> Form Validation</h1>
            <h4>Form validation sample</h4>
        </div>
    </div>
    <!-- END Page Title -->

    <!-- BEGIN Breadcrumb -->
    <div id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="icon-home"></i>
                <a href="index.html">Home</a>
                <span class="divider"><i class="icon-angle-right"></i></span>
            </li>
            <li class="active">Form Validation</li>
        </ul>
    </div>
    <!-- END Breadcrumb -->

    <!-- BEGIN Main Content -->
    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="box-title">
                    <h3><i class="icon-reorder"></i> Validation Form</h3>
                    <div class="box-tool">
                        <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
                        <a data-action="close" href="#"><i class="icon-remove"></i></a>
                    </div>
                </div>
                <div class="box-content">
                    <form action="#" class="form-horizontal" id="validation-form" method="get">
                        <div class="control-group">
                            <label class="control-label" for="username">Username:</label>
                            <div class="controls">
                                <div class="span12">
                                    <input type="text" name="username" id="username" class="input-xlarge" data-rule-required="true" data-rule-minlength="3" />
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="email">Email Address:</label>
                            <div class="controls">
                                <div class="span12">
                                    <input type="email" name="email" id="email" class="input-xlarge" data-rule-required="true" data-rule-email="true" />
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="password">Password:</label>
                            <div class="controls">
                                <div class="span12">
                                    <input type="password" name="password" id="password" class="input-xlarge" data-rule-required="true" data-rule-minlength="6" />
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="confirm-password">Confirm Password:</label>
                            <div class="controls">
                                <div class="span12">
                                    <input type="password" name="confirm-password" id="confirm-password" class="input-xlarge" data-rule-required="true" data-rule-minlength="6" data-rule-equalTo="#password" />
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="control-group">
                            <label for="select" class="control-label">Select</label>
                            <div class="controls">
                                <select class="input-xlarge" name="select" id="select" data-rule-required="true">
                                    <option value="">-- Please select --</option>
                                    <option value="1">Option-1</option>
                                    <option value="2">Option-2</option>
                                    <option value="3">Option-3</option>
                                    <option value="4">Option-4</option>
                                    <option value="5">Option-5</option>
                                    <option value="6">Option-6</option>
                                    <option value="7">Option-7</option>
                                    <option value="8">Option-8</option>
                                    <option value="9">Option-9</option>
                                    <option value="10">Option-10</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="urlfield" class="control-label">URL</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" placeholder="Enter valid URL" name="urlfield" id="urlfield" data-rule-url="true" data-rule-required="true">
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="minlengthfield" class="control-label">Minlength</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" placeholder="At least 3 characters" name="minlengthfield" id="minlengthfield" data-rule-minlength="3" data-rule-required="true">
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="maxlengthfield" class="control-label">Maxlength</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" placeholder="Max 6 characters" name="maxlengthfield" id="maxlengthfield" data-rule-maxlength="6" data-rule-required="true">
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="datefield" class="control-label">Date</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" placeholder="YYYY-MM-DD" name="datefield" id="datefield" data-rule-dateISO="true" data-rule-required="true">
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="numberfield" class="control-label">Number</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" placeholder="Only numbers" name="numberfield" id="numberfield" data-rule-number="true" data-rule-required="true">
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="digitsfield" class="control-label">Digits</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" placeholder="Only digits" name="digitsfield" id="digitsfield" data-rule-digits="true" data-rule-required="true">
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="creditcardfield" class="control-label">Creditcard</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" placeholder="Enter valid creditcard. ex: 446-667-651" name="creditcardfield" id="creditcardfield" data-rule-creditcard="true" data-rule-required="true">
                            </div>
                        </div>
                        <div class="form-actions">
                            <input type="submit" class="btn btn-primary" value="Submit">
                            <button type="button" class="btn">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END Main Content -->

    <footer>
        <p>2013 © FLATY Admin Template.</p>
    </footer>

    <a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>
