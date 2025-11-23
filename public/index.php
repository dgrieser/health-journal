<?php

require_once '../src/database.php';

initialize_database();

$allFields = [
    'entry_date',
    'entry_time',
    'tremor_present',
    'tremor_regions',
    'tremor_regions_other',
    'tremor_intensity',
    'tremor_duration_min',
    'tremor_context',
    'tremor_context_other',
    'tremor_notes',
    'rigor_present',
    'rigor_regions',
    'rigor_regions_other',
    'rigor_intensity',
    'rigor_severity',
    'rigor_time_of_day',
    'rigor_improved_by_movement',
    'rigor_notes',
    'bradykinesia_present',
    'bradykinesia_activities',
    'bradykinesia_activities_other',
    'bradykinesia_intensity',
    'bradykinesia_impact',
    'bradykinesia_examples',
    'bradykinesia_notes',
    'arm_swing_asymmetry_present',
    'arm_swing_side',
    'arm_swing_severity',
    'arm_swing_intensity',
    'arm_swing_consistency',
    'arm_swing_notes',
    'fine_motor_issues_present',
    'fine_motor_activities',
    'fine_motor_activities_other',
    'fine_motor_intensity',
    'fine_motor_desc',
    'fine_motor_notes',
    'gait_issues_present',
    'gait_characteristics',
    'gait_characteristics_other',
    'gait_intensity',
    'gait_time_of_day',
    'gait_trigger',
    'gait_notes',
    'balance_issues_present',
    'balance_type',
    'balance_type_other',
    'balance_intensity',
    'balance_situations',
    'balance_situations_other',
    'balance_notes',
    'posture_status',
    'posture_observed_in_mirror',
    'posture_notes',
    'dystonia_present',
    'dystonia_location',
    'dystonia_location_other',
    'dystonia_intensity',
    'dystonia_duration_min',
    'dystonia_trigger',
    'dystonia_notes',
    'sleep_quality',
    'sleep_problems_list',
    'sleep_rem_behavior_present',
    'sleep_rem_symptoms',
    'sleep_rem_trigger_notes',
    'sleep_hours_duration',
    'sleep_quality_score',
    'sleep_wake_count',
    'sleep_longest_wake_min',
    'sleep_environment_factors',
    'sleep_environment_notes',
    'fatigue_severity',
    'fatigue_nap_count',
    'fatigue_nap_avg_min',
    'fatigue_nap_voluntary',
    'fatigue_activities_affected',
    'fatigue_energy_morning',
    'fatigue_energy_noon',
    'fatigue_energy_evening',
    'fatigue_energy_avg',
    'fatigue_notes',
    'smell_ability',
    'smell_missing_items',
    'smell_subjective_perception',
    'smell_impact',
    'taste_ability',
    'taste_change_desc',
    'taste_impacts',
    'mood_general',
    'mood_depression_severity',
    'mood_depressive_symptoms',
    'mood_apathy_severity',
    'mood_motivation_score',
    'mood_anxiety_severity',
    'mood_anxiety_types',
    'cognitive_concentration',
    'cognitive_memory',
    'cognitive_specific_issues',
    'cognitive_fitness_score',
    'cognitive_impact',
    'pain_present',
    'pain_severity',
    'pain_locations',
    'pain_locations_other',
    'pain_character',
    'pain_character_other',
    'pain_intensity',
    'pain_duration',
    'pain_trigger',
    'pain_relief_methods',
    'pain_notes',
    'digestion_status',
    'stool_frequency',
    'constipation_details',
    'appetite_status',
    'weight_change',
    'weight_change_notes',
    'digestion_notes',
    'vegetative_symptoms',
    'vegetative_blood_pressure',
    'vegetative_sweating',
    'vegetative_temperature',
    'vegetative_bladder',
    'meds_taken',
    'meds_details',
    'meds_effect',
    'meds_timing',
    'lifestyle_factors',
    'lifestyle_factors_other',
    'pattern_recognized',
    'pattern_triggers',
    'pattern_helped',
    'overall_wellbeing_score',
    'daily_summary_notes'
];

