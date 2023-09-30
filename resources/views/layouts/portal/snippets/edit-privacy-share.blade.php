box for sharing<br/>

<div class="tag-area">

    <label for="tag-input" class="label">Add Languages</label>

    <ul>
        <input type="text" class="tag-input" id="tag-input" />
    </ul>

</div>


<script>

    const tagInput = document.querySelector(".tag-input");
    const tagArea = document.querySelector(".tag-area");
    const ul = document.querySelector(".tag-area ul");
    const label = document.querySelector(".label");

    let tags = [];

    function addEvent(element) {
        tagArea.addEventListener("click", () => {
            element.focus();
        });

        element.addEventListener("focus", () => {
            tagArea.classList.add("active");
            label.classList.add("label-active");
        });

        element.addEventListener("blur", (e) => {
            tagArea.classList.remove("active");
            if (element.value === "" && tags.length === 0) {
                label.classList.remove("label-active");
            }
            if (!element.value.match(/^\s+$/gi) && element.value !== "") {
                tags.push(e.target.value.trim());
                element.value = "";
                renderTags();
            }
        });
        /**
         * NOTE: Keyboard events works unexpected on mobile devices.
         * For mobile devices you need to add this code
         * to get the last character user entered.
         * value[value.length - 1] === " "
         *
         * keycode 32 is for SpaceBar
         * keycode 13 is for EnterKey
         */

        element.addEventListener("keydown", (e) => {
            console.log(e);
            const value = e.target.value;
            if (
                (e.keyCode === 32 ||
                    e.keyCode === 13 ||
                    value[value.length - 1] === " ") &&
                !value.match(/^\s+$/gi) &&
                value !== ""
            ) {
                tags.push(e.target.value.trim());
                element.value = "";
                renderTags();
            }
            if (e.keyCode === 8 && value === "") {
                tags.pop();
                renderTags();
            }
        });
    }
    addEvent(tagInput);

    function renderTags() {
        ul.innerHTML = "";
        tags.forEach((tag, index) => {
            createTag(tag, index);
        });
        const input = document.createElement("input");
        input.type = "text";
        input.className = "tag-input";
        addEvent(input);
        ul.appendChild(input);
        input.focus();
        setTimeout(() => (input.value = ""), 0);
    }

    function createTag(tag, index) {
        const li = document.createElement("li");
        li.className = "tag";
        const text = document.createTextNode(tag);
        const span = document.createElement("span");
        span.className = "cross";
        span.dataset.index = index;
        span.addEventListener("click", (e) => {
            tags = tags.filter((_, index) => index != e.target.dataset.index);
            renderTags();
        });
        li.appendChild(text);
        li.appendChild(span);
        ul.appendChild(li);
    }

</script>

<style>

body {

}

    ul {
    margin-block-start: 0;
    margin-block-end: 0;
    padding-inline-start: 0px;
}

li {
    list-style: none;
}

.tag-area {
    padding: 1rem;
    outline: none;
    width: 600px;
    border: 2px solid #605f6f;
    border-radius: 5px;
    transition: all 0.2s;
    cursor: text;
    display: flex;
    align-items: center;
    position: relative;
}

.label {
    position: absolute;
    background: #1f2023;
    padding: 0 0.3rem;
    color: #adadad;
    top: 22px;
    transition: all 0.1s;
}

.label-active {
    top: -11px;
    color: #8d29ff;
    font-size: 13px;
}

.tag-area ul {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
}

.active {
    border: 2px solid #8d29ff !important;
}

.tag {
    padding-left: 0.8rem;
    background: #353535;
    border-radius: 100px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 0.5rem;
}

.tag-input {
    padding: 0.5rem;
    outline: none;
    border: none;
    width: 150px;
    margin-left: 0.5rem;
    background: transparent;
    font-size: 20px;
    color: #fff;
}

.cross {
    cursor: pointer;
    display: flex;
    margin-left: 0.5rem;
    justify-content: center;
    align-items: center;
    padding: 1.3rem;
    transform: rotate(45deg);
    border-radius: 50%;
    background: #414141;
}

.cross:hover {
    background: #818181b1;
}

.cross::before {
    content: "";
    width: 2px;
    height: 16px;
    position: absolute;
    background: rgb(255, 255, 255);
}

.cross::after {
    content: "";
    height: 2px;
    width: 16px;
    position: absolute;
    background: rgb(255, 255, 255);
}

@media (max-width: 650px) {
    .tag-area {
        width: 300px;
    }
}
</style>