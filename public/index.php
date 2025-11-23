<?php

require_once '../src/database.php';

initialize_database();

$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'new':
        $entry = null;
        require 'new_entry_form.php';
        break;
    case 'edit':
        $db = get_db_connection();
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $db->prepare("SELECT * FROM daily_symptom_log WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $entry = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$entry) {
            header('Location: index.php');
            exit;
        }

        require 'new_entry_form.php';
        break;
    case 'save':
        $db = get_db_connection();
        $stmt = $db->prepare("
            INSERT INTO daily_symptom_log (
                entry_date, entry_time, tremor_present, tremor_regions, tremor_intensity,
                tremor_duration_min, tremor_context, tremor_notes, rigor_present, rigor_regions,
                rigor_intensity, sleep_quality, sleep_hours_duration, overall_wellbeing_score
            ) VALUES (
                :entry_date, :entry_time, :tremor_present, :tremor_regions, :tremor_intensity,
                :tremor_duration_min, :tremor_context, :tremor_notes, :rigor_present, :rigor_regions,
                :rigor_intensity, :sleep_quality, :sleep_hours_duration, :overall_wellbeing_score
            )
        ");

        $tremor_regions = isset($_POST['tremor_regions']) ? implode(', ', $_POST['tremor_regions']) : '';
        $rigor_regions = isset($_POST['rigor_regions']) ? implode(', ', $_POST['rigor_regions']) : '';

        $stmt->execute([
            ':entry_date' => $_POST['entry_date'],
            ':entry_time' => $_POST['entry_time'],
            ':tremor_present' => isset($_POST['tremor_present']) ? 1 : 0,
            ':tremor_regions' => $tremor_regions,
            ':tremor_intensity' => $_POST['tremor_intensity'],
            ':tremor_duration_min' => $_POST['tremor_duration_min'],
            ':tremor_context' => $_POST['tremor_context'],
            ':tremor_notes' => $_POST['tremor_notes'],
            ':rigor_present' => isset($_POST['rigor_present']) ? 1 : 0,
            ':rigor_regions' => $rigor_regions,
            ':rigor_intensity' => $_POST['rigor_intensity'],
            ':sleep_quality' => $_POST['sleep_quality'],
            ':sleep_hours_duration' => $_POST['sleep_hours_duration'],
            ':overall_wellbeing_score' => $_POST['overall_wellbeing_score'],
        ]);

        header('Location: index.php');
        break;
    case 'update':
        $db = get_db_connection();
        $id = (int)($_POST['id'] ?? 0);

        if (!$id) {
            header('Location: index.php');
            exit;
        }

        $stmt = $db->prepare("
            UPDATE daily_symptom_log SET
                entry_date = :entry_date,
                entry_time = :entry_time,
                tremor_present = :tremor_present,
                tremor_regions = :tremor_regions,
                tremor_intensity = :tremor_intensity,
                tremor_duration_min = :tremor_duration_min,
                tremor_context = :tremor_context,
                tremor_notes = :tremor_notes,
                rigor_present = :rigor_present,
                rigor_regions = :rigor_regions,
                rigor_intensity = :rigor_intensity,
                sleep_quality = :sleep_quality,
                sleep_hours_duration = :sleep_hours_duration,
                overall_wellbeing_score = :overall_wellbeing_score
            WHERE id = :id
        ");

        $tremor_regions = isset($_POST['tremor_regions']) ? implode(', ', $_POST['tremor_regions']) : '';
        $rigor_regions = isset($_POST['rigor_regions']) ? implode(', ', $_POST['rigor_regions']) : '';

        $stmt->execute([
            ':entry_date' => $_POST['entry_date'],
            ':entry_time' => $_POST['entry_time'],
            ':tremor_present' => isset($_POST['tremor_present']) ? 1 : 0,
            ':tremor_regions' => $tremor_regions,
            ':tremor_intensity' => $_POST['tremor_intensity'],
            ':tremor_duration_min' => $_POST['tremor_duration_min'],
            ':tremor_context' => $_POST['tremor_context'],
            ':tremor_notes' => $_POST['tremor_notes'],
            ':rigor_present' => isset($_POST['rigor_present']) ? 1 : 0,
            ':rigor_regions' => $rigor_regions,
            ':rigor_intensity' => $_POST['rigor_intensity'],
            ':sleep_quality' => $_POST['sleep_quality'],
            ':sleep_hours_duration' => $_POST['sleep_hours_duration'],
            ':overall_wellbeing_score' => $_POST['overall_wellbeing_score'],
            ':id' => $id,
        ]);

        header('Location: index.php');
        break;
    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);

        if ($id) {
            $db = get_db_connection();
            $stmt = $db->prepare("DELETE FROM daily_symptom_log WHERE id = :id");
            $stmt->execute([':id' => $id]);
        }

        header('Location: index.php');
        break;
    case 'list':
    default:
        $db = get_db_connection();
        $stmt = $db->query("SELECT * FROM daily_symptom_log ORDER BY entry_date DESC");
        $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <!DOCTYPE html>
        <html lang="de">
        <head>
            <meta charset="UTF-8">
            <title>Tagebuch</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
            <h1>Tagebuch</h1>
            <a href="index.php?action=new">Neuer Eintrag</a>
            <table border="1" style="width:100%; margin-top: 1em;">
                <thead>
                    <tr>
                        <th>Datum</th>
                        <th>Zeit</th>
                        <th>Tremor</th>
                        <th>Steifheit</th>
                        <th>Schlafqualität</th>
                        <th>Befinden</th>
                        <th>Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entries as $entry): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($entry['entry_date']); ?></td>
                            <td><?php echo htmlspecialchars($entry['entry_time']); ?></td>
                            <td><?php echo $entry['tremor_present'] ? 'Ja' : 'Nein'; ?></td>
                            <td><?php echo $entry['rigor_present'] ? 'Ja' : 'Nein'; ?></td>
                            <td><?php echo htmlspecialchars($entry['sleep_quality']); ?></td>
                            <td><?php echo htmlspecialchars($entry['overall_wellbeing_score']); ?></td>
                            <td>
                                <a href="index.php?action=edit&id=<?php echo (int)$entry['id']; ?>">Bearbeiten</a>
                                <form action="index.php?action=delete" method="post" style="display:inline;" onsubmit="return confirm('Diesen Eintrag löschen?');">
                                    <input type="hidden" name="id" value="<?php echo (int)$entry['id']; ?>">
                                    <button type="submit">Löschen</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </body>
        </html>
        <?php
        break;
}
