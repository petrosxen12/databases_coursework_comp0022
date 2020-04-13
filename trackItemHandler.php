<?php


function showSuccessPopUp()
{
  $tr = isset($_GET['trackitem']);
  if ($tr) {
    echo <<<"EOT"
        <div class="alert alert-success" role="alert">
        <strong>Item Saved</strong> You can check its progress in the tracked items section
      </div>
    EOT;
  }
}


function showUntrackPopUp()
{
  echo <<<"EOT"
        <div class="alert alert-danger" role="alert">
        <strong>Item unsaved</strong> Removed from tracked items
      </div>
    EOT;
}
