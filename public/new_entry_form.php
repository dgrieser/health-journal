<?php
$isEdit = !empty($entry);
$entryData = $entry ?? [];
$tremor_regions_selected = [];
$rigor_regions_selected = [];

if (!empty($entryData['tremor_regions'])) {
    $tremor_regions_selected = array_filter(array_map('trim', explode(',', $entryData['tremor_regions'])));
}

if (!empty($entryData['rigor_regions'])) {
    $rigor_regions_selected = array_filter(array_map('trim', explode(',', $entryData['rigor_regions'])));
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title><?php echo $isEdit ? 'Eintrag bearbeiten' : 'Neuer Tagebucheintrag'; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1><?php echo $isEdit ? 'Eintrag bearbeiten' : 'Neuer Tagebucheintrag'; ?></h1>
    <form action="index.php?action=<?php echo $isEdit ? 'update' : 'save'; ?>" method="post">
        <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?php echo (int)$entryData['id']; ?>">
        <?php endif; ?>
        <fieldset>
            <legend>Metadaten</legend>
            <label for="entry_date">Datum:</label>
            <input type="date" id="entry_date" name="entry_date" value="<?php echo htmlspecialchars($entryData['entry_date'] ?? date('Y-m-d'), ENT_QUOTES); ?>" required>
            <label for="entry_time">Uhrzeit:</label>
            <input type="time" id="entry_time" name="entry_time" value="<?php echo htmlspecialchars($entryData['entry_time'] ?? date('H:i'), ENT_QUOTES); ?>">
        </fieldset>

        <h2>A. MOTORISCHE SYMPTOME</h2>

        <fieldset>
            <legend>1. Tremor (Zittern)</legend>
            <label><input type="checkbox" name="tremor_present" value="1" <?php echo !empty($entryData['tremor_present']) ? 'checked' : ''; ?>> Tremor vorhanden?</label>
            <div class="checkbox-group">
                Betroffene Körperregion(en):<br>
                <label><input type="checkbox" name="tremor_regions[]" value="Hand links" <?php echo in_array('Hand links', $tremor_regions_selected) ? 'checked' : ''; ?>> Hand links</label>
                <label><input type="checkbox" name="tremor_regions[]" value="Hand rechts" <?php echo in_array('Hand rechts', $tremor_regions_selected) ? 'checked' : ''; ?>> Hand rechts</label>
                <label><input type="checkbox" name="tremor_regions[]" value="Kinn/Lippe" <?php echo in_array('Kinn/Lippe', $tremor_regions_selected) ? 'checked' : ''; ?>> Kinn/Lippe</label>
                <label><input type="checkbox" name="tremor_regions[]" value="Bein" <?php echo in_array('Bein', $tremor_regions_selected) ? 'checked' : ''; ?>> Bein</label>
                <label><input type="checkbox" name="tremor_regions[]" value="Kopf" <?php echo in_array('Kopf', $tremor_regions_selected) ? 'checked' : ''; ?>> Kopf</label>
            </div>
            <label for="tremor_intensity">Intensität (1-10):</label>
            <input type="number" id="tremor_intensity" name="tremor_intensity" min="1" max="10" value="<?php echo htmlspecialchars($entryData['tremor_intensity'] ?? '', ENT_QUOTES); ?>">
            <label for="tremor_duration_min">Dauer (Minuten):</label>
            <input type="number" id="tremor_duration_min" name="tremor_duration_min" value="<?php echo htmlspecialchars($entryData['tremor_duration_min'] ?? '', ENT_QUOTES); ?>">
            <label>Auslöser/Situation:</label>
            <select name="tremor_context">
                <option value="">Bitte wählen</option>
                <option value="In Ruhe" <?php echo ($entryData['tremor_context'] ?? '') === 'In Ruhe' ? 'selected' : ''; ?>>In Ruhe</option>
                <option value="Nur bei Bewegung" <?php echo ($entryData['tremor_context'] ?? '') === 'Nur bei Bewegung' ? 'selected' : ''; ?>>Nur bei Bewegung</option>
                <option value="Bei Stress" <?php echo ($entryData['tremor_context'] ?? '') === 'Bei Stress' ? 'selected' : ''; ?>>Bei Stress</option>
                <option value="Nach Kaffee/Energiedrinks" <?php echo ($entryData['tremor_context'] ?? '') === 'Nach Kaffee/Energiedrinks' ? 'selected' : ''; ?>>Nach Kaffee/Energiedrinks</option>
            </select>
            <label for="tremor_notes">Besonderheiten:</label>
            <textarea id="tremor_notes" name="tremor_notes"><?php echo htmlspecialchars($entryData['tremor_notes'] ?? '', ENT_QUOTES); ?></textarea>
        </fieldset>

        <fieldset>
            <legend>2. Steifheit/Rigor (Muskelspannung)</legend>
            <label><input type="checkbox" name="rigor_present" value="1" <?php echo !empty($entryData['rigor_present']) ? 'checked' : ''; ?>> Steifheit vorhanden?</label>
            <div class="checkbox-group">
                Betroffene Körperregion(en):<br>
                <label><input type="checkbox" name="rigor_regions[]" value="Nackenbereich" <?php echo in_array('Nackenbereich', $rigor_regions_selected) ? 'checked' : ''; ?>> Nackenbereich</label>
                <label><input type="checkbox" name="rigor_regions[]" value="Schulter(n)" <?php echo in_array('Schulter(n)', $rigor_regions_selected) ? 'checked' : ''; ?>> Schulter(n)</label>
            </div>
            <label for="rigor_intensity">Intensität (1-10):</label>
            <input type="number" id="rigor_intensity" name="rigor_intensity" min="1" max="10" value="<?php echo htmlspecialchars($entryData['rigor_intensity'] ?? '', ENT_QUOTES); ?>">
        </fieldset>

        <h2>B. NICHT-MOTORISCHE SYMPTOME</h2>
        <fieldset>
            <legend>10. Schlafstörungen</legend>
            <label for="sleep_quality">Schlafqualität:</label>
            <select id="sleep_quality" name="sleep_quality">
                <option value="Gut / Erholsam" <?php echo ($entryData['sleep_quality'] ?? '') === 'Gut / Erholsam' ? 'selected' : ''; ?>>Gut / Erholsam</option>
                <option value="Ausreichend" <?php echo ($entryData['sleep_quality'] ?? '') === 'Ausreichend' ? 'selected' : ''; ?>>Ausreichend</option>
                <option value="Schlecht / Nicht erholsam" <?php echo ($entryData['sleep_quality'] ?? '') === 'Schlecht / Nicht erholsam' ? 'selected' : ''; ?>>Schlecht / Nicht erholsam</option>
            </select>
            <label for="sleep_hours_duration">Schlafdauer (Stunden):</label>
            <input type="number" id="sleep_hours_duration" name="sleep_hours_duration" step="0.5" value="<?php echo htmlspecialchars($entryData['sleep_hours_duration'] ?? '', ENT_QUOTES); ?>">
        </fieldset>

        <fieldset>
            <legend>Gesamtbefindlichkeit</legend>
            <label for="overall_wellbeing_score">Gesamtbefindlichkeit heute (1-10):</label>
            <input type="number" id="overall_wellbeing_score" name="overall_wellbeing_score" min="1" max="10" value="<?php echo htmlspecialchars($entryData['overall_wellbeing_score'] ?? '', ENT_QUOTES); ?>">
        </fieldset>

        <button type="submit"><?php echo $isEdit ? 'Änderungen speichern' : 'Eintrag speichern'; ?></button>
    </form>
</body>
</html>
