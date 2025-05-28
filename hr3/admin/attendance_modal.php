<!-- Pending Approvals Modal -->
<div class="modal fade" id="pendingApprovalsModal" tabindex="-1" aria-labelledby="pendingApprovalsLabel" aria-hidden="true" data-bs-backdrop="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="pendingApprovalsLabel">Pending Approvals</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Modal content -->
        <div class=" px-2 m-4">
          <div>
            <h4>Request ID: <span class="fs-5">#123456</span></h4>
            <h4>Employee Name: <span class="fs-5">John Doe</span></h4>
            <h4>Date: <span class="fs-5">2023-10-01</span></h4>
            <h4>Type: <span class="fs-5">Manual Clock Out</span></h4>
          </div>
          <br>
          <div>
            <h4>Employee Reason: <span class="fs-5">"Forgot to Clock Out"</span></h4>
          </div>
          <div>
            <h4>Actions:
              <span>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approveModal">Approve</button>
                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal">Reject</button>
              </span>
            </h4>
            <h4>Comments: <span><input class="form-control fs-5" type="text" id=""></span></h4>
          </div>
          <br>
          <div>
            <button class="btn btn-primary">Submit Decision</button>
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
