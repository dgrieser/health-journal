# SQLite Schema
This schema is designed to be a "flat" structure (one primary table) to make exporting to CSV (as requested in the protocol) easy, while still maintaining data integrity for the specific scales (1-10) and boolean checks required.
## Database Design Strategy
 * Booleans: SQLite uses INTEGER (0 for No, 1 for Yes).
 * Scales: INTEGER for 1-10 ratings.
 * Multi-Selects: Fields like "Affected Body Regions" are defined as TEXT. You should store these as comma-separated values (e.g., "Hand left, Leg") or JSON strings to maintain the simplicity of a single daily log entry.
 * Sections: The columns are grouped by the sections defined in the document (A, B, C).
## SQLite Schema Code
```
CREATE TABLE daily_symptom_log (
    -- METADATA
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    entry_date TEXT NOT NULL, -- Format: YYYY-MM-DD
    entry_time TEXT, -- Format: HH:MM
    
    -- A. MOTORISCHE SYMPTOME
    
    -- 1. Tremor
    tremor_present INTEGER DEFAULT 0, -- 0=No, 1=Yes
    tremor_regions TEXT, -- e.g., "Hand Left, Chin"
    tremor_intensity INTEGER, -- Scale 1-10
    tremor_duration_min INTEGER,
    tremor_context TEXT, -- e.g., "Resting", "Stress"
    tremor_notes TEXT,

    -- 2. Steifheit/Rigor
    rigor_present INTEGER DEFAULT 0,
    rigor_regions TEXT, -- e.g., "Neck, Shoulders"
    rigor_intensity INTEGER, -- Scale 1-10
    rigor_severity TEXT, -- "Light", "Moderate", "Severe"
    rigor_time_of_day TEXT, -- "Morning", "Evening", etc.
    rigor_improved_by_movement INTEGER, -- 0=No, 1=Yes
    rigor_notes TEXT,

    -- 3. Bewegungsverlangsamung (Bradykinese)
    bradykinesia_present INTEGER DEFAULT 0,
    bradykinesia_activities TEXT, -- e.g., "Walking, Writing"
    bradykinesia_intensity INTEGER, -- Scale 1-10
    bradykinesia_impact TEXT, -- "Barely noticeable", "Disturbing"
    bradykinesia_notes TEXT,

    -- 4. Asymmetrischer Armschlag
    arm_swing_asymmetry_present INTEGER DEFAULT 0,
    arm_swing_side TEXT, -- "Left", "Right", "Both"
    arm_swing_severity TEXT, -- "Reduced", "Still", "Delayed"
    arm_swing_intensity INTEGER, -- Scale 1-10
    arm_swing_consistency TEXT, -- "Always", "Fatigue only"

    -- 5. Feinmotorik
    fine_motor_issues_present INTEGER DEFAULT 0,
    fine_motor_activities TEXT, -- "Schreiben", "Buttons"
    fine_motor_intensity INTEGER, -- Scale 1-10
    fine_motor_desc TEXT, -- Description of specific issues

    -- 6. Gangstörung
    gait_issues_present INTEGER DEFAULT 0,
    gait_characteristics TEXT, -- "Small steps", "Shuffling"
    gait_intensity INTEGER, -- Scale 1-10
    gait_time_of_day TEXT,
    gait_trigger TEXT,

    -- 7. Gleichgewicht
    balance_issues_present INTEGER DEFAULT 0,
    balance_type TEXT, -- "Dizziness", "Fall tendency"
    balance_intensity INTEGER, -- Scale 1-10
    balance_situations TEXT, -- "Standing up", "Dark room"

    -- 8. Körperhaltung
    posture_status TEXT, -- "Upright", "Stooped", "Variable"
    posture_observed_in_mirror INTEGER, -- 0=No, 1=Yes

    -- 9. Dystonie und Krämpfe
    dystonia_present INTEGER DEFAULT 0,
    dystonia_location TEXT,
    dystonia_intensity INTEGER, -- Scale 1-10
    dystonia_duration_min INTEGER,
    dystonia_trigger TEXT,

    -- B. NICHT-MOTORISCHE SYMPTOME
    
    -- 10. Schlaf
    sleep_quality TEXT, -- "Good", "Sufficient", "Bad"
    sleep_problems_list TEXT, -- "Falling asleep", "Waking up"
    sleep_rem_behavior_present INTEGER DEFAULT 0, -- REM Behavior Disorder check
    sleep_rem_symptoms TEXT, -- "Vivid dreams", "Talking", "Kicking"
    sleep_hours_duration REAL,
    sleep_quality_score INTEGER, -- Scale 1-10
    sleep_wake_count INTEGER,
    sleep_longest_wake_min INTEGER,

    -- 11. Tagesschläfrigkeit (Fatigue)
    fatigue_severity TEXT, -- "None", "Light", "Moderate", "Severe"
    fatigue_nap_count INTEGER,
    fatigue_nap_avg_min INTEGER,
    fatigue_nap_voluntary INTEGER, -- 0=Involuntary, 1=Voluntary
    fatigue_energy_avg INTEGER, -- Average of Morning/Noon/Eve (Scale 1-10)

    -- 12. Riechstörung (Hyposmie)
    -- Note: Though tracked weekly, included here for schema consistency
    smell_ability TEXT, -- "Normal", "Reduced", "Anosmia"
    smell_missing_items TEXT, -- "Coffee", "Flowers"
    
    -- 13. Geschmacksstörung
    taste_ability TEXT, -- "Normal", "Reduced", "Metallic"
    
    -- 14. Stimmung
    mood_general TEXT, -- "Stable", "Sad", "Anxious"
    mood_depressive_symptoms TEXT, -- List of symptoms
    mood_apathy_severity TEXT, -- "None", "Light", "Severe"
    mood_motivation_score INTEGER, -- Scale 1-10
    mood_anxiety_severity TEXT,
    
    -- 15. Kognitive Funktionen
    cognitive_concentration TEXT, -- "Normal", "Reduced"
    cognitive_memory TEXT, -- "Normal", "Reduced"
    cognitive_specific_issues TEXT, -- "Words", "Names"
    cognitive_fitness_score INTEGER, -- Scale 1-10
    
    -- 16. Schmerzen
    pain_present INTEGER DEFAULT 0,
    pain_locations TEXT, -- "Neck", "Back", "Shoulder"
    pain_character TEXT, -- "Sharp", "Dull", "Cramp"
    pain_intensity INTEGER, -- Scale 1-10
    pain_relief_methods TEXT, -- "Heat", "Meds"
    
    -- 17. Verdauung
    digestion_status TEXT, -- "Normal", "Constipation"
    stool_frequency TEXT,
    appetite_status TEXT,
    
    -- 18. Vegetative Symptome
    vegetative_symptoms TEXT, -- "Dizziness", "Sweating", "Bladder"

    -- C. ZUSÄTZLICHE BEOBACHTUNGEN
    
    -- 19. Medikamente
    meds_taken INTEGER DEFAULT 0,
    meds_details TEXT, -- JSON or text: "Levodopa: 100mg, ..."
    meds_effect TEXT, -- "Improved", "Worse", "None"
    lifestyle_factors TEXT, -- "Exercise", "Stress"

    -- 20. Auslöser und Muster
    pattern_recognized TEXT, -- "Worse in morning", "After food"
    overall_wellbeing_score INTEGER, -- Scale 1-10
    daily_summary_notes TEXT
);
```
## How to map the Form to this Schema
 * Checkboxes (Multiple Choice): For fields like tremor_regions where the user might select "Hand left" AND "Leg", concatenating them into a string is the most efficient storage method for this use case (e.g., "Hand left, Leg").
 * Scales: All questions asking for a "Skala 1-10" are mapped to INTEGER columns (e.g., tremor_intensity).
 * Weekly Items: Items labeled "Weekly" in the text (like Smell or Weight) are included in this table. The user can simply leave them NULL on days they do not check them.

## Helper View for Weekly Analysis
The protocol emphasizes identifying patterns over 8-12 weeks. You can use this SQL View to quickly see average severity of key symptoms per week:
```
CREATE VIEW weekly_parkinson_summary AS
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
```
