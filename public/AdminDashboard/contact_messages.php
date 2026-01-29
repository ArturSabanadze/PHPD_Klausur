<?php
// LOAD STORAGE FILE
$storageFile = '../../../../Frontend/src/Data/messages.txt';

// Load data
$data = file_exists($storageFile)
    ? unserialize(file_get_contents($storageFile))
    : [];
?>

<h1>Ticket Übersicht</h1>

<div class="ticket-list">
    <h2>Tickets</h2>

    <?php if (empty($data)): ?>
        <p>Keine Tickets vorhanden.</p>
    <?php else: ?>
        <?php foreach ($data as $caseId => $case): ?>
            <div class="ticket-item">
                <strong>Ticket:</strong> <?= htmlspecialchars($caseId) ?><br>
                <strong>Erstellt:</strong> <?= htmlspecialchars($case['created_at']) ?><br>
                <!-- Use data-case for JS -->
                <a href="#" class="open-ticket" data-case="<?= htmlspecialchars($caseId) ?>">Ticket anzeigen</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- MODAL WINDOW -->
<div id="ticketModal" class="modal-overlay">
    <div class="modal">
        <span class="modal-close" onclick="closeModal()">×</span>
        <div id="ticketContent"></div>
    </div>
</div>

<script>
    // Close modal function
    function closeModal() {
        document.getElementById('ticketModal').style.display = 'none';
    }

    // Pass PHP tickets data to JS
    let tickets = <?php echo json_encode($data); ?>;

    // Add click listener for all ticket links
    document.querySelectorAll('.open-ticket').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault(); // prevent page reload
            let caseId = this.dataset.case;

            if (tickets[caseId]) {
                let content = `<h2>Ticket: ${caseId}</h2>`;
                tickets[caseId]['messages'].forEach(function (msg) {
                    content += `
                    <div class="message-box">
                        <strong>Zeit:</strong> ${msg.timestamp}<br>
                        <strong>Name:</strong> ${msg.name}<br>
                        <strong>E-Mail:</strong> ${msg.email}<br><br>
                        <strong>Nachricht:</strong><br>
                        ${msg.message.replace(/\n/g, "<br>")}
                    </div>
                    `;
                });

                document.getElementById('ticketContent').innerHTML = content;
                document.getElementById('ticketModal').style.display = 'flex';
            }
        });
    });
</script>