$booleanFields = [
    'tremor_present',
    'rigor_present',
    'bradykinesia_present',
    'arm_swing_asymmetry_present',
    'fine_motor_issues_present',
    'gait_issues_present',
    'balance_issues_present',
    'posture_observed_in_mirror',
    'dystonia_present',
    'sleep_rem_behavior_present',
    'pain_present',
    'meds_taken'
];

$arrayFields = [
    'tremor_regions',
    'rigor_regions',
    'bradykinesia_activities',
    'fine_motor_activities',
    'gait_characteristics',
    'balance_type',
    'balance_situations',
    'sleep_problems_list',
    'sleep_rem_symptoms',
    'sleep_environment_factors',
    'fatigue_activities_affected',
    'smell_missing_items',
    'smell_subjective_perception',
    'taste_impacts',
    'mood_depressive_symptoms',
    'mood_anxiety_types',
    'cognitive_specific_issues',
    'pain_locations',
    'pain_character',
    'pain_relief_methods',
    'constipation_details',
    'vegetative_blood_pressure',
    'dystonia_location',
    'lifestyle_factors',
    'pattern_recognized'
];

function fetch_previous_meds_details(PDO $db): string {
    $today = date('Y-m-d');
    $stmt = $db->prepare("SELECT meds_details FROM daily_symptom_log WHERE entry_date < :today AND meds_details IS NOT NULL AND meds_details != '' ORDER BY entry_date DESC LIMIT 1");
    $stmt->execute([':today' => $today]);

    $result = $stmt->fetchColumn();
    return $result !== false ? (string)$result : '';
}

function collect_form_data(array $allFields, array $booleanFields, array $arrayFields): array {
    $data = [];

    foreach ($allFields as $field) {
        if (in_array($field, $booleanFields, true)) {
            $data[$field] = isset($_POST[$field]) ? 1 : 0;
            continue;
        }

        if (in_array($field, $arrayFields, true)) {
            $values = $_POST[$field] ?? [];
            $data[$field] = $values ? implode(', ', array_map('trim', (array)$values)) : '';
            continue;
        }

        if ($field === 'fatigue_nap_voluntary') {
            if (!array_key_exists($field, $_POST) || $_POST[$field] === '') {
                $data[$field] = null;
            } else {
                $data[$field] = (int)$_POST[$field];
            }
            continue;
        }

        if ($field === 'meds_details') {
            $rows = isset($_POST['meds_table']) && is_array($_POST['meds_table']) ? $_POST['meds_table'] : [];
            $normalizedRows = array_values(array_filter(array_map(static function ($row) {
                $name = isset($row['name']) ? trim((string)$row['name']) : '';
                $dose = isset($row['dose']) ? trim((string)$row['dose']) : '';
                $time = isset($row['time']) ? trim((string)$row['time']) : '';

                if ($name === '' && $dose === '' && $time === '') {
                    return null;
                }

                return ['name' => $name, 'dose' => $dose, 'time' => $time];
            }, $rows)));

            $data[$field] = $normalizedRows ? json_encode($normalizedRows, JSON_UNESCAPED_UNICODE) : '';
            continue;
        }

        $value = $_POST[$field] ?? null;
        $data[$field] = is_string($value) ? trim($value) : $value;
    }

    $energyValues = array_filter([
        isset($_POST['fatigue_energy_morning']) ? (int)$_POST['fatigue_energy_morning'] : null,
        isset($_POST['fatigue_energy_noon']) ? (int)$_POST['fatigue_energy_noon'] : null,
        isset($_POST['fatigue_energy_evening']) ? (int)$_POST['fatigue_energy_evening'] : null,
    ], static fn($v) => $v !== null && $v !== 0);

    $data['fatigue_energy_avg'] = $energyValues ? (int)round(array_sum($energyValues) / count($energyValues)) : null;

    $vegetativeParts = array_filter([
        $data['vegetative_blood_pressure'],
        $data['vegetative_sweating'],
        $data['vegetative_temperature'],
        $data['vegetative_bladder']
    ], static fn($v) => $v !== null && $v !== '');

    $data['vegetative_symptoms'] = $vegetativeParts ? implode(', ', $vegetativeParts) : '';

    return $data;
}

