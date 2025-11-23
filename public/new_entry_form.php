<?php
$isEdit = !empty($entry);
$entryData = $entry ?? [];

function parse_multi_value(array $entryData, string $key): array {
    if (empty($entryData[$key])) {
        return [];
    }

    return array_filter(array_map('trim', explode(',', $entryData[$key])));
}

function parse_medication_rows(?string $raw): array {
    if (empty($raw)) {
        return [];
    }

    $decoded = json_decode($raw, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        return array_values(array_filter(array_map(static function ($row) {
            if (!is_array($row)) {
                return null;
            }

            $name = isset($row['name']) ? trim((string)$row['name']) : '';
            $dose = isset($row['dose']) ? trim((string)$row['dose']) : '';
            $time = isset($row['time']) ? trim((string)$row['time']) : '';

            if ($name === '' && $dose === '' && $time === '') {
                return null;
            }

            return ['name' => $name, 'dose' => $dose, 'time' => $time];
        }, $decoded)));
    }

    return [['name' => trim($raw), 'dose' => '', 'time' => '']];
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
$previousMedsDetails = $previousMedsDetails ?? '';
$medicationRows = parse_medication_rows($entryData['meds_details'] ?? '');

if (!$isEdit && empty($medicationRows) && $previousMedsDetails !== '') {
    $medicationRows = parse_medication_rows($previousMedsDetails);

    if (!empty($medicationRows)) {
        $entryData['meds_taken'] = $entryData['meds_taken'] ?? 1;
    }
}

if (empty($medicationRows)) {
    $medicationRows = [['name' => '', 'dose' => '', 'time' => '']];
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title><?php echo $isEdit ? 'Eintrag bearbeiten' : 'Neuer Tagebucheintrag'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4"><?php echo $isEdit ? 'Eintrag bearbeiten' : 'Neuer Tagebucheintrag'; ?></h1>
        <form action="index.php?action=<?php echo $isEdit ? 'update' : 'save'; ?>" method="post" class="space-y-6">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?php echo (int)$entryData['id']; ?>">
            <?php endif; ?>

            <div class="bg-white p-4 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2 cursor-pointer accordion-header">Metadaten</h2>
                <div class="accordion-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="entry_date" class="block text-sm font-medium text-gray-700">Datum:</label>
                            <input type="date" id="entry_date" name="entry_date" value="<?php echo htmlspecialchars($entryData['entry_date'] ?? date('Y-m-d'), ENT_QUOTES); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="entry_time" class="block text-sm font-medium text-gray-700">Uhrzeit:</label>
                            <input type="time" id="entry_time" name="entry_time" value="<?php echo htmlspecialchars($entryData['entry_time'] ?? date('H:i'), ENT_QUOTES); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2 cursor-pointer accordion-header">A. MOTORISCHE SYMPTOME</h2>
                <div class="accordion-content hidden">

                    <fieldset class="mt-4">
                        <legend class="text-lg font-medium text-gray-900">1. Tremor (Zittern)</legend>
                        <div class="mt-2 space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="tremor_present" value="1" <?php echo !empty($entryData['tremor_present']) ? 'checked' : ''; ?> class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Tremor vorhanden?</span>
                            </label>
                            <div>
                                <span class="text-sm font-medium text-gray-700">Betroffene Körperregion(en):</span>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-1">
                                    <label><input type="checkbox" name="tremor_regions[]" value="Hand links" <?php echo in_array('Hand links', $selected['tremor_regions']) ? 'checked' : ''; ?>> Hand links</label>
                                    <label><input type="checkbox" name="tremor_regions[]" value="Hand rechts" <?php echo in_array('Hand rechts', $selected['tremor_regions']) ? 'checked' : ''; ?>> Hand rechts</label>
                                    <label><input type="checkbox" name="tremor_regions[]" value="Kinn/Lippe" <?php echo in_array('Kinn/Lippe', $selected['tremor_regions']) ? 'checked' : ''; ?>> Kinn/Lippe</label>
                                    <label><input type="checkbox" name="tremor_regions[]" value="Bein" <?php echo in_array('Bein', $selected['tremor_regions']) ? 'checked' : ''; ?>> Bein</label>
                                    <label><input type="checkbox" name="tremor_regions[]" value="Kopf" <?php echo in_array('Kopf', $selected['tremor_regions']) ? 'checked' : ''; ?>> Kopf</label>
                                    <label><input type="checkbox" name="tremor_regions[]" value="Sonstiges" <?php echo in_array('Sonstiges', $selected['tremor_regions']) ? 'checked' : ''; ?>> Sonstiges</label>
                                </div>
                                <input type="text" name="tremor_regions_other" placeholder="Sonstiges" value="<?php echo htmlspecialchars($entryData['tremor_regions_other'] ?? '', ENT_QUOTES); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
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
                        </div>
                    </fieldset>
                    
                    <fieldset class="mt-4">
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
                </div>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"><?php echo $isEdit ? 'Änderungen speichern' : 'Eintrag speichern'; ?></button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const accordionHeaders = document.querySelectorAll('.accordion-header');

            accordionHeaders.forEach(header => {
                header.addEventListener('click', () => {
                    const content = header.nextElementSibling;
                    content.classList.toggle('hidden');
                });
            });

            const tableBody = document.getElementById('meds-table-body');
            if (tableBody) {
                const addButton = document.getElementById('add-meds-row');
                let medsRowCounter = tableBody.querySelectorAll('tr').length;

                function createInput(name, value, placeholder, type = 'text') {
                    const input = document.createElement('input');
                    input.type = type;
                    input.name = name;
                    input.value = value || '';
                    if (placeholder) {
                        input.placeholder = placeholder;
                    }
                    input.className = 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm';
                    return input;
                }

                function removeRow(row) {
                    row.remove();
                    if (!tableBody.querySelector('tr')) {
                        addRow();
                    }
                }

                function addRow(data = {}) {
                    const rowIndex = medsRowCounter++;
                    const tr = document.createElement('tr');

                    const nameTd = document.createElement('td');
                    nameTd.appendChild(createInput(`meds_table[${rowIndex}][name]`, data.name, 'Medikament'));

                    const doseTd = document.createElement('td');
                    doseTd.appendChild(createInput(`meds_table[${rowIndex}][dose]`, data.dose, 'z.B. 100 mg'));

                    const timeTd = document.createElement('td');
                    timeTd.appendChild(createInput(`meds_table[${rowIndex}][time]`, data.time, '', 'time'));

                    const actionsTd = document.createElement('td');
                    actionsTd.className = 'meds-actions';
                    const removeButton = document.createElement('button');
                    removeButton.type = 'button';
                    removeButton.className = 'remove-meds-row bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded';
                    removeButton.textContent = 'Entfernen';
                    removeButton.addEventListener('click', () => removeRow(tr));
                    actionsTd.appendChild(removeButton);

                    tr.appendChild(nameTd);
                    tr.appendChild(doseTd);
                    tr.appendChild(timeTd);
                    tr.appendChild(actionsTd);

                    tableBody.appendChild(tr);
                }

                if (addButton) {
                    addButton.addEventListener('click', () => addRow());
                }

                tableBody.querySelectorAll('.remove-meds-row').forEach((button) => {
                    button.addEventListener('click', (event) => {
                        const row = event.target.closest('tr');
                        if (row) {
                            removeRow(row);
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>

