<?php
$isEdit = !empty($entry);
$entryData = $entry ?? [];

function parse_multi_value(array $entryData, string $key): array {
    if (empty($entryData[$key])) {
        return [];
    }

    return array_filter(array_map('trim', explode(',', $entryData[$key])));
}

$selected = [
    'tremor_regions' => parse_multi_value($entryData, 'tremor_regions'),
    'rigor_regions' => parse_multi_value($entryData, 'rigor_regions'),
    'bradykinesia_activities' => parse_multi_value($entryData, 'bradykinesia_activities'),
    'fine_motor_activities' => parse_multi_value($entryData, 'fine_motor_activities'),
    'gait_characteristics' => parse_multi_value($entryData, 'gait_characteristics'),
    'balance_type' => parse_multi_value($entryData, 'balance_type'),
    'balance_situations' => parse_multi_value($entryData, 'balance_situations'),
    'sleep_problems_list' => parse_multi_value($entryData, 'sleep_problems_list'),
    'sleep_rem_symptoms' => parse_multi_value($entryData, 'sleep_rem_symptoms'),
    'sleep_environment_factors' => parse_multi_value($entryData, 'sleep_environment_factors'),
    'fatigue_activities_affected' => parse_multi_value($entryData, 'fatigue_activities_affected'),
    'smell_missing_items' => parse_multi_value($entryData, 'smell_missing_items'),
    'smell_subjective_perception' => parse_multi_value($entryData, 'smell_subjective_perception'),
    'taste_impacts' => parse_multi_value($entryData, 'taste_impacts'),
    'mood_depressive_symptoms' => parse_multi_value($entryData, 'mood_depressive_symptoms'),
    'mood_anxiety_types' => parse_multi_value($entryData, 'mood_anxiety_types'),
    'cognitive_specific_issues' => parse_multi_value($entryData, 'cognitive_specific_issues'),
    'pain_locations' => parse_multi_value($entryData, 'pain_locations'),
    'pain_character' => parse_multi_value($entryData, 'pain_character'),
    'pain_relief_methods' => parse_multi_value($entryData, 'pain_relief_methods'),
    'constipation_details' => parse_multi_value($entryData, 'constipation_details'),
    'vegetative_blood_pressure' => parse_multi_value($entryData, 'vegetative_blood_pressure'),
    'lifestyle_factors' => parse_multi_value($entryData, 'lifestyle_factors'),
    'pattern_recognized' => parse_multi_value($entryData, 'pattern_recognized'),
    'dystonia_location' => parse_multi_value($entryData, 'dystonia_location'),
];

