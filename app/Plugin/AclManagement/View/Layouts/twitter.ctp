<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>CakePHP Authentication and ACL Management Plugin</title>
    <meta name="description" content="CakePHP Authentication and ACL Management Plugin">
    <meta name="author" content="vu khanh truong">


    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
   
    <script src="https://www.google.com/jsapi?key=ABQIAAAAa44qXAhHZFTYANZzBZYvahSJNboRFY-KWCF1_jCiST2eg5RhLRSZtibOiJfIYeMGYIUbzDeGeg5hww" type="text/javascript"></script>
    <script type="text/javascript">
        google.load("jquery", "1.7.1");
    </script>
    
    <!-- Le styles -->
    <?php echo $this->Html->css('/acl_management/css/twitter/bootstrap.min');?>
    <?php echo $this->Html->script(array(
        '/acl_management/js/twitter/bootstrap-alerts',
        '/acl_management/js/twitter/bootstrap-dropdown'
    ));?>
    <style type="text/css">
      /* Override some defaults */
      html, body {
        background-color: #eee;
      }
      body {
        padding-top: 40px; /* 40px to make the container go all the way to the bottom of the topbar */
      }
      .container > footer p {
        text-align: center; /* center align it with the container */
      }
      .container {
        width: 820px; /* downsize our container to make the content feel a bit tighter and more cohesive. NOTE: this removes two full columns from the grid, meaning you only go to 14 columns and not 16. */
      }

      /* The white background content wrapper */
      .container > .content {
        background-color: #fff;
        padding: 20px;
        margin: 0 -20px; /* negative indent the amount of the padding to maintain the grid system */
        -webkit-border-radius: 0 0 6px 6px;
           -moz-border-radius: 0 0 6px 6px;
                border-radius: 0 0 6px 6px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.15);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.15);
                box-shadow: 0 1px 2px rgba(0,0,0,.15);
      }

      /* Page header tweaks */
      .page-header {
        background-color: #f5f5f5;
        padding: 20px 20px 10px;
        margin: -20px -20px 20px;
      }

      /* Styles you shouldn't keep as they are for displaying this base example only */
      .content .span16,
      .content .span7,
      .content .span7,
      .content .span10,
      .content .span4 {
        min-height: 500px;
      }
      /* Give a quick and non-cross-browser friendly divider */
      .content .span7,
      .content .span4 {
        margin-left: 0;
        padding-left: 19px;
        border-left: 1px solid #eee;
      }

      .topbar .btn {
        border: 0;
      }

    </style>

  </head>

  <body>

    <div class="topbar">
      <div class="fill">
        <div class="container">
          <a class="brand" href="/demo">ACL Management</a>
          <?php echo $this->element('AclManagement.top-nav');?>         
        </div>
      </div>
    </div>

    <div class="container">
      <div class="content">
        <div class="row">
            <?php echo $this->Session->flash(); ?>
            <?php echo $this->Session->flash('auth'); ?>
            <?php echo $content_for_layout; ?>
        </div>
      </div>

      <footer>
        <p><a href="mailto:vukhanhtruong@gmail.com">vukhanhtruong@gmail.com</a> &copy; 2012</p>
      </footer>

    </div> <!-- /container -->

  </body>
</html>
