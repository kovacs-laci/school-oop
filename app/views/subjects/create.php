<?php
echo <<<HTML
        <form method='post' action='/subjects'>
            <fieldset>
                <label for="name">Tantárgy</label>
                <input type="text" name="name" id="name">
            </fieldset>
            <button type="submit" name="btn-save">Mentés</button>
            <a href="/subjects">Mégse</a>
        </form>
    HTML;