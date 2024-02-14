<div class="timelineAbout">

    <div class="control-submit control-submit-sticky">
        <button data-id="{{ $timeline->id }}" type="submit" class="btn" disabled>Update About</button>
    </div>

    <p>Give a summary of what your timeline is about.</p>

    <!--<div class="control control--select">
        <label class="control__label" for="select">Test Dropdown</label>
        <select name="select" id="select">
            <option value="volvo">Volvo</option>
            <option value="saab">Saab</option>
            <option value="mercedes">Mercedes</option>
            <option value="audi">Audi</option>
        </select>
        <p>Helpful line of text goes along here.</p>
    </div>-->

    <div class="control control--textarea">
        <label class="control__label" for="description">Timeline Description</label>
        <textarea id="description" name="description" maxlength="1000" rows="6" cols="50">{{ old('description', $timeline->description) }}</textarea>
        <p>Provide a description of what the timeline is about.</p>
        <p>New lines are converted to paragraphs.</p>
        <p>HTML tags are not allowed and are automatically removed.</p>
    </div>

    <div class="control control--image control--select {{ isset($timeline->image) ? 'control--image-exists' : '' }}">
        <label class="control__label" for="image">Timeline Image</label>
        <input type="file" id="image" name="image" accept=".jpeg, .png, .jpg, .gif, .webp">
        <input type="hidden" name="image_delete" value="0">
        <div class="image-preview">
            <a href="#" class="btn btn-danger">
                Remove / Replace Image
            </a>
            <div>
                <div>
                    <strong>
                        Preview
                    </strong>
                    <img src="{{ isset($timeline->image) ? asset('storage/images/timeline/'.$timeline->id.'/'.$timeline->image) : '' }}" width="380">
                </div>
            </div>
        </div>
    </div>

    <div class="control-submit">
        <button data-id="{{ $timeline->id }}" type="submit" class="btn" disabled>Update About</button>
    </div>

</div>

@isset($modal)
    @vite('resources/js/portal/timeline/about.js')
@else
    @push('scripts')
        @vite('resources/js/portal/timeline/about.js')
    @endpush
@endif
