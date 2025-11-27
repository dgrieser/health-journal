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
            if (!is_array($row)) return null;
            $name = isset($row['name']) ? trim((string)$row['name']) : '';
            $dose = isset($row['dose']) ? trim((string)$row['dose']) : '';
            $time = isset($row['time']) ? trim((string)$row['time']) : '';
            if ($name === '' && $dose === '' && $time === '') return null;
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

// Helper to render checkbox grid
function render_checkbox_grid(string $name, array $options, array $selectedValues, bool $allowOther = false, string $otherName = '', string $otherValue = ''): void {
    echo '<div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-2">';
    foreach ($options as $opt) {
        $isChecked = in_array($opt, $selectedValues) ? 'checked' : '';
        echo "
        <label class='selection-card'>
            <input type='checkbox' name='{$name}[]' value='" . htmlspecialchars($opt, ENT_QUOTES) . "' $isChecked>
            <span>" . htmlspecialchars($opt) . "</span>
        </label>";
    }
    if ($allowOther) {
        $isOtherChecked = in_array('Sonstiges', $selectedValues) ? 'checked' : '';
        echo "
        <label class='selection-card sm:col-span-2'>
            <input type='checkbox' name='{$name}[]' value='Sonstiges' $isOtherChecked>
            <span>Sonstiges</span>
        </label>";
        echo "<div class='sm:col-span-2 mt-2'>
                <input type='text' name='$otherName' placeholder='Bitte spezifizieren...' value='" . htmlspecialchars($otherValue, ENT_QUOTES) . "' class='form-input'>
              </div>";
    }
    echo '</div>';
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEdit ? 'Eintrag bearbeiten' : 'Neuer Tagebucheintrag'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 pb-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight"><?php echo $isEdit ? 'Eintrag bearbeiten' : 'Neuer Eintrag'; ?></h1>
                <p class="text-gray-500 mt-1">Symptom-Tagebuch</p>
            </div>
            <a href="index.php" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center gap-1">
                &larr; Zurück zur Übersicht
            </a>
        </div>

        <form action="index.php?action=<?php echo $isEdit ? 'update' : 'save'; ?>" method="post" id="entry-form">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?php echo (int)$entryData['id']; ?>">
            <?php endif; ?>

            <div class="sticky top-0 z-10 bg-gray-50 pt-4 pb-4 mb-6">
                <div class="bg-white rounded-xl shadow-md border border-gray-100 p-4">
                    <div class="flex items-center justify-between text-sm font-medium mb-3">
                        <span id="wizard-step-label" class="text-gray-500">Schritt 1 von 6</span>
                        <span id="wizard-step-name" class="text-indigo-600 font-bold">Metadaten</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                        <div id="wizard-progress-bar" class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500 ease-out" style="width: 16.7%;"></div>
                    </div>
                </div>
            </div>

            <div class="wizard-step" data-step-title="Metadaten">
                <div class="section-card">
                    <div class="section-header">
                        <h2 class="section-title">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Datum & Zeit
                        </h2>
                    </div>
                    <div class="section-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="entry_date">Datum <span class="text-red-500">*</span></label>
                                <input type="date" id="entry_date" name="entry_date" value="<?php echo htmlspecialchars($entryData['entry_date'] ?? date('Y-m-d'), ENT_QUOTES); ?>" required>
                            </div>
                            <div>
                                <label for="entry_time">Uhrzeit</label>
                                <input type="time" id="entry_time" name="entry_time" value="<?php echo htmlspecialchars($entryData['entry_time'] ?? date('H:i'), ENT_QUOTES); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wizard-step hidden" data-step-title="Motorik: Tremor & Steifheit">
                
                <div class="section-card">
                    <div class="section-header">
                        <h2 class="section-title">1. Tremor (Zittern)</h2>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="tremor_present" value="1" <?php echo !empty($entryData['tremor_present']) ? 'checked' : ''; ?> class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700">Vorhanden?</span>
                        </label>
                    </div>
                    <div class="section-body">
                        <div class="space-y-4">
                            <div>
                                <label>Betroffene Regionen</label>
                                <?php render_checkbox_grid('tremor_regions', ['Hand links', 'Hand rechts', 'Kinn/Lippe', 'Bein', 'Kopf'], $selected['tremor_regions'], true, 'tremor_regions_other', $entryData['tremor_regions_other'] ?? ''); ?>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="tremor_intensity">Intensität (1-10)</label>
                                    <input type="number" id="tremor_intensity" name="tremor_intensity" min="1" max="10" placeholder="-" class="intensity-input w-full" value="<?php echo htmlspecialchars($entryData['tremor_intensity'] ?? '', ENT_QUOTES); ?>">
                                </div>
                                <div>
                                    <label for="tremor_duration_min">Dauer (Minuten)</label>
                                    <input type="number" id="tremor_duration_min" name="tremor_duration_min" placeholder="min" value="<?php echo htmlspecialchars($entryData['tremor_duration_min'] ?? '', ENT_QUOTES); ?>">
                                </div>
                            </div>

                            <div>
                                <label>Kontext / Auslöser</label>
                                <select name="tremor_context">
                                    <option value="">Bitte wählen...</option>
                                    <option value="In Ruhe" <?php echo ($entryData['tremor_context'] ?? '') === 'In Ruhe' ? 'selected' : ''; ?>>In Ruhe (typisch)</option>
                                    <option value="Nur bei Bewegung" <?php echo ($entryData['tremor_context'] ?? '') === 'Nur bei Bewegung' ? 'selected' : ''; ?>>Nur bei Bewegung</option>
                                    <option value="Bei Stress" <?php echo ($entryData['tremor_context'] ?? '') === 'Bei Stress' ? 'selected' : ''; ?>>Bei Stress</option>
                                    <option value="Nach Kaffee/Energiedrinks" <?php echo ($entryData['tremor_context'] ?? '') === 'Nach Kaffee/Energiedrinks' ? 'selected' : ''; ?>>Nach Koffein</option>
                                </select>
                                <input type="text" name="tremor_context_other" placeholder="Anderer Kontext..." value="<?php echo htmlspecialchars($entryData['tremor_context_other'] ?? '', ENT_QUOTES); ?>" class="mt-2">
                            </div>
                            
                            <div>
                                <label for="tremor_notes">Notizen</label>
                                <textarea id="tremor_notes" name="tremor_notes" rows="2" placeholder="Besondere Beobachtungen..."><?php echo htmlspecialchars($entryData['tremor_notes'] ?? '', ENT_QUOTES); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-card">
                    <div class="section-header">
                        <h2 class="section-title">2. Steifheit (Rigor)</h2>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="rigor_present" value="1" <?php echo !empty($entryData['rigor_present']) ? 'checked' : ''; ?> class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700">Vorhanden?</span>
                        </label>
                    </div>
                    <div class="section-body">
                         <div>
                            <label>Betroffene Regionen</label>
                            <?php render_checkbox_grid('rigor_regions', ['Nackenbereich', 'Schulter(n)', 'Oberarme', 'Unterarme', 'Hände', 'Rücken', 'Beine'], $selected['rigor_regions'], true, 'rigor_regions_other', $entryData['rigor_regions_other'] ?? ''); ?>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="rigor_intensity">Intensität (1-10)</label>
                                <input type="number" id="rigor_intensity" name="rigor_intensity" min="1" max="10" placeholder="-" class="intensity-input w-full" value="<?php echo htmlspecialchars($entryData['rigor_intensity'] ?? '', ENT_QUOTES); ?>">
                            </div>
                            <div>
                                <label for="rigor_time_of_day">Tageszeit</label>
                                <select name="rigor_time_of_day" id="rigor_time_of_day">
                                    <option value="">Bitte wählen...</option>
                                    <option value="Morgens" <?php echo ($entryData['rigor_time_of_day'] ?? '') === 'Morgens' ? 'selected' : ''; ?>>Morgens</option>
                                    <option value="Mittags" <?php echo ($entryData['rigor_time_of_day'] ?? '') === 'Mittags' ? 'selected' : ''; ?>>Mittags</option>
                                    <option value="Abends" <?php echo ($entryData['rigor_time_of_day'] ?? '') === 'Abends' ? 'selected' : ''; ?>>Abends</option>
                                    <option value="Nachts" <?php echo ($entryData['rigor_time_of_day'] ?? '') === 'Nachts' ? 'selected' : ''; ?>>Nachts</option>
                                    <option value="Immer" <?php echo ($entryData['rigor_time_of_day'] ?? '') === 'Immer' ? 'selected' : ''; ?>>Immer</option>
                                </select>
                            </div>
                        </div>
                        
                        <label class="selection-card">
                            <input type="checkbox" name="rigor_improved_by_movement" value="1" <?php echo !empty($entryData['rigor_improved_by_movement']) ? 'checked' : ''; ?>>
                            <span>Verbesserung durch Bewegung?</span>
                        </label>

                        <div>
                             <label for="rigor_notes">Notizen</label>
                             <textarea id="rigor_notes" name="rigor_notes" rows="2"><?php echo htmlspecialchars($entryData['rigor_notes'] ?? '', ENT_QUOTES); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="section-card">
                    <div class="section-header">
                        <h2 class="section-title">3. Verlangsamung (Bradykinese)</h2>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="bradykinesia_present" value="1" <?php echo !empty($entryData['bradykinesia_present']) ? 'checked' : ''; ?> class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700">Vorhanden?</span>
                        </label>
                    </div>
                    <div class="section-body">
                         <div>
                            <label>Betroffene Aktivitäten</label>
                            <?php render_checkbox_grid('bradykinesia_activities', ['Beim Gehen', 'Beim Schreiben', 'Bei Knöpfen/Reißverschlüssen', 'Bei der Körperpflege', 'Bei alltäglichen Arbeiten'], $selected['bradykinesia_activities'], true, 'bradykinesia_activities_other', $entryData['bradykinesia_activities_other'] ?? ''); ?>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="bradykinesia_intensity">Intensität (1-10)</label>
                                <input type="number" id="bradykinesia_intensity" name="bradykinesia_intensity" min="1" max="10" class="intensity-input w-full" value="<?php echo htmlspecialchars($entryData['bradykinesia_intensity'] ?? '', ENT_QUOTES); ?>">
                            </div>
                             <div>
                                <label for="bradykinesia_impact">Auswirkungen</label>
                                <select name="bradykinesia_impact" id="bradykinesia_impact">
                                    <option value="">Bitte wählen...</option>
                                    <option value="Kaum merklich" <?php echo ($entryData['bradykinesia_impact'] ?? '') === 'Kaum merklich' ? 'selected' : ''; ?>>Kaum merklich</option>
                                    <option value="Gelegentlich störend" <?php echo ($entryData['bradykinesia_impact'] ?? '') === 'Gelegentlich störend' ? 'selected' : ''; ?>>Gelegentlich störend</option>
                                    <option value="Deutlich behindert" <?php echo ($entryData['bradykinesia_impact'] ?? '') === 'Deutlich behindert' ? 'selected' : ''; ?>>Deutlich behindert</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="bradykinesia_examples">Konkrete Beispiele</label>
                            <textarea id="bradykinesia_examples" name="bradykinesia_examples" rows="2" placeholder="z.B. Schreiben dauerte doppelt so lange"><?php echo htmlspecialchars($entryData['bradykinesia_examples'] ?? '', ENT_QUOTES); ?></textarea>
                        </div>
                    </div>
                </div>
                
                 <div class="section-card">
                    <div class="section-header">
                        <h2 class="section-title">4. Armschlag</h2>
                         <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="arm_swing_asymmetry_present" value="1" <?php echo !empty($entryData['arm_swing_asymmetry_present']) ? 'checked' : ''; ?> class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700">Asymmetrisch?</span>
                        </label>
                    </div>
                    <div class="section-body">
                         <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="arm_swing_side">Seite</label>
                                <select name="arm_swing_side" id="arm_swing_side">
                                    <option value="">Bitte wählen...</option>
                                    <option value="Links" <?php echo ($entryData['arm_swing_side'] ?? '') === 'Links' ? 'selected' : ''; ?>>Links</option>
                                    <option value="Rechts" <?php echo ($entryData['arm_swing_side'] ?? '') === 'Rechts' ? 'selected' : ''; ?>>Rechts</option>
                                    <option value="Manchmal beide" <?php echo ($entryData['arm_swing_side'] ?? '') === 'Manchmal beide' ? 'selected' : ''; ?>>Manchmal beide</option>
                                </select>
                            </div>
                            <div>
                                <label for="arm_swing_severity">Ausprägung</label>
                                <select name="arm_swing_severity" id="arm_swing_severity">
                                    <option value="">Bitte wählen...</option>
                                    <option value="Arm bewegt sich weniger" <?php echo ($entryData['arm_swing_severity'] ?? '') === 'Arm bewegt sich weniger' ? 'selected' : ''; ?>>Weniger Bewegung</option>
                                    <option value="Arm bleibt vollständig still" <?php echo ($entryData['arm_swing_severity'] ?? '') === 'Arm bleibt vollständig still' ? 'selected' : ''; ?>>Bleibt still</option>
                                </select>
                            </div>
                         </div>
                    </div>
                 </div>
            </div>

            <div class="wizard-step hidden" data-step-title="Motorik: Gang & Haltung">
                
                <div class="section-card">
                    <div class="section-header">
                         <h2 class="section-title">5. Feinmotorik</h2>
                         <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="fine_motor_issues_present" value="1" <?php echo !empty($entryData['fine_motor_issues_present']) ? 'checked' : ''; ?> class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700">Probleme?</span>
                        </label>
                    </div>
                    <div class="section-body">
                        <div>
                            <label>Aktivitäten</label>
                            <?php render_checkbox_grid('fine_motor_activities', ['Schreiben', 'Knöpfe / Reißverschlüsse', 'Besteck', 'Handy / Computer', 'Nagelpflege'], $selected['fine_motor_activities'], true, 'fine_motor_activities_other', $entryData['fine_motor_activities_other'] ?? ''); ?>
                        </div>
                        <div>
                             <label for="fine_motor_intensity">Intensität (1-10)</label>
                             <input type="number" id="fine_motor_intensity" name="fine_motor_intensity" min="1" max="10" class="intensity-input" value="<?php echo htmlspecialchars($entryData['fine_motor_intensity'] ?? '', ENT_QUOTES); ?>">
                        </div>
                        <div>
                             <label for="fine_motor_desc">Beschreibung</label>
                             <textarea id="fine_motor_desc" name="fine_motor_desc" rows="2"><?php echo htmlspecialchars($entryData['fine_motor_desc'] ?? '', ENT_QUOTES); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="section-card">
                    <div class="section-header">
                        <h2 class="section-title">6. Gangbild</h2>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="gait_issues_present" value="1" <?php echo !empty($entryData['gait_issues_present']) ? 'checked' : ''; ?> class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700">Verändert?</span>
                        </label>
                    </div>
                    <div class="section-body">
                         <div>
                            <label>Charakteristika</label>
                            <?php render_checkbox_grid('gait_characteristics', ['Kleinere Schritte', 'Schlurfender Gang', 'Unbeweglicher Oberkörper', 'Verlangsamter Gang', 'Steifer Gang'], $selected['gait_characteristics'], true, 'gait_characteristics_other', $entryData['gait_characteristics_other'] ?? ''); ?>
                        </div>
                         <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label for="gait_intensity">Intensität (1-10)</label>
                                <input type="number" id="gait_intensity" name="gait_intensity" min="1" max="10" class="intensity-input w-full" value="<?php echo htmlspecialchars($entryData['gait_intensity'] ?? '', ENT_QUOTES); ?>">
                            </div>
                            <div>
                                <label for="gait_trigger">Auslöser</label>
                                <input type="text" id="gait_trigger" name="gait_trigger" placeholder="z.B. Stress" value="<?php echo htmlspecialchars($entryData['gait_trigger'] ?? '', ENT_QUOTES); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-card">
                     <div class="section-header">
                        <h2 class="section-title">7. Gleichgewicht</h2>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="balance_issues_present" value="1" <?php echo !empty($entryData['balance_issues_present']) ? 'checked' : ''; ?> class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700">Störungen?</span>
                        </label>
                    </div>
                     <div class="section-body">
                         <div>
                            <label>Art der Störung</label>
                            <?php render_checkbox_grid('balance_type', ['Schwindel beim Aufstehen', 'Unsicherheitsgefühl', 'Fallneigung zur Seite', 'Vornüberneigung', 'Schwankend beim Stehen'], $selected['balance_type'], true, 'balance_type_other', $entryData['balance_type_other'] ?? ''); ?>
                        </div>
                        <div class="mt-4">
                            <label>Situationen</label>
                            <?php render_checkbox_grid('balance_situations', ['Beim Aufstehen', 'Bei schnellen Kopfbewegungen', 'Im Dunkeln', 'Beim Gehen', 'Beim Drehen'], $selected['balance_situations'], true, 'balance_situations_other', $entryData['balance_situations_other'] ?? ''); ?>
                        </div>
                     </div>
                </div>
                
                <div class="section-card">
                    <div class="section-header">
                         <h2 class="section-title">8. Haltung & 9. Dystonie</h2>
                    </div>
                    <div class="section-body">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="posture_status">Körperhaltung</label>
                                <select name="posture_status" id="posture_status">
                                    <option value="">Bitte wählen...</option>
                                    <option value="Aufrecht / Normal" <?php echo ($entryData['posture_status'] ?? '') === 'Aufrecht / Normal' ? 'selected' : ''; ?>>Aufrecht / Normal</option>
                                    <option value="Leicht nach vorne gebeugt" <?php echo ($entryData['posture_status'] ?? '') === 'Leicht nach vorne gebeugt' ? 'selected' : ''; ?>>Leicht nach vorne gebeugt</option>
                                    <option value="Deutlich gebeugt" <?php echo ($entryData['posture_status'] ?? '') === 'Deutlich gebeugt' ? 'selected' : ''; ?>>Deutlich gebeugt</option>
                                </select>
                            </div>
                            
                            <div class="border-t border-gray-100 pt-4">
                                <label class="flex items-center gap-2 cursor-pointer mb-2">
                                    <input type="checkbox" name="dystonia_present" value="1" <?php echo !empty($entryData['dystonia_present']) ? 'checked' : ''; ?> class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                                    <span class="text-sm font-medium text-gray-700">Dystonie/Krämpfe vorhanden?</span>
                                </label>
                                <?php render_checkbox_grid('dystonia_location', ['Hand', 'Fuß', 'Nacken', 'Rücken', 'Bein'], $selected['dystonia_location'], true, 'dystonia_location_other', $entryData['dystonia_location_other'] ?? ''); ?>
                            </div>
                        </div>
                    </div>
                 </div>
            </div>

            <div class="wizard-step hidden" data-step-title="Nicht-Motorisch: Schlaf & Fatigue">
                
                <div class="section-card">
                    <div class="section-header">
                        <h2 class="section-title">10. Schlaf</h2>
                    </div>
                    <div class="section-body">
                         <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="sleep_quality">Qualität</label>
                                <select id="sleep_quality" name="sleep_quality">
                                    <option value="Gut / Erholsam" <?php echo ($entryData['sleep_quality'] ?? '') === 'Gut / Erholsam' ? 'selected' : ''; ?>>Gut / Erholsam</option>
                                    <option value="Ausreichend" <?php echo ($entryData['sleep_quality'] ?? '') === 'Ausreichend' ? 'selected' : ''; ?>>Ausreichend</option>
                                    <option value="Schlecht / Nicht erholsam" <?php echo ($entryData['sleep_quality'] ?? '') === 'Schlecht / Nicht erholsam' ? 'selected' : ''; ?>>Schlecht / Nicht erholsam</option>
                                </select>
                            </div>
                            <div>
                                <label for="sleep_hours_duration">Dauer (Std)</label>
                                <input type="number" step="0.5" id="sleep_hours_duration" name="sleep_hours_duration" value="<?php echo htmlspecialchars($entryData['sleep_hours_duration'] ?? '', ENT_QUOTES); ?>">
                            </div>
                         </div>

                         <div class="mt-4">
                             <label>Probleme</label>
                             <?php render_checkbox_grid('sleep_problems_list', ['Einschlafstörung', 'Durchschlafstörung', 'Früherwachen', 'Unruhige Beine', 'Keine Probleme'], $selected['sleep_problems_list']); ?>
                         </div>
                         
                         <div class="mt-6 border-t border-gray-100 pt-4">
                             <h3 class="text-md font-semibold text-indigo-700 mb-2">REM-Verhaltensstörung</h3>
                             <label class="flex items-center gap-2 cursor-pointer mb-2">
                                <input type="checkbox" name="sleep_rem_behavior_present" value="1" <?php echo !empty($entryData['sleep_rem_behavior_present']) ? 'checked' : ''; ?> class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                                <span class="text-sm text-gray-700">Auffälligkeiten im Traumschlaf?</span>
                            </label>
                             <?php render_checkbox_grid('sleep_rem_symptoms', ['Lebhafte/Alptraum-Träume', 'Reden im Schlaf', 'Schreien', 'Um sich schlagen', 'Bewegungen'], $selected['sleep_rem_symptoms']); ?>
                         </div>
                    </div>
                </div>

                <div class="section-card">
                    <div class="section-header">
                        <h2 class="section-title">11. Tagesschläfrigkeit (Fatigue)</h2>
                    </div>
                    <div class="section-body">
                        <div>
                            <label for="fatigue_severity">Schweregrad</label>
                            <select id="fatigue_severity" name="fatigue_severity">
                                <option value="">Bitte wählen...</option>
                                <option value="Nein" <?php echo ($entryData['fatigue_severity'] ?? '') === 'Nein' ? 'selected' : ''; ?>>Nein</option>
                                <option value="Leicht" <?php echo ($entryData['fatigue_severity'] ?? '') === 'Leicht' ? 'selected' : ''; ?>>Leicht</option>
                                <option value="Moderat" <?php echo ($entryData['fatigue_severity'] ?? '') === 'Moderat' ? 'selected' : ''; ?>>Moderat</option>
                                <option value="Schwer" <?php echo ($entryData['fatigue_severity'] ?? '') === 'Schwer' ? 'selected' : ''; ?>>Schwer</option>
                            </select>
                        </div>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-4 bg-gray-50 p-4 rounded-lg">
                             <div class="col-span-2 sm:col-span-4 font-semibold text-gray-700 mb-2">Energieniveau (1-10)</div>
                             <div>
                                 <label class="text-xs">Morgens</label>
                                 <input type="number" name="fatigue_energy_morning" min="1" max="10" class="intensity-input w-full" value="<?php echo htmlspecialchars($entryData['fatigue_energy_morning'] ?? '', ENT_QUOTES); ?>">
                             </div>
                             <div>
                                 <label class="text-xs">Mittags</label>
                                 <input type="number" name="fatigue_energy_noon" min="1" max="10" class="intensity-input w-full" value="<?php echo htmlspecialchars($entryData['fatigue_energy_noon'] ?? '', ENT_QUOTES); ?>">
                             </div>
                             <div>
                                 <label class="text-xs">Abends</label>
                                 <input type="number" name="fatigue_energy_evening" min="1" max="10" class="intensity-input w-full" value="<?php echo htmlspecialchars($entryData['fatigue_energy_evening'] ?? '', ENT_QUOTES); ?>">
                             </div>
                        </div>
                        
                        <div class="mt-4">
                             <label>Nickerchen</label>
                             <div class="grid grid-cols-2 gap-4">
                                <input type="number" name="fatigue_nap_count" placeholder="Anzahl" value="<?php echo htmlspecialchars($entryData['fatigue_nap_count'] ?? '', ENT_QUOTES); ?>">
                                <input type="number" name="fatigue_nap_avg_min" placeholder="Dauer (min)" value="<?php echo htmlspecialchars($entryData['fatigue_nap_avg_min'] ?? '', ENT_QUOTES); ?>">
                             </div>
                        </div>
                    </div>
                </div>
                
                 <div class="section-card">
                    <div class="section-header"><h2 class="section-title">12./13. Riechen & Schmecken</h2></div>
                    <div class="section-body">
                         <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="smell_ability">Geruch</label>
                                <select name="smell_ability" id="smell_ability">
                                    <option value="">Bitte wählen...</option>
                                    <option value="Normal" <?php echo ($entryData['smell_ability'] ?? '') === 'Normal' ? 'selected' : ''; ?>>Normal</option>
                                    <option value="Leicht vermindert" <?php echo ($entryData['smell_ability'] ?? '') === 'Leicht vermindert' ? 'selected' : ''; ?>>Leicht vermindert</option>
                                    <option value="Deutlich vermindert" <?php echo ($entryData['smell_ability'] ?? '') === 'Deutlich vermindert' ? 'selected' : ''; ?>>Deutlich vermindert</option>
                                    <option value="Fehlend (Anosmie)" <?php echo ($entryData['smell_ability'] ?? '') === 'Fehlend (Anosmie)' ? 'selected' : ''; ?>>Fehlend (Anosmie)</option>
                                </select>
                            </div>
                            <div>
                                <label for="taste_ability">Geschmack</label>
                                <select name="taste_ability" id="taste_ability">
                                    <option value="">Bitte wählen...</option>
                                    <option value="Normal" <?php echo ($entryData['taste_ability'] ?? '') === 'Normal' ? 'selected' : ''; ?>>Normal</option>
                                    <option value="Vermindert" <?php echo ($entryData['taste_ability'] ?? '') === 'Vermindert' ? 'selected' : ''; ?>>Vermindert</option>
                                </select>
                            </div>
                         </div>
                    </div>
                </div>
            </div>

            <div class="wizard-step hidden" data-step-title="Stimmung, Kognition & Schmerz">
                 
                 <div class="section-card">
                    <div class="section-header">
                        <h2 class="section-title">14. Stimmung</h2>
                    </div>
                    <div class="section-body">
                        <div>
                             <label for="mood_general">Generelle Stimmung</label>
                             <select name="mood_general" id="mood_general">
                                <option value="">Bitte wählen</option>
                                <option value="Stabil / Normal" <?php echo ($entryData['mood_general'] ?? '') === 'Stabil / Normal' ? 'selected' : ''; ?>>Stabil / Normal</option>
                                <option value="Reizbar / Ungeduldig" <?php echo ($entryData['mood_general'] ?? '') === 'Reizbar / Ungeduldig' ? 'selected' : ''; ?>>Reizbar / Ungeduldig</option>
                                <option value="Niedergeschlagen / Traurig" <?php echo ($entryData['mood_general'] ?? '') === 'Niedergeschlagen / Traurig' ? 'selected' : ''; ?>>Niedergeschlagen / Traurig</option>
                                <option value="Ängstlich" <?php echo ($entryData['mood_general'] ?? '') === 'Ängstlich' ? 'selected' : ''; ?>>Ängstlich</option>
                                <option value="Apathisch / Gefühllos" <?php echo ($entryData['mood_general'] ?? '') === 'Apathisch / Gefühllos' ? 'selected' : ''; ?>>Apathisch / Gefühllos</option>
                             </select>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label for="mood_motivation_score">Motivation (1-10)</label>
                                <input type="number" id="mood_motivation_score" name="mood_motivation_score" min="1" max="10" class="intensity-input w-full" value="<?php echo htmlspecialchars($entryData['mood_motivation_score'] ?? '', ENT_QUOTES); ?>">
                            </div>
                        </div>
                    </div>
                 </div>

                 <div class="section-card">
                     <div class="section-header"><h2 class="section-title">15. Kognitive Funktionen</h2></div>
                     <div class="section-body">
                         <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="cognitive_concentration">Konzentration</label>
                                <select name="cognitive_concentration" id="cognitive_concentration">
                                    <option value="">Bitte wählen</option>
                                    <option value="Normal / Gut" <?php echo ($entryData['cognitive_concentration'] ?? '') === 'Normal / Gut' ? 'selected' : ''; ?>>Normal / Gut</option>
                                    <option value="Leicht vermindert" <?php echo ($entryData['cognitive_concentration'] ?? '') === 'Leicht vermindert' ? 'selected' : ''; ?>>Leicht vermindert</option>
                                    <option value="Deutlich vermindert" <?php echo ($entryData['cognitive_concentration'] ?? '') === 'Deutlich vermindert' ? 'selected' : ''; ?>>Deutlich vermindert</option>
                                </select>
                            </div>
                            <div>
                                <label for="cognitive_memory">Gedächtnis</label>
                                <select name="cognitive_memory" id="cognitive_memory">
                                    <option value="">Bitte wählen</option>
                                    <option value="Normal" <?php echo ($entryData['cognitive_memory'] ?? '') === 'Normal' ? 'selected' : ''; ?>>Normal</option>
                                    <option value="Leicht vermindert" <?php echo ($entryData['cognitive_memory'] ?? '') === 'Leicht vermindert' ? 'selected' : ''; ?>>Leicht vermindert</option>
                                    <option value="Deutlich vermindert" <?php echo ($entryData['cognitive_memory'] ?? '') === 'Deutlich vermindert' ? 'selected' : ''; ?>>Deutlich vermindert</option>
                                </select>
                            </div>
                         </div>
                         <div class="mt-4">
                             <label>Spezifische Probleme</label>
                             <?php render_checkbox_grid('cognitive_specific_issues', ['Worte vergessen', 'Namen vergessen', 'Warum ging ich ins Zimmer?', 'Termine vergessen', 'Leicht abgelenkt'], $selected['cognitive_specific_issues']); ?>
                         </div>
                     </div>
                 </div>

                 <div class="section-card">
                     <div class="section-header">
                        <h2 class="section-title">16. Schmerzen</h2>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="pain_present" value="1" <?php echo !empty($entryData['pain_present']) ? 'checked' : ''; ?> class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700">Vorhanden?</span>
                        </label>
                     </div>
                     <div class="section-body">
                         <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="pain_severity">Schweregrad</label>
                                <select name="pain_severity" id="pain_severity">
                                    <option value="">Bitte wählen</option>
                                    <option value="Nein" <?php echo ($entryData['pain_severity'] ?? '') === 'Nein' ? 'selected' : ''; ?>>Nein</option>
                                    <option value="Ja, leicht" <?php echo ($entryData['pain_severity'] ?? '') === 'Ja, leicht' ? 'selected' : ''; ?>>Leicht</option>
                                    <option value="Ja, moderat" <?php echo ($entryData['pain_severity'] ?? '') === 'Ja, moderat' ? 'selected' : ''; ?>>Moderat</option>
                                    <option value="Ja, stark" <?php echo ($entryData['pain_severity'] ?? '') === 'Ja, stark' ? 'selected' : ''; ?>>Stark</option>
                                </select>
                            </div>
                            <div>
                                <label for="pain_intensity">Intensität (1-10)</label>
                                <input type="number" id="pain_intensity" name="pain_intensity" min="1" max="10" class="intensity-input w-full" value="<?php echo htmlspecialchars($entryData['pain_intensity'] ?? '', ENT_QUOTES); ?>">
                            </div>
                         </div>
                         <div class="mt-4">
                             <label>Lokalisation</label>
                             <?php render_checkbox_grid('pain_locations', ['Nackenbereich', 'Schultern', 'Rücken (oben)', 'Rücken (unten)', 'Arme', 'Beine'], $selected['pain_locations'], true, 'pain_locations_other', $entryData['pain_locations_other'] ?? ''); ?>
                         </div>
                     </div>
                 </div>
            </div>

            <div class="wizard-step hidden" data-step-title="Verdauung, Medis & Notizen">
                
                <div class="section-card">
                    <div class="section-header"><h2 class="section-title">17./18. Verdauung & Vegetatives</h2></div>
                    <div class="section-body">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="digestion_status">Verdauung</label>
                                <select name="digestion_status" id="digestion_status">
                                    <option value="">Bitte wählen</option>
                                    <option value="Normal" <?php echo ($entryData['digestion_status'] ?? '') === 'Normal' ? 'selected' : ''; ?>>Normal</option>
                                    <option value="Verstopfung" <?php echo ($entryData['digestion_status'] ?? '') === 'Verstopfung' ? 'selected' : ''; ?>>Verstopfung</option>
                                    <option value="Durchfall" <?php echo ($entryData['digestion_status'] ?? '') === 'Durchfall' ? 'selected' : ''; ?>>Durchfall</option>
                                </select>
                            </div>
                             <div>
                                <label for="vegetative_sweating">Schwitzen</label>
                                <select name="vegetative_sweating" id="vegetative_sweating">
                                    <option value="">Bitte wählen</option>
                                    <option value="Normal" <?php echo ($entryData['vegetative_sweating'] ?? '') === 'Normal' ? 'selected' : ''; ?>>Normal</option>
                                    <option value="Erhöht" <?php echo ($entryData['vegetative_sweating'] ?? '') === 'Erhöhte Schweißproduktion' ? 'selected' : ''; ?>>Erhöht</option>
                                    <option value="Nachtschweiß" <?php echo ($entryData['vegetative_sweating'] ?? '') === 'Nachtschweiß' ? 'selected' : ''; ?>>Nachtschweiß</option>
                                </select>
                            </div>
                        </div>
                    </div>
                 </div>

                <div class="section-card">
                    <div class="section-header">
                        <h2 class="section-title">19. Medikamente</h2>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="meds_taken" value="1" <?php echo !empty($entryData['meds_taken']) ? 'checked' : ''; ?> class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700">Eingenommen?</span>
                        </label>
                    </div>
                    <div class="section-body">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="meds-table">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosis</th>
                                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uhrzeit</th>
                                        <th class="px-3 py-3"></th>
                                    </tr>
                                </thead>
                                <tbody id="meds-table-body" class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($medicationRows as $index => $row): ?>
                                        <tr>
                                            <td class="px-3 py-2"><input type="text" name="meds_table[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($row['name'] ?? '', ENT_QUOTES); ?>" placeholder="Medikament" class="form-input text-sm"></td>
                                            <td class="px-3 py-2"><input type="text" name="meds_table[<?php echo $index; ?>][dose]" value="<?php echo htmlspecialchars($row['dose'] ?? '', ENT_QUOTES); ?>" placeholder="z.B. 100mg" class="form-input text-sm"></td>
                                            <td class="px-3 py-2"><input type="time" name="meds_table[<?php echo $index; ?>][time]" value="<?php echo htmlspecialchars($row['time'] ?? '', ENT_QUOTES); ?>" class="form-input text-sm"></td>
                                            <td class="px-3 py-2 text-center"><button type="button" class="remove-meds-row text-red-600 hover:text-red-900 font-bold">&times;</button></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" id="add-meds-row" class="mt-3 btn btn-secondary w-full sm:w-auto">+ Zeile hinzufügen</button>
                        
                        <div class="mt-6">
                            <label for="meds_effect">Wirkung</label>
                            <select name="meds_effect" id="meds_effect">
                                <option value="">Bitte wählen</option>
                                <option value="Keine" <?php echo ($entryData['meds_effect'] ?? '') === 'Keine' ? 'selected' : ''; ?>>Keine</option>
                                <option value="Verbessert" <?php echo ($entryData['meds_effect'] ?? '') === 'Verbessert' ? 'selected' : ''; ?>>Verbessert</option>
                                <option value="Verschlimmert" <?php echo ($entryData['meds_effect'] ?? '') === 'Verschlimmert' ? 'selected' : ''; ?>>Verschlimmert</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="section-card">
                    <div class="section-header"><h2 class="section-title">20. Fazit</h2></div>
                    <div class="section-body">
                         <div>
                            <label>Erkannte Muster</label>
                            <?php render_checkbox_grid('pattern_recognized', ['Morgens schlimmer', 'Abends schlimmer', 'Nach Mahlzeiten', 'Nach Stress', 'Nach wenig Schlaf'], $selected['pattern_recognized']); ?>
                        </div>
                        <div class="mt-6">
                            <label for="overall_wellbeing_score" class="text-lg font-bold text-gray-800">Gesamtbefindlichkeit heute (1-10)</label>
                            <input type="number" id="overall_wellbeing_score" name="overall_wellbeing_score" min="1" max="10" class="mt-2 block w-full text-center text-2xl font-bold text-indigo-600 border-2 border-indigo-100 rounded-lg py-4 focus:ring-indigo-500 focus:border-indigo-500" value="<?php echo htmlspecialchars($entryData['overall_wellbeing_score'] ?? '', ENT_QUOTES); ?>">
                        </div>
                        <div class="mt-6">
                             <label for="daily_summary_notes">Tagesnotizen</label>
                             <textarea id="daily_summary_notes" name="daily_summary_notes" rows="4" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"><?php echo htmlspecialchars($entryData['daily_summary_notes'] ?? '', ENT_QUOTES); ?></textarea>
                        </div>
                    </div>
                </div>

            </div>

            <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 shadow-lg z-20">
                <div class="max-w-3xl mx-auto flex items-center justify-between">
                    <button type="button" id="wizard-prev" class="btn btn-secondary">
                        &larr; Zurück
                    </button>
                    <div class="flex gap-3">
                        <button type="button" id="wizard-next" class="btn btn-primary">
                            Weiter &rarr;
                        </button>
                        <button type="submit" id="wizard-submit" class="btn bg-green-600 text-white hover:bg-green-700 hidden">
                            Speichern &check;
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Wizard Logic
            const steps = Array.from(document.querySelectorAll('.wizard-step'));
            const progressLabel = document.getElementById('wizard-step-label');
            const progressName = document.getElementById('wizard-step-name');
            const progressBar = document.getElementById('wizard-progress-bar');
            const nextButton = document.getElementById('wizard-next');
            const prevButton = document.getElementById('wizard-prev');
            const submitButton = document.getElementById('wizard-submit');
            const totalSteps = steps.length;
            let currentStep = 0;

            function updateWizard() {
                steps.forEach((step, index) => {
                    if (index === currentStep) {
                        step.classList.remove('hidden');
                        setTimeout(() => step.classList.remove('opacity-0'), 50); // Fade in
                    } else {
                        step.classList.add('hidden', 'opacity-0');
                    }
                });

                const title = steps[currentStep]?.dataset.stepTitle || `Schritt ${currentStep + 1}`;
                if (progressLabel) progressLabel.textContent = `Schritt ${currentStep + 1} von ${totalSteps}`;
                if (progressName) progressName.textContent = title;
                if (progressBar) progressBar.style.width = `${Math.round(((currentStep + 1) / totalSteps) * 100)}%`;

                if (prevButton) {
                    prevButton.disabled = currentStep === 0;
                    prevButton.classList.toggle('opacity-50', currentStep === 0);
                    prevButton.classList.toggle('cursor-not-allowed', currentStep === 0);
                }

                if (nextButton) nextButton.classList.toggle('hidden', currentStep === totalSteps - 1);
                if (submitButton) submitButton.classList.toggle('hidden', currentStep !== totalSteps - 1);
                
                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            nextButton?.addEventListener('click', () => {
                if (currentStep < totalSteps - 1) { currentStep++; updateWizard(); }
            });

            prevButton?.addEventListener('click', () => {
                if (currentStep > 0) { currentStep--; updateWizard(); }
            });

            // Meds Table Logic
            const tableBody = document.getElementById('meds-table-body');
            const addButton = document.getElementById('add-meds-row');
            let medsRowCounter = tableBody ? tableBody.querySelectorAll('tr').length : 0;

            function addRow() {
                const rowIndex = medsRowCounter++;
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="px-3 py-2"><input type="text" name="meds_table[${rowIndex}][name]" placeholder="Medikament" class="form-input text-sm"></td>
                    <td class="px-3 py-2"><input type="text" name="meds_table[${rowIndex}][dose]" placeholder="Dosis" class="form-input text-sm"></td>
                    <td class="px-3 py-2"><input type="time" name="meds_table[${rowIndex}][time]" class="form-input text-sm"></td>
                    <td class="px-3 py-2 text-center"><button type="button" class="remove-meds-row text-red-600 hover:text-red-900 font-bold text-xl">&times;</button></td>
                `;
                tableBody.appendChild(tr);
            }

            if (addButton) addButton.addEventListener('click', addRow);

            tableBody?.addEventListener('click', (e) => {
                if (e.target.classList.contains('remove-meds-row')) {
                    e.target.closest('tr').remove();
                }
            });

            // Initialize
            updateWizard();
        });
    </script>
</body>
</html>
