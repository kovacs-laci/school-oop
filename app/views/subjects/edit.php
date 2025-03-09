<?php
$html = <<<HTML
        <form method='post' action='/subjects'>
            <input type='hidden' name='_method' value='PATCH'>
            <input type="hidden" name="id" value="{$subject->id}">
            <fieldset>
                <label for="subject">Tantárgy</label>
                <input type="text" name="name" id="name" value="{$subject->name}">
            </fieldset>
            <button type="submit" name="btn-update">Mentés</button>
            <a href="/subjects">Mégse</a>
        </form>
    HTML;

echo $html;