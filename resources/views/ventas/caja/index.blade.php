@extends ('layouts.admin3')
@section('contenido')
<div class="col-md-9">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#activity" data-toggle="tab">Activity</a></li>
        <li><a href="#timeline" data-toggle="tab">Timeline</a></li>
        <li><a href="#settings" data-toggle="tab">Settings</a></li>
      </ul>
      <div class="tab-content">
        <div class="active tab-pane" id="activity">
          <!-- Post -->
          
          <!-- /.post -->
        </div>
        <!-- /.tab-pane -->
        <div class="tab-pane" id="timeline">
          <!-- The timeline -->
          
        </div>
        <!-- /.tab-pane -->

        <div class="tab-pane" id="settings">
         
        </div>
        <!-- /.tab-pane -->
      </div>
      <!-- /.tab-content -->
    </div>
    <!-- /.nav-tabs-custom -->
  </div>
  @endsection