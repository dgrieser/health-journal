<?php

function get_db_connection() {
    $dbDir = __DIR__ . '/../db';
    if (!is_dir($dbDir)) {
        mkdir($dbDir, 0777, true);
    }

    $dbPath = $dbDir . '/journal.db';
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
}

function initialize_database() {
    $db = get_db_connection();
    $db->exec(<<<'SQL'
        CREATE TABLE IF NOT EXISTS daily_symptom_log (
            -- METADATA
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            entry_date TEXT NOT NULL, -- Format: YYYY-MM-DD
            entry_time TEXT, -- Format: HH:MM

            -- A. MOTORISCHE SYMPTOME

            -- 1. Tremor
            tremor_present INTEGER DEFAULT 0, -- 0=No, 1=Yes
            tremor_regions TEXT, -- e.g., "Hand Left, Chin"
            tremor_regions_other TEXT,
            tremor_intensity INTEGER, -- Scale 1-10
            tremor_duration_min INTEGER,
            tremor_context TEXT, -- e.g., "Resting", "Stress"
            tremor_context_other TEXT,
            tremor_notes TEXT,

            -- 2. Steifheit/Rigor
            rigor_present INTEGER DEFAULT 0,
            rigor_regions TEXT, -- e.g., "Neck, Shoulders"
            rigor_regions_other TEXT,
            rigor_intensity INTEGER, -- Scale 1-10
            rigor_severity TEXT, -- "Light", "Moderate", "Severe"
            rigor_time_of_day TEXT, -- "Morning", "Evening", etc.
            rigor_improved_by_movement INTEGER, -- 0=No, 1=Yes
            rigor_notes TEXT,

            -- 3. Bewegungsverlangsamung (Bradykinese)
            bradykinesia_present INTEGER DEFAULT 0,
            bradykinesia_activities TEXT, -- e.g., "Walking, Writing"
            bradykinesia_activities_other TEXT,
            bradykinesia_intensity INTEGER, -- Scale 1-10
            bradykinesia_impact TEXT, -- "Barely noticeable", "Disturbing"
            bradykinesia_examples TEXT,
            bradykinesia_notes TEXT,

            -- 4. Asymmetrischer Armschlag
            arm_swing_asymmetry_present INTEGER DEFAULT 0,
            arm_swing_side TEXT, -- "Left", "Right", "Both"
            arm_swing_severity TEXT, -- "Reduced", "Still", "Delayed"
            arm_swing_intensity INTEGER, -- Scale 1-10
            arm_swing_consistency TEXT, -- "Always", "Fatigue only"
            arm_swing_notes TEXT,

            -- 5. Feinmotorik
            fine_motor_issues_present INTEGER DEFAULT 0,
            fine_motor_activities TEXT, -- "Schreiben", "Buttons"
            fine_motor_activities_other TEXT,
            fine_motor_intensity INTEGER, -- Scale 1-10
            fine_motor_desc TEXT, -- Description of specific issues
            fine_motor_notes TEXT,

            -- 6. Gangstörung
            gait_issues_present INTEGER DEFAULT 0,
            gait_characteristics TEXT, -- "Small steps", "Shuffling"
            gait_characteristics_other TEXT,
            gait_intensity INTEGER, -- Scale 1-10
            gait_time_of_day TEXT,
            gait_trigger TEXT,
            gait_notes TEXT,

            -- 7. Gleichgewicht
            balance_issues_present INTEGER DEFAULT 0,
            balance_type TEXT, -- "Dizziness", "Fall tendency"
            balance_intensity INTEGER, -- Scale 1-10
            balance_type_other TEXT,
            balance_situations TEXT, -- "Standing up", "Dark room"
            balance_situations_other TEXT,
            balance_notes TEXT,

            -- 8. Körperhaltung
            posture_status TEXT, -- "Upright", "Stooped", "Variable"
            posture_observed_in_mirror INTEGER, -- 0=No, 1=Yes
            posture_notes TEXT,

            -- 9. Dystonie und Krämpfe
            dystonia_present INTEGER DEFAULT 0,
            dystonia_location TEXT,
            dystonia_location_other TEXT,
            dystonia_intensity INTEGER, -- Scale 1-10
            dystonia_duration_min INTEGER,
            dystonia_trigger TEXT,
            dystonia_notes TEXT,

            -- B. NICHT-MOTORISCHE SYMPTOME

            -- 10. Schlaf
            sleep_quality TEXT, -- "Good", "Sufficient", "Bad"
            sleep_problems_list TEXT, -- "Falling asleep", "Waking up"
            sleep_rem_behavior_present INTEGER DEFAULT 0, -- REM Behavior Disorder check
            sleep_rem_symptoms TEXT, -- "Vivid dreams", "Talking", "Kicking"
            sleep_rem_trigger_notes TEXT,
            sleep_hours_duration REAL,
            sleep_quality_score INTEGER, -- Scale 1-10
            sleep_wake_count INTEGER,
            sleep_longest_wake_min INTEGER,
            sleep_environment_factors TEXT,
            sleep_environment_notes TEXT,

            -- 11. Tagesschläfrigkeit (Fatigue)
            fatigue_severity TEXT, -- "None", "Light", "Moderate", "Severe"
            fatigue_nap_count INTEGER,
            fatigue_nap_avg_min INTEGER,
            fatigue_nap_voluntary INTEGER, -- 0=Involuntary, 1=Voluntary
            fatigue_activities_affected TEXT,
            fatigue_energy_morning INTEGER,
            fatigue_energy_noon INTEGER,
            fatigue_energy_evening INTEGER,
            fatigue_energy_avg INTEGER, -- Average of Morning/Noon/Eve (Scale 1-10)
            fatigue_notes TEXT,

            -- 12. Riechstörung (Hyposmie)
            -- Note: Though tracked weekly, included here for schema consistency
            smell_ability TEXT, -- "Normal", "Reduced", "Anosmia"
            smell_missing_items TEXT, -- "Coffee", "Flowers"
            smell_subjective_perception TEXT,
            smell_impact TEXT,

            -- 13. Geschmacksstörung
            taste_ability TEXT, -- "Normal", "Reduced", "Metallic"
            taste_change_desc TEXT,
            taste_impacts TEXT,

            -- 14. Stimmung
            mood_general TEXT, -- "Stable", "Sad", "Anxious"
            mood_depressive_symptoms TEXT, -- List of symptoms
            mood_depression_severity TEXT,
            mood_apathy_severity TEXT, -- "None", "Light", "Severe"
            mood_motivation_score INTEGER, -- Scale 1-10
            mood_anxiety_severity TEXT,
            mood_anxiety_types TEXT,

            -- 15. Kognitive Funktionen
            cognitive_concentration TEXT, -- "Normal", "Reduced"
            cognitive_memory TEXT, -- "Normal", "Reduced"
            cognitive_specific_issues TEXT, -- "Words", "Names"
            cognitive_fitness_score INTEGER, -- Scale 1-10
            cognitive_impact TEXT,

            -- 16. Schmerzen
            pain_present INTEGER DEFAULT 0,
            pain_severity TEXT,
            pain_locations TEXT, -- "Neck", "Back", "Shoulder"
            pain_locations_other TEXT,
            pain_character TEXT, -- "Sharp", "Dull", "Cramp"
            pain_character_other TEXT,
            pain_intensity INTEGER, -- Scale 1-10
            pain_duration TEXT,
            pain_trigger TEXT,
            pain_relief_methods TEXT, -- "Heat", "Meds"
            pain_notes TEXT,

            -- 17. Verdauung
            digestion_status TEXT, -- "Normal", "Constipation"
            stool_frequency TEXT,
            constipation_details TEXT,
            appetite_status TEXT,
            weight_change TEXT,
            weight_change_notes TEXT,
            digestion_notes TEXT,

            -- 18. Vegetative Symptome
            vegetative_symptoms TEXT, -- "Dizziness", "Sweating", "Bladder"
            vegetative_blood_pressure TEXT,
            vegetative_sweating TEXT,
            vegetative_temperature TEXT,
            vegetative_bladder TEXT,

            -- C. ZUSÄTZLICHE BEOBACHTUNGEN

            -- 19. Medikamente
            meds_taken INTEGER DEFAULT 0,
            meds_details TEXT, -- JSON or text: "Levodopa: 100mg, ..."
            meds_effect TEXT, -- "Improved", "Worse", "None"
            meds_timing TEXT,
            lifestyle_factors TEXT, -- "Exercise", "Stress"
            lifestyle_factors_other TEXT,

            -- 20. Auslöser und Muster
            pattern_recognized TEXT, -- "Worse in morning", "After food"
            pattern_triggers TEXT,
            pattern_helped TEXT,
            overall_wellbeing_score INTEGER, -- Scale 1-10
            daily_summary_notes TEXT
        )
    SQL);

    $columns = [
        'tremor_regions_other' => 'TEXT',
        'tremor_context_other' => 'TEXT',
        'rigor_regions_other' => 'TEXT',
        'bradykinesia_activities_other' => 'TEXT',
        'bradykinesia_examples' => 'TEXT',
        'arm_swing_notes' => 'TEXT',
        'fine_motor_activities_other' => 'TEXT',
        'fine_motor_notes' => 'TEXT',
        'gait_characteristics_other' => 'TEXT',
        'gait_notes' => 'TEXT',
        'balance_type_other' => 'TEXT',
        'balance_situations_other' => 'TEXT',
        'balance_notes' => 'TEXT',
        'posture_notes' => 'TEXT',
        'dystonia_location_other' => 'TEXT',
        'dystonia_notes' => 'TEXT',
        'sleep_rem_trigger_notes' => 'TEXT',
        'sleep_environment_factors' => 'TEXT',
        'sleep_environment_notes' => 'TEXT',
        'fatigue_activities_affected' => 'TEXT',
        'fatigue_energy_morning' => 'INTEGER',
        'fatigue_energy_noon' => 'INTEGER',
        'fatigue_energy_evening' => 'INTEGER',
        'fatigue_notes' => 'TEXT',
        'smell_subjective_perception' => 'TEXT',
        'smell_impact' => 'TEXT',
        'taste_change_desc' => 'TEXT',
        'taste_impacts' => 'TEXT',
        'mood_depression_severity' => 'TEXT',
        'mood_anxiety_types' => 'TEXT',
        'cognitive_impact' => 'TEXT',
        'pain_severity' => 'TEXT',
        'pain_locations_other' => 'TEXT',
        'pain_character_other' => 'TEXT',
        'pain_duration' => 'TEXT',
        'pain_trigger' => 'TEXT',
        'pain_notes' => 'TEXT',
        'constipation_details' => 'TEXT',
        'weight_change' => 'TEXT',
        'weight_change_notes' => 'TEXT',
        'digestion_notes' => 'TEXT',
        'vegetative_blood_pressure' => 'TEXT',
        'vegetative_sweating' => 'TEXT',
        'vegetative_temperature' => 'TEXT',
        'vegetative_bladder' => 'TEXT',
        'meds_timing' => 'TEXT',
        'lifestyle_factors_other' => 'TEXT',
        'pattern_triggers' => 'TEXT',
        'pattern_helped' => 'TEXT'
    ];

    foreach ($columns as $column => $definition) {
        add_column_if_missing($db, 'daily_symptom_log', $column, $definition);
    }

    $db->exec(<<<'SQL'
        CREATE VIEW IF NOT EXISTS weekly_parkinson_summary AS
        SELECT
            strftime('%W', entry_date) as week_number,
            COUNT(id) as entries_count,
            AVG(tremor_intensity) as avg_tremor,
            AVG(rigor_intensity) as avg_rigor,
            AVG(bradykinesia_intensity) as avg_slowness,
            AVG(sleep_quality_score) as avg_sleep_quality,
            AVG(overall_wellbeing_score) as avg_wellbeing
        FROM daily_symptom_log
        GROUP BY week_number;
    SQL);
}

function add_column_if_missing(PDO $db, string $table, string $column, string $definition): void {
    $stmt = $db->query("PRAGMA table_info($table)");
    $existingColumns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($existingColumns as $existingColumn) {
        if ($existingColumn['name'] === $column) {
            return;
        }
    }

    $db->exec("ALTER TABLE $table ADD COLUMN $column $definition");
}
