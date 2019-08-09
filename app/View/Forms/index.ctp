<div id="main-content">
<!-- BEGIN Page Title -->
<div class="page-title">
    <div>
        <h1><i class="icon-file-alt"></i> Form Components</h1>
        <h4>Simple and advance form elements</h4>
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
        <li class="active">Form Components</li>
    </ul>
</div>
<!-- END Breadcrumb -->

<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i> Grid System</h3>

                <div class="box-tool">
                    <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <form action="#" class="form-horizontal" id="validation-form">
                    <div class="control-group">
                        <label class="control-label">Input with Popover</label>

                        <div class="controls">
                            <input type="text" class="span6 show-popover" data-trigger="hover"
                                   data-content="Popover body goes here. Popover body goes here."
                                   data-original-title="Popover header"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Auto Complete</label>

                        <div class="controls">
                            <input type="text" class="span6" style="margin: 0 auto;" data-provide="typeahead"
                                   data-items="4"
                                   data-source="[&quot;Alabama&quot;,&quot;Alaska&quot;,&quot;Arizona&quot;,&quot;Arkansas&quot;,&quot;California&quot;,&quot;Colorado&quot;,&quot;Connecticut&quot;,&quot;Delaware&quot;,&quot;Florida&quot;,&quot;Georgia&quot;,&quot;Hawaii&quot;,&quot;Idaho&quot;,&quot;Illinois&quot;,&quot;Indiana&quot;,&quot;Iowa&quot;,&quot;Kansas&quot;,&quot;Kentucky&quot;,&quot;Louisiana&quot;,&quot;Maine&quot;,&quot;Maryland&quot;,&quot;Massachusetts&quot;,&quot;Michigan&quot;,&quot;Minnesota&quot;,&quot;Mississippi&quot;,&quot;Missouri&quot;,&quot;Montana&quot;,&quot;Nebraska&quot;,&quot;Nevada&quot;,&quot;New Hampshire&quot;,&quot;New Jersey&quot;,&quot;New Mexico&quot;,&quot;New York&quot;,&quot;North Dakota&quot;,&quot;North Carolina&quot;,&quot;Ohio&quot;,&quot;Oklahoma&quot;,&quot;Oregon&quot;,&quot;Pennsylvania&quot;,&quot;Rhode Island&quot;,&quot;South Carolina&quot;,&quot;South Dakota&quot;,&quot;Tennessee&quot;,&quot;Texas&quot;,&quot;Utah&quot;,&quot;Vermont&quot;,&quot;Virginia&quot;,&quot;Washington&quot;,&quot;West Virginia&quot;,&quot;Wisconsin&quot;,&quot;Wyoming&quot;]"/>

                            <p class="help-block">Start typing to auto complete!. E.g: California</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Email Address Input</label>

                        <div class="controls">
                            <div class="input-icon left">
                                <i class="icon-envelope"></i>
                                <input class="" type="text" id="email" placeholder="Email Address" class="input-xlarge" data-rule-required="true" data-rule-email="true" />
                            </div>
                        </div>
                    </div>
<!--                    <div class="control-group">-->
<!--                        <label class="control-label" for="email">Email Address:</label>-->
<!--                        <div class="controls">-->
<!--                            <div class="span12">-->
<!--                                <input type="email" name="email" id="email" class="input-xlarge" data-rule-required="true" data-rule-email="true" />-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="control-group">
                        <label class="control-label">Starts with years view</label>

                        <div class="controls">
                            <div class="input-append date date-picker" data-date="12-02-2012"
                                 data-date-format="dd-mm-yyyy" data-date-viewmode="years">
                                <input class="date-picker" size="16" type="text" value="12-02-2012"/><span
                                    class="add-on"><i class="icon-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Default Date Ranges</label>

                        <div class="controls">
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-calendar"></i></span>
                                <input type="text" class="date-range"/>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Currency Input</label>

                        <div class="controls">
                            <div class="input-prepend input-append">
                                <span class="add-on">$</span><input class="" type="text"/><span
                                    class="add-on">.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Default Dropdown</label>

                        <div class="controls">
                            <select class="span6" data-placeholder="Choose a Category" tabindex="1">
                                <option value="">Select...</option>
                                <option value="Category 1">Category 1</option>
                                <option value="Category 2">Category 2</option>
                                <option value="Category 3">Category 5</option>
                                <option value="Category 4">Category 4</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Radio Buttons</label>

                        <div class="controls">
                            <label class="radio inline">
                                <input type="radio" name="optionsRadios2" value="option1"/> Option 1
                            </label>
                            <label class="radio inline">
                                <input type="radio" name="optionsRadios2" value="option2" checked/> Option 2
                            </label>
                            <label class="radio inline">
                                <input type="radio" name="optionsRadios2" value="option2"/> Option 3
                            </label>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Checkbox</label>

                        <div class="controls">
                            <label class="checkbox inline">
                                <input type="checkbox" value=""/> Checkbox 1
                            </label>
                            <label class="checkbox inline">
                                <input type="checkbox" value=""/> Checkbox 2
                            </label>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Advanced</label>

                        <div class="controls">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="input-append">
                                    <div class="uneditable-input">
                                        <i class="icon-file fileupload-exists"></i>
                                        <span class="fileupload-preview"></span>
                                    </div>
                                               <span class="btn btn-file">
                                                   <span class="fileupload-new">Select file</span>
                                                   <span class="fileupload-exists">Change</span>
                                                   <input type="file" class="default"/>
                                               </span>
                                    <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Image Upload</label>

                        <div class="controls">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                    <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt=""/>
                                </div>
                                <div class="fileupload-preview fileupload-exists thumbnail"
                                     style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                <div>
                                               <span class="btn btn-file"><span
                                                       class="fileupload-new">Select image</span>
                                               <span class="fileupload-exists">Change</span>
                                               <input type="file" class="default"/></span>
                                    <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                </div>
                            </div>
                            <span class="label label-important">NOTE!</span>
                                         <span>
                                         Attached image thumbnail is
                                         supported in Latest Firefox, Chrome, Opera,
                                         Safari and Internet Explorer 10 only
                                         </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">WYSIWYG Editor</label>

                        <div class="controls">
                            <textarea name="editor" class="ckeditor span12" rows="6"></textarea>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Submit</button>
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
</div>