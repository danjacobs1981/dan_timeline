<div class="timelineAbout">

    <div class="control-submit control-submit-sticky">
        <button data-id="{{ $timeline->id }}" type="submit" class="btn" disabled>Update About</button>
    </div>

    <p>Give a summary of what your timeline is about.</p>

    <div class="control control--select">
        <label class="control__label" for="select">Test Dropdown</label>
        <select name="select" id="select">
            <option value="volvo">Volvo</option>
            <option value="saab">Saab</option>
            <option value="mercedes">Mercedes</option>
            <option value="audi">Audi</option>
        </select>
        <p>Helpful line of text goes along here.</p>
    </div>

    <div class="control control--textarea">
        <label class="control__label" for="textarea">Test Textarea</label>
        <textarea id="textarea" name="textarea" maxlength="1000" rows="4" cols="50">Blah blah blah</textarea>
        <p>Helpful line of text goes along here.</p>
    </div>

    <div class="control-submit">
        <button data-id="{{ $timeline->id }}" type="submit" class="btn" disabled>Update About</button>
    </div>

</div>
