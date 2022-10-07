<ul class="social">
    <li class="social-facebook">
        <a href="#fb-{{ $timeline->id }}" target="_blank" data-popover="Facebook" data-popover-position="bottom">
            <i class="fa-brands fa-facebook-f"></i>
            <span>Facebook</span>
        </a>
    </li>
    <li class="social-reddit">
        <a href="#" target="_blank" data-popover="Reddit" data-popover-position="bottom">
            <i class="fa-brands fa-reddit-alien"></i>
            <span>Reddit</span>
        </a>
    </li>
    <li class="social-twitter">
        <a href="#" target="_blank" data-popover="Twitter" data-popover-position="bottom">
            <i class="fa-brands fa-twitter"></i>
            <span>Twitter</span>
        </a>
    </li>
    <li class="social-whatsapp">
        <a href="#" target="_blank" data-popover="WhatsApp" data-popover-position="bottom">
            <i class="fa-brands fa-whatsapp"></i>
            <span>WhatsApp</span>
        </a>
    </li>
    <li class="social-blogger">
        <a href="#" target="_blank" data-popover="Blogger" data-popover-position="bottom">
            <i class="fa-brands fa-blogger-b"></i>
            <span>Blogger</span>
        </a>
    </li>
    <li class="social-tumblr">
        <a href="#" target="_blank" data-popover="Tumblr" data-popover-position="bottom">
            <i class="fa-brands fa-tumblr"></i>
            <span>Tumblr</span>
        </a>
    </li>
    <li class="social-linkedin">
        <a href="#" target="_blank" data-popover="LinkedIn" data-popover-position="bottom">
            <i class="fa-brands fa-linkedin-in"></i>
            <span>LinkedIn</span>
        </a>
    </li>
    <li class="social-email">
        <a href="#" target="_blank" data-popover="Email" data-popover-position="bottom">
            <i class="fa-regular fa-envelope"></i>
            <span>Email</span>
        </a>
    </li>
    @if ($more)
    <li class="social-more">
        <a data-modal href="#modal-share">
            <i class="fa-solid fa-plus"></i>
        </a>
    </li>
    @endif
</ul>