$napVoluntary = $entryData['fatigue_nap_voluntary'] ?? null;
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
                <label><input type="checkbox" name="tremor_regions[]" value="Hand links" <?php echo in_array('Hand links', $selected['tremor_regions']) ? 'checked' : ''; ?>> Hand links</label>
                <label><input type="checkbox" name="tremor_regions[]" value="Hand rechts" <?php echo in_array('Hand rechts', $selected['tremor_regions']) ? 'checked' : ''; ?>> Hand rechts</label>
                <label><input type="checkbox" name="tremor_regions[]" value="Kinn/Lippe" <?php echo in_array('Kinn/Lippe', $selected['tremor_regions']) ? 'checked' : ''; ?>> Kinn/Lippe</label>
                <label><input type="checkbox" name="tremor_regions[]" value="Bein" <?php echo in_array('Bein', $selected['tremor_regions']) ? 'checked' : ''; ?>> Bein</label>
                <label><input type="checkbox" name="tremor_regions[]" value="Kopf" <?php echo in_array('Kopf', $selected['tremor_regions']) ? 'checked' : ''; ?>> Kopf</label>
                <label><input type="checkbox" name="tremor_regions[]" value="Sonstiges" <?php echo in_array('Sonstiges', $selected['tremor_regions']) ? 'checked' : ''; ?>> Sonstiges</label>
                <input type="text" name="tremor_regions_other" placeholder="Sonstiges" value="<?php echo htmlspecialchars($entryData['tremor_regions_other'] ?? '', ENT_QUOTES); ?>">
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
            <input type="text" name="tremor_context_other" placeholder="Andere Situation" value="<?php echo htmlspecialchars($entryData['tremor_context_other'] ?? '', ENT_QUOTES); ?>">
            <label for="tremor_notes">Besonderheiten:</label>
            <textarea id="tremor_notes" name="tremor_notes"><?php echo htmlspecialchars($entryData['tremor_notes'] ?? '', ENT_QUOTES); ?></textarea>
        </fieldset>

        <fieldset>
            <legend>2. Steifheit/Rigor (Muskelspannung)</legend>
            <label><input type="checkbox" name="rigor_present" value="1" <?php echo !empty($entryData['rigor_present']) ? 'checked' : ''; ?>> Steifheit vorhanden?</label>
            <div class="checkbox-group">
                Betroffene Körperregion(en):<br>
                <label><input type="checkbox" name="rigor_regions[]" value="Nackenbereich" <?php echo in_array('Nackenbereich', $selected['rigor_regions']) ? 'checked' : ''; ?>> Nackenbereich</label>
                <label><input type="checkbox" name="rigor_regions[]" value="Schulter(n)" <?php echo in_array('Schulter(n)', $selected['rigor_regions']) ? 'checked' : ''; ?>> Schulter(n)</label>
                <label><input type="checkbox" name="rigor_regions[]" value="Oberarme" <?php echo in_array('Oberarme', $selected['rigor_regions']) ? 'checked' : ''; ?>> Oberarme</label>
                <label><input type="checkbox" name="rigor_regions[]" value="Unterarme" <?php echo in_array('Unterarme', $selected['rigor_regions']) ? 'checked' : ''; ?>> Unterarme</label>
                <label><input type="checkbox" name="rigor_regions[]" value="Hände" <?php echo in_array('Hände', $selected['rigor_regions']) ? 'checked' : ''; ?>> Hände</label>
                <label><input type="checkbox" name="rigor_regions[]" value="Rücken" <?php echo in_array('Rücken', $selected['rigor_regions']) ? 'checked' : ''; ?>> Rücken</label>
                <label><input type="checkbox" name="rigor_regions[]" value="Beine" <?php echo in_array('Beine', $selected['rigor_regions']) ? 'checked' : ''; ?>> Beine</label>
                <label><input type="checkbox" name="rigor_regions[]" value="Sonstiges" <?php echo in_array('Sonstiges', $selected['rigor_regions']) ? 'checked' : ''; ?>> Sonstiges</label>
                <input type="text" name="rigor_regions_other" placeholder="Sonstiges" value="<?php echo htmlspecialchars($entryData['rigor_regions_other'] ?? '', ENT_QUOTES); ?>">
            </div>
            <label for="rigor_intensity">Intensität (1-10):</label>
            <input type="number" id="rigor_intensity" name="rigor_intensity" min="1" max="10" value="<?php echo htmlspecialchars($entryData['rigor_intensity'] ?? '', ENT_QUOTES); ?>">
            <label for="rigor_severity">Ausprägung:</label>
            <select name="rigor_severity" id="rigor_severity">
                <option value="">Bitte wählen</option>
                <option value="Leicht" <?php echo ($entryData['rigor_severity'] ?? '') === 'Leicht' ? 'selected' : ''; ?>>Leicht</option>
                <option value="Moderat" <?php echo ($entryData['rigor_severity'] ?? '') === 'Moderat' ? 'selected' : ''; ?>>Moderat</option>
                <option value="Stark" <?php echo ($entryData['rigor_severity'] ?? '') === 'Stark' ? 'selected' : ''; ?>>Stark</option>
            </select>
            <label for="rigor_time_of_day">Tageszeit:</label>
            <select name="rigor_time_of_day" id="rigor_time_of_day">
                <option value="">Bitte wählen</option>
                <option value="Morgens" <?php echo ($entryData['rigor_time_of_day'] ?? '') === 'Morgens' ? 'selected' : ''; ?>>Morgens</option>
                <option value="Mittags" <?php echo ($entryData['rigor_time_of_day'] ?? '') === 'Mittags' ? 'selected' : ''; ?>>Mittags</option>
                <option value="Abends" <?php echo ($entryData['rigor_time_of_day'] ?? '') === 'Abends' ? 'selected' : ''; ?>>Abends</option>
                <option value="Nachts" <?php echo ($entryData['rigor_time_of_day'] ?? '') === 'Nachts' ? 'selected' : ''; ?>>Nachts</option>
                <option value="Immer" <?php echo ($entryData['rigor_time_of_day'] ?? '') === 'Immer' ? 'selected' : ''; ?>>Immer</option>
            </select>
            <label><input type="checkbox" name="rigor_improved_by_movement" value="1" <?php echo !empty($entryData['rigor_improved_by_movement']) ? 'checked' : ''; ?>> Verbesserung durch Bewegung?</label>
            <label for="rigor_notes">Besonderheiten:</label>
            <textarea id="rigor_notes" name="rigor_notes"><?php echo htmlspecialchars($entryData['rigor_notes'] ?? '', ENT_QUOTES); ?></textarea>
        </fieldset>

        <fieldset>
            <legend>3. Bewegungsverlangsamung (Bradykinese)</legend>
            <label><input type="checkbox" name="bradykinesia_present" value="1" <?php echo !empty($entryData['bradykinesia_present']) ? 'checked' : ''; ?>> Bewegungsverlangsamung vorhanden?</label>
            <div class="checkbox-group">
                Betroffene Aktivitäten:<br>
                <label><input type="checkbox" name="bradykinesia_activities[]" value="Beim Gehen" <?php echo in_array('Beim Gehen', $selected['bradykinesia_activities']) ? 'checked' : ''; ?>> Beim Gehen</label>
                <label><input type="checkbox" name="bradykinesia_activities[]" value="Beim Schreiben" <?php echo in_array('Beim Schreiben', $selected['bradykinesia_activities']) ? 'checked' : ''; ?>> Beim Schreiben</label>
                <label><input type="checkbox" name="bradykinesia_activities[]" value="Bei Knöpfen/Reißverschlüssen" <?php echo in_array('Bei Knöpfen/Reißverschlüssen', $selected['bradykinesia_activities']) ? 'checked' : ''; ?>> Bei Knöpfen/Reißverschlüssen</label>
                <label><input type="checkbox" name="bradykinesia_activities[]" value="Bei der Körperpflege" <?php echo in_array('Bei der Körperpflege', $selected['bradykinesia_activities']) ? 'checked' : ''; ?>> Bei der Körperpflege</label>
                <label><input type="checkbox" name="bradykinesia_activities[]" value="Bei alltäglichen Arbeiten" <?php echo in_array('Bei alltäglichen Arbeiten', $selected['bradykinesia_activities']) ? 'checked' : ''; ?>> Bei alltäglichen Arbeiten</label>
                <label><input type="checkbox" name="bradykinesia_activities[]" value="Sonstiges" <?php echo in_array('Sonstiges', $selected['bradykinesia_activities']) ? 'checked' : ''; ?>> Sonstiges</label>
                <input type="text" name="bradykinesia_activities_other" placeholder="Sonstiges" value="<?php echo htmlspecialchars($entryData['bradykinesia_activities_other'] ?? '', ENT_QUOTES); ?>">
            </div>
            <label for="bradykinesia_intensity">Intensität (1-10):</label>
            <input type="number" id="bradykinesia_intensity" name="bradykinesia_intensity" min="1" max="10" value="<?php echo htmlspecialchars($entryData['bradykinesia_intensity'] ?? '', ENT_QUOTES); ?>">
            <label for="bradykinesia_impact">Auswirkungen im Alltag:</label>
            <select name="bradykinesia_impact" id="bradykinesia_impact">
                <option value="">Bitte wählen</option>
                <option value="Kaum merklich" <?php echo ($entryData['bradykinesia_impact'] ?? '') === 'Kaum merklich' ? 'selected' : ''; ?>>Kaum merklich</option>
                <option value="Gelegentlich störend" <?php echo ($entryData['bradykinesia_impact'] ?? '') === 'Gelegentlich störend' ? 'selected' : ''; ?>>Gelegentlich störend</option>
                <option value="Deutlich behindert" <?php echo ($entryData['bradykinesia_impact'] ?? '') === 'Deutlich behindert' ? 'selected' : ''; ?>>Deutlich behindert</option>
            </select>
            <label for="bradykinesia_examples">Beispiele:</label>
            <textarea id="bradykinesia_examples" name="bradykinesia_examples"><?php echo htmlspecialchars($entryData['bradykinesia_examples'] ?? '', ENT_QUOTES); ?></textarea>
            <label for="bradykinesia_notes">Besonderheiten:</label>
            <textarea id="bradykinesia_notes" name="bradykinesia_notes"><?php echo htmlspecialchars($entryData['bradykinesia_notes'] ?? '', ENT_QUOTES); ?></textarea>
        </fieldset>

        <fieldset>
            <legend>4. Asymmetrischer Armschlag</legend>
            <label><input type="checkbox" name="arm_swing_asymmetry_present" value="1" <?php echo !empty($entryData['arm_swing_asymmetry_present']) ? 'checked' : ''; ?>> Asymmetrischer Armschlag?</label>
            <label for="arm_swing_side">Betroffene Seite:</label>
            <select name="arm_swing_side" id="arm_swing_side">
                <option value="">Bitte wählen</option>
                <option value="Links" <?php echo ($entryData['arm_swing_side'] ?? '') === 'Links' ? 'selected' : ''; ?>>Links</option>
                <option value="Rechts" <?php echo ($entryData['arm_swing_side'] ?? '') === 'Rechts' ? 'selected' : ''; ?>>Rechts</option>
                <option value="Manchmal beide" <?php echo ($entryData['arm_swing_side'] ?? '') === 'Manchmal beide' ? 'selected' : ''; ?>>Manchmal beide</option>
            </select>
            <label for="arm_swing_severity">Ausprägung:</label>
            <select name="arm_swing_severity" id="arm_swing_severity">
                <option value="">Bitte wählen</option>
                <option value="Arm bewegt sich weniger" <?php echo ($entryData['arm_swing_severity'] ?? '') === 'Arm bewegt sich weniger' ? 'selected' : ''; ?>>Arm bewegt sich weniger</option>
                <option value="Arm bleibt vollständig still" <?php echo ($entryData['arm_swing_severity'] ?? '') === 'Arm bleibt vollständig still' ? 'selected' : ''; ?>>Arm bleibt vollständig still</option>
                <option value="Arm bewegt sich verzögert" <?php echo ($entryData['arm_swing_severity'] ?? '') === 'Arm bewegt sich verzögert' ? 'selected' : ''; ?>>Arm bewegt sich verzögert</option>
            </select>
            <label for="arm_swing_intensity">Intensität (1-10):</label>
            <input type="number" id="arm_swing_intensity" name="arm_swing_intensity" min="1" max="10" value="<?php echo htmlspecialchars($entryData['arm_swing_intensity'] ?? '', ENT_QUOTES); ?>">
            <label for="arm_swing_consistency">Konsistenz:</label>
            <select name="arm_swing_consistency" id="arm_swing_consistency">
                <option value="">Bitte wählen</option>
                <option value="Immer" <?php echo ($entryData['arm_swing_consistency'] ?? '') === 'Immer' ? 'selected' : ''; ?>>Immer</option>
                <option value="Meistens" <?php echo ($entryData['arm_swing_consistency'] ?? '') === 'Meistens' ? 'selected' : ''; ?>>Meistens</option>
                <option value="Manchmal" <?php echo ($entryData['arm_swing_consistency'] ?? '') === 'Manchmal' ? 'selected' : ''; ?>>Manchmal</option>
                <option value="Nur bei Müdigkeit" <?php echo ($entryData['arm_swing_consistency'] ?? '') === 'Nur bei Müdigkeit' ? 'selected' : ''; ?>>Nur bei Müdigkeit</option>
            </select>
            <label for="arm_swing_notes">Besonderheiten:</label>
            <textarea id="arm_swing_notes" name="arm_swing_notes"><?php echo htmlspecialchars($entryData['arm_swing_notes'] ?? '', ENT_QUOTES); ?></textarea>
        </fieldset>

        <fieldset>
            <legend>5. Feinmotorik-Probleme</legend>
            <label><input type="checkbox" name="fine_motor_issues_present" value="1" <?php echo !empty($entryData['fine_motor_issues_present']) ? 'checked' : ''; ?>> Feinmotorik-Probleme vorhanden?</label>
            <div class="checkbox-group">
                Betroffene Aktivitäten:<br>
                <label><input type="checkbox" name="fine_motor_activities[]" value="Schreiben" <?php echo in_array('Schreiben', $selected['fine_motor_activities']) ? 'checked' : ''; ?>> Schreiben</label>
                <label><input type="checkbox" name="fine_motor_activities[]" value="Knöpfe / Reißverschlüsse" <?php echo in_array('Knöpfe / Reißverschlüsse', $selected['fine_motor_activities']) ? 'checked' : ''; ?>> Knöpfe / Reißverschlüsse</label>
                <label><input type="checkbox" name="fine_motor_activities[]" value="Besteck handhaben" <?php echo in_array('Besteck handhaben', $selected['fine_motor_activities']) ? 'checked' : ''; ?>> Besteck handhaben</label>
                <label><input type="checkbox" name="fine_motor_activities[]" value="Handy / Computer bedienen" <?php echo in_array('Handy / Computer bedienen', $selected['fine_motor_activities']) ? 'checked' : ''; ?>> Handy / Computer bedienen</label>
                <label><input type="checkbox" name="fine_motor_activities[]" value="Nagelpflege" <?php echo in_array('Nagelpflege', $selected['fine_motor_activities']) ? 'checked' : ''; ?>> Nagelpflege</label>
                <label><input type="checkbox" name="fine_motor_activities[]" value="Sonstiges" <?php echo in_array('Sonstiges', $selected['fine_motor_activities']) ? 'checked' : ''; ?>> Sonstiges</label>
                <input type="text" name="fine_motor_activities_other" placeholder="Sonstiges" value="<?php echo htmlspecialchars($entryData['fine_motor_activities_other'] ?? '', ENT_QUOTES); ?>">
            </div>
            <label for="fine_motor_intensity">Intensität (1-10):</label>
            <input type="number" id="fine_motor_intensity" name="fine_motor_intensity" min="1" max="10" value="<?php echo htmlspecialchars($entryData['fine_motor_intensity'] ?? '', ENT_QUOTES); ?>">
            <label for="fine_motor_desc">Beschreibung der Probleme:</label>
            <textarea id="fine_motor_desc" name="fine_motor_desc"><?php echo htmlspecialchars($entryData['fine_motor_desc'] ?? '', ENT_QUOTES); ?></textarea>
            <label for="fine_motor_notes">Besonderheiten:</label>
            <textarea id="fine_motor_notes" name="fine_motor_notes"><?php echo htmlspecialchars($entryData['fine_motor_notes'] ?? '', ENT_QUOTES); ?></textarea>
        </fieldset>

        <fieldset>
            <legend>6. Gangstörung</legend>
            <label><input type="checkbox" name="gait_issues_present" value="1" <?php echo !empty($entryData['gait_issues_present']) ? 'checked' : ''; ?>> Gangveränderung?</label>
            <div class="checkbox-group">
                Charakteristika:<br>
                <label><input type="checkbox" name="gait_characteristics[]" value="Kleinere Schritte als normal" <?php echo in_array('Kleinere Schritte als normal', $selected['gait_characteristics']) ? 'checked' : ''; ?>> Kleinere Schritte als normal</label>
                <label><input type="checkbox" name="gait_characteristics[]" value="Schlurfender Gang" <?php echo in_array('Schlurfender Gang', $selected['gait_characteristics']) ? 'checked' : ''; ?>> Schlurfender Gang</label>
                <label><input type="checkbox" name="gait_characteristics[]" value="Unbeweglicher Oberkörper beim Gehen" <?php echo in_array('Unbeweglicher Oberkörper beim Gehen', $selected['gait_characteristics']) ? 'checked' : ''; ?>> Unbeweglicher Oberkörper beim Gehen</label>
                <label><input type="checkbox" name="gait_characteristics[]" value="Verlangsamter Gang" <?php echo in_array('Verlangsamter Gang', $selected['gait_characteristics']) ? 'checked' : ''; ?>> Verlangsamter Gang</label>
                <label><input type="checkbox" name="gait_characteristics[]" value="Steifer Gang" <?php echo in_array('Steifer Gang', $selected['gait_characteristics']) ? 'checked' : ''; ?>> Steifer Gang</label>
                <label><input type="checkbox" name="gait_characteristics[]" value="Sonstiges" <?php echo in_array('Sonstiges', $selected['gait_characteristics']) ? 'checked' : ''; ?>> Sonstiges</label>
                <input type="text" name="gait_characteristics_other" placeholder="Sonstiges" value="<?php echo htmlspecialchars($entryData['gait_characteristics_other'] ?? '', ENT_QUOTES); ?>">
            </div>
            <label for="gait_intensity">Intensität (1-10):</label>
            <input type="number" id="gait_intensity" name="gait_intensity" min="1" max="10" value="<?php echo htmlspecialchars($entryData['gait_intensity'] ?? '', ENT_QUOTES); ?>">
            <label for="gait_time_of_day">Tageszeit:</label>
            <select name="gait_time_of_day" id="gait_time_of_day">
                <option value="">Bitte wählen</option>
                <option value="Morgens am schlimmsten" <?php echo ($entryData['gait_time_of_day'] ?? '') === 'Morgens am schlimmsten' ? 'selected' : ''; ?>>Morgens am schlimmsten</option>
                <option value="Tagsüber variabel" <?php echo ($entryData['gait_time_of_day'] ?? '') === 'Tagsüber variabel' ? 'selected' : ''; ?>>Tagsüber variabel</option>
                <option value="Abends" <?php echo ($entryData['gait_time_of_day'] ?? '') === 'Abends' ? 'selected' : ''; ?>>Abends</option>
                <option value="Immer ähnlich" <?php echo ($entryData['gait_time_of_day'] ?? '') === 'Immer ähnlich' ? 'selected' : ''; ?>>Immer ähnlich</option>
            </select>
            <label for="gait_trigger">Auslöser:</label>
            <input type="text" id="gait_trigger" name="gait_trigger" placeholder="z.B. Müdigkeit, Stress" value="<?php echo htmlspecialchars($entryData['gait_trigger'] ?? '', ENT_QUOTES); ?>">
            <label for="gait_notes">Besonderheiten:</label>
            <textarea id="gait_notes" name="gait_notes"><?php echo htmlspecialchars($entryData['gait_notes'] ?? '', ENT_QUOTES); ?></textarea>
        </fieldset>

        <fieldset>
            <legend>7. Gleichgewichtsstörungen</legend>
            <label><input type="checkbox" name="balance_issues_present" value="1" <?php echo !empty($entryData['balance_issues_present']) ? 'checked' : ''; ?>> Gleichgewichtsstörungen?</label>
            <div class="checkbox-group">
                Art der Störung:<br>
                <label><input type="checkbox" name="balance_type[]" value="Schwindel beim Aufstehen" <?php echo in_array('Schwindel beim Aufstehen', $selected['balance_type']) ? 'checked' : ''; ?>> Schwindel beim Aufstehen</label>
                <label><input type="checkbox" name="balance_type[]" value="Unsicherheitsgefühl" <?php echo in_array('Unsicherheitsgefühl', $selected['balance_type']) ? 'checked' : ''; ?>> Unsicherheitsgefühl</label>
                <label><input type="checkbox" name="balance_type[]" value="Fallneigung zur Seite" <?php echo in_array('Fallneigung zur Seite', $selected['balance_type']) ? 'checked' : ''; ?>> Fallneigung zur Seite</label>
                <label><input type="checkbox" name="balance_type[]" value="Vornüberneigung" <?php echo in_array('Vornüberneigung', $selected['balance_type']) ? 'checked' : ''; ?>> Vornüberneigung</label>
                <label><input type="checkbox" name="balance_type[]" value="Schwankend beim Stehen" <?php echo in_array('Schwankend beim Stehen', $selected['balance_type']) ? 'checked' : ''; ?>> Schwankend beim Stehen</label>
                <label><input type="checkbox" name="balance_type[]" value="Sonstiges" <?php echo in_array('Sonstiges', $selected['balance_type']) ? 'checked' : ''; ?>> Sonstiges</label>
                <input type="text" name="balance_type_other" placeholder="Sonstiges" value="<?php echo htmlspecialchars($entryData['balance_type_other'] ?? '', ENT_QUOTES); ?>">
            </div>
            <label for="balance_intensity">Intensität (1-10):</label>
            <input type="number" id="balance_intensity" name="balance_intensity" min="1" max="10" value="<?php echo htmlspecialchars($entryData['balance_intensity'] ?? '', ENT_QUOTES); ?>">
            <div class="checkbox-group">
                Situationen:<br>
                <label><input type="checkbox" name="balance_situations[]" value="Beim Aufstehen" <?php echo in_array('Beim Aufstehen', $selected['balance_situations']) ? 'checked' : ''; ?>> Beim Aufstehen</label>
                <label><input type="checkbox" name="balance_situations[]" value="Bei schnellen Kopfbewegungen" <?php echo in_array('Bei schnellen Kopfbewegungen', $selected['balance_situations']) ? 'checked' : ''; ?>> Bei schnellen Kopfbewegungen</label>
                <label><input type="checkbox" name="balance_situations[]" value="Im dunklen Raum" <?php echo in_array('Im dunklen Raum', $selected['balance_situations']) ? 'checked' : ''; ?>> Im dunklen Raum</label>
                <label><input type="checkbox" name="balance_situations[]" value="Beim Gehen" <?php echo in_array('Beim Gehen', $selected['balance_situations']) ? 'checked' : ''; ?>> Beim Gehen</label>
                <label><input type="checkbox" name="balance_situations[]" value="Beim Drehen" <?php echo in_array('Beim Drehen', $selected['balance_situations']) ? 'checked' : ''; ?>> Beim Drehen</label>
                <label><input type="checkbox" name="balance_situations[]" value="Sonstiges" <?php echo in_array('Sonstiges', $selected['balance_situations']) ? 'checked' : ''; ?>> Sonstiges</label>
                <input type="text" name="balance_situations_other" placeholder="Sonstiges" value="<?php echo htmlspecialchars($entryData['balance_situations_other'] ?? '', ENT_QUOTES); ?>">
            </div>
            <label for="balance_notes">Besonderheiten:</label>
            <textarea id="balance_notes" name="balance_notes"><?php echo htmlspecialchars($entryData['balance_notes'] ?? '', ENT_QUOTES); ?></textarea>
        </fieldset>

        <fieldset>
            <legend>8. Körperhaltung</legend>
            <label for="posture_status">Beobachtete Körperhaltung:</label>
            <select name="posture_status" id="posture_status">
                <option value="">Bitte wählen</option>
                <option value="Aufrecht / Normal" <?php echo ($entryData['posture_status'] ?? '') === 'Aufrecht / Normal' ? 'selected' : ''; ?>>Aufrecht / Normal</option>
                <option value="Leicht nach vorne gebeugt" <?php echo ($entryData['posture_status'] ?? '') === 'Leicht nach vorne gebeugt' ? 'selected' : ''; ?>>Leicht nach vorne gebeugt</option>
                <option value="Deutlich gebeugt" <?php echo ($entryData['posture_status'] ?? '') === 'Deutlich gebeugt' ? 'selected' : ''; ?>>Deutlich gebeugt</option>
                <option value="Variabel" <?php echo ($entryData['posture_status'] ?? '') === 'Variabel' ? 'selected' : ''; ?>>Variabel</option>
            </select>
            <label><input type="checkbox" name="posture_observed_in_mirror" value="1" <?php echo !empty($entryData['posture_observed_in_mirror']) ? 'checked' : ''; ?>> Im Spiegel beobachtet?</label>
            <label for="posture_notes">Besonderheiten:</label>
            <textarea id="posture_notes" name="posture_notes"><?php echo htmlspecialchars($entryData['posture_notes'] ?? '', ENT_QUOTES); ?></textarea>
        </fieldset>

        <fieldset>
            <legend>9. Dystonie und Krämpfe</legend>
            <label><input type="checkbox" name="dystonia_present" value="1" <?php echo !empty($entryData['dystonia_present']) ? 'checked' : ''; ?>> Dystonie/Krämpfe?</label>
            <div class="checkbox-group">
                Lokalisation:<br>
                <label><input type="checkbox" name="dystonia_location[]" value="Hand" <?php echo in_array('Hand', $selected['dystonia_location']) ? 'checked' : ''; ?>> Hand</label>
                <label><input type="checkbox" name="dystonia_location[]" value="Fuß" <?php echo in_array('Fuß', $selected['dystonia_location']) ? 'checked' : ''; ?>> Fuß</label>
                <label><input type="checkbox" name="dystonia_location[]" value="Nacken" <?php echo in_array('Nacken', $selected['dystonia_location']) ? 'checked' : ''; ?>> Nacken</label>
                <label><input type="checkbox" name="dystonia_location[]" value="Rücken" <?php echo in_array('Rücken', $selected['dystonia_location']) ? 'checked' : ''; ?>> Rücken</label>
                <label><input type="checkbox" name="dystonia_location[]" value="Bein" <?php echo in_array('Bein', $selected['dystonia_location']) ? 'checked' : ''; ?>> Bein</label>
                <label><input type="checkbox" name="dystonia_location[]" value="Sonstiges" <?php echo in_array('Sonstiges', $selected['dystonia_location']) ? 'checked' : ''; ?>> Sonstiges</label>
                <input type="text" name="dystonia_location_other" placeholder="Sonstiges" value="<?php echo htmlspecialchars($entryData['dystonia_location_other'] ?? '', ENT_QUOTES); ?>">
            </div>
            <label for="dystonia_intensity">Intensität (1-10):</label>
            <input type="number" id="dystonia_intensity" name="dystonia_intensity" min="1" max="10" value="<?php echo htmlspecialchars($entryData['dystonia_intensity'] ?? '', ENT_QUOTES); ?>">
            <label for="dystonia_duration_min">Dauer (Minuten):</label>
            <input type="number" id="dystonia_duration_min" name="dystonia_duration_min" value="<?php echo htmlspecialchars($entryData['dystonia_duration_min'] ?? '', ENT_QUOTES); ?>">
            <label for="dystonia_trigger">Tageszeit / Auslöser:</label>
            <input type="text" id="dystonia_trigger" name="dystonia_trigger" value="<?php echo htmlspecialchars($entryData['dystonia_trigger'] ?? '', ENT_QUOTES); ?>">
            <label for="dystonia_notes">Besonderheiten:</label>
            <textarea id="dystonia_notes" name="dystonia_notes"><?php echo htmlspecialchars($entryData['dystonia_notes'] ?? '', ENT_QUOTES); ?></textarea>
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
            <div class="checkbox-group">
                Schlafprobleme:<br>
                <label><input type="checkbox" name="sleep_problems_list[]" value="Einschlafstörung" <?php echo in_array('Einschlafstörung', $selected['sleep_problems_list']) ? 'checked' : ''; ?>> Einschlafstörung</label>
                <label><input type="checkbox" name="sleep_problems_list[]" value="Durchschlafstörung" <?php echo in_array('Durchschlafstörung', $selected['sleep_problems_list']) ? 'checked' : ''; ?>> Durchschlafstörung</label>
                <label><input type="checkbox" name="sleep_problems_list[]" value="Früherwachen" <?php echo in_array('Früherwachen', $selected['sleep_problems_list']) ? 'checked' : ''; ?>> Früherwachen</label>
                <label><input type="checkbox" name="sleep_problems_list[]" value="REM-Schlaf-Verhaltensstörung" <?php echo in_array('REM-Schlaf-Verhaltensstörung', $selected['sleep_problems_list']) ? 'checked' : ''; ?>> REM-Schlaf-Verhaltensstörung</label>
                <label><input type="checkbox" name="sleep_problems_list[]" value="Unruhige Beine beim Einschlafen" <?php echo in_array('Unruhige Beine beim Einschlafen', $selected['sleep_problems_list']) ? 'checked' : ''; ?>> Unruhige Beine beim Einschlafen</label>
                <label><input type="checkbox" name="sleep_problems_list[]" value="Keine Probleme" <?php echo in_array('Keine Probleme', $selected['sleep_problems_list']) ? 'checked' : ''; ?>> Keine Probleme</label>
            </div>
            <label><input type="checkbox" name="sleep_rem_behavior_present" value="1" <?php echo !empty($entryData['sleep_rem_behavior_present']) ? 'checked' : ''; ?>> REM-Verhaltensstörung vorhanden?</label>
            <div class="checkbox-group">
                REM-Verhaltensstörung Details:<br>
                <label><input type="checkbox" name="sleep_rem_symptoms[]" value="Lebhafte/Alptraum-Träume" <?php echo in_array('Lebhafte/Alptraum-Träume', $selected['sleep_rem_symptoms']) ? 'checked' : ''; ?>> Lebhafte/Alptraum-Träume</label>
                <label><input type="checkbox" name="sleep_rem_symptoms[]" value="Reden im Schlaf" <?php echo in_array('Reden im Schlaf', $selected['sleep_rem_symptoms']) ? 'checked' : ''; ?>> Reden im Schlaf</label>
                <label><input type="checkbox" name="sleep_rem_symptoms[]" value="Schreien im Schlaf" <?php echo in_array('Schreien im Schlaf', $selected['sleep_rem_symptoms']) ? 'checked' : ''; ?>> Schreien im Schlaf</label>
                <label><input type="checkbox" name="sleep_rem_symptoms[]" value="Um sich Treten/Schlagen im Schlaf" <?php echo in_array('Um sich Treten/Schlagen im Schlaf', $selected['sleep_rem_symptoms']) ? 'checked' : ''; ?>> Um sich Treten/Schlagen im Schlaf</label>
                <label><input type="checkbox" name="sleep_rem_symptoms[]" value="Bewegungen, die Trauminhalte darstellen" <?php echo in_array('Bewegungen, die Trauminhalte darstellen', $selected['sleep_rem_symptoms']) ? 'checked' : ''; ?>> Bewegungen, die Trauminhalte darstellen</label>
            </div>
            <label for="sleep_rem_trigger_notes">Tageszeit/Trigger bei REM:</label>
            <input type="text" id="sleep_rem_trigger_notes" name="sleep_rem_trigger_notes" value="<?php echo htmlspecialchars($entryData['sleep_rem_trigger_notes'] ?? '', ENT_QUOTES); ?>">
            <label for="sleep_hours_duration">Schlafdauer (Stunden):</label>
            <input type="number" id="sleep_hours_duration" name="sleep_hours_duration" step="0.5" value="<?php echo htmlspecialchars($entryData['sleep_hours_duration'] ?? '', ENT_QUOTES); ?>">
            <label for="sleep_quality_score">Qualitative Bewertung (1-10):</label>
            <input type="number" id="sleep_quality_score" name="sleep_quality_score" min="1" max="10" value="<?php echo htmlspecialchars($entryData['sleep_quality_score'] ?? '', ENT_QUOTES); ?>">
            <label for="sleep_wake_count">Anzahl nächtlicher Wachphasen:</label>
            <input type="number" id="sleep_wake_count" name="sleep_wake_count" value="<?php echo htmlspecialchars($entryData['sleep_wake_count'] ?? '', ENT_QUOTES); ?>">
            <label for="sleep_longest_wake_min">Dauer längster Wachphase (Minuten):</label>
            <input type="number" id="sleep_longest_wake_min" name="sleep_longest_wake_min" value="<?php echo htmlspecialchars($entryData['sleep_longest_wake_min'] ?? '', ENT_QUOTES); ?>">
            <div class="checkbox-group">
                Schlafumgebung / Interventionen:<br>
                <label><input type="checkbox" name="sleep_environment_factors[]" value="Gleicher Schlafplatz genutzt" <?php echo in_array('Gleicher Schlafplatz genutzt', $selected['sleep_environment_factors']) ? 'checked' : ''; ?>> Gleicher Schlafplatz genutzt</label>
                <label><input type="checkbox" name="sleep_environment_factors[]" value="Schlafenszeit regulär" <?php echo in_array('Schlafenszeit regulär', $selected['sleep_environment_factors']) ? 'checked' : ''; ?>> Schlafenszeit regulär</label>
                <label><input type="checkbox" name="sleep_environment_factors[]" value="Schlafhygiene beachtet" <?php echo in_array('Schlafhygiene beachtet', $selected['sleep_environment_factors']) ? 'checked' : ''; ?>> Schlafhygiene beachtet</label>
            </div>
            <label for="sleep_environment_notes">Sonstiges zur Schlafumgebung:</label>
            <textarea id="sleep_environment_notes" name="sleep_environment_notes"><?php echo htmlspecialchars($entryData['sleep_environment_notes'] ?? '', ENT_QUOTES); ?></textarea>
        </fieldset>

        <fieldset>
            <legend>11. Tagesschläfrigkeit (Fatigue)</legend>
            <label for="fatigue_severity">Tagesschläfrigkeit:</label>
            <select id="fatigue_severity" name="fatigue_severity">
                <option value="">Bitte wählen</option>
                <option value="Nein" <?php echo ($entryData['fatigue_severity'] ?? '') === 'Nein' ? 'selected' : ''; ?>>Nein</option>
                <option value="Leicht" <?php echo ($entryData['fatigue_severity'] ?? '') === 'Leicht' ? 'selected' : ''; ?>>Leicht</option>
                <option value="Moderat" <?php echo ($entryData['fatigue_severity'] ?? '') === 'Moderat' ? 'selected' : ''; ?>>Moderat</option>
                <option value="Schwer" <?php echo ($entryData['fatigue_severity'] ?? '') === 'Schwer' ? 'selected' : ''; ?>>Schwer</option>
            </select>
            <label for="fatigue_nap_count">Anzahl Nickerchen pro Tag:</label>
            <input type="number" id="fatigue_nap_count" name="fatigue_nap_count" value="<?php echo htmlspecialchars($entryData['fatigue_nap_count'] ?? '', ENT_QUOTES); ?>">
            <label for="fatigue_nap_avg_min">Dauer Durchschnitt (Minuten):</label>
            <input type="number" id="fatigue_nap_avg_min" name="fatigue_nap_avg_min" value="<?php echo htmlspecialchars($entryData['fatigue_nap_avg_min'] ?? '', ENT_QUOTES); ?>">
            <div class="checkbox-group">
                Gewollt oder unwillkürlich?<br>
                <label><input type="radio" name="fatigue_nap_voluntary" value="1" <?php echo isset($napVoluntary) && (string)$napVoluntary === '1' ? 'checked' : ''; ?>> Gewollt</label>
                <label><input type="radio" name="fatigue_nap_voluntary" value="0" <?php echo isset($napVoluntary) && (string)$napVoluntary === '0' ? 'checked' : ''; ?>> Unwillkürlich</label>
            </div>
            <div class="checkbox-group">
                Aktivitäten beeinträchtigt:<br>
                <label><input type="checkbox" name="fatigue_activities_affected[]" value="Autofahren" <?php echo in_array('Autofahren', $selected['fatigue_activities_affected']) ? 'checked' : ''; ?>> Autofahren</label>
                <label><input type="checkbox" name="fatigue_activities_affected[]" value="Konzentration" <?php echo in_array('Konzentration', $selected['fatigue_activities_affected']) ? 'checked' : ''; ?>> Konzentration</label>
                <label><input type="checkbox" name="fatigue_activities_affected[]" value="Arbeit" <?php echo in_array('Arbeit', $selected['fatigue_activities_affected']) ? 'checked' : ''; ?>> Arbeit</label>
                <label><input type="checkbox" name="fatigue_activities_affected[]" value="Sociale Aktivitäten" <?php echo in_array('Sociale Aktivitäten', $selected['fatigue_activities_affected']) ? 'checked' : ''; ?>> Sociale Aktivitäten</label>
                <label><input type="checkbox" name="fatigue_activities_affected[]" value="Sonstiges" <?php echo in_array('Sonstiges', $selected['fatigue_activities_affected']) ? 'checked' : ''; ?>> Sonstiges</label>
            </div>
            <label for="fatigue_energy_morning">Energie morgens (1-10):</label>
            <input type="number" id="fatigue_energy_morning" name="fatigue_energy_morning" min="1" max="10" value="<?php echo htmlspecialchars($entryData['fatigue_energy_morning'] ?? '', ENT_QUOTES); ?>">
            <label for="fatigue_energy_noon">Energie mittags (1-10):</label>
            <input type="number" id="fatigue_energy_noon" name="fatigue_energy_noon" min="1" max="10" value="<?php echo htmlspecialchars($entryData['fatigue_energy_noon'] ?? '', ENT_QUOTES); ?>">
            <label for="fatigue_energy_evening">Energie abends (1-10):</label>
            <input type="number" id="fatigue_energy_evening" name="fatigue_energy_evening" min="1" max="10" value="<?php echo htmlspecialchars($entryData['fatigue_energy_evening'] ?? '', ENT_QUOTES); ?>">
            <label for="fatigue_notes">Besonderheiten:</label>
            <textarea id="fatigue_notes" name="fatigue_notes"><?php echo htmlspecialchars($entryData['fatigue_notes'] ?? '', ENT_QUOTES); ?></textarea>
        </fieldset>

        <fieldset>
            <legend>12. Riechstörung (Hyposmie / Anosmie)</legend>
            <label for="smell_ability">Geruchsvermögen:</label>
            <select name="smell_ability" id="smell_ability">
                <option value="">Bitte wählen</option>
                <option value="Normal" <?php echo ($entryData['smell_ability'] ?? '') === 'Normal' ? 'selected' : ''; ?>>Normal</option>
                <option value="Leicht vermindert" <?php echo ($entryData['smell_ability'] ?? '') === 'Leicht vermindert' ? 'selected' : ''; ?>>Leicht vermindert</option>
                <option value="Deutlich vermindert" <?php echo ($entryData['smell_ability'] ?? '') === 'Deutlich vermindert' ? 'selected' : ''; ?>>Deutlich vermindert</option>
                <option value="Fehlend (Anosmie)" <?php echo ($entryData['smell_ability'] ?? '') === 'Fehlend (Anosmie)' ? 'selected' : ''; ?>>Fehlend (Anosmie)</option>
                <option value="Verändert / verzerrt" <?php echo ($entryData['smell_ability'] ?? '') === 'Verändert / verzerrt' ? 'selected' : ''; ?>>Verändert / verzerrt</option>
            </select>
            <div class="checkbox-group">
                Was wird nicht mehr gerochen?<br>
                <label><input type="checkbox" name="smell_missing_items[]" value="Kaffee" <?php echo in_array('Kaffee', $selected['smell_missing_items']) ? 'checked' : ''; ?>> Kaffee</label>
                <label><input type="checkbox" name="smell_missing_items[]" value="Essen / Speisen" <?php echo in_array('Essen / Speisen', $selected['smell_missing_items']) ? 'checked' : ''; ?>> Essen / Speisen</label>
                <label><input type="checkbox" name="smell_missing_items[]" value="Blumen / Düfte" <?php echo in_array('Blumen / Düfte', $selected['smell_missing_items']) ? 'checked' : ''; ?>> Blumen / Düfte</label>
                <label><input type="checkbox" name="smell_missing_items[]" value="Gewürze" <?php echo in_array('Gewürze', $selected['smell_missing_items']) ? 'checked' : ''; ?>> Gewürze</label>
                <label><input type="checkbox" name="smell_missing_items[]" value="Andere" <?php echo in_array('Andere', $selected['smell_missing_items']) ? 'checked' : ''; ?>> Andere</label>
                <input type="text" name="smell_missing_items[]" placeholder="Weitere Gerüche" value="">
            </div>
            <div class="checkbox-group">
                Subjektive Wahrnehmung:<br>
                <label><input type="checkbox" name="smell_subjective_perception[]" value="Mir ist es selbst aufgefallen" <?php echo in_array('Mir ist es selbst aufgefallen', $selected['smell_subjective_perception']) ? 'checked' : ''; ?>> Mir ist es selbst aufgefallen</label>
                <label><input type="checkbox" name="smell_subjective_perception[]" value="Wurde von Familie erwähnt" <?php echo in_array('Wurde von Familie erwähnt', $selected['smell_subjective_perception']) ? 'checked' : ''; ?>> Wurde von Familie erwähnt</label>
                <label><input type="checkbox" name="smell_subjective_perception[]" value="Merke es nur beim Essen" <?php echo in_array('Merke es nur beim Essen', $selected['smell_subjective_perception']) ? 'checked' : ''; ?>> Merke es nur beim Essen</label>
                <label><input type="checkbox" name="smell_subjective_perception[]" value="Merke es nicht besonders" <?php echo in_array('Merke es nicht besonders', $selected['smell_subjective_perception']) ? 'checked' : ''; ?>> Merke es nicht besonders</label>
            </div>
            <label for="smell_impact">Auswirkung auf Lebensqualität:</label>
            <select name="smell_impact" id="smell_impact">
                <option value="">Bitte wählen</option>
                <option value="Keine" <?php echo ($entryData['smell_impact'] ?? '') === 'Keine' ? 'selected' : ''; ?>>Keine</option>
                <option value="Gering" <?php echo ($entryData['smell_impact'] ?? '') === 'Gering' ? 'selected' : ''; ?>>Gering</option>
                <option value="Moderat" <?php echo ($entryData['smell_impact'] ?? '') === 'Moderat' ? 'selected' : ''; ?>>Moderat</option>
                <option value="Bedeutsam" <?php echo ($entryData['smell_impact'] ?? '') === 'Bedeutsam' ? 'selected' : ''; ?>>Bedeutsam</option>
            </select>
        </fieldset>

        <fieldset>
            <legend>13. Geschmacksstörung</legend>
            <label for="taste_ability">Geschmacksvermögen:</label>
            <select name="taste_ability" id="taste_ability">
                <option value="">Bitte wählen</option>
                <option value="Normal" <?php echo ($entryData['taste_ability'] ?? '') === 'Normal' ? 'selected' : ''; ?>>Normal</option>
                <option value="Vermindert" <?php echo ($entryData['taste_ability'] ?? '') === 'Vermindert' ? 'selected' : ''; ?>>Vermindert</option>
                <option value="Verändert / verzerrt" <?php echo ($entryData['taste_ability'] ?? '') === 'Verändert / verzerrt' ? 'selected' : ''; ?>>Verändert / verzerrt</option>
                <option value="Unverändert" <?php echo ($entryData['taste_ability'] ?? '') === 'Unverändert' ? 'selected' : ''; ?>>Unverändert</option>
            </select>
            <label for="taste_change_desc">Wie hat sich der Geschmack verändert?</label>
            <textarea id="taste_change_desc" name="taste_change_desc"><?php echo htmlspecialchars($entryData['taste_change_desc'] ?? '', ENT_QUOTES); ?></textarea>
            <div class="checkbox-group">
                Auswirkungen:<br>
                <label><input type="checkbox" name="taste_impacts[]" value="Appetitlosigkeit" <?php echo in_array('Appetitlosigkeit', $selected['taste_impacts']) ? 'checked' : ''; ?>> Appetitlosigkeit</label>
                <label><input type="checkbox" name="taste_impacts[]" value="Gewichtsverlust" <?php echo in_array('Gewichtsverlust', $selected['taste_impacts']) ? 'checked' : ''; ?>> Gewichtsverlust</label>
                <label><input type="checkbox" name="taste_impacts[]" value="Keine Auswirkung" <?php echo in_array('Keine Auswirkung', $selected['taste_impacts']) ? 'checked' : ''; ?>> Keine Auswirkung</label>
                <label><input type="checkbox" name="taste_impacts[]" value="Sonstiges" <?php echo in_array('Sonstiges', $selected['taste_impacts']) ? 'checked' : ''; ?>> Sonstiges</label>
            </div>
        </fieldset>

        <fieldset>
            <legend>14. Stimmung und Emotion</legend>
            <label for="mood_general">Generelle Stimmung:</label>
            <select name="mood_general" id="mood_general">
                <option value="">Bitte wählen</option>
                <option value="Stabil / Normal" <?php echo ($entryData['mood_general'] ?? '') === 'Stabil / Normal' ? 'selected' : ''; ?>>Stabil / Normal</option>
                <option value="Reizbar / Ungeduldig" <?php echo ($entryData['mood_general'] ?? '') === 'Reizbar / Ungeduldig' ? 'selected' : ''; ?>>Reizbar / Ungeduldig</option>
                <option value="Niedergeschlagen / Traurig" <?php echo ($entryData['mood_general'] ?? '') === 'Niedergeschlagen / Traurig' ? 'selected' : ''; ?>>Niedergeschlagen / Traurig</option>
                <option value="Ängstlich" <?php echo ($entryData['mood_general'] ?? '') === 'Ängstlich' ? 'selected' : ''; ?>>Ängstlich</option>
                <option value="Angespannt / Nervös" <?php echo ($entryData['mood_general'] ?? '') === 'Angespannt / Nervös' ? 'selected' : ''; ?>>Angespannt / Nervös</option>
                <option value="Apathisch / Gefühllos" <?php echo ($entryData['mood_general'] ?? '') === 'Apathisch / Gefühllos' ? 'selected' : ''; ?>>Apathisch / Gefühllos</option>
                <option value="Variabel / Wechselhaft" <?php echo ($entryData['mood_general'] ?? '') === 'Variabel / Wechselhaft' ? 'selected' : ''; ?>>Variabel / Wechselhaft</option>
            </select>
            <label for="mood_depression_severity">Depressive Symptome vorhanden?</label>
            <select name="mood_depression_severity" id="mood_depression_severity">
                <option value="">Bitte wählen</option>
                <option value="Nein" <?php echo ($entryData['mood_depression_severity'] ?? '') === 'Nein' ? 'selected' : ''; ?>>Nein</option>
                <option value="Leichte depressive Symptome" <?php echo ($entryData['mood_depression_severity'] ?? '') === 'Leichte depressive Symptome' ? 'selected' : ''; ?>>Leichte depressive Symptome</option>
                <option value="Moderate depressive Symptome" <?php echo ($entryData['mood_depression_severity'] ?? '') === 'Moderate depressive Symptome' ? 'selected' : ''; ?>>Moderate depressive Symptome</option>
                <option value="Schwere depressive Symptome" <?php echo ($entryData['mood_depression_severity'] ?? '') === 'Schwere depressive Symptome' ? 'selected' : ''; ?>>Schwere depressive Symptome</option>
            </select>
            <div class="checkbox-group">
                Wenn ja, welche?<br>
                <label><input type="checkbox" name="mood_depressive_symptoms[]" value="Traurigkeit" <?php echo in_array('Traurigkeit', $selected['mood_depressive_symptoms']) ? 'checked' : ''; ?>> Traurigkeit</label>
                <label><input type="checkbox" name="mood_depressive_symptoms[]" value="Hoffnungslosigkeit" <?php echo in_array('Hoffnungslosigkeit', $selected['mood_depressive_symptoms']) ? 'checked' : ''; ?>> Hoffnungslosigkeit</label>
                <label><input type="checkbox" name="mood_depressive_symptoms[]" value="Schlafstörungen" <?php echo in_array('Schlafstörungen', $selected['mood_depressive_symptoms']) ? 'checked' : ''; ?>> Schlafstörungen</label>
                <label><input type="checkbox" name="mood_depressive_symptoms[]" value="Appetitlosigkeit" <?php echo in_array('Appetitlosigkeit', $selected['mood_depressive_symptoms']) ? 'checked' : ''; ?>> Appetitlosigkeit</label>
                <label><input type="checkbox" name="mood_depressive_symptoms[]" value="Konzentrationsprobleme" <?php echo in_array('Konzentrationsprobleme', $selected['mood_depressive_symptoms']) ? 'checked' : ''; ?>> Konzentrationsprobleme</label>
                <label><input type="checkbox" name="mood_depressive_symptoms[]" value="Motivationsverlust" <?php echo in_array('Motivationsverlust', $selected['mood_depressive_symptoms']) ? 'checked' : ''; ?>> Motivationsverlust</label>
                <label><input type="checkbox" name="mood_depressive_symptoms[]" value="Gedanken schadet sich selbst / anderen" <?php echo in_array('Gedanken schadet sich selbst / anderen', $selected['mood_depressive_symptoms']) ? 'checked' : ''; ?>> Gedanken schadet sich selbst / anderen</label>
                <label><input type="checkbox" name="mood_depressive_symptoms[]" value="Sonstiges" <?php echo in_array('Sonstiges', $selected['mood_depressive_symptoms']) ? 'checked' : ''; ?>> Sonstiges</label>
            </div>
            <label for="mood_apathy_severity">Antriebslosigkeit / Apathie:</label>
            <select name="mood_apathy_severity" id="mood_apathy_severity">
                <option value="">Bitte wählen</option>
                <option value="Nein" <?php echo ($entryData['mood_apathy_severity'] ?? '') === 'Nein' ? 'selected' : ''; ?>>Nein</option>
                <option value="Leicht" <?php echo ($entryData['mood_apathy_severity'] ?? '') === 'Leicht' ? 'selected' : ''; ?>>Leicht</option>
                <option value="Moderat" <?php echo ($entryData['mood_apathy_severity'] ?? '') === 'Moderat' ? 'selected' : ''; ?>>Moderat</option>
                <option value="Schwer" <?php echo ($entryData['mood_apathy_severity'] ?? '') === 'Schwer' ? 'selected' : ''; ?>>Schwer</option>
            </select>
            <label for="mood_motivation_score">Motivation/Antrieb (1-10):</label>
            <input type="number" id="mood_motivation_score" name="mood_motivation_score" min="1" max="10" value="<?php echo htmlspecialchars($entryData['mood_motivation_score'] ?? '', ENT_QUOTES); ?>">
            <label for="mood_anxiety_severity">Angst / Angststörung:</label>
            <select name="mood_anxiety_severity" id="mood_anxiety_severity">
                <option value="">Bitte wählen</option>
                <option value="Nein" <?php echo ($entryData['mood_anxiety_severity'] ?? '') === 'Nein' ? 'selected' : ''; ?>>Nein</option>
                <option value="Leichte Besorgnis" <?php echo ($entryData['mood_anxiety_severity'] ?? '') === 'Leichte Besorgnis' ? 'selected' : ''; ?>>Leichte Besorgnis</option>
                <option value="Moderate Angst" <?php echo ($entryData['mood_anxiety_severity'] ?? '') === 'Moderate Angst' ? 'selected' : ''; ?>>Moderate Angst</option>
                <option value="Schwere Angststörung" <?php echo ($entryData['mood_anxiety_severity'] ?? '') === 'Schwere Angststörung' ? 'selected' : ''; ?>>Schwere Angststörung</option>
            </select>
            <div class="checkbox-group">
                Art der Angst:<br>
                <label><input type="checkbox" name="mood_anxiety_types[]" value="Generalisierte Angst" <?php echo in_array('Generalisierte Angst', $selected['mood_anxiety_types']) ? 'checked' : ''; ?>> Generalisierte Angst</label>
                <label><input type="checkbox" name="mood_anxiety_types[]" value="Panikattacken" <?php echo in_array('Panikattacken', $selected['mood_anxiety_types']) ? 'checked' : ''; ?>> Panikattacken</label>
                <label><input type="checkbox" name="mood_anxiety_types[]" value="Soziale Angst" <?php echo in_array('Soziale Angst', $selected['mood_anxiety_types']) ? 'checked' : ''; ?>> Soziale Angst</label>
                <label><input type="checkbox" name="mood_anxiety_types[]" value="Angst vor Erkrankung" <?php echo in_array('Angst vor Erkrankung', $selected['mood_anxiety_types']) ? 'checked' : ''; ?>> Angst vor Erkrankung</label>
                <label><input type="checkbox" name="mood_anxiety_types[]" value="Unspezifisch" <?php echo in_array('Unspezifisch', $selected['mood_anxiety_types']) ? 'checked' : ''; ?>> Unspezifisch</label>
                <label><input type="checkbox" name="mood_anxiety_types[]" value="Sonstiges" <?php echo in_array('Sonstiges', $selected['mood_anxiety_types']) ? 'checked' : ''; ?>> Sonstiges</label>
            </div>
        </fieldset>

        <fieldset>
            <legend>15. Kognitive Funktionen</legend>
            <label for="cognitive_concentration">Konzentration:</label>
            <select name="cognitive_concentration" id="cognitive_concentration">
                <option value="">Bitte wählen</option>
                <option value="Normal / Gut" <?php echo ($entryData['cognitive_concentration'] ?? '') === 'Normal / Gut' ? 'selected' : ''; ?>>Normal / Gut</option>
                <option value="Leicht vermindert" <?php echo ($entryData['cognitive_concentration'] ?? '') === 'Leicht vermindert' ? 'selected' : ''; ?>>Leicht vermindert</option>
                <option value="Deutlich vermindert" <?php echo ($entryData['cognitive_concentration'] ?? '') === 'Deutlich vermindert' ? 'selected' : ''; ?>>Deutlich vermindert</option>
                <option value="Schwer beeinträchtigt" <?php echo ($entryData['cognitive_concentration'] ?? '') === 'Schwer beeinträchtigt' ? 'selected' : ''; ?>>Schwer beeinträchtigt</option>
            </select>
            <label for="cognitive_memory">Gedächtnis:</label>
            <select name="cognitive_memory" id="cognitive_memory">
                <option value="">Bitte wählen</option>
                <option value="Normal" <?php echo ($entryData['cognitive_memory'] ?? '') === 'Normal' ? 'selected' : ''; ?>>Normal</option>
                <option value="Leicht vermindert" <?php echo ($entryData['cognitive_memory'] ?? '') === 'Leicht vermindert' ? 'selected' : ''; ?>>Leicht vermindert</option>
                <option value="Deutlich vermindert" <?php echo ($entryData['cognitive_memory'] ?? '') === 'Deutlich vermindert' ? 'selected' : ''; ?>>Deutlich vermindert</option>
                <option value="Schwer beeinträchtigt" <?php echo ($entryData['cognitive_memory'] ?? '') === 'Schwer beeinträchtigt' ? 'selected' : ''; ?>>Schwer beeinträchtigt</option>
            </select>
            <div class="checkbox-group">
                Spezifische Probleme:<br>
                <label><input type="checkbox" name="cognitive_specific_issues[]" value="Worte vergessen / Zungenspitzenwort" <?php echo in_array('Worte vergessen / Zungenspitzenwort', $selected['cognitive_specific_issues']) ? 'checked' : ''; ?>> Worte vergessen / Zungenspitzenwort</label>
                <label><input type="checkbox" name="cognitive_specific_issues[]" value="Namen vergessen" <?php echo in_array('Namen vergessen', $selected['cognitive_specific_issues']) ? 'checked' : ''; ?>> Namen vergessen</label>
                <label><input type="checkbox" name="cognitive_specific_issues[]" value="Warum ging ich ins Zimmer?" <?php echo in_array('Warum ging ich ins Zimmer?', $selected['cognitive_specific_issues']) ? 'checked' : ''; ?>> Warum ging ich ins Zimmer?</label>
                <label><input type="checkbox" name="cognitive_specific_issues[]" value="Termine/Aufgaben vergessen" <?php echo in_array('Termine/Aufgaben vergessen', $selected['cognitive_specific_issues']) ? 'checked' : ''; ?>> Termine/Aufgaben vergessen</label>
                <label><input type="checkbox" name="cognitive_specific_issues[]" value="Schwer mich zu konzentrieren" <?php echo in_array('Schwer mich zu konzentrieren', $selected['cognitive_specific_issues']) ? 'checked' : ''; ?>> Schwer mich zu konzentrieren</label>
                <label><input type="checkbox" name="cognitive_specific_issues[]" value="Leicht abgelenkt" <?php echo in_array('Leicht abgelenkt', $selected['cognitive_specific_issues']) ? 'checked' : ''; ?>> Leicht abgelenkt</label>
                <label><input type="checkbox" name="cognitive_specific_issues[]" value="Sonstiges" <?php echo in_array('Sonstiges', $selected['cognitive_specific_issues']) ? 'checked' : ''; ?>> Sonstiges</label>
            </div>
            <label for="cognitive_fitness_score">Kognitive Fitness (1-10):</label>
            <input type="number" id="cognitive_fitness_score" name="cognitive_fitness_score" min="1" max="10" value="<?php echo htmlspecialchars($entryData['cognitive_fitness_score'] ?? '', ENT_QUOTES); ?>">
            <label for="cognitive_impact">Auswirkungen:</label>
            <select name="cognitive_impact" id="cognitive_impact">
                <option value="">Bitte wählen</option>
                <option value="Keine im Alltag" <?php echo ($entryData['cognitive_impact'] ?? '') === 'Keine im Alltag' ? 'selected' : ''; ?>>Keine im Alltag</option>
                <option value="Gering" <?php echo ($entryData['cognitive_impact'] ?? '') === 'Gering' ? 'selected' : ''; ?>>Gering</option>
                <option value="Moderat" <?php echo ($entryData['cognitive_impact'] ?? '') === 'Moderat' ? 'selected' : ''; ?>>Moderat</option>
                <option value="Bedeutsam beeinträchtigt" <?php echo ($entryData['cognitive_impact'] ?? '') === 'Bedeutsam beeinträchtigt' ? 'selected' : ''; ?>>Bedeutsam beeinträchtigt</option>
            </select>
        </fieldset>

        <fieldset>
            <legend>16. Schmerzen</legend>
            <label for="pain_severity">Schmerzen vorhanden?</label>
            <select name="pain_severity" id="pain_severity">
                <option value="">Bitte wählen</option>
                <option value="Nein" <?php echo ($entryData['pain_severity'] ?? '') === 'Nein' ? 'selected' : ''; ?>>Nein</option>
                <option value="Ja, leicht" <?php echo ($entryData['pain_severity'] ?? '') === 'Ja, leicht' ? 'selected' : ''; ?>>Ja, leicht</option>
                <option value="Ja, moderat" <?php echo ($entryData['pain_severity'] ?? '') === 'Ja, moderat' ? 'selected' : ''; ?>>Ja, moderat</option>
                <option value="Ja, stark" <?php echo ($entryData['pain_severity'] ?? '') === 'Ja, stark' ? 'selected' : ''; ?>>Ja, stark</option>
            </select>
            <label><input type="checkbox" name="pain_present" value="1" <?php echo !empty($entryData['pain_present']) ? 'checked' : ''; ?>> Schmerzen vorhanden (Detail)</label>
            <div class="checkbox-group">
                Lokalisation:<br>
                <label><input type="checkbox" name="pain_locations[]" value="Nackenbereich" <?php echo in_array('Nackenbereich', $selected['pain_locations']) ? 'checked' : ''; ?>> Nackenbereich</label>
                <label><input type="checkbox" name="pain_locations[]" value="Schultern" <?php echo in_array('Schultern', $selected['pain_locations']) ? 'checked' : ''; ?>> Schultern</label>
                <label><input type="checkbox" name="pain_locations[]" value="Oberer Rücken" <?php echo in_array('Oberer Rücken', $selected['pain_locations']) ? 'checked' : ''; ?>> Oberer Rücken</label>
                <label><input type="checkbox" name="pain_locations[]" value="Unterer Rücken" <?php echo in_array('Unterer Rücken', $selected['pain_locations']) ? 'checked' : ''; ?>> Unterer Rücken</label>
                <label><input type="checkbox" name="pain_locations[]" value="Arme / Unterarme" <?php echo in_array('Arme / Unterarme', $selected['pain_locations']) ? 'checked' : ''; ?>> Arme / Unterarme</label>
                <label><input type="checkbox" name="pain_locations[]" value="Hände / Finger" <?php echo in_array('Hände / Finger', $selected['pain_locations']) ? 'checked' : ''; ?>> Hände / Finger</label>
                <label><input type="checkbox" name="pain_locations[]" value="Hüfte" <?php echo in_array('Hüfte', $selected['pain_locations']) ? 'checked' : ''; ?>> Hüfte</label>
                <label><input type="checkbox" name="pain_locations[]" value="Beine" <?php echo in_array('Beine', $selected['pain_locations']) ? 'checked' : ''; ?>> Beine</label>
                <label><input type="checkbox" name="pain_locations[]" value="Füße" <?php echo in_array('Füße', $selected['pain_locations']) ? 'checked' : ''; ?>> Füße</label>
                <label><input type="checkbox" name="pain_locations[]" value="Sonstiges" <?php echo in_array('Sonstiges', $selected['pain_locations']) ? 'checked' : ''; ?>> Sonstiges</label>
                <input type="text" name="pain_locations_other" placeholder="Sonstiges" value="<?php echo htmlspecialchars($entryData['pain_locations_other'] ?? '', ENT_QUOTES); ?>">
            </div>
            <div class="checkbox-group">
                Schmerzcharakter:<br>
                <label><input type="checkbox" name="pain_character[]" value="Stechend / Akut" <?php echo in_array('Stechend / Akut', $selected['pain_character']) ? 'checked' : ''; ?>> Stechend / Akut</label>
                <label><input type="checkbox" name="pain_character[]" value="Ziehend / Dehnend" <?php echo in_array('Ziehend / Dehnend', $selected['pain_character']) ? 'checked' : ''; ?>> Ziehend / Dehnend</label>
                <label><input type="checkbox" name="pain_character[]" value="Krampf / Spastik" <?php echo in_array('Krampf / Spastik', $selected['pain_character']) ? 'checked' : ''; ?>> Krampf / Spastik</label>
                <label><input type="checkbox" name="pain_character[]" value="Muskelkater / Druckschmerz" <?php echo in_array('Muskelkater / Druckschmerz', $selected['pain_character']) ? 'checked' : ''; ?>> Muskelkater / Druckschmerz</label>
                <label><input type="checkbox" name="pain_character[]" value="Taubes Gefühl / Kribbeln" <?php echo in_array('Taubes Gefühl / Kribbeln', $selected['pain_character']) ? 'checked' : ''; ?>> Taubes Gefühl / Kribbeln</label>
                <label><input type="checkbox" name="pain_character[]" value="Unspezifisch" <?php echo in_array('Unspezifisch', $selected['pain_character']) ? 'checked' : ''; ?>> Unspezifisch</label>
                <label><input type="checkbox" name="pain_character[]" value="Sonstiges" <?php echo in_array('Sonstiges', $selected['pain_character']) ? 'checked' : ''; ?>> Sonstiges</label>
                <input type="text" name="pain_character_other" placeholder="Weitere Beschreibung" value="<?php echo htmlspecialchars($entryData['pain_character_other'] ?? '', ENT_QUOTES); ?>">
            </div>
            <label for="pain_intensity">Intensität (1-10):</label>
            <input type="number" id="pain_intensity" name="pain_intensity" min="1" max="10" value="<?php echo htmlspecialchars($entryData['pain_intensity'] ?? '', ENT_QUOTES); ?>">
            <label for="pain_duration">Dauer:</label>
            <select name="pain_duration" id="pain_duration">
                <option value="">Bitte wählen</option>
                <option value="Weniger als 1 Stunde" <?php echo ($entryData['pain_duration'] ?? '') === 'Weniger als 1 Stunde' ? 'selected' : ''; ?>>Weniger als 1 Stunde</option>
                <option value="1-4 Stunden" <?php echo ($entryData['pain_duration'] ?? '') === '1-4 Stunden' ? 'selected' : ''; ?>>1-4 Stunden</option>
                <option value="Mehr als 4 Stunden" <?php echo ($entryData['pain_duration'] ?? '') === 'Mehr als 4 Stunden' ? 'selected' : ''; ?>>Mehr als 4 Stunden</option>
                <option value="Ganztägig" <?php echo ($entryData['pain_duration'] ?? '') === 'Ganztägig' ? 'selected' : ''; ?>>Ganztägig</option>
            </select>
            <label for="pain_trigger">Auslöser / Triggering:</label>
            <textarea id="pain_trigger" name="pain_trigger"><?php echo htmlspecialchars($entryData['pain_trigger'] ?? '', ENT_QUOTES); ?></textarea>
            <div class="checkbox-group">
                Besserung durch:<br>
                <label><input type="checkbox" name="pain_relief_methods[]" value="Ruhe" <?php echo in_array('Ruhe', $selected['pain_relief_methods']) ? 'checked' : ''; ?>> Ruhe</label>
                <label><input type="checkbox" name="pain_relief_methods[]" value="Bewegung" <?php echo in_array('Bewegung', $selected['pain_relief_methods']) ? 'checked' : ''; ?>> Bewegung</label>
                <label><input type="checkbox" name="pain_relief_methods[]" value="Wärme" <?php echo in_array('Wärme', $selected['pain_relief_methods']) ? 'checked' : ''; ?>> Wärme</label>
                <label><input type="checkbox" name="pain_relief_methods[]" value="Kälte" <?php echo in_array('Kälte', $selected['pain_relief_methods']) ? 'checked' : ''; ?>> Kälte</label>
                <label><input type="checkbox" name="pain_relief_methods[]" value="Position" <?php echo in_array('Position', $selected['pain_relief_methods']) ? 'checked' : ''; ?>> Position</label>
                <label><input type="checkbox" name="pain_relief_methods[]" value="Massage" <?php echo in_array('Massage', $selected['pain_relief_methods']) ? 'checked' : ''; ?>> Massage</label>
                <label><input type="checkbox" name="pain_relief_methods[]" value="Medikamente" <?php echo in_array('Medikamente', $selected['pain_relief_methods']) ? 'checked' : ''; ?>> Medikamente</label>
                <label><input type="checkbox" name="pain_relief_methods[]" value="Sonstiges" <?php echo in_array('Sonstiges', $selected['pain_relief_methods']) ? 'checked' : ''; ?>> Sonstiges</label>
            </div>
            <label for="pain_notes">Besonderheiten:</label>
            <textarea id="pain_notes" name="pain_notes"><?php echo htmlspecialchars($entryData['pain_notes'] ?? '', ENT_QUOTES); ?></textarea>
        </fieldset>

        <fieldset>
            <legend>17. Verdauung und Appetit</legend>
            <label for="digestion_status">Verdauungsfunktion:</label>
            <select name="digestion_status" id="digestion_status">
                <option value="">Bitte wählen</option>
                <option value="Normal" <?php echo ($entryData['digestion_status'] ?? '') === 'Normal' ? 'selected' : ''; ?>>Normal</option>
                <option value="Verstopfung" <?php echo ($entryData['digestion_status'] ?? '') === 'Verstopfung' ? 'selected' : ''; ?>>Verstopfung</option>
                <option value="Durchfall" <?php echo ($entryData['digestion_status'] ?? '') === 'Durchfall' ? 'selected' : ''; ?>>Durchfall</option>
                <option value="Unregelmäßig" <?php echo ($entryData['digestion_status'] ?? '') === 'Unregelmäßig' ? 'selected' : ''; ?>>Unregelmäßig</option>
            </select>
            <label for="stool_frequency">Stuhlgang-Häufigkeit:</label>
            <select name="stool_frequency" id="stool_frequency">
                <option value="">Bitte wählen</option>
                <option value="1-2x täglich" <?php echo ($entryData['stool_frequency'] ?? '') === '1-2x täglich' ? 'selected' : ''; ?>>1-2x täglich</option>
                <option value="Jeden zweiten Tag" <?php echo ($entryData['stool_frequency'] ?? '') === 'Jeden zweiten Tag' ? 'selected' : ''; ?>>Jeden zweiten Tag</option>
                <option value="Alle 2-3 Tage" <?php echo ($entryData['stool_frequency'] ?? '') === 'Alle 2-3 Tage' ? 'selected' : ''; ?>>Alle 2-3 Tage</option>
                <option value="Seltener als 3 Tage" <?php echo ($entryData['stool_frequency'] ?? '') === 'Seltener als 3 Tage' ? 'selected' : ''; ?>>Seltener als 3 Tage</option>
            </select>
            <div class="checkbox-group">
                Verstopfung-Details:<br>
                <label><input type="checkbox" name="constipation_details[]" value="Harter Stuhl" <?php echo in_array('Harter Stuhl', $selected['constipation_details']) ? 'checked' : ''; ?>> Harter Stuhl</label>
                <label><input type="checkbox" name="constipation_details[]" value="Mühe beim Toilettengang" <?php echo in_array('Mühe beim Toilettengang', $selected['constipation_details']) ? 'checked' : ''; ?>> Mühe beim Toilettengang</label>
                <label><input type="checkbox" name="constipation_details[]" value="Unvollständiges Gefühl" <?php echo in_array('Unvollständiges Gefühl', $selected['constipation_details']) ? 'checked' : ''; ?>> Unvollständiges Gefühl</label>
                <label><input type="checkbox" name="constipation_details[]" value="Bauchschmerzen" <?php echo in_array('Bauchschmerzen', $selected['constipation_details']) ? 'checked' : ''; ?>> Bauchschmerzen</label>
                <label><input type="checkbox" name="constipation_details[]" value="Aufgetriebenes Gefühl" <?php echo in_array('Aufgetriebenes Gefühl', $selected['constipation_details']) ? 'checked' : ''; ?>> Aufgetriebenes Gefühl</label>
            </div>
            <label for="appetite_status">Appetit:</label>
            <select name="appetite_status" id="appetite_status">
                <option value="">Bitte wählen</option>
                <option value="Normal" <?php echo ($entryData['appetite_status'] ?? '') === 'Normal' ? 'selected' : ''; ?>>Normal</option>
                <option value="Leicht vermindert" <?php echo ($entryData['appetite_status'] ?? '') === 'Leicht vermindert' ? 'selected' : ''; ?>>Leicht vermindert</option>
                <option value="Deutlich vermindert" <?php echo ($entryData['appetite_status'] ?? '') === 'Deutlich vermindert' ? 'selected' : ''; ?>>Deutlich vermindert</option>
                <option value="Gesteigert" <?php echo ($entryData['appetite_status'] ?? '') === 'Gesteigert' ? 'selected' : ''; ?>>Gesteigert</option>
            </select>
            <label for="weight_change">Gewichtsveränderung:</label>
            <select name="weight_change" id="weight_change">
                <option value="">Bitte wählen</option>
                <option value="Stabil" <?php echo ($entryData['weight_change'] ?? '') === 'Stabil' ? 'selected' : ''; ?>>Stabil</option>
                <option value="Langsamer Gewichtsverlust" <?php echo ($entryData['weight_change'] ?? '') === 'Langsamer Gewichtsverlust' ? 'selected' : ''; ?>>Langsamer Gewichtsverlust</option>
                <option value="Gewichtszunahme" <?php echo ($entryData['weight_change'] ?? '') === 'Gewichtszunahme' ? 'selected' : ''; ?>>Gewichtszunahme</option>
                <option value="Schwankend" <?php echo ($entryData['weight_change'] ?? '') === 'Schwankend' ? 'selected' : ''; ?>>Schwankend</option>
            </select>
            <label for="weight_change_notes">Details zu Gewichtsveränderung (z.B. kg/Wochen):</label>
            <input type="text" id="weight_change_notes" name="weight_change_notes" value="<?php echo htmlspecialchars($entryData['weight_change_notes'] ?? '', ENT_QUOTES); ?>">
            <label for="digestion_notes">Besonderheiten:</label>
            <textarea id="digestion_notes" name="digestion_notes"><?php echo htmlspecialchars($entryData['digestion_notes'] ?? '', ENT_QUOTES); ?></textarea>
        </fieldset>

        <fieldset>
            <legend>18. Vegetative Symptome</legend>
            <div class="checkbox-group">
                Blutdruck-Symptome:<br>
                <label><input type="checkbox" name="vegetative_blood_pressure[]" value="Keine" <?php echo in_array('Keine', $selected['vegetative_blood_pressure']) ? 'checked' : ''; ?>> Nein</label>
                <label><input type="checkbox" name="vegetative_blood_pressure[]" value="Schwindel beim Aufstehen" <?php echo in_array('Schwindel beim Aufstehen', $selected['vegetative_blood_pressure']) ? 'checked' : ''; ?>> Schwindel beim Aufstehen</label>
                <label><input type="checkbox" name="vegetative_blood_pressure[]" value="Schwarzwerden vor Augen" <?php echo in_array('Schwarzwerden vor Augen', $selected['vegetative_blood_pressure']) ? 'checked' : ''; ?>> Schwarzwerden vor Augen</label>
                <label><input type="checkbox" name="vegetative_blood_pressure[]" value="Ohnmachtsgefühl" <?php echo in_array('Ohnmachtsgefühl', $selected['vegetative_blood_pressure']) ? 'checked' : ''; ?>> Ohnmachtsgefühl</label>
                <label><input type="checkbox" name="vegetative_blood_pressure[]" value="Ohrensausen" <?php echo in_array('Ohrensausen', $selected['vegetative_blood_pressure']) ? 'checked' : ''; ?>> Ohrensausen</label>
            </div>
            <label for="vegetative_sweating">Schwitzen:</label>
            <select name="vegetative_sweating" id="vegetative_sweating">
                <option value="">Bitte wählen</option>
                <option value="Normal" <?php echo ($entryData['vegetative_sweating'] ?? '') === 'Normal' ? 'selected' : ''; ?>>Normal</option>
                <option value="Erhöhte Schweißproduktion" <?php echo ($entryData['vegetative_sweating'] ?? '') === 'Erhöhte Schweißproduktion' ? 'selected' : ''; ?>>Erhöhte Schweißproduktion</option>
                <option value="Nachtschweiß" <?php echo ($entryData['vegetative_sweating'] ?? '') === 'Nachtschweiß' ? 'selected' : ''; ?>>Nachtschweiß</option>
                <option value="Unpassende Zeiten" <?php echo ($entryData['vegetative_sweating'] ?? '') === 'Unpassende Zeiten' ? 'selected' : ''; ?>>Unpassende Zeiten</option>
            </select>
            <label for="vegetative_temperature">Temperaturempfindung:</label>
            <select name="vegetative_temperature" id="vegetative_temperature">
                <option value="">Bitte wählen</option>
                <option value="Normal" <?php echo ($entryData['vegetative_temperature'] ?? '') === 'Normal' ? 'selected' : ''; ?>>Normal</option>
                <option value="Frieren" <?php echo ($entryData['vegetative_temperature'] ?? '') === 'Frieren' ? 'selected' : ''; ?>>Frieren</option>
                <option value="Überempfindlich gegen Kälte" <?php echo ($entryData['vegetative_temperature'] ?? '') === 'Überempfindlich gegen Kälte' ? 'selected' : ''; ?>>Überempfindlich gegen Kälte</option>
                <option value="Überempfindlich gegen Wärme" <?php echo ($entryData['vegetative_temperature'] ?? '') === 'Überempfindlich gegen Wärme' ? 'selected' : ''; ?>>Überempfindlich gegen Wärme</option>
            </select>
            <label for="vegetative_bladder">Harndrang / Blasenfunktion:</label>
            <select name="vegetative_bladder" id="vegetative_bladder">
                <option value="">Bitte wählen</option>
                <option value="Normal" <?php echo ($entryData['vegetative_bladder'] ?? '') === 'Normal' ? 'selected' : ''; ?>>Normal</option>
                <option value="Erhöhter Harndrang" <?php echo ($entryData['vegetative_bladder'] ?? '') === 'Erhöhter Harndrang' ? 'selected' : ''; ?>>Erhöhter Harndrang</option>
                <option value="Nykturie" <?php echo ($entryData['vegetative_bladder'] ?? '') === 'Nykturie' ? 'selected' : ''; ?>>Nykturie (nächtliches Wasserlassen)</option>
                <option value="Urininkontinenz" <?php echo ($entryData['vegetative_bladder'] ?? '') === 'Urininkontinenz' ? 'selected' : ''; ?>>Urininkontinenz</option>
                <option value="Harnverhalt" <?php echo ($entryData['vegetative_bladder'] ?? '') === 'Harnverhalt' ? 'selected' : ''; ?>>Harnverhalt</option>
            </select>
        </fieldset>

        <h2>C. ZUSÄTZLICHE BEOBACHTUNGEN</h2>
        <fieldset>
            <legend>19. Medikamente und Interventionen</legend>
            <label><input type="checkbox" name="meds_taken" value="1" <?php echo !empty($entryData['meds_taken']) ? 'checked' : ''; ?>> Medikamente eingenommen?</label>
            <label for="meds_details">Medikamente (Name + Dosierung):</label>
            <textarea id="meds_details" name="meds_details"><?php echo htmlspecialchars($entryData['meds_details'] ?? '', ENT_QUOTES); ?></textarea>
            <label for="meds_timing">Zeitpunkt der Medikation:</label>
            <input type="text" id="meds_timing" name="meds_timing" value="<?php echo htmlspecialchars($entryData['meds_timing'] ?? '', ENT_QUOTES); ?>">
            <label for="meds_effect">Auswirkung auf Symptome:</label>
            <select name="meds_effect" id="meds_effect">
                <option value="">Bitte wählen</option>
                <option value="Keine" <?php echo ($entryData['meds_effect'] ?? '') === 'Keine' ? 'selected' : ''; ?>>Keine</option>
                <option value="Verbessert" <?php echo ($entryData['meds_effect'] ?? '') === 'Verbessert' ? 'selected' : ''; ?>>Verbessert</option>
                <option value="Verschlimmert" <?php echo ($entryData['meds_effect'] ?? '') === 'Verschlimmert' ? 'selected' : ''; ?>>Verschlimmert</option>
                <option value="Andere" <?php echo ($entryData['meds_effect'] ?? '') === 'Andere' ? 'selected' : ''; ?>>Andere</option>
            </select>
            <div class="checkbox-group">
                Sonstiges:<br>
                <label><input type="checkbox" name="lifestyle_factors[]" value="Sport / Bewegung durchgeführt" <?php echo in_array('Sport / Bewegung durchgeführt', $selected['lifestyle_factors']) ? 'checked' : ''; ?>> Sport / Bewegung durchgeführt</label>
                <label><input type="checkbox" name="lifestyle_factors[]" value="Stressfulles Ereignis" <?php echo in_array('Stressfulles Ereignis', $selected['lifestyle_factors']) ? 'checked' : ''; ?>> Stressfulles Ereignis</label>
                <label><input type="checkbox" name="lifestyle_factors[]" value="Besondere Belastung" <?php echo in_array('Besondere Belastung', $selected['lifestyle_factors']) ? 'checked' : ''; ?>> Besondere Belastung</label>
                <label><input type="checkbox" name="lifestyle_factors[]" value="Mehr/weniger Schlaf" <?php echo in_array('Mehr/weniger Schlaf', $selected['lifestyle_factors']) ? 'checked' : ''; ?>> Mehr/weniger Schlaf</label>
                <label><input type="checkbox" name="lifestyle_factors[]" value="Anderes" <?php echo in_array('Anderes', $selected['lifestyle_factors']) ? 'checked' : ''; ?>> Anderes</label>
            </div>
            <label for="lifestyle_factors_other">Details zu Anderes:</label>
            <input type="text" id="lifestyle_factors_other" name="lifestyle_factors_other" value="<?php echo htmlspecialchars($entryData['lifestyle_factors_other'] ?? '', ENT_QUOTES); ?>">
        </fieldset>

        <fieldset>
            <legend>20. Auslöser und Muster</legend>
            <div class="checkbox-group">
                Wurde ein Muster erkannt?<br>
                <label><input type="checkbox" name="pattern_recognized[]" value="Symptome morgens schlimmer" <?php echo in_array('Symptome morgens schlimmer', $selected['pattern_recognized']) ? 'checked' : ''; ?>> Symptome morgens schlimmer</label>
                <label><input type="checkbox" name="pattern_recognized[]" value="Symptome abends schlimmer" <?php echo in_array('Symptome abends schlimmer', $selected['pattern_recognized']) ? 'checked' : ''; ?>> Symptome abends schlimmer</label>
                <label><input type="checkbox" name="pattern_recognized[]" value="Symptome nach Mahlzeiten" <?php echo in_array('Symptome nach Mahlzeiten', $selected['pattern_recognized']) ? 'checked' : ''; ?>> Symptome nach Mahlzeiten</label>
                <label><input type="checkbox" name="pattern_recognized[]" value="Symptome nach Kaffee" <?php echo in_array('Symptome nach Kaffee', $selected['pattern_recognized']) ? 'checked' : ''; ?>> Symptome nach Kaffee</label>
                <label><input type="checkbox" name="pattern_recognized[]" value="Symptome nach Stress" <?php echo in_array('Symptome nach Stress', $selected['pattern_recognized']) ? 'checked' : ''; ?>> Symptome nach Stress</label>
                <label><input type="checkbox" name="pattern_recognized[]" value="Symptome nach wenig Schlaf" <?php echo in_array('Symptome nach wenig Schlaf', $selected['pattern_recognized']) ? 'checked' : ''; ?>> Symptome nach wenig Schlaf</label>
                <label><input type="checkbox" name="pattern_recognized[]" value="Symptome nach Bewegung" <?php echo in_array('Symptome nach Bewegung', $selected['pattern_recognized']) ? 'checked' : ''; ?>> Symptome nach Bewegung</label>
                <label><input type="checkbox" name="pattern_recognized[]" value="Kein erkanntes Muster" <?php echo in_array('Kein erkanntes Muster', $selected['pattern_recognized']) ? 'checked' : ''; ?>> Kein erkanntes Muster</label>
            </div>
            <label for="pattern_triggers">Mögliche Auslöser heute:</label>
            <textarea id="pattern_triggers" name="pattern_triggers"><?php echo htmlspecialchars($entryData['pattern_triggers'] ?? '', ENT_QUOTES); ?></textarea>
            <label for="pattern_helped">Was hat geholfen?</label>
            <textarea id="pattern_helped" name="pattern_helped"><?php echo htmlspecialchars($entryData['pattern_helped'] ?? '', ENT_QUOTES); ?></textarea>
            <label for="overall_wellbeing_score">Gesamtbefindlichkeit heute (1-10):</label>
            <input type="number" id="overall_wellbeing_score" name="overall_wellbeing_score" min="1" max="10" value="<?php echo htmlspecialchars($entryData['overall_wellbeing_score'] ?? '', ENT_QUOTES); ?>">
        </fieldset>

        <fieldset>
            <legend>Tägliche Zusammenfassung</legend>
            <label for="daily_summary_notes">Notizen / Zusammenfassung:</label>
            <textarea id="daily_summary_notes" name="daily_summary_notes"><?php echo htmlspecialchars($entryData['daily_summary_notes'] ?? '', ENT_QUOTES); ?></textarea>
        </fieldset>

        <button type="submit"><?php echo $isEdit ? 'Änderungen speichern' : 'Eintrag speichern'; ?></button>
    </form>
</body>
</html>
