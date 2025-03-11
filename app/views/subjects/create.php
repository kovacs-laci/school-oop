<?php
echo <<<HTML
        <form method='post' action='/subjects'>
            <fieldset>
                <label for="name">Tantárgy</label>
                <input type="text" name="name" id="name">
                <hr>
                <button type="submit" name="btn-save"><i class="fa fa-save"></i>&nbsp;Mentés</button>
                <a href="/subjects"><i class="fa fa-cancel"></i>&nbsp;Mégse</a>
            </fieldset>
        </form>
    HTML;