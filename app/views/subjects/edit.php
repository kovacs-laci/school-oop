<?php
    $html = <<<HTML
        <form method='post' action='/subjects'>
            <input type='hidden' name='_method' value='PATCH'>
            <input type="hidden" name="id" value="{$subject->id}">
            <fieldset>
                <label for="subject">Tantárgy</label>
                <input type="text" name="name" id="name" value="{$subject->name}">
                <hr>
                <button type="submit" name="btn-update"><i class="fa fa-save"></i>&nbsp;Mentés</button>
                <a href="/subjects"><i class="fa fa-cancel"></i>&nbsp;Mégse</a>
            </fieldset>
        </form>
    HTML;
    echo $html;