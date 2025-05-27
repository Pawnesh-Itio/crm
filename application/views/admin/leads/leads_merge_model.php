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
              <input type="checkbox" class="field-checkbox" name="country_code[]" value="<?= htmlspecialchars($lead['country_code'] ?? '') ?>" data-field="country_code" data-column="<?= $index ?>">
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

<script>
(function() {
  // Fields that allow multiple selection
  const fields = ['email', 'website', 'phonenumber', 'country_code'];
  // Store click order for each field (global order for submission)
  const clickOrder = {
    email: [],
    website: [],
    phonenumber: [],
    country_code: []
  };

  // Helper: update highlights and text for all fields
  function updateFieldHighlights() {
    fields.forEach(field => {
      // Remove unchecked from order array
      clickOrder[field] = clickOrder[field].filter(input => input.checked);
      // Reset all highlights and remove highlight labels
      document.querySelectorAll('.field-checkbox[data-field="' + field + '"]').forEach(input => {
        const label = input.closest('p').querySelector('.field-label');
        if (label) {
          label.classList.remove('field-selected-primary', 'field-selected-additional');
          // Remove any existing highlight label
          const next = label.nextElementSibling;
          if (next && next.classList.contains('field-highlight-label')) {
            next.remove();
          }
        }
      });
      // Highlight in click order and add text
      clickOrder[field].forEach((input, idx) => {
        const label = input.closest('p').querySelector('.field-label');
        if (label) {
          let highlightText = '';
          if (idx === 0) {
            label.classList.add('field-selected-primary');
            highlightText = 'Primary';
          } else {
            label.classList.add('field-selected-additional');
            highlightText = 'Additional';
          }
          // Remove any existing highlight label
          const next = label.nextElementSibling;
          if (next && next.classList.contains('field-highlight-label')) {
            next.remove();
          }
          // Add new highlight label
          if (highlightText) {
            const span = document.createElement('span');
            span.className = 'field-highlight-label';
            span.style.marginLeft = '8px';
            span.style.fontWeight = 'bold';
            span.style.color = idx === 0 ? '#007bff' : '#6c757d';
            span.textContent = highlightText;
            label.parentNode.insertBefore(span, label.nextSibling);
          }
        }
      });
    });
  }

  // Checkbox click: always put first checked at 0th key, others after
  document.addEventListener('change', function (e) {
    if (e.target.matches('.field-checkbox')) {
      const field = e.target.getAttribute('data-field');
      if (!fields.includes(field)) return;
      // Remove from array if present
      clickOrder[field] = clickOrder[field].filter(input => input !== e.target);
      if (e.target.checked) {
        if (clickOrder[field].length === 0) {
          clickOrder[field].unshift(e.target); // first checked always at 0
        } else {
          clickOrder[field].push(e.target);
        }
      }
      updateFieldHighlights();
    }
  });

  // Check All logic: select all checkboxes for this card/lead
  window.toggleCheckAll = function(btn, cardId) {
    fields.forEach(function(field) {
      // Get all checkboxes for this field in this card
      const checkboxes = Array.from(document.querySelectorAll('.field-checkbox[data-field="' + field + '"][data-column="' + cardId + '"]'));
      // Remove all these from click order
      clickOrder[field] = clickOrder[field].filter(input => !checkboxes.includes(input));
      if (btn.checked) {
        // Check all
        checkboxes.forEach(cb => { cb.checked = true; });
        // Add to click order: first checked in this card goes to 0th key if none selected yet, others after
        if (checkboxes.length > 0) {
          if (clickOrder[field].length === 0) {
            clickOrder[field].unshift(checkboxes[0]);
            for (let i = 1; i < checkboxes.length; i++) {
              clickOrder[field].push(checkboxes[i]);
            }
          } else {
            checkboxes.forEach(cb => { clickOrder[field].push(cb); });
          }
        }
      } else {
        // Uncheck all
        checkboxes.forEach(cb => { cb.checked = false; });
        // No need to add to clickOrder, as they're already removed above
      }
    });
    // Also check/uncheck all other checkboxes and radios in this card (excluding .check-all)
    const card = btn.closest('.card');
    if (card) {
      // All checkboxes except .check-all and except the multi-select fields
      card.querySelectorAll('input[type="checkbox"]:not(.check-all):not([data-field]), input[type="radio"]').forEach(function(input) {
        input.checked = btn.checked;
      });
    }
    updateFieldHighlights();
  };

  // On form submit, create hidden inputs in click order and submit via AJAX
  document.getElementById('leads_merge_form').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent default form submission
    fields.forEach(function(field) {
      // Remove any previously added hidden inputs
      document.querySelectorAll('.dynamic-' + field).forEach(function(el) { el.remove(); });
      // Remove name from all checkboxes to prevent duplicate submission
      document.querySelectorAll('.field-checkbox[name="' + field + '[]"]').forEach(function(cb) {
        cb.removeAttribute('name');
      });
      // Add hidden inputs in click order
      clickOrder[field].forEach(function(cb) {
        if (cb.checked) {
          const input = document.createElement('input');
          input.type = 'hidden';
          input.name = field + '[]';
          input.value = cb.value;
          input.className = 'dynamic-' + field;
          e.target.appendChild(input);
        }
      });
    });
    // Collect form data
    const form = e.target;
    const formData = new FormData(form);
    // Submit via fetch
    fetch(form.action, {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.json().catch(() => response.text()))
    .then(data => {
      // Handle response (customize as needed)
      if (typeof data === 'object' && data.success) {
        // Success case
        alert_float('success','Leads merged successfully!');
        // redirect to leads page or update UI as needed
        window.location.href = '<?= admin_url('leads') ?>'; // Redirect to leads page
        // Optionally close modal or update UI here
      } else {
         // Show error with line breaks if multiple errors
         var msg = (data.message || data);
         if (typeof msg === 'string') {
           msg = msg.replace(/\n/g, '<br>');
         }
         alert_float('danger', msg);
      }
    })
    .catch(err => {
      alert_float('danger', 'An error occurred: ' + err);
    });
  });
})();
</script>
