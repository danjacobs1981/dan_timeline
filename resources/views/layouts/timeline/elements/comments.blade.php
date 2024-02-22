@push('stylesheets')
    @vite('resources/css/timeline/comments.scss')
@endpush
@push('scripts')
    @vite('resources/js/timeline/comments.js')
@endpush
<div class="reveal__wrapper">
    <div class="reveal__header">
        <strong>Comments</strong>
        <span class="fa-stack reveal__close">
            <i class="fa-solid fa-circle fa-stack-2x"></i>
            <i class="fa-solid fa-xmark fa-stack-1x"></i>
        </span>
    </div>
    <div class="reveal__body">
        <div class="comments">
            <div class="loading">
                <div class="dots"><div></div><div></div><div></div><div></div></div>
            </div>
            <div class="comments-add">
                @auth
                    <header>
                        <div>
                            <span class="fa-stack">
                                <i class="fa-solid fa-circle fa-stack-2x"></i>
                                <i class="fa-solid fa-user fa-stack-1x"></i>
                            </span>
                        </div>
                        <strong>
                            {{ auth()->user()->username }}
                        </strong>
                    </header>
                    <div class="grow-wrap">
                        <textarea rows="1" placeholder="Write a comment..." onInput="this.parentNode.dataset.replicatedValue = this.value"></textarea>
                    </div>
                    <footer>
                        <a href="#">
                            Cancel
                        </a>
                        <button class="btn">
                            Send
                        </button>
                    </footer>
                @else
                    <a href="{{ route('login.showModal', [ 'incentive' => 'Log in to add comments...' ]) }}" class="grow-wrap" data-modal data-modal-scroll data-modal-class="modal-login" data-modal-size="modal-md" data-modal-clickclose="true">
                        <textarea rows="1" placeholder="Write a comment..."></textarea>
                    </a>
                @endauth
            </div>
            <div class="comments-options">
                <span class="dropdown-toggle">
                    <span class="dropdown-title">
                        Most relevant
                    </span>
                    <div class="dropdown" data-select data-position-x="left" data-position-y="bottom">
                        <ul>
                            <li>
                                <a href="#">
                                    Most relevant
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Most recent
                                </a>
                            </li>
                        </ul>
                    </div>
                </span>    
            </div>
            <div class="comments-wrapper"></div>
        </div>
    </div>
</div>