<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Neuer Tagebucheintrag</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Neuer Tagebucheintrag</h1>
    <form action="index.php?action=save" method="post">
        <fieldset>
            <legend>Metadaten</legend>
            <label for="entry_date">Datum:</label>
            <input type="date" id="entry_date" name="entry_date" value="<?php echo date('Y-m-d'); ?>" required>
            <label for="entry_time">Uhrzeit:</label>
            <input type="time" id="entry_time" name="entry_time" value="<?php echo date('H:i'); ?>">
        </fieldset>

        <h2>A. MOTORISCHE SYMPTOME</h2>

        <fieldset>
            <legend>1. Tremor (Zittern)</legend>
            <label><input type="checkbox" name="tremor_present" value="1"> Tremor vorhanden?</label>
            <div class="checkbox-group">
                Betroffene Körperregion(en):<br>
                <label><input type="checkbox" name="tremor_regions[]" value="Hand links"> Hand links</label>
                <label><input type="checkbox" name="tremor_regions[]" value="Hand rechts"> Hand rechts</label>
                <label><input type="checkbox" name="tremor_regions[]" value="Kinn/Lippe"> Kinn/Lippe</label>
                <label><input type="checkbox" name="tremor_regions[]" value="Bein"> Bein</label>
                <label><input type="checkbox" name="tremor_regions[]" value="Kopf"> Kopf</label>
            </div>
            <label for="tremor_intensity">Intensität (1-10):</label>
            <input type="number" id="tremor_intensity" name="tremor_intensity" min="1" max="10">
            <label for="tremor_duration_min">Dauer (Minuten):</label>
            <input type="number" id="tremor_duration_min" name="tremor_duration_min">
            <label>Auslöser/Situation:</label>
            <select name="tremor_context">
                <option value="">Bitte wählen</option>
                <option value="In Ruhe">In Ruhe</option>
                <option value="Nur bei Bewegung">Nur bei Bewegung</option>
                <option value="Bei Stress">Bei Stress</option>
                <option value="Nach Kaffee/Energiedrinks">Nach Kaffee/Energiedrinks</option>
            </select>
            <label for="tremor_notes">Besonderheiten:</label>
            <textarea id="tremor_notes" name="tremor_notes"></textarea>
        </fieldset>

        <fieldset>
            <legend>2. Steifheit/Rigor (Muskelspannung)</legend>
            <label><input type="checkbox" name="rigor_present" value="1"> Steifheit vorhanden?</label>
            <div class="checkbox-group">
                Betroffene Körperregion(en):<br>
                <label><input type="checkbox" name="rigor_regions[]" value="Nackenbereich"> Nackenbereich</label>
                <label><input type="checkbox" name="rigor_regions[]" value="Schulter(n)"> Schulter(n)</label>
            </div>
            <label for="rigor_intensity">Intensität (1-10):</label>
            <input type="number" id="rigor_intensity" name="rigor_intensity" min="1" max="10">
        </fieldset>

        <h2>B. NICHT-MOTORISCHE SYMPTOME</h2>
        <fieldset>
            <legend>10. Schlafstörungen</legend>
            <label for="sleep_quality">Schlafqualität:</label>
            <select id="sleep_quality" name="sleep_quality">
                <option value="Gut / Erholsam">Gut / Erholsam</option>
                <option value="Ausreichend">Ausreichend</option>
                <option value="Schlecht / Nicht erholsam">Schlecht / Nicht erholsam</option>
            </select>
            <label for="sleep_hours_duration">Schlafdauer (Stunden):</label>
            <input type="number" id="sleep_hours_duration" name="sleep_hours_duration" step="0.5">
        </fieldset>

        <fieldset>
            <legend>Gesamtbefindlichkeit</legend>
            <label for="overall_wellbeing_score">Gesamtbefindlichkeit heute (1-10):</label>
            <input type="number" id="overall_wellbeing_score" name="overall_wellbeing_score" min="1" max="10">
        </fieldset>

        <button type="submit">Eintrag speichern</button>
    </form>
</body>
</html>