$previousMedsDetails = '';
$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'new':
        $entry = null;
        $previousMedsDetails = fetch_previous_meds_details(get_db_connection());
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
        $data = collect_form_data($allFields, $booleanFields, $arrayFields);

        $columns = implode(', ', $allFields);
        $placeholders = ':' . implode(', :', $allFields);
        $stmt = $db->prepare("INSERT INTO daily_symptom_log ($columns) VALUES ($placeholders)");

        $params = [];
        foreach ($allFields as $field) {
            $params[":$field"] = $data[$field];
        }

        $stmt->execute($params);

        header('Location: index.php');
        break;
    case 'update':
        $db = get_db_connection();
        $id = (int)($_POST['id'] ?? 0);

        if (!$id) {
            header('Location: index.php');
            exit;
        }

        $data = collect_form_data($allFields, $booleanFields, $arrayFields);

        $setClause = implode(', ', array_map(static fn($field) => "$field = :$field", $allFields));
        $stmt = $db->prepare("UPDATE daily_symptom_log SET $setClause WHERE id = :id");

        $params = [':id' => $id];
        foreach ($allFields as $field) {
            $params[":$field"] = $data[$field];
        }

        $stmt->execute($params);

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
            <script src="https://cdn.tailwindcss.com"></script>
            <link rel="stylesheet" href="style.css">
        </head>
        <body class="bg-gray-100 text-gray-800">
            <div class="container mx-auto p-4">
                <h1 class="text-2xl font-bold mb-4">Tagebuch</h1>
                <a href="index.php?action=new" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Neuer Eintrag</a>
                <div class="overflow-x-auto mt-4">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="py-2 px-4">Datum</th>
                                <th class="py-2 px-4">Zeit</th>
                                <th class="py-2 px-4">Tremor</th>
                                <th class="py-2 px-4">Steifheit</th>
                                <th class="py-2 px-4">Bradykinese</th>
                                <th class="py-2 px-4">Schlafqualität</th>
                                <th class="py-2 px-4">Fatigue</th>
                                <th class="py-2 px-4">Stimmung</th>
                                <th class="py-2 px-4">Schmerzen</th>
                                <th class="py-2 px-4">Befinden</th>
                                <th class="py-2 px-4">Aktionen</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <?php foreach ($entries as $entry): ?>
                                <tr class="border-b">
                                    <td class="py-2 px-4"><?php echo htmlspecialchars($entry['entry_date']); ?></td>
                                    <td class="py-2 px-4"><?php echo htmlspecialchars($entry['entry_time']); ?></td>
                                    <td class="py-2 px-4"><?php echo $entry['tremor_present'] ? 'Ja' : 'Nein'; ?></td>
                                    <td class="py-2 px-4"><?php echo $entry['rigor_present'] ? 'Ja' : 'Nein'; ?></td>
                                    <td class="py-2 px-4"><?php echo $entry['bradykinesia_present'] ? 'Ja' : 'Nein'; ?></td>
                                    <td class="py-2 px-4"><?php echo htmlspecialchars($entry['sleep_quality']); ?></td>
                                    <td class="py-2 px-4"><?php echo htmlspecialchars($entry['fatigue_severity']); ?></td>
                                    <td class="py-2 px-4"><?php echo htmlspecialchars($entry['mood_general']); ?></td>
                                    <td class="py-2 px-4"><?php echo htmlspecialchars($entry['pain_severity']); ?></td>
                                    <td class="py-2 px-4"><?php echo htmlspecialchars($entry['overall_wellbeing_score']); ?></td>
                                    <td class="py-2 px-4">
                                        <a href="index.php?action=edit&id=<?php echo (int)$entry['id']; ?>" class="text-blue-500 hover:underline">Bearbeiten</a>
                                        <form action="index.php?action=delete" method="post" style="display:inline;" onsubmit="return confirm('Diesen Eintrag löschen?');">
                                            <input type="hidden" name="id" value="<?php echo (int)$entry['id']; ?>">
                                            <button type="submit" class="text-red-500 hover:underline ml-2">Löschen</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </body>
        </html>
        <?php
        break;
}
