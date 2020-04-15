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

function showModal($itemID, $type)
{
  echo <<<"EOT"
  <button type="button" class="btn btn-$type" data-toggle="modal" data-target="#modalItem$itemID">
                                    Show Details
  </button>

  <div class="modal fade" id="modalItem$itemID" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              ...
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Save changes</button>
          </div>
      </div>
  </div>
  </div>
  EOT;
}
