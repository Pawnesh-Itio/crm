<!-- Modal Header -->
<div class="modal-header">
  <h5 class="modal-title text-center" id="mergeModal">Lead Merge</h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span>&times;</span>
  </button>
</div>

<!-- Merge Form -->
<form action="<?= admin_url('leads/merge_leads') ?>" method="post" id="leads_merge_form">
  <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
  
  <div class="modal-body">
    <div class="row">
      <?php foreach ($leads as $index => $lead): ?>
        <input type="hidden" name="lead_ids[]" value="<?= (int)$lead['id'] ?>">
        <div class="col-md-6">
          <div class="card mb-3 p-3 border">
            <h5>
              Lead ID: <?= (int)$lead['id'] ?>
              <input type="checkbox" class="check-all float-right" onclick="toggleCheckAll(this, <?= $index ?>)"> Check All
            </h5>

            <p>
              <input type="radio" name="name" value="<?= htmlspecialchars($lead['name'] ?? '') ?>">
              <strong>Name:</strong> <?= htmlspecialchars($lead['name'] ?? 'N/A') ?>
            </p>

            <p>
              <input type="checkbox" class="field-checkbox" name="email[]" value="<?= htmlspecialchars($lead['email'] ?? '') ?>" data-field="email" data-column="<?= $index ?>">
              <strong>Email:</strong> <span class="field-label"><?= htmlspecialchars($lead['email'] ?? 'N/A') ?></span>
            </p>

            <p>
              <input type="checkbox" class="field-checkbox" name="website[]" value="<?= htmlspecialchars($lead['website'] ?? '') ?>" data-field="website" data-column="<?= $index ?>">
              <strong>Website:</strong> <span class="field-label"><?= htmlspecialchars($lead['website'] ?? 'N/A') ?></span>
            </p>

            <p>
              <input type="checkbox" class="field-checkbox" name="phonenumber[]" value="<?= htmlspecialchars($lead['phonenumber'] ?? '') ?>" data-field="phonenumber" data-column="<?= $index ?>">
              <strong>Phone:</strong> <span class="field-label"><?= htmlspecialchars($lead['phonenumber'] ?? 'N/A') ?></span>
            </p>
            <p>
              <input type="checkbox" class="field-checkbox" name="country_code[]" value="<?= htmlspecialchars($lead['country_code'] ?? '') ?>" data-field="phonenumber" data-column="<?= $index ?>">
              <strong>Country Code:</strong> <span class="field-label"><?= htmlspecialchars($lead['country_code'] ?? 'N/A') ?></span>
            </p>

            <p><input type="radio" name="country" value="<?= htmlspecialchars($lead['country'] ?? '') ?>"><strong>Country:</strong> <?= htmlspecialchars($lead['country_name'] ?? 'N/A') ?></p>
            <p><input type="radio" name="address" value="<?= htmlspecialchars($lead['address'] ?? '') ?>"><strong>Address:</strong> <?= htmlspecialchars($lead['address'] ?? 'N/A') ?></p>
            <p><input type="radio" name="company" value="<?= htmlspecialchars($lead['company'] ?? '') ?>"><strong>Company:</strong> <?= htmlspecialchars($lead['company'] ?? 'N/A') ?></p>
            <p><input type="radio" name="status" value="<?= htmlspecialchars($lead['status'] ?? '') ?>"><strong>Status:</strong> <?= htmlspecialchars($lead['status_name'] ?? 'N/A') ?></p>
            <p><input type="radio" name="source" value="<?= htmlspecialchars($lead['source'] ?? '') ?>"><strong>Source:</strong> <?= htmlspecialchars($lead['source'] ?? 'N/A') ?></p>
            <p><input type="radio" name="assigned" value="<?= htmlspecialchars($lead['assigned'] ?? '') ?>"><strong>Assigned:</strong> <?= htmlspecialchars(($lead['firstname'] ?? '') . ' ' . ($lead['lastname'] ?? '')) ?></p>
            <p><input type="radio" name="description" value="<?= htmlspecialchars($lead['description'] ?? '') ?>"><strong>Description:</strong> <?= htmlspecialchars($lead['description'] ?? 'N/A') ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="modal-footer">
    <button type="submit" class="btn btn-success">Merge and Create New Lead</button>
  </div>
</form